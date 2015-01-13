<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Regular expression validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblRegExpValidator extends EblValidator
	{
		/**
		* Regual expression pattern
		* 
		* @var string
		*/
		private $_regExp;
		
		/**
		* Constructur of class
		* 
		* @param string value
		* @param string $regExp
		* @param string|null $errorMessage
		* 
		* @return void
		*/
		public function __construct($value, $regExp, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
			$this->_regExp = $regExp;
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			$result = preg_match($this->_regExp, $this->_value);
			if (0 === $result) {
				$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Invalid value!';
			}
			elseif (false === $result) {
				throw new Exception('Invalid regexp pattern!');
			}

			return empty($this->_validationErrors);
		}
	}
?>