<?php
	/**
	 * Base asigner class - define assigns array and __get
	 * and __set magic methods.
	 *
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabAssigner
	{
		private $_assigns=array();
		
		public function __construct()
		{
			$this->_assigns=array();
		}
		public final function assign($key,$val)
		{
			$this->assigns[$key]=$val;
		}
		public final function getAssignes($key)
		{
			return isset($this->assigns[$key]) ? $this->assigns[$key] : null;
		}
		public function __get($prop)
		{
			if(isset($this->_assigns[$prop])){
				return $this->_assigns[$prop];
			}
			else{
				throw new EabException("Property not found!", EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}
		}
		public function __set($prop, $args)
		{
			$this->_assigns[$prop] = $args;
		}
	}
?>