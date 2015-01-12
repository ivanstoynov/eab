<?php
	/**
	* IValidatable interface
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	*/
	interface IValidatable
	{
		public function validate();
		
		public function getValidationErrors();
	}
?>