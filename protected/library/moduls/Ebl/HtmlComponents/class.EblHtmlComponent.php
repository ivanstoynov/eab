<?php

	/**
	 * This is abstract class describe basic html component.
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents
	 */
	abstract class EblHtmlComponent
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
		 * Constructor of class
		 * 
		 * @param array
		 */
		public function __construct($attributes = array())
		{
			$this->setAttributes($attributes);
			$this->_name = '';
			$this->_value = '';
			$this->_text = '';
			$this->_visible = TRUE;
			$this->_attributes = array();
		}
		/**
		 * Abstract metod
		 * 
		 * @param array
		 */
		abstract public function display($arrtibutes = array());
		/**
		 * Static method to render component
		 * 
		 * @param EblHtmlComponent
		 * @param array
		 */
		public static function RenderComponent(EblHtmlComponent $componenet, $arrtibutes=array())
		{
			$componenet->display($arrtibutes);
		}
		/**
		 * Add attribute
		 *
		 * @param string
		 * @param string
		 * @return EblHtmlComponent
		 */
		public function addAttribute($key, $val)
		{
			$this->_attributes[$key] = $val;
			return $this;
		}
		/**
		 * Add attribute (same like addAttribute)
		 *
		 * @param string
		 * @param string
		 * @return EblHtmlComponent
		 */
		public function setAttribute($key, $val)
		{
			$this->addAttribute($key, $val);
			return $this;
		}
		/**
		 * Remove attribute (if exist)
		 *
		 * @param string
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
		 * @param string
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
		 * Handle and set value from request
		 *
		 * @return void
		 */
		public function handleRequestValue()
		{
			$val = $_REQUEST[$this->getName()];
			$this->setValue($val);
		}
		/**
		 * Set attributes (setter)
		 *
		 * @param array
		 * @return EblHtmlComponent
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
		 * @param string
		 * @return EblHtmlComponent
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
		 * @param string
		 * @return EblHtmlComponent
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
		 * @param string
		 * @return EblHtmlComponent
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