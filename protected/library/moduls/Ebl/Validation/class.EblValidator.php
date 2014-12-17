<?php

	include_once(dirname(__FILE__).'/interface.IEblValidator.php');

	/**
	 * Class describe html element validator
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage Validation
	 */
	abstract class EblValidator implements IEblValidator
	{
		/**
		* @var array
		*/
		private $_errors;
		/**
		* @var string
		*/
		private $_value;
	    /**
		* Set validation value
		* 
		* @param mixed
		* @return void
		*/
		public function setValue($value)
		{
			$this->_value = $value;
		}
	}
?>