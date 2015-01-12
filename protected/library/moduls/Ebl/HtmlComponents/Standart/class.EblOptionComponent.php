<?php

	include_once(dirname(__FILE__).'/../../interfaces/interface.IHtmlPrintable.php');

	/**
	* Class describe html select element
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	class EblOptionComponent implements IHtmlPrintable
	{
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
		private $_selected;
		/**
		* @var array
		*/
		private $_attributes;

		
		/**
		* Constructor of class
		* 
		* @param string
		* @param string
		* @param boolean
		* @param array
		*/
		public function __construct($value, $text, $selected = FALSE, $attributes = array())
		{
			$this->_value = $value;
			$this->_text = $text;
			$this->_selected = (boolean) $selected;
			$this->_attributes = $attributes;
		}
		/**
		* Display method - print the option element
		*
		* @param array
		* @return void
		*/
		public function printHtml()
		{
			if (TRUE === $this->_selected) {
				$this->_attributes['selected'] = 'selected';
			}
			elseif (isset($this->_attributes['selected'])) {
				unset($this->_attributes['selected']);
			}
			
			$this->_attributes['value'] = $this->_value;
			
			$attributesString = '';
			foreach ($this->_attributes as $attribute => $attributeValue) {
				$attributesString .= $attribute . '="' . ((string) $attributeValue) . '" ';
			}
			echo '<option ' . $attributesString . '>' . $this->getText() . '</option>' . "\n";
		}
		/**
		* Set selected (setter)
		*
		* @param string
		* @return EblOptionComponent
		*/
		public function setSelected($selected)
		{
			$this->_selected = (boolean) $selected;
			return $this;
		}
		/**
		* Get selected (getter)
		*
		* @return void
		*/
		public function getSelected()
		{
			return $this->_selected;
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