<?php

	include_once dirname(__FILE__) . '/../class.EblValidator.php';

	/**
	* Range validator class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	class EblRangeValidator extends EblValidator
	{
		/**
		* @var float|integer|null
		*/
		private $_from;
		/**
		* @var float|integer|null
		*/
		private $_to;
		
		/**
		* Constructur of class
		* 
		* @param float|integer $value
		* @param float|integer|null $from
		* @param float|integer|null $to
		* @param string|null $errorMessage
		* 
		* @return void
		*/
		public function __construct($value, $from, $to, $errorMessage = null)
		{
			parent::__construct($value, $errorMessage);
			$this->_from = $from;
			$this->_to = $to;
		}
		
		/**
		* Validate value
		* 
		* @return boolean
		*/
		public function validate()
		{
			if (NULL !== $this->_from  && NULL !== $this->_to ) {
				if ($this->_value < $this->_from || $this->_value > $this->_to) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Value must be in interval[' . $this->_from . ';' . $this->_to . ']!';
				}
			}
			elseif (NULL !== $this->_from) {
				if ($this->_value < $this->_from) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Value must be greater or equal then ' . $this->_from . '!';
				}
			}
			else {
				if ($this->_value > $this->_to) {
					$this->_validationErrors[] = ! empty($this->_errorMessage) ? $this->_errorMessage : 'Value must be less or equal then ' . $this->_to . '!';
				}
			}
			
			return empty($this->_validationErrors);
		}
	}

?>