<?php
	/**
	* Base model class
	*
	* @category   Core
	* @package    Eab
	* @author     Ivan Stoyanov <iv44@yahoo.com>
	* @copyright  2010-2014 Stoqnoff
	* @since      1.0.0
	*/
	class EabModel
	{
		/**
		* Database adapter
		* 
		* @var resource
		*/
		private $_dbAdapter;
		/**
		* Table name
		* 
		* @var string
		*/
		private $_tableName;
		/**
		* Primary key column
		* 
		* @var string
		*/
		private $_pkColumn;
		/**
		* Model fields
		* 
		* @var array
		*/
		private $_modelFields;

		/**
		* Constructor of class
		* 
		* @param string|NULL $tableName
		* @param mixed $pkColumn
		* 
		* @return void
		*/
		public function __construct($tableName = NULL, $pkColumn = NULL)
		{
			if (NULL === $tableName) {
				$this->_loadTableNameFromClass();
			}
			if (NULL === $pkColumn) {
				$this->_pkColumn = 'id';
			}
			$this->_modelFields = array();
		}
		/**
		* Load table name from class name
		* 
		* @return void
		*/
		private function _loadTableNameFromClass()
		{
			$class = get_class($this);
			if ('model' !== substr(strtolower($class), -5, 5)) {
				throw new EabException('Model class must be ended with "model"!', EabExceptionCodes::UNKNOWN_EXC);
			}
			$this->_tableName = $class . 's';
		}
		/**
		* Load model from database
		* 
		* @return
		*/
		public function load()
		{
			$pkStatement = $this->createPkStatement();
			if (empty($pkStatement)) {
				throw new EabException('No primary key defined!', EabExceptionCodes::UNKNOWN_EXC);
			}

			$sql = "SELECT * FROM `" . $this->_dbAdapter->escape($this->_tableName) . "` WHERE " . $pkStatement . " LIMIT 1";
			$row = $this->_dbAdapter->fetchRow($sql);
			$this->loadFromArray($row);

			return TRUE;
		}
		/**
		* Save model
		* 
		* @return void to database
		*/
		public function save()
		{
			$pkStatement = $this->createPkStatement();
			if (! $pkStatement) {
				$sql = "INSERT INTO `" . $this->_tableName . "` (`" . implode('`,`', $this->_tableColumns) . "`) VALUES \n";
				$sql.= "(";
				foreach ($this->_tableColumns as $col) {
					if (is_null($this->{$col})) $sql .= "NULL,";
					else $sql .= "'" . stripslashes($this->{$col}) . "',";
				}
				$sql .= substr($values, 0, -1) . ")";
				$this->_dbAdapter->exec($sql);

				if (strtolower($this->_pkColumn) === 'id') {
					$this->{$this->_pkColumn} = $this->_dbAdapter->lastInsertId();
				}
			}
			else{
				$sql = "UPDATE `" . $this->_tableName . "` SET \n";
				foreach ($this->_tableColumns as $col) {
					if (! is_null($this->{$col})) {
						$sql .= "'" . $this->_dbAdapter->escape($this->{$col}) . "',";
					}
				}

				$sql .= substr($values, 0, -1) . " WHERE " . $pkStatement . " LIMIT 1";
				$this->_dbAdapter->exec($sql);
			}
		}
		/**
		* Delete model from database
		* 
		* @return void
		*/
		public function delete()
		{
			$pkStatement = $this->createPkStatement();
			
			$sql = "DELETE FROM `" . $this->_dbAdapter->escape($this->_tableName) . "` WHERE " . $where_expr . " LIMIT 1";
			$this->_dbAdapter->exec($sql);
		}
		/**
		* Find model
		* 
		* @param undefined $criterias
		* 
		* @return
		*/
		public function find($criterias = array())
		{
			if (! empty($criterias['columns'])) {
				if (is_array($criterias['columns'])) {
					$sql = 'SELECT `' . implode('`,`', $criterias['columns']) . '` FROM ';
				}
				else{
					$sql = 'SELECT '.$criterias['columns'].' FROM ';
				}
			}
			else{
				$sql = 'SELECT * FROM `'.$this->_tableName.'` ';
			}
			
			if (! empty($criterias['where'])) {
				$sql .= "\nWHERE " . $criterias['where'] . ' ';
			}
			
			if (! empty($criterias['order'])) {
				$sql .= "\nORDER BY " . $criterias['order'] . ' ';
			}
			
			if (! empty($criterias['having'])) {
				$sql .= "\nHAVING " . $criterias['having'] . ' ';
			}

			if (! empty($criterias['limit'])) {
				$sql .= "\nLIMIT " . $criterias['limit'] . ' ';
			}
			
			$class = get_class($this);
			$models = array();
			$result = $this->_dbAdapter->query($sql);
			while ($row = $result->fetchRow()) {
				$model = new $class();
				$model->loadFromArray($row);
				$models[] = $model;
			}

			return $models;
		}
		/**
		* Create primary key statement
		* 
		* @return string
		*/
		private function createPkStatement()
		{
			if (empty($this->_pkColumn)) {
				throw new EabException('Primary key column for model "' . get_class($this) . '" is empty!', EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}

			$statement = '';
			if (is_array($this->_pkColumn)) {
				foreach ($this->_pkColumn as $col) {
					$statement = $this->_dbAdapter->escape($col) . "='" . $this->_dbAdapter->escape($this->{$col}) . "'";
				}
				$statement = substr($statement, 0, -1);
			}
			else{
				$statement = $this->_dbAdapter->escape($this->_pkColumn) . "='" . $this->_dbAdapter->escape($this->{$this->_pkColumn}) . "'";
			}

			return $statement;
		}
		/**
		* Load model data from array
		* 
		* @param undefined $data
		* 
		* @return void
		*/
		public function loadFromArray($data)
		{
			$this->_modelFields = array();
			
			foreach ($data as $column => $value) {
				$this->_modelFields[$column] = $value;
			}
		}
		/**
		* Set pkColumn (setter)
		* 
		* @param string|array $column
		* 
		* @return EabModel
		*/
		public function setPkColumn($column)
		{
			$this->_pkColumn = $column;
			return $this;
		}
		/**
		* Get pkColumn (getter)
		* 
		* @return string|array
		*/
		public function getPkColumn()
		{
			return $this->_pkColumn;
		}
		/**
		* Set tableName (setter)
		* 
		* @param string $tableName
		* 
		* @return EabModel
		*/
		public function setTableName($tableName)
		{
			$this->_tableName = $tableName;
			return $this;
		}
		/**
		* Set tableName (getter)
		* 
		* @return string
		*/
		public function getTableName()
		{
			return $this->_tableName;
		}
		/**
		* Magic method __get
		*
		* @param string $prop
		* 
		* @return mixed
		* @throws EabException
		*/
		public function __get($prop)
		{
			if (isset($this->_modelFields[$prop])) {
				return $this->_modelFields[$prop];
			}
			else{
				throw new EabException("Property not found!", EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}
		}
		/**
		* Magic method __set
		*
		* @param string $prop
		* @param array $args
		* 
		* @return void
		*/
		public function __set($prop, $args)
		{
			$this->_modelFields[$prop] = $args;
		}

	}
?>