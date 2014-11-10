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
		public function __constuct($msg, $code, $isDebug)
		{
			if($isDebug){
				$msg = $this->appendBacktraceToMsg($msg);
			}
			parent::__constuct($msg, $code);
		}
		
		private function appendBacktraceToMsg($msg)
		{
			// @TODO: da dobavq footera na exceptiona
			return $msg;
		}
	}

?>