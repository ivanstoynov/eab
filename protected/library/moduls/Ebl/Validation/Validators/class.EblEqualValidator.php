<?php

	include_once dirname(__FILE__) . '/../class.EblValidator.php';

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
		* @param mixed $value
		* @param mixed $comparableValue
		* @param boolean $strictCompare
		* @param string|null $errorMessage
		* 
		* @return void
		*/
		public function __construct($value, $comparableValue, $strictCompare = TRUE, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
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
			$isEqual = TRUE;
			if (TRUE === $this->_strictCompare) {
				if ($this->_value !== $this->_comparableValue) {
					$isEqual = FALSE;
				}
			}
			else {
				if ($this->_value != $this->_comparableValue) {
					$isEqual = FALSE;
				}
			}
			
			if (FALSE === $isEqual) {
				$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage :  'Value must be equal!';
			}
			
			return $isEqual;
		}
	}
?>