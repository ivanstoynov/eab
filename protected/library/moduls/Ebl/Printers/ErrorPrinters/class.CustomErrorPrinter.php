<?php

	include_once dirname(__FILE__) . '/class.EblErrorPrinter.php';

	/**
	* CustumErrorPrinter class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl/Printers
	* @subpakage ErrorPrinters
	*/
	class CustomErrorPrinter extends EblErrorPrinter
	{
		/**
		* Printer callback
		* 
		* @var string
		*/
		private $_printerCallback;
		
		/**
		* Constructur of class
		* 
		* @param array $errors
		* @param callable $printerCallback
		* @return void
		*/
		public function __construct($errors, $printerCallback)
		{
			$this->_errors = $errors;
			$this->_printerCallback = $printerCallback;
		}
		/**
		* Print error
		* 
		* @return void
		*/
		public function printError()
		{
			if (! empty($this->_errors)) {
				call_user_func($this->_printerCallback, $this-_errors);
			}
		}
	}
?>