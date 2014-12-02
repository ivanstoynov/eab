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
	class EabModel extends EabAssigner
	{
		private $_dbAdapter;
		private $_tableName;
		private $_tableColumns;
		private $_pkColumn;

		public function __construct()
		{
			$this->_pkColumn = 'id';
			$this->_tableColumns = array();
			if (empty($this->_tableName)) {
				$this->_loadTableNameFromClass();
			}
			if (empty($this->_tableColumns)) {
				$this->_loadTableColumnsFromDb();
			}
		}
		
		private function _loadTableNameFromClass()
		{
			$class = strtolower(get_class($this));
			if (substr($class, -5, 5) !== 'model') {
				throw new EabException('Model class must be ended with "model"!', EabExceptionCodes::UNKNOWN_EXC);
			}
			$this->_tableName = $class . 's';
		}
		
		private function _loadTableColumnsFromDb()
		{
			//$result = mysql_query("SHOW TABLES LIKE 'myTable'");
			//$sql="SHOW COLUMNS FROM authors";
		}

		public function load()
		{
			$stmt = $this->createPkStatement();
			if (! $stmt) {
				return FALSE;
			}

			$sql = "SELECT * FROM `" . $this->_tableName . "` WHERE " . $stmt . " LIMIT 1";
			$row = $this->_dbAdapter->fetchRow($sql);
			$this->loadFromArray($row);

			return TRUE;
		}

		public function save()
		{
			$stmt = $this->createPkStatement();
			if (! $stmt) {
				$sql = "INSERT INTO `" . $this->_tableName . "` (`" . implode('`,`', $this->_tableColumns) . "`) VALUES \n";
				$sql.= "(";
				foreach ($this->_tableColumns as $col) {
					if (is_null($this->{$col})) $sql .= "null,";
					else $sql .= "'" . stripslashes($this->{$col}) . "',";
				}
				$sql .= substr($values, 0, -1) . ")";
				$this->_dbAdapter->exec($sql);

				if (strtolower($this->_pkColumn) === 'id') {
					$this->id = $this->_dbAdapter->lastInsertId();
				}
			}
			else{
				$sql = "UPDATE `" . $this->_tableName . "` SET \n";
				foreach ($this->_tableColumns as $col) {
					if (! is_null($this->{$col})) {
						$sql .= "'" . stripslashes($this->{$col}) . "',";
					}
				}

				$sql .= substr($values, 0, -1) . " WHERE " . $stmt . " LIMIT 1";
				$this->_dbAdapter->exec($sql);
			}
		}
		public function delete()
		{
			$sql = "DELETE FROM `" . $this->_tableName . "` WHERE " . $where_expr . " LIMIT 1";
			$this->_dbAdapter->exec($sql);
		}
		
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
			$res = $this->_dbAdapter->query($sql);
			while ( ( ($row = $res->fetchRow()) {
				$model = new $class();
				$model->loadFromArray($row);
				$models[] = $model;
			}

			return $models;
		}

		private function createPkStatement()
		{
			if (empty($this->_pkColumn)) {
				throw new EabException('Primary key column for model "' . get_class($this) . '" is empty!', EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}

			$stmt = '';
			if (is_array($this->_pkColumn)) {
				if (empty($id)) {
					throw new EabException('Key column is empty"' . $this->_pkColumn . '" for model "' . get_class($this) . '"!', EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
				}
				foreach ($this->_pkColumn as $col) {
					$stmt = $col . "='" . addslashes($this->{$col}) . "'";
				}
				$stmt = substr($stmt, 0, -1);
			}
			else{
				$stmt = $this->_pkColumn . "='" . addslashes($this->{$this->_pkColumn}) . "'";
			}

			return $stmt;
		}
		
		public function loadFromArray($data)
		{
			foreach ($data as $col => $val) {
				$this->assign($col, $val);
			}
		}
		
		public function setPkColumn($column)
		{
			$this->_pkColumn = $column;
		}
		public function getPkColumn()
		{
			return $this->_pkColumn;
		}
		public function setTableName($tableName)
		{
			$this->_tableName = $tableName;
		}
		public function getTableName()
		{
			return $this->_tableName;
		}
		public function setTableColumn($tableColumns)
		{
			$this->_tableColumns = $tableColumns;
		}
		public function getTableColumn()
		{
			return $this->_tableColumns;
		}
	}
?>