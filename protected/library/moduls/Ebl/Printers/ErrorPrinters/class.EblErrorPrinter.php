<?php
	/**
	* EblErrorPrinter interface
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl/Printers
	* @subpackage ErrorPrinters
	*/
	abstract class EblErrorPrinter
	{
		/**
		* Errors array
		* 
		* @var array
		*/
		protected $_errors;
		
		/**
		* Constructur of class
		* 
		* @param array $errors
		* @return void
		*/
		public function __construct($errors)
		{
			$this->_errors = $errors;
		}
		/**
		* Print html
		* 
		* @return void
		*/
		abstract public function printError();
	}
?>