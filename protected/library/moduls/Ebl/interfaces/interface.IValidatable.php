<?php
	/**
	* IValidatable interface
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	*/
	interface IValidatable
	{
		/**
		* Validate data
		* 
		* @return boolean
		*/
		public function validate();
		/**
		* Get validation errors
		* 
		* @return array
		*/
		public function getValidationErrors();
	}
?>