<?php

	include_once(dirname(__FILE__).'/class.EblErrorPrinter.php');

	/**
	* EblListErrorPrinter class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl/Printers
	* @subpakage ErrorPrinters
	*/
	class EblListErrorPrinter extends EblErrorPrinter
	{
		/**
		* Class of ul element
		* 
		* @var string
		*/
		private $_ulClass;
		
		/**
		* Constructur of class
		* 
		* @param array $errors
		* @return void
		*/
		public function __construct($errors)
		{
			parent::__construct($errors);
			$this->_ulClass = 'form-error';
		}
		/**
		* Print error
		* 
		* @return void
		*/
		public function printError()
		{
			if (empty($this->_errors)) {
				return;
			}
			
			echo "\t\t" . '<ul class="' . $this->_ulClass . '">' . "\n";
			foreach ($this->_errors as $error) {
				echo "\t\t\t" . '<li>'.$error . '</li>' . "\n";
			}
			echo "\t\t" . '</ul>';
		}
		/**
		* Set ulClass (setter)
		* 
		* @param string $ulClass
		* @return EblListErrorPrinter
		*/
		public function setUlClass($ulClass)
		{
			$this->_ulClass = $ulClass;
			return $this;
		}
		/**
		* Get ulClass (getter)
		* 
		* @return string
		*/
		public function getUlClass()
		{
			return $this->_ulClass;
		}
	}
?>