<?php
	/**
	 * IEblElemValidator interface
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage Validation
	 */
	interface IEblElemValidator
	{
		/**
		 * Add rule.
		 *
		 * @param EblValidationRulesTypes
		 * @param string
		 * @param string
		 * @return EblElemValidator
		 */
		public function addRule($type, $expression = null, $errMgs = null);
		/**
		 * Validate html field. If element is valid return true, otherwise 
		 * return false and set errors.
		 *
		 * @return boolean
		 */
		public function validate();
		/**
		 * Validate this field. If element is valid return true, 
		 * otherwise return false and set errors.
		 *
		 * @return boolean
		 */
		public function isValid();
		/**
		 * Print validation errors 
		 *
		 * @return void
		 */
		public function displayErrors();
	}
?>