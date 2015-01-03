<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Custom validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblCustomValidator extends EblValidator
	{
		/**
		* Validation callback
		* 
		* @var string
		*/
		private $_validationCallback;
		
		/**
		* Constructur of class
		* 
		* @param string
		* @param string
		* @param string|null
		* @return void
		*/
		public function __construct($value, $validationCallback, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
			$this->_validationCallback = $validationCallback;
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			$result = call_user_func($this->_validationCallback, $this->_value);
			if (false === $result) {
				$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage :  "Invalid value!";
				return FALSE;
			}

			return TRUE;
		}
	}
?>