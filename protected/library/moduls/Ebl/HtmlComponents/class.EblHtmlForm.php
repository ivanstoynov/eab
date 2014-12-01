<?php

	class EblHtmlForm
	{
		private static $instances=array();

		private $_formName;
		private $_components;
		private $_formValidator;
		
		private function __construct($formName)
		{
			$this->_formName = $formName;
			$this->_components = array();
		}

		private function __clone(){}

		public static function make($formName)
		{
			if (! isset(self::$instances[$formName])){
				self::$instances[$formName] = new self($formName);
			}
			return self::$instances[$formName];
		}
		
		public function handleRequest()
		{
			foreach ($this->_components as $component){
				$name = $component->getName();
				if (isset($_REQUEST[$name])){
					$val = $_REQUEST[$name];
				}
			}
		}

		public function addComponent(EblHtmlComponent $component)
		{
			$this->_components[] = $component;
		}

		public function clearComponent()
		{
			$this->_components[] = array();
		}
	}
?>