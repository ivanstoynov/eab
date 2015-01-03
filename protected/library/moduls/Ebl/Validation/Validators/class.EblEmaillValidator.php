<?php

	include_once(dirname(__FILE__).'/../class.EblValidator.php');

	/**
	* Email validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblEmaillValidator extends EblValidator
	{
		/**
		* Constructur of class
		* 
		* @param string
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
			if (function_exists('filter_var')) {
				if (! filter_var($this->_value, FILTER_VALIDATE_EMAIL)) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Invalid e-mail address!';
				}
			}
			else{
				$expression = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)) {255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)) {65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.) {1,126}) {1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {7})|(?:(?!(?:.*[a-f0-9][:\\]]) {7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {5}:)|(?:(?!(?:.*[a-f0-9]:) {5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}) {0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))) {3}))\\]))$/iD';
				
				$result = preg_match($expression, $this->_value);
				if (0 === $result) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Invalid e-mail address!';
				}
			}
			
			return empty($this->_validationErrors);
		}
	}
?>