<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Numeric validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblNumericValidator extends EblValidator
	{
		/**
		* Constructur of class
		* 
		* @param mixed $value
		* @param string|null $errorMessage
		* 
		* @return void
		*/
		public function __construct($value, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			if (! is_numeric($this->_value)) {
				$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Value must be numeric!';
				return FALSE;
			}
			
			return TRUE;
		}
	}
?>