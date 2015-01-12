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
		* Message to displayed
		* 
		* @var string
		*/
		protected $_errorMessage;
		/**
		* @var array
		*/
		protected $_validationErrors;
		/**
		* @var string
		*/
		protected $_value;
		
		/**
		* Constructur of class
		* 
		* @return
		*/
		public function __construct($value, $errorMessage = null)
		{
			$this->_value = $value;
			$this->_errorMessage = $errorMessage;
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