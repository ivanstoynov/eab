<?php

	include_once(dirname(__FILE__).'/../interfaces/interface.IValidatable.php');

	/**
	* IEblValidator interface
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage Validation
	*/
	interface IEblValidator extends IValidatable
	{
		/**
		* Set validation value
		* 
		* @param mixed
		* @return void
		*/
		public function setValue($value);
	}
?>