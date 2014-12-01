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
		/**
		 * Array with assigned vars
		 *
		 * @var array
		 */
		private $_assigns = array();
		
		/**
		 * Constructor of class
		 */
		public function __construct()
		{
			$this->_assigns = array();
		}
		/**
		 * Assign method
		 *
		 * @param string
		 * @param mixed
		 */
		final public function assign($key, $val)
		{
			$this->assigns[$key] = $val;
		}
		/**
		 * Get assigned value
		 *
		 * @param string $key
		 * @return unknown
		 */
		public final function getAssigned($key)
		{
			return isset($this->assigns[$key]) ? $this->assigns[$key] : null;
		}
		/**
		 * Magic method __get
		 *
		 * @param string
		 * @return mixed
		 * @throws EabException
		 */
		public function __get($prop)
		{
			if (isset($this->_assigns[$prop])){
				return $this->_assigns[$prop];
			}
			else{
				throw new EabException("Property not found!", EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}
		}
		/**
		 * Magic method __set
		 *
		 * @param string
		 * @param array
		 * @return void
		 */
		public function __set($prop, $args)
		{
			$this->_assigns[$prop] = $args;
		}
	}
?>