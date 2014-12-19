<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Equal validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblEqualValidator extends EblValidator
	{
		/**
		* This is a equaled value
		* 
		* @var mixed
		*/
		private $_comparableValue;
		/**
		* Indicate for strict comparation
		* 
		* @var boolean
		*/
		private $_strictCompare;
		
		/**
		* Constructur of class
		* 
		* @param mixed
		* @param mixed
		* @param boolean
		* @return void
		*/
		public function __construct($value, $comparableValue, $strictCompare = TRUE)
		{
			parent::__construct($value);
			$this->_comparableValue = $comparableValue;
			$this->strictCompare = $strictCompare;
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			$value = $this->getValue();
			
			$isEqual = TRUE;
			if (TRUE === $this->_strictCompare) {
				if ($value !== $this->_comparableValue) {
					$isEqual = FALSE;
				}
			}
			else {
				if ($value != $this->_comparableValue) {
					$isEqual = FALSE;
				}
			}
			
			if (FALSE === $isEqual) {
				$this->_errors[] = 'Value must be equal!';
			}
			
			return $isEqual;
		}
	}
?>