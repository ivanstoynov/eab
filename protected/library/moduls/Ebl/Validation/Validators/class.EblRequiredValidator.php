<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Required validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblRequiredValidator extends EblValidator
	{
		/**
		* Constructur of class
		* 
		* @param mixed
		* @param string|null
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
			if (empty($this->_value)) {
				$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Required filed!';
				return FALSE;
			}
			
			return TRUE;
		}
	}

?>