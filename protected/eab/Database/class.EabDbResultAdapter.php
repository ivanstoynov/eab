<?php

	/**
	* Class define db result resource interface
	*
	* This is not valid adapter, it is just MySql 
	* database result class.
	*
	*
	* PHP version 5.0 +
	*
	* @category   Database
	* @package    Eab
	* @author     Ivan Stoyanov <iv44@yahoo.com>
	* @copyright  2010-2014 Stoqnoff
	* @since      1.0.0
	*/
	class EabDbResultAdapter
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
		protected $_defaultFetchMode;

		/**
		* Constructor of class
		*
		* @param resource
		* @param integer
		*/
		public function __construct($res, $fetchMode = MYSQL_ASSOC)
		{
			$this->result = $res;
			$this->_defaultFetchMode = $fetchMode;
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
		public function fetchAll($fetchMode = NULL)
		{
			if (NULL === $fetchMode) {
				$fetchMode = $this->_defaultFetchMode;
			}
			if (! is_resource($this->result)) {
				throw new Exception('Property ' . get_class($this) . '->_result is not valid mysql resource!');
			}

			$data = array();
			if (mysql_numrows($this->result) > 0) {
				$this->seek(0);
				while ($row = mysql_fetch_array($this->result, $fetchMode)) {
					$data[]=$row;
				}
			}
			return $data;
		}
		/**
		* Return row from result. If specified row, then seek result to this row.
		*
		* @param integer|NULL
		* @param integer|NULL
		* @return array
		*/
		public function fetchRow($fetchMode = NULL, $row = NULL)
		{
			if (NULL === $fetchMode) {
				$fetchMode = $this->_defaultFetchMode;
			}
			if (! is_resource($this->result)) {
				throw new Exception('Property '.get_class($this).'->_result is not valid mysql resource!');
			}

			if (NULL === $row) {
				return mysql_fetch_array($this->result, $fetchMode);
			} 
			else {
				$numRows = mysql_num_rows($this->result);
				if ($row >= $numRows) {
					throw new Exception('Can not find this row in result. Result have only '.$numRows.' rows!');
				}

				$this->seek($row);
				return mysql_fetch_array($this->result, $fetchMode);
			}
		}
		/**
		* Return only one field
		*
		* @param integer|NULL
		* @param integer|NULL
		* @return mixed
		*/
		public function fetchOne($col = NULL, $row = NULL)
		{
			$fetchmode = is_numeric($col) ? MYSQL_NUM : MYSQL_ASSOC;
			$row = $this->fetchRow($fetchmode, $row);

			if (! $row) {
				return NULL;
			}

			if (NULL === $col) {
				return current($row);
			}
			elseif (isset($row[$col])) {
				return $row[$col];
			}
			else {
				throw new Exception('Not find column with key "' . $col . '"');
			}
		}
		/**
		* Seek pointer of result to specified row
		*
		* @param integer
		* @return boolean
		*/
		public function seek($rowNum = 0)
		{
			if (0 === mysql_num_rows($this->result)) {
				return FALSE;
			}

			if (! mysql_data_seek($this->result, $rowNum)) {
				throw new Exception('Can not seek result pointer to row ' . $rowNum.': ' . mysql_error() . "\n");
			}
			return TRUE;
		}

		/**
		* Osvobojdava zaetata pamet ot resurs na resultata
		*
		* @return void
		*/
		public function free()
		{
			mysql_free_result($this->result);
			$this->result = NULL;
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