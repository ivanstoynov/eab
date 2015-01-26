<?php

	include_once dirname(__FILE__) . '/../class.EblValidator.php';

	/**
	* Length validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblLengthValidator extends EblValidator
	{
		/**
		* @var integer|null
		*/
		private $_minLength;
		/**
		* @var integer|null
		*/
		private $_maxLength;
		
		/**
		* Constructur of class
		* 
		* @param string $value
		* @param integer|null $minLength
		* @param integer|null $maxLength
		* @param string|null $errorMessage
		* 
		* @return void
		*/
		public function __construct($value, $minLength, $maxLength, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
			$this->_minLength = $minLength;
			$this->_maxLength = $maxLength;
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			$length = strlen($this->_value);

			if (NULL !== $this->_minLength  && NULL !== $this->_maxLength ) {
				if ($length < $this->_minLength || $length > $this->_maxLength) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Length must be in interval[' . $this->_minLength . ';' . $this->_maxLength.']!';
				}
			}
			elseif (NULL !== $this->_minLength) {
				if ($length < $this->_minLength) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Min length is ' . $this->_minLength . '!';
				}
			}
			else {
				if ($length > $this->_maxLength) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Max length is ' . $this->_maxLength . '!';
				}
			}
			
			return empty($this->_validationErrors);
		}
	}

?>