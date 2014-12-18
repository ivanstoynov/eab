<?php

	include_once(dirname(__FILE__).'/interface.IEblValidator.php');

	/**
	* Class describe html element validator
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	abstract class EblValidator implements IEblValidator
	{
		/**
		* @var array
		*/
		private $_validationErrors;
		/**
		* @var string
		*/
		private $_value;
		
		/**
		* Constructur of class
		* 
		* @return
		*/
		public function __construct($value)
		{
			$this->_value = $value;
		}
	    /**
		* Set validation value
		* 
		* @param mixed
		* @return void
		*/
		public function setValue($value)
		{
			$this->_value = $value;
		}
		/**
		* Get value
		* 
		* @return mixed
		*/
		public function getValue()
		{
			return $this->_value;
		}
		/**
		* Get errors (getter)
		*
		* @param array
		* @return mixed
		*/
		public function getValidationErrors()
		{
			return $this->_validationErrors;
		}		
	}
?>