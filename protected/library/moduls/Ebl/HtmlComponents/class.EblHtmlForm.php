<?php

	include_once(dirname(__FILE__).'/../interfaces/interface.IValidatable.php');
	
	/**
	* EblHtmlForm class
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents
	*/
	class EblHtmlForm implements IValidatable, IRequestHandable
	{
		/**
		* Form name
		* 
		* @var string
		*/
		private $_name;
		/**
		* Form elements
		* 
		* @var array
		*/
		private $_elements;
		
		/**
		* Constructor of class
		* 
		* @param string|NULL $name
		* @return void
		*/
		private function __construct($name = NULL)
		{
			$this->_name = $name;
			$this->_elements = array();
		}
		/**
		* Handle request
		* 
		* @return void
		*/
		public function handleRequest()
		{
			foreach ($this->_elements as $element) {
				$elementName = $element->getName();
				if (isset($_REQUEST[$elementName])) {
					$element->setValue($_REQUEST[$elementName]);
				}
			}
		}
		/**
		* Add element to form
		* 
		* @param EblHtmlComponent $element
		* @return EblHtmlForm
		*/
		public function addElement(EblHtmlComponent $element)
		{
			$this->_components[] = $element;
			return $this;
		}
		/**
		* Clear elements
		* 
		* @return void
		*/
		public function clearElements()
		{
			$this->_components[] = array();
		}
		/**
		* Validate element
		* 
		* @return boolean
		*/
		public function validate()
		{
		}
		/**
		* Get validation errors
		* 
		* @return array
		*/
		public function getValidationErrors()
		{
		}		
	}
?>