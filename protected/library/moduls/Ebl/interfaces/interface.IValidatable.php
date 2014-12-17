<?php

	interface IValidatable
	{
		public function validate();
		
		public function getValidationErrors();
	}
?>