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
			
			if (! is_numeric($value)) {
				$this->_errors[] = 'Value must be numeric!';
				return FALSE;
			}
			
			return TRUE;
		}
	}

?>