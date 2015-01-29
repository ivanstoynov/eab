<?php

	include_once dirname(__FILE__) . '/../class.EblHtmlComponent.php';
	include_once dirname(__FILE__) . '/../Validation/interface.IEblValidator.php';
	include_once dirname(__FILE__) . '/../Interfaces/interface.IRequestHandable.php';

	/**
	* This is abstract class describe basic html component.
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents
	*/
	abstract class EblFormComponent extends EblHtmlComponent implements IValidatable, IRequestHandable
	{
		/**
		* @var string
		*/
		private $_name;
		/**
		* @var string
		*/
		private $_value;
		/**
		* @var string
		*/
		private $_text;
		/**
		* @var boolean
		*/
		private $_visible;
		/**
		* @var array
		*/
		private $_attributes;
		/**
		* @var array
		*/
		private $_validators;
		/**
		* Validation errors
		* 
		* @var array
		*/
		private $_validationErrors;

		/**
		* Constructor of class
		* 
		* @param string $name
		* @param array $attributes
		* 
		* @return void
		*/
		public function __construct($name, $attributes = array())
		{
			$this->_name = $name;
			$this->_value = '';
			$this->_text = '';
			$this->_visible = TRUE;
			$this->_attributes = $attributes;
			$this->_validators = array();
			$this->_validationErrors = array();
		}
		/**
		* Add attribute
		*
		* @param string $key
		* @param string $value
		* 
		* @return EblFormComponent
		*/
		public function addAttribute($key, $value)
		{
			$this->_attributes[$key] = $value;
			return $this;
		}
		/**
		* Add attribute (same like addAttribute)
		*
		* @param string $key
		* @param string $value
		* 
		* @return EblFormComponent
		*/
		public function setAttribute($key, $value)
		{
			$this->_attributes[$key] = $value;
			return $this;
		}
		/**
		* Remove attribute (if exist)
		*
		* @param string $key
		* 
		* @return void
		*/
		public function removeAttribute($key)
		{
			if (isset($this->_attributes[$key])) {
				unset($this->_attributes[$key]);
			}
		}
		/**
		* Clear attributes
		*
		* @return void
		*/
		public function clearAttributes()
		{
			$this->_attributes = array();
		}
		/**
		* Get attribute by key
		*
		* @param string $key
		* 
		* @return boolean
		*/
		public function getAttributeByKey($key)
		{
			return isset($this->_attributes[$key]) ? $this->_attributes[$key] : null;
		}
		/**
		* Get attributes as string
		*
		* @return string
		*/
		public function getAttributesAsString()
		{
			$attStr = ' ';
			$attributes = $this->getAttributes();
			foreach ($attributes as $att => $val) {
				$attStr .= $att . '="' . ((string) $val) . '" ';
			}
			return $attStr;
		}
		/**
		* Handle request
		*
		* @return void
		*/
		public function handleRequest()
		{
			$value = $_REQUEST[$this->getName()];
			$this->setValue($value);
			$this->updateValidatorValues();
		}
		/**
		* Validate element
		* 
		* @return boolean
		*/
		public function validate()
		{
			$isValid = TRUE;
			$validationErrors = array();
			foreach ($this->_validators as $validator) {
				$isValid = $isValid && $validator->validate();
				$validationErrors = array_merge($validationErrors, $validator->getValidationErrors());
			}
			$this->_validationErrors = $validationErrors;
			return $isValid;
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
		* Add validator to component
		* 
		* @param EblValidator $validator
		* 
		* @return EblFormComponent
		*/
		public function addValidator(EblValidator $validator)
		{
			$this->_validators[] = $validator;
			return $this;
		}
		/**
		* Update validators and set current value
		* 
		* @return void
		*/
		public function updateValidatorValues()
		{
			foreach ($this->_validators as &$validator) {
				$validator->setValue($this->_value);
			}
		}
		/**
		* Set attributes (setter)
		*
		* @param array $attributes
		* 
		* @return EblFormComponent
		*/
		public function setAttributes($attributes)
		{
			$this->_attributes = $attributes;
			return $this;
		}
		/**
		* Get attributes (getter)
		*
		* @return void
		*/
		public function getAttributes()
		{
			return $this->_attributes;
		}
		/**
		* Set name (setter)
		*
		* @param string $name
		* 
		* @return EblFormComponent
		*/
		public function setName($name)
		{
			$this->_name = (string) $name;
			return $this;
		}
		/**
		* Get name (getter)
		*
		* @return void
		*/
		public function getName()
		{
			return (string) $this->_name;
		}
		/**
		* Set value (setter)
		*
		* @param string $value
		* 
		* @return EblFormComponent
		*/
		public function setValue($value)
		{
			$this->_value = (string) $value;
			return $this;
		}
		/**
		* Get value (getter)
		*
		* @return void
		*/
		public function getValue()
		{
			return $this->_value;
		}
		/**
		* Set text (setter)
		*
		* @param string $text
		* 
		* @return EblFormComponent
		*/
		public function setText($text)
		{
			$this->_text = (string) $text;
			return $this;
		}
		/**
		* Get text (getter)
		*
		* @return void
		*/
		public function getText()
		{
			return (string) $this->_text;
		}
	}
?>