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
		* Validation errors
		* 
		* @var array
		*/
		private $_validationErrors;		

		/**
		* Constructor of class
		* 
		* @param string|NULL $name
		* 
		* @return void
		*/
		public function __construct($name = NULL)
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
			foreach ($this->_elements as &$element) {
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
		* 
		* @return EblHtmlForm
		*/
		public function addElement(EblHtmlComponent $element)
		{
			$this->_elements[$element->getName()] = $element;
			return $this;
		}
		/**
		* Clear elements
		* 
		* @return void
		*/
		public function clearElements()
		{
			$this->_elements = array();
		}
		/**
		* Validate element
		* 
		* @return boolean
		*/
		public function validate()
		{
			$this->_validationErrors = array();
			foreach ($this->_elements as $element) {
				if (FALSE === $element->validate()) {
					$this->_validationErrors[] = $element->getValidationErrors();
				}
			}
			
			return ! empty($this->_validationErrors);
		}
		/**
		* Get validation errors
		* 
		* @return array
		*/
		public function getValidationErrors()
		{
			return $this->_validationErrors;
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
			if (isset($this->_elements[$prop])) {
				return $this->_elements[$prop];
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
			$this->_elements[$prop] = $args;
		}
	}
?>