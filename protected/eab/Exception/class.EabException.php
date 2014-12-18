<?php
	/**
	* EabException class
	*
	* @category   Exception
	* @package    Eab
	* @author     Ivan Stoyanov <iv44@yahoo.com>
	* @copyright  2010-2014 Stoqnoff
	* @since      1.0.0
	*/
	class EabException extends Exception
	{
		/**
		* Constructor of class
		*
		* @param string $msg
		* @param integer $code
		*/
		public function __constuct($msg, $code/*, $isDebug*/)
		{
			//if ($isDebug) {
				$msg = $this->appendBacktraceToMsg($msg);
			//}
			parent::__constuct($msg, $code);
		}
		/**
		* Append backtrace to message
		*
		* @param string $msg
		* @return string
		*/
		private function appendBacktraceToMsg($msg)
		{
			// @TODO: da dobavq footera na exceptiona
			return $msg;
		}
	}

?>