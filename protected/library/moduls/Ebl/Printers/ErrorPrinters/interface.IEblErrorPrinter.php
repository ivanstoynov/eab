<?php
	/**
	* IEblErrorPrinter interface
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl/Printers
	* @subpackage ErrorPrinters
	*/
	interface IEblErrorPrinter
	{
		/**
		* Print html
		* 
		* @return void
		*/
		public function printError();
	}
?>