<?php

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