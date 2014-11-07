<?php

 /**
 * Definition of classes to make database connection and 
 * implement basic operation.
 *
 *
 * PHP version 5.0 +
 *
 * @example 
 * 		$db = EabDb::GetInstance();
 * 		$sql = "SELECT * FROM `my_table`";
 * 		$result_rows = $db->fetchAll($sql);
 *
 * @example
 * 		$db = EabDb::Singleton();
 * 		$sql = "SELECT * FROM `some_table`";
 * 		$result	= $db->query($sql);
 * 		while($row = $result->fetchRow()) { print_r($row); }
 *
 * @example
 * 		$db = EabDb::Singleton();
 * 		$sql = "INSERT INTO `some_table` (field1, field2) VALUES ('1','2'); ";
 * 		$db->exec($sql);
 *
 * @category   Database
 * @package    Eab
 * @author     Ivan Stoyanov <iv44@yahoo.com>
 * @copyright  2010-2014 Stoqnoff
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      File available since Release 1.0.0
 */



/**
 *
 * Singleton class define db connection interface
 *
 * @example
 * 		$db  = EabDb::Singleton();
 * 		$sql = "SELECT * from `my_table`";
 * 		$ResData = $db->fetchAll($sql);
 *
 *
 * PHP version 5.0 +
 *
 * @category   Database
 * @package    Eab
 * @author     Ivan Stoyanov <iv44@yahoo.com>
 * @copyright  2010-2014 Stoqnoff
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      1.0.0
 */
class EabDb
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
    private $_is_debug_mode;
	/**
	 * @var string
	 */
    private $_fetch_mode;
	/**
	 * @var string
	 */
    private $_new_link;
	/**
	 * @var string
	 */
    private $_client_flags;
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
	private $_db_conn;
	/**
	 * Time of last connection. Need to reconnect method.
	 *
	 * @var integer
	 */
	private $_conneted_time;
	/**
	 * Vremetraene na vryzkata s DB
	 *
	 * @var integer
	 */
	private $_reconnection_timeout=60;
	
	/**
	 * Statichna instanciq na klasa
	 *
	 * @var GDB
	 */
	private static $_instance=null;

	/**
	 * Constructor of class
	 *
	 * @param array
	 */
	private function __construct($settings=array())
	{
		$defaultSettings=$this->_getDefautSettings();

		$this->applySettings($defaultSettings);
		
		$timeout = ini_get('mysql.connect_timeout');
		if($timeout){
			$this->_reconnection_timeout=$timeout;
		}
		$this->applySettings($settings);
	}
	/**
	 * Private magic clone method
	 */
	private function __clone(){}

	/**
	 * Get instance (singleton method)
	 *
	 * @param array
	 * @return EabDb
	 */
	static function GetInstance($settings=array())
	{
		if(null==self::$_instance) {
			self::$_instance=new self($settings);
			self::$_instance->connect();
		}
		return self::$_instance;
	}
	/**
	 * Clear existing instance
	 *
	 * @return void
	 */
	public static function ClearInstance()
	{
		self::$_instance = null;
	}
	/**
	 * Get default settings
	 *
	 * @return array
	 */
	private function _getDefautSettings()
	{
		return array(
			'username'=>'root',
			'password'=>'',
			'host'=>'localhost',
			'database'=>'test',
			'charset'=>'UTF8',
			'debug_mode'=>false,
			'fetch_mode'=>MYSQL_ASSOC,
			'new_link'=>false,
			'client_flags'=>196608,
			'queries'=>array()
		);
	}

	/**
	 * Set settings to properties and call reconnect method to apply these settings
	 *
	 * @param array
	 */
	public function applySettings($settings=array())
	{
		foreach($settings as $k=>$v){
			$prop='_'.$k;
			if(property_exists($this,$prop)){
				$this->{$prop}=$v;
			}
		}
		
		if(self::$_instance){
			$this->reconnect();
		}
	}

	/**
	 * Connect to database
	 *
	 * @return void
	 */
	private function connect()
	{
		$conn=@mysql_connect($this->_host,$this->_username,$this->_password,$this->_new_link,$this->_client_flags);
		if(!is_resource($conn)){ 
			throw new Exception('DB connection error:  '.mysql_error(), mysql_errno()); 
		}

		$this->_db_conn = $conn;
		$this->_conneted_time = time();

		$this->setConnectionCharset($this->_charset);
		$this->selectDatabase($this->_database);
	}
	/**
	 * Prepare connection - reconnect if connection has been lost
	 *
	 * @return void
	 */
	private function prepareConnection() {

		if( !is_resource($this->_db_conn) ) {
			$this->reconnect();
		}
		else {
			$now = time();
			if(($now-$this->_conneted_time)>($this->_reconnection_timeout-2)){
				$this->reconnect();
			}
		}
	}
	/**
	 * Set transaction isolation
	 *
	 * @param string
	 * @param string
	 * @return void
	 */
	public function setTransactionIsolation($isolation='READ COMMITTED', $option='SESSION')
	{
		$this->exec('SET '.$this->escape($option).' TRANSACTION ISOLATION LEVEL '.$this->escape($isolation).';');
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
	 * Select work database
	 *
	 * @param string $database
	 * @return void
	 */
	public function selectDatabase($database)
	{
		$this->prepareConnection();
		if( !mysql_select_db($database, $this->_db_conn) ){
			throw new Exception('DB select db error: '.mysql_error($this->_db_conn), mysql_errno($this->_db_conn));
		}
	}
	/**
	 * Set fetch mode
	 *
	 * @param string
	 */
	public function setFetchMode($fetchmode)
	{
		$this->_fetch_mode=$fetchmode;
	}

	/**
	 * Set charset
	 *
	 * @param string
	 */
	public function setConnectionCharset($charset='UTF8')
	{
		$this->prepareConnection();
		if(function_exists('mysql_set_charset')) {
		
			if(!mysql_set_charset($charset,$this->_db_conn)) {
				throw new Exception('DB set charset error: '.mysql_error($this->_db_conn), mysql_errno($this->_db_conn));
			}
		} else {
			$res = mysql_query("SET NAMES '".$this->escape($charset)."'", $this->_db_conn);
			if(!$res){ 
				throw new Exception('DB set names error: '.mysql_error($this->_db_conn), mysql_errno($this->_db_conn));
			}

			$res = mysql_query("SET CHARACTER SET '".$this->escape($charset)."'", $this->_db_conn);
			if(!$res){
				throw new Exception( 'DB set charset error: '.mysql_error($this->_db_conn), mysql_errno($this->_db_conn));
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
		$id = mysql_insert_id($this->_db_conn);
		if(!$id){
			throw new Exception('DB get last_insert_id error: '.mysql_error(), mysql_errno($this->_db_conn)); 
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
		$res = mysql_query($sql, $this->_db_conn);
		$end = microtime();
		if(!$res) { 
			throw new Exception('DB exec error: '.mysql_error($this->_db_conn)); 
		}
		
		if($this->_is_debug_mode){
			list($usec1,$sec1)=explode(' ',$start);
			list($usec2,$sec2)=explode(' ',$end);
			$diff = round($sec2-$sec1+$usec2-$usec1, 6);
			$this->_queries[]=array($sql,$diff);
		}
		
		$aff_rows = mysql_affected_rows($this->_db_conn);

		return $aff_rows;
	}

	/**
	 * Execute database query
	 *
	 * @param string
	 * @return EabDbResult
	 */
	public function query($sql)
	{
		$this->prepareConnection();

		$this->last_query = $sql;
		$start = microtime();
		$res = mysql_query($sql, $this->_db_conn);
		$end = microtime();

		if(!$res){ 
			throw new Exception('DB query error: '.mysql_error($this->_db_conn)); 
		}

		if($this->_is_debug_mode){
			list($usec1,$sec1)=explode(' ',$start);
			list($usec2,$sec2)=explode(' ',$end);
			$diff=round($sec2-$sec1+$usec2-$usec1,6);
			$this->_queries[]=array($sql,$diff);
		}

		return new EabDbResult($res,$this->_fetch_mode);
	}

	/**
	 * Return only one field from result
	 *
	 * @param string
	 * @param integer
	 * @param integer
	 * @return mixed
	 */
	public function fetchOne($sql,$col=null,$row=null)
	{
		$r=$this->query($sql);
		$field=$r->fetchOne($col,$row);
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
	public function fetchRow($sql,$row=null)
	{
		$r=$this->query($sql);
		$row = $r->fetchRow($this->_fetch_mode,$row);
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
		$r=$this->query($sql);
		$data=$r->fetchAll($this->_fetch_mode);
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
	public function escape($value,$quotes=false)
	{
		if(is_array($value)){
	        foreach ($value as $key=>$val) {
	            $value[$key]=$this->escape($val, $quotes);
        	}
        	return $value;
		}
		else{
			if(is_string($value)&&get_magic_quotes_gpc()) {
				$value=stripslashes($value);
			}

			if (is_bool($value)) {
				return $value ? 1 : 0;
			}
			elseif(is_string($value)) {
				$this->prepareConnection();
				$value = mysql_real_escape_string($value,$this->_db_conn);
				if(false===$value){
					throw new Exception('DB escape string error: '.mysql_error($this->_db_conn)); 
				}
				if(true==$quotes){ 
					$value = "'".$value."'";
				}
				return $value;
			}
			elseif (/*is_numeric($value)*/ is_float($value) || is_int($value)) {
				return $value;
			}
//			elseif (null === $value) {
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
		return mysql_client_encoding($this->db_con);
	}
	/**
	 * Begin transaction
	 *
	 * @param mixed
	 */
    function beginTransaction($savepoint = null)
    {
    	return $this->exec('START TRANSACTION');
    }
    /**
     * Rollback transaction
     *
     * @param mixed
     */
    function rollback($savepoint = null)
    {
    	return $this->exec('ROLLBACK');
    }
	/**
	 * Commit transaction
	 *
	 * @param mixed
	 * @return void
	 */
    function commit($savepoint = null)
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
		if(!empty($this->_db_conn) && is_resource($this->_db_conn)){
			mysql_close($this->_db_conn);
		}
		$this->_db_conn= null;
		$this->_conneted_time=0;
		self::$_instance=null;
	}
	/**
	 * Check if exist instance
	 *
	 * @return boolean
	 */
	public static function haveInstance()
	{
		return isset(self::$_instance);
	}
    /**
     * Call stored procedure
     *
     * @param string 
     * @param array
     * @return EabDbResult
     */
    public function executeStoredProc($name,$params=null)
    {
        $query='CALL '.$name;
        $query.=$params ? '('.implode(',', $params).')' : '()';
        $r=$this->query($query);

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
	 * Class destructor
	 *
	 * @return void
	 */
	public function __destruct()
	{
		if(!empty($this->_db_conn)){
			$this->disconnect(); 
		}
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
	 * @return EabDb
	 */
	public function setUsername($username)
	{
		$this->_username=$username;
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
	 * @return EabDb
	 */
	public function setPassword($password)
	{
		$this->_password=$password;
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
	 * @return EabDb
	 */
	public function setHost($host)
	{
		$this->_host=$host;
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
	 * @return EabDb
	 */
	public function setDatabase($database)
	{
		$this->_host=$database;
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
	 * @return EabDb
	 */
	public function setCharset($charset)
	{
		$this->_charset=$charset;
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
	 * @return EabDb
	 */
	public function setIsDebugMode($is_debug_mode)
	{
		$this->_is_debug_mode=$is_debug_mode;
		return $this;
	}
	/**
	 * Get is_debug_mode (getter)
	 *
	 * @return string
	 */
	public function getIsDebugMode()
	{
		return $this->_is_debug_mode;
	}
	/**
	 * Set new_link (setter)
	 *
	 * @param string
	 * @return EabDb
	 */
	public function setNewLink($new_link)
	{
		$this->_new_link=$new_link;
		return $this;
	}
	/**
	 * Get new_link (getter)
	 *
	 * @return string
	 */
	public function getNewLink()
	{
		return $this->_new_link;
	}
	/**
	 * Set client_flags (setter)
	 *
	 * @param string
	 * @return EabDb
	 */
	public function setClientFlags($client_flags)
	{
		$this->_client_flags=$client_flags;
		return $this;
	}
	/**
	 * Get client_flags (getter)
	 *
	 * @return string
	 */
	public function getClientFlags()
	{
		return $this->_client_flags;
	}
}


/**
 *
 * Class define db result resource interface
 *
 *
 * PHP version 5.0 +
 *
 * @category   Database
 * @package    Eab
 * @author     Ivan Stoyanov <iv44@yahoo.com>
 * @copyright  2010-2014 Stoqnoff
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @since      1.0.0
 */
class EabDbResult
{
	/**
	 * Result resource
	 *
	 * @var resource
	 */
	protected $result;
	/**
	 * Default fetch mode
	 *
	 * @var integer
	 */
	protected $_default_fetch_mode;

	/**
	 * Constructor of class
	 *
	 * @param resource
	 * @param integer
	 */
	public function __construct($res, $fetch_mode=MYSQL_ASSOC)
	{
		$this->result=$res;
		$this->_default_fetch_mode=$fetch_mode;
	}
	/**
	 * Return number of rows in result
	 *
	 * @return integer
	 */
	public function numRows()
	{
		return mysql_num_rows($this->result);
	}
	/**
	 * Return array from rows
	 *
	 * @param integer
	 * @return array
	 */
	public function fetchAll($fetchmode=null)
	{
		if(!$fetchmode){
			$fetchmode=$this->_default_fetch_mode;
		}
		if(!is_resource($this->result)){
			throw new Exception('Property '.get_class($this).'->_result is not valid mysql resource!');
		}

		$data = array();
		if(mysql_numrows($this->result)>0){
			$this->seek(0);
			while($row=mysql_fetch_array($this->result,$fetchmode)){
				$data[]=$row;
			}
		}
		return $data;
	}
	/**
	 * Return row from result. If specified row, then seek result to this row.
	 *
	 * @param integer|null
	 * @param integer|null
	 * @return array
	 */
	public function fetchRow($fetchmode=null,$row=null)
	{
		if(!$fetchmode){
			$fetchmode=$this->_default_fetch_mode;
		}
		if(!is_resource($this->result)){
			throw new Exception('Property '.get_class($this).'->_result is not valid mysql resource!');
		}

		if(null==$row) {
			return mysql_fetch_array($this->result,$fetchmode);
		} else {
			$num_rows = mysql_num_rows($this->result);
			if($row>=$num_rows) {
				throw new Exception('Can not find this row in result. Result have only '.$num_rows.' rows!');
			}

			$this->seek($row);
			return mysql_fetch_array($this->result, $fetchmode);
		}
	}
	/**
	 * Return only one field
	 *
	 * @param integer|null
	 * @param integer|null
	 * @return mixed
	 */
	public function fetchOne($col=null,$row=null)
	{
		$fetchmode=is_numeric($col) ? MYSQL_NUM : MYSQL_ASSOC;
		$row=$this->fetchRow($fetchmode,$row);

		if(!$row) return null;

		if(null==$col) return current($row);
		elseif(isset($row[$col])) return $row[$col];
		else throw new Exception('Not find column with key "'.$col.'"');
	}
	/**
	 * Seek pointer of result to specified row
	 *
	 * @param integer
	 * @return boolean
	 */
	public function seek($rownum=0)
	{
		if(0 == mysql_num_rows($this->result)) return false;

		if(!mysql_data_seek($this->result,$rownum)) {
			throw new Exception('Can not seek result pointer to row '.$rownum.': '.mysql_error()."\n");
		}
		return true;
	}

	/**
	 * Osvobojdava zaetata pamet ot resurs na resultata
	 *
	 * @return void
	 */
	public function free()
	{
		mysql_free_result($this->result);
		$this->result=null;
	}

	/**
	 * Return number of affected rows from previous query
	 *
	 * @return integer
	 */
	function affectedRows()
	{
		return mysql_affected_rows($this->result);
	}
}
?>