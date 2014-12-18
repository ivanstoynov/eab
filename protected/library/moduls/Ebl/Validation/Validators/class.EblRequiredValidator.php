<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Required validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblValidator extends EblValidator
	{
		/**
		* Constructur of class
		* 
		* @param mixed
		* @return void
		*/
		public function __construct($value)
		{
			parent::__construct($value);
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			$value = $this->getValue();
			
			if (empty($value)) {
				$this->_errors[] = 'Required filed!';
				return FALSE;
			}
			
			return TRUE;
		}
	}

?>