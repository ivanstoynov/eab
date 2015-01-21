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
		protected $_result;
		/**
		* Default fetch mode
		*
		* @var integer
		*/
		protected $_defaultResultType;

		/**
		* Constructor of class
		*
		* @param resource
		* @param integer
		*/
		public function __construct($res, $resultType = MYSQLI_ASSOC)
		{
			$this->_result = $res;
			$this->_defaultResultType = $resultType;
		}
		/**
		* Return number of rows in result
		*
		* @return integer
		*/
		public function numRows()
		{
			return mysqli_num_rows($this->_result);
		}
		/**
		* Return array from rows
		*
		* @param integer|NULL
		* 
		* @return array
		*/
		public function fetchAll($resultType = NULL)
		{
			if (NULL === $resultType) {
				$resultType = $this->_defaultResultType;
			}
			if (empty($this->_result)) {
				throw new Exception('Property ' . get_class($this) . '->_result is not valid mysqli resource!', EabExceptionCodes::DB_EXC);
			}
			
			if (function_exists('mysqli_fetch_all')) {
				return mysqli_fetch_all($this->_result, $resultType);
			}
			else {
				$data = array();
				if (mysqli_num_rows($this->_result) > 0) {
					$this->seek(0);
					while ($row = mysqli_fetch_array($this->_result, $resultType)) {
						$data[]=$row;
					}
				}
				return $data;
			}
		}
		/**
		* Return row from result. If specified row, then seek result to this row.
		*
		* @param integer|NULL
		* @param integer|NULL
		* 
		* @return array
		*/
		public function fetchRow($resultType = NULL, $row = NULL)
		{
			if (NULL === $resultType) {
				$resultType = $this->_defaultResultType;
			}
			if (empty($this->_result)) {
				throw new Exception('Property '.get_class($this).'->_result is not valid mysql resource!', EabExceptionCodes::DB_EXC);
			}

			if (NULL === $row) {
				return mysqli_fetch_array($this->_result, $resultType);
			} 
			else {
				$numRows = mysqli_num_rows($this->_result);
				if ($row >= $numRows) {
					throw new Exception('Can not find this row in result. Result have only '.$numRows.' rows!', EabExceptionCodes::DB_EXC);
				}

				$this->seek($row);
				return mysqli_fetch_array($this->_result, $resultType);
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
			$resultType = is_numeric($col) ? MYSQLI_NUM : MYSQLI_ASSOC;
			$row = $this->fetchRow($resultType, $row);

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
				throw new Exception('Not find column with key "' . $col . '"', EabExceptionCodes::DB_EXC);
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
			if (0 === mysqli_num_rows($this->_result)) {
				return FALSE;
			}

			if (! mysqli_data_seek($this->_result, $rowNum)) {
				throw new Exception('Can not seek result pointer to row ' . $rowNum.': ' . mysql_error() . "\n", EabExceptionCodes::DB_EXC);
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
			mysqli_free_result($this->_result);
			$this->_result = NULL;
		}

		/**
		* Return number of affected rows from previous query
		*
		* @return integer
		*/
		function affectedRows()
		{
			return mysql_affected_rows($this->_result);
		}
	}
?>