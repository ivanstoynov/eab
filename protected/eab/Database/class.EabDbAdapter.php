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
	* 		$db = new EabDbAdapter();
	* 		$sql = "SELECT * FROM `my_table`";
	* 		$result_rows = $db->fetchAll($sql);
	*
	* @example
	* 		$db = new EabDbAdapter();
	* 		$sql = "SELECT * FROM `some_table`";
	* 		$result	= $db->query($sql);
	* 		while ($row = $result->fetchRow()) { print_r($row); }
	*
	* @example
	* 		$db = new EabDbAdapter();
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
		private $_resultType;
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
		private $_conn;
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
			if (! empty($timeout)) {
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
			if (! empty($this->_conn)) {
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
			$this->_resultType = MYSQLI_ASSOC;
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
			foreach ($settings as $k => $v) {
				$prop = '_' . $k;
				if (property_exists($this, $prop)) {
					$this->{$prop} = $v;
				}
			}
		}
		/**
		* Connect to database
		*
		* @return void
		*/
		public function connect()
		{
			$conn = @ mysqli_connect($this->_host, $this->_username, $this->_password, $this->_database);

			/* check connection */
			if (mysqli_connect_errno()) {
				throw new EabException('DB connection error: ' . mysqli_connect_error(), EabExceptionCodes::DB_EXC);
			}			

			$this->_conn = $conn;
			$this->_connetedTime = time();

			$this->_setConnectionCharset($this->_charset);
		}
		/**
		* Prepare connection - reconnect if connection has been lost
		*
		* @return void
		*/
		private function _prepareConnection() {

			if (empty($this->_conn)) {
				$this->connect();
			}
			else {
				$now = time();
				if (($now-$this->_connetedTime) > ($this->_reconnectionTimeout - 2)) {
					$this->reconnect();
				}
			}
		}
		/**
		* Set fetch mode
		*
		* @param string
		*/
		public function setFetchMode($resultType)
		{
			$this->_resultType = $resultType;
		}
		/**
		* Set charset
		*
		* @param string
		*/
		public function _setConnectionCharset($charset = 'utf8')
		{
			$this->_prepareConnection();
			if (function_exists('mysqli_set_charset(')) {
				if (FALSE === mysqli_set_charset($this->_conn, $charset)) {
					throw new EabException('DB set charset error: ' . mysqli_error($this->_conn), EabExceptionCodes::DB_EXC);
				}
			}
			else {
				$result = mysqli_query($this->_conn, "SET NAMES '".$this->escape($charset)."'");
				if (FALSE === $result) { 
					throw new EabException('DB set names error: ' . mysqli_error($this->_conn), EabExceptionCodes::DB_EXC);
				}

				$result = mysqli_query($this->_conn, "SET CHARACTER SET '".$this->escape($charset)."'");
				if (FALSE === $result) {
					throw new EabException( 'DB set charset error: ' . mysqli_error($this->_conn), EabExceptionCodes::DB_EXC);
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
			$id = mysqli_insert_id($this->_conn);
			if (FALSE === $id) {
				throw new EabException('DB get last_insert_id error: ' . mysqli_error($this->_conn), EabExceptionCodes::DB_EXC); 
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
			$this->_prepareConnection();

			$t1 = microtime();
			$result = mysqli_query($this->_conn, $sql);
			$t2 = microtime();
			if (FALSE === $result) { 
				throw new EabException('DB exec error: ' . mysqli_error($this->_conn));
			}
			
			if (TRUE === $this->_isDebugMode) {
				list($usec1, $sec1) = explode(' ', $t1);
				list($usec2, $sec2) = explode(' ', $t2);
				$difference = round($sec2 - $sec1 + $usec2 - $usec1, 6);
				$this->_queries[] = array($sql, $difference);
			}
			
			$affectedRows = mysqli_affected_rows($this->_conn);

			return $affectedRows;
		}

		/**
		* Execute database query
		*
		* @param string
		* @return EabDbResultAdapter
		*/
		public function query($sql)
		{
			$this->_prepareConnection();

			$t1 = microtime();
			$result = mysqli_query($this->_conn, $sql);
			$t2 = microtime();

			if (FALSE === $result) { 
				throw new EabException('DB query error: '.mysqli_error($this->_conn), EabExceptionCodes::DB_EXC); 
			}

			if (TRUE === $this->_isDebugMode) {
				list($usec1, $sec1) = explode(' ', $t1);
				list($usec2, $sec2) = explode(' ', $t2);
				$difference = round($sec2 - $sec1 + $usec2 - $usec1, 6);
				$this->_queries[] = array($sql, $difference);
			}

			return new EabDbResultAdapter($result, $this->_resultType);
		}
		
		/**
		* Execute database multy query
		*
		* @param string
		* 
		* @return void
		*/
		public function multyQuery($sql)
		{
			$this->_prepareConnection();

			$t1 = microtime();
			$result = mysqli_query($this->_conn, $sql);
			$t2 = microtime();

			if (FALSE === $result) { 
				throw new EabException('DB query error: '.mysqli_error($this->_conn), EabExceptionCodes::DB_EXC); 
			}

			if (TRUE === $this->_isDebugMode) {
				list($usec1, $sec1) = explode(' ', $t1);
				list($usec2, $sec2) = explode(' ', $t2);
				$difference = round($sec2 - $sec1 + $usec2 - $usec1, 6);
				$this->_queries[] = array($sql, $difference);
			}
		}

		/**
		* Get stored result
		*
		* @return EabDbResultAdapter
		*/
		public function storeResult()
		{
			$result = mysqli_store_result($this->_conn);
			if (FALSE === $result) {
				throw new EabException('DB store result not found', EabExceptionCodes::DB_EXC);
			}
			return new EabDbResultAdapter($result, $this->_resultType);
		}
		/**
		* Check if there are any more query results from a multi query
		* 
		* @return EabDbResultAdapter
		*/
		public function moreResults()
		{
			$result = mysqli_more_results($this->_conn);
			return new EabDbResultAdapter($result, $this->_resultType);
		}
		/**
		* Prepare next result from multi_query
		* 
		* @return boolean
		*/
		public function nextResult()
		{
			$result = mysqli_next_result($this->_conn);
			return new EabDbResultAdapter($result, $this->_resultType);
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
			$row = $r->fetchRow($this->_resultType, $row);
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
			$data = $r->fetchAll($this->_resultType);
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
		public function escape($value)
		{
			$quotes = (boolean) $quotes;
			if (is_array($value)) {
				foreach ($value as $k => $v) {
					$value[$k]=$this->escape($v, $quotes);
				}
				return $value;
			}
			else{
				if (is_string($value) && get_magic_quotes_gpc()) {
					$value = stripslashes($value);
				}

				if (is_bool($value)) {
					return TRUE === $value ? 1 : 0;
				}
				elseif (is_string($value)) {
					$this->_prepareConnection();
					$value = mysqli_real_escape_string($this->_conn, $value);
					if (FALSE === $value) {
						throw new EabException('DB escape string error: '.mysqli_error($this->_conn));
					}
					return $value;
				}
				elseif (is_float($value) || is_int($value)) {
					return $value;
				}
				else {
					return '';
				}
			}
		}
		/**
		* Prepare statement
		* 
		* @return mysqli_stmt
		*/
		public function prerapeStatement($sql)
		{
			$stmt = mysqli_prepare($this->_conn, $sql);
			if (FALSE === $stmt) {
				throw new EabException('DB prepare statement error: '.mysqli_error($this->_conn), EabExceptionCodes::DB_EXC);
			}
			
			return $stmt;
		}
		/**
		* Get the default character set for the database connection
		*
		* @return string
		*/
		public function getConnectionDefaultCharset()
		{
			return mysqli_character_set_name($this->_conn);
		}
		/**
		* Set transaction isolation
		*
		* @param string $isolation
		* @param string $option
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
		public function activateAutoCommit()
		{
			$this->exec("SET AUTOCOMMIT = 1");
		}
		/**
		* Deactivate auto commit
		*
		* @return void
		*/
		public function deactivateAutoCommit()
		{
			$this->exec("SET AUTOCOMMIT = 0");
		}
		/**
		* Begin transaction
		*
		* @return void
		*/
		function beginTransaction()
		{
			if (function_exists('mysqli_begin_transaction')) {
				mysqli_begin_transaction($this->_conn);
			}
			else {
				$this->exec('START TRANSACTION');
			}
		}
		/**
		* Rollback transaction
		*
		* @return integer
		*/
		function rollBack()
		{
			if (function_exists('mysqli_rollback')) {
				mysqli_rollback($this->_conn);
			}
			else {
				$this->exec('ROLLBACK');
			}
		}
		/**
		* Commit transaction
		*
		* @return integer
		*/
		function commit()
		{
			if (function_exists('mysqli_rollback')) {
				mysqli_commit($this->_conn);
			}
			else {
				$this->exec('COMMIT');
			}
		}
		/**
		* Disconnect
		*
		* @return void
		*/
		public function disconnect()
		{
			if (! empty($this->_conn)) {
				mysqli_close($this->_conn);
			}
			$this->_conn = NULL;
			$this->_connetedTime = 0;
		}
		/**
		* Call stored procedure (with multy query).
		* 
		* Each param in params must be array with:
		* 0 => param name
		* 1 => param value
		* 2 => param type symbol
		*
		* @param string 
		* @param array
		* 
		* @return void
		*/
		public function executeStoredProc($name, $params = NULL)
		{
			if (! empty($params)) {
				foreach ($params as $k => $v) {
					$params[$k] = $this->escape($v);
				}
			}
			
			$query = 'CALL ' . $this->escape($name);
			$query .= ! empty($params) ? '(' . implode(',', $params) . ')' : '()';
			
			$this->multyQuery($query);
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