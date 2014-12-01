<?php

	require_once 'class.EabDbResultAdapter.php';

	/**
	 * Definition of classes to make database connection and 
	 * implement basic operation.
	 *
	 * This is not valid adapter, it is just MySql 
	 * database class.
	 *
	 * PHP version 5.0 +
	 *
	 * @example 
	 * 		$db = new EabDb();
	 * 		$sql = "SELECT * FROM `my_table`";
	 * 		$result_rows = $db->fetchAll($sql);
	 *
	 * @example
	 * 		$db = new EabDb();
	 * 		$sql = "SELECT * FROM `some_table`";
	 * 		$result	= $db->query($sql);
	 * 		while ($row = $result->fetchRow()) { print_r($row); }
	 *
	 * @example
	 * 		$db = new EabDb();
	 * 		$sql = "INSERT INTO `some_table` (field1, field2) VALUES ('1','2'); ";
	 * 		$db->exec($sql);
	 *
	 * @category   Database
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabDbAdapter
	{
		/**
		 * @var string
		 */
		private $_username;
		/**
		 * @var string
		 */	
		private $_password;
		/**
		 * @var string
		 */
		private $_host;
		/**
		 * @var string
		 */
		private $_database;
		/**
		 * @var string
		 */
		private $_charset;
		/**
		 * @var boolean
		 */
		private $_isDebugMode;
		/**
		 * @var string
		 */
		private $_fetchMode;
		/**
		 * @var string
		 */
		private $_newLink;
		/**
		 * @var string
		 */
		private $_clientFlags;
		/**
		 * Database queries as query string and execution time
		 *
		 * @var array
		 */
		private $_queries;
		/**
		 * Database connection identifier
		 *
		 * @var resource
		 */
		private $_dbConn;
		/**
		 * Time of last connection. Need to reconnect method.
		 *
		 * @var integer
		 */
		private $_connetedTime;
		/**
		 * Vremetraene na vryzkata s DB
		 *
		 * @var integer
		 */
		private $_reconnectionTimeout;
		/**
		 * Constructor of class
		 *
		 * @param array
		 */
		public function __construct($settings = array())
		{
			$this->loadDefautSettings();
			
			$timeout = ini_get('mysql.connect_timeout');
			if ($timeout){
				$this->_reconnectionTimeout = $timeout;
			}
			$this->setSettings($settings);
		}
		/**
		 * Class destructor
		 *
		 * @return void
		 */
		public function __destruct()
		{
			if (! empty($this->_dbConn)){
				$this->disconnect(); 
			}
		}
		/**
		 * Get default settings
		 *
		 * @return array
		 */
		public function loadDefautSettings()
		{
			$this->_username = 'root';
			$this->_password = '';
			$this->_host = 'localhost';
			$this->_database = 'test';
			$this->_charset = 'UTF8';
			$this->_isDebugMode = TRUE;
			$this->_detfetch_mode = MYSQL_ASSOC;
			$this->_newLink = FALSE;
			$this->_clientFlags = 196608;
			$this->_queries = array();
			$this->_reconnectionTimeout = 60;
		}

		/**
		 * Set settings to properties
		 * Note: this method not reconnect db connection
		 *
		 * @param array
		 */
		public function setSettings($settings = array())
		{
			foreach ($settings as $k => $v){
				$prop = '_' . $k;
				if (property_exists($this, $prop)){
					$this->{$prop} = $v;
				}
			}
		}
		/**
		 * Connect to database
		 *
		 * @return void
		 */
		private function connect()
		{
			$conn = @mysql_connect($this->_host, $this->_username, $this->_password, $this->_newLink, $this->_clientFlags);
			if (! is_resource($conn)){ 
				throw new Exception('DB connection error:  ' . mysql_error(), mysql_errno()); 
			}

			$this->_dbConn = $conn;
			$this->_connetedTime = time();

			$this->setConnectionCharset($this->_charset);
			$this->selectDatabase($this->_database);
		}
		/**
		 * Prepare connection - reconnect if connection has been lost
		 *
		 * @return void
		 */
		private function prepareConnection() {

			if (! is_resource($this->_dbConn) ) {
				$this->reconnect();
			}
			else {
				$now = time();
				if (($now-$this->_connetedTime) > ($this->_reconnectionTimeout - 2)){
					$this->reconnect();
				}
			}
		}

		/**
		 * Select work database
		 *
		 * @param string $database
		 * @return void
		 */
		public function selectDatabase($database)
		{
			$this->prepareConnection();
			if (! @mysql_select_db($database, $this->_dbConn) ){
				throw new Exception('DB select db error: ' . mysql_error($this->_dbConn), mysql_errno($this->_dbConn));
			}
		}
		/**
		 * Set fetch mode
		 *
		 * @param string
		 */
		public function setFetchMode($fetchmode)
		{
			$this->_fetchMode = $fetchmode;
		}

		/**
		 * Set charset
		 *
		 * @param string
		 */
		public function setConnectionCharset($charset = 'UTF8')
		{
			$this->prepareConnection();
			if (function_exists('mysql_set_charset')) {
			
				if (! mysql_set_charset($charset, $this->_dbConn)) {
					throw new Exception('DB set charset error: ' . mysql_error($this->_dbConn), mysql_errno($this->_dbConn));
				}
			} 
			else {
				$res = mysql_query("SET NAMES '".$this->escape($charset)."'", $this->_dbConn);
				if (! $res){ 
					throw new Exception('DB set names error: ' . mysql_error($this->_dbConn), mysql_errno($this->_dbConn));
				}

				$res = mysql_query("SET CHARACTER SET '".$this->escape($charset)."'", $this->_dbConn);
				if (! $res){
					throw new Exception( 'DB set charset error: ' . mysql_error($this->_dbConn), mysql_errno($this->_dbConn));
				}
			}
		}

		/**
		 * Return last inserted id
		 *
		 * @return integer
		 */
		public function lastInsertID()
		{
			$id = mysql_insert_id($this->_dbConn);
			if (! $id){
				throw new Exception('DB get last_insert_id error: ' . mysql_error(), mysql_errno($this->_dbConn)); 
			}
			return $id;
		}

		/**
		 * Execute database insert or update query
		 *
		 * @param string
		 * @return integer
		 */
		public function exec($sql)
		{
			$this->prepareConnection();

			$this->last_query = $sql;
			$start = microtime();
			$res = mysql_query($sql, $this->_dbConn);
			$end = microtime();
			if (! $res) { 
				throw new Exception('DB exec error: ' . mysql_error($this->_dbConn));
			}
			
			if ($this->_isDebugMode){
				list($usec1, $sec1) = explode(' ', $start);
				list($usec2, $sec2) = explode(' ', $end);
				$diff = round($sec2 - $sec1 + $usec2 - $usec1, 6);
				$this->_queries[] = array($sql, $diff);
			}
			
			$affRows = mysql_affected_rows($this->_dbConn);

			return $affRows;
		}

		/**
		 * Execute database query
		 *
		 * @param string
		 * @return EabDbResultAdapter
		 */
		public function query($sql)
		{
			$this->prepareConnection();

			$this->last_query = $sql;
			$start = microtime();
			$res = mysql_query($sql, $this->_dbConn);
			$end = microtime();

			if (! $res){ 
				throw new Exception('DB query error: '.mysql_error($this->_dbConn)); 
			}

			if($this->_isDebugMode){
				list($usec1, $sec1) = explode(' ', $start);
				list($usec2, $sec2) = explode(' ', $end);
				$diff = round($sec2 - $sec1 + $usec2 - $usec1, 6);
				$this->_queries[] = array($sql, $diff);
			}

			return new EabDbResultAdapter($res, $this->_fetchMode);
		}

		/**
		 * Return only one field from result
		 *
		 * @param string
		 * @param integer
		 * @param integer
		 * @return mixed
		 */
		public function fetchOne($sql, $col = NULL, $row = NULL)
		{
			$r = $this->query($sql);
			$field = $r->fetchOne($col, $row);
			$r->free();
			return $field;
		}

		/**
		 * Fetch row
		 *
		 * @param string
		 * @param integer
		 * @return array
		 */
		public function fetchRow($sql, $row = NULL)
		{
			$r = $this->query($sql);
			$row = $r->fetchRow($this->_fetchMode, $row);
			$r->free();
			return $row;
		}

		/**
		 * Fetch all
		 *
		 * @param string
		 * @return array
		 */
		public function fetchAll($sql)
		{
			$r = $this->query($sql);
			$data = $r->fetchAll($this->_fetchMode);
			$r->free();
			return $data;
		}

		/**
		 * Escape input value
		 *
		 * @param string|array
		 * @param boolean
		 * @return mixed
		 */
		public function escape($value, $quotes = FALSE)
		{
			$quotes = (boolean) $quotes;
			if (is_array($value)){
				foreach ($value as $key => $val) {
					$value[$key]=$this->escape($val, $quotes);
				}
				return $value;
			}
			else{
				if (is_string($value) && get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}

				if (is_bool($value)) {
					return $value ? 1 : 0;
				}
				elseif (is_string($value)){
					$this->prepareConnection();
					$value = mysql_real_escape_string($value, $this->_dbConn);
					if (FALSE === $value){
						throw new Exception('DB escape string error: '.mysql_error($this->_dbConn));
					}
					if (TRUE === $quotes){
						$value = "'" . $value . "'";
					}
					return $value;
				}
				elseif (/*is_numeric($value)*/ is_float($value) || is_int($value)){
					return $value;
				}
	//			elseif (NULL === $value) {
	//				return 'NULL';
	//			}
				else {
					return '';
				}
			}
		}
		/**
		 * Get client encoding
		 *
		 * @return string
		 */
		public function getClientEncoding()
		{
			return mysql_client_encoding($this->_dbConn);
		}
		/**
		 * Set transaction isolation
		 *
		 * @param string
		 * @param string
		 * @return void
		 */
		public function setTransactionIsolation($isolation = 'READ COMMITTED', $option = 'SESSION')
		{
			$this->exec('SET ' . $this->escape($option) . ' TRANSACTION ISOLATION LEVEL ' . $this->escape($isolation) . ';');
		}
		/**
		 * Activate auto commit
		 *
		 * @return void
		 */
		public function activateCommitTransaction()
		{
			$this->exec("SET AUTOCOMMIT=1");
		}
		/**
		 * Remove auto commit
		 *
		 * @return void
		 */
		public function deactivateAutoCommitTransaction()
		{
			$this->exec("SET AUTOCOMMIT=0");
		}
		/**
		 * Begin transaction
		 *
		 * @return void
		 */
		function beginTransaction()
		{
			$this->exec('START TRANSACTION');
		}
		/**
		 * Rollback transaction
		 *
		 * @return integer
		 */
		function rollBack()
		{
			return $this->exec('ROLLBACK');
		}
		/**
		 * Commit transaction
		 *
		 * @return integer
		 */
		function commit()
		{
			return $this->exec('COMMIT');
		}
		/**
		 * Disconnect
		 *
		 * @return void
		 */
		public function disconnect()
		{
			if (isset($this->_dbConn) && is_resource($this->_dbConn)){
				mysql_close($this->_dbConn);
			}
			$this->_dbConn = NULL;
			$this->_connetedTime = 0;
		}
		/**
		 * Call stored procedure
		 *
		 * @param string 
		 * @param array
		 * @return EabDbResultAdapter
		 */
		public function executeStoredProc($name, $params = NULL)
		{
			$query = 'CALL ' . $name;
			$query .= $params ? '(' . implode(',', $params) . ')' : '()';
			$r = $this->query($query);

			$this->reconnect();
			return $r;
		}
		/**
		 * Reconnect database
		 *
		 * @return void
		 */
		public function reconnect()
		{
			$this->disconnect();
			$this->connect();
		}
		/**
		 * Get queries
		 *
		 * @return array
		 */
		public function getQueries()
		{ 
			return $this->_queries; 
		}
		/**
		 * Set username (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setUsername($username)
		{
			$this->_username = $username;
			return $this;
		}
		/**
		 * Get username (getter)
		 *
		 * @return string
		 */
		public function getUsername()
		{
			return $this->_username;
		}
		/**
		 * Set password (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setPassword($password)
		{
			$this->_password = $password;
			return $this;
		}
		/**
		 * Get password (getter)
		 *
		 * @return string
		 */
		public function getPassword()
		{
			return $this->_password;
		}
		/**
		 * Set host (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setHost($host)
		{
			$this->_host = $host;
			return $this;
		}
		/**
		 * Get host (getter)
		 *
		 * @return string
		 */
		public function getHost()
		{
			return $this->_host;
		}
		/**
		 * Set database (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setDatabase($database)
		{
			$this->_host = $database;
			return $this;
		}
		/**
		 * Get database (getter)
		 *
		 * @return string
		 */
		public function getDatabase()
		{
			return $this->_database;
		}
		/**
		 * Set charset (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setCharset($charset)
		{
			$this->_charset = $charset;
			return $this;
		}
		/**
		 * Get charset (getter)
		 *
		 * @return string
		 */
		public function getCharset()
		{
			return $this->_charset;
		}
		/**
		 * Set is_debug_mode (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setIsDebugMode($isDebugMode)
		{
			$this->_isDebugMode = $isDebugMode;
			return $this;
		}
		/**
		 * Get is_debug_mode (getter)
		 *
		 * @return string
		 */
		public function getIsDebugMode()
		{
			return $this->_isDebugMode;
		}
		/**
		 * Set new_link (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setNewLink($newLink)
		{
			$this->_newLink = $newLink;
			return $this;
		}
		/**
		 * Get new_link (getter)
		 *
		 * @return string
		 */
		public function getNewLink()
		{
			return $this->_newLink;
		}
		/**
		 * Set client_flags (setter)
		 *
		 * @param string
		 * @return EabDbAdapter
		 */
		public function setClientFlags($clientFlags)
		{
			$this->_clientFlags = $clientFlags;
			return $this;
		}
		/**
		 * Get client_flags (getter)
		 *
		 * @return string
		 */
		public function getClientFlags()
		{
			return $this->_clientFlags;
		}
	}
?>