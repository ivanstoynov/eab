<?php

	include_once('class.EblElemValidator.php');

	/**
	 * Class implement form validation operations
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage Validation
	 */
	class EblFormValidator
	{
		/**
		 * @var array
		 */
		private $_validators;
		
		/**
		 * Constructor of class (private)
		 */
		public function __construct()
		{
			$this->_validators = array();
		}

		/**
		 * Make validator, if not exist, and return them
		 *
		 * @param string
		 * @return EblElemValidator
		 */
		public function getValidator($field)
		{
			if (! isset($this->_validators[$field])) {
				$this->_validators[$field] = new EblElemValidator($field);
			}
			return $this->_validators[$field];
		}

		/**
		 * validate method
		 *
		 * @param string
		 * @return boolean
		 */
		public function validate()
		{
			if (empty($this->_validators)) {
				return true;
			}
			
			$isValid = true;
			foreach ($this->_validators as $validator) {
				if (! $validator->validate()) {
					$isValid = false;
				}
			}
			return $isValid;
		}
		/**
		 * reset validators (same as clear)
		 *
		 * @return void
		 */
		public function reset()
		{
			$this->_validators = array();
		}
		/**
		 * clear validators (same clear)
		 *
		 * @return void
		 */
		public function clear()
		{
			$this->_validators = array();
		}
	}
?>