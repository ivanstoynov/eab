<?php

	include_once('class.EblElemValidator.php');

	/**
	 * Singleton class, describe form validator
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage Validation
	 */
	class EblFormValidator
	{
		/**
		 * @var EblFormValidator
		 */
		private static $_instance;
		/**
		 * @var array
		 */
		private $_validators;
		
		/**
		 * Constructor of class (private)
		 */
		private function __construct()
		{
			$this->_validators=array();
		}
	
		private function __clone(){}
		/**
		 * Get instance
		 *
		 * @return EblFormValidator
		 */
		public static function GetInstance()
		{
			if(!isset(self::$_instance)){
				self::$_instance=new self();
			}
			return self::$_instance;
		}
		/**
		 * make method
		 *
		 * @param string
		 * @return EblElemValidator
		 */
		public function make($field)
		{
			if(!isset($this->_validators[$field])){
				$this->_validators[$field]=new EblElemValidator($field);
			}
			return $this->_validators[$field];
		}
		/**
		 * validate method
		 *
		 * @param string
		 * @return boolean
		 */
		public function validate()
		{
			if(empty($this->_validators)) return true;
			$is_valid=true;
			foreach($this->_validators as $validator){
				if(!$validator->validate()){
					$is_valid=false;
				}
			}
			return $is_valid;
		}
		/**
		 * reset validators (same as clear)
		 *
		 * @return void
		 */
		public function reset()
		{
			$this->_validators=array();
		}
		/**
		 * clear validators (same clear)
		 *
		 * @return void
		 */
		public function clear()
		{
			$this->_validators=array();
		}
	}
?>