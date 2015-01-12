<?php

	include_once(dirname(__FILE__).'/class.EblChekableList.php');
	include_once(dirname(__FILE__).'/class.EblOptionComponent.php');
	
	/**
	 * Class of html select element
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblSelectComponent extends EblHtmlComponent
	{
		/**
		* Options of select component
		* 
		* @var array
		*/
		private $_options;
		/**
		* Is multiple list
		* 
		* @var boolean
		*/
		private $_multiple;		
		/**
		* Constructor of class
		* 
		* @param string
		* @param array
		* @param array
		*/
		public function __construct($name, $options = array(), $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->_options = $options;
			$this->_multiple = FALSE;
		}
		/**
		* Display method - print the select element
		*
		* @param array
		* @return void
		*/
		public function printHtml()
		{
			$name = $this->getName();
			if (TRUE === $this->_multiple) {
				$this->addAttribute('multiple', 'multiple');
				$name .= '[]';
			}
			$this->addAttribute('name', $name);
			$attributesString = $this->getAttributesAsString();
			
			echo '<select ' . $attributesString . ' />' . "\n";
			foreach ($this->_options as $option) {
				$option->printHtml();
			}
			echo '</select>' . "\n";
		}
		/**
		* Add option element
		* 
		* @param EblOptionComponent
		* @return EblSelectComponent;
		*/
		public function addOption(EblOptionComponent $option)
		{
			$this->_options[] = $option;
			return $this;
		}
		/**
		* Set options (setter)
		*
		* @param array
		* @return EblSelectComponent
		*/
		public function setOptions($options)
		{
			$this->_options = $options;
			return $this;
		}
		/**
		* Get options (getter)
		*
		* @return array
		*/
		public function getOptions()
		{
			return $this->_options;
		}
		/**
		* Set is multiple component
		*
		* @param boolean $multiple
		* @return EblSelectComponent
		*/		
		public function setMultiple($multiple)
		{
			$this->_multiple = $multiple;
			return $this;
		}
		/**
		* get text position (getter)
		*
		* @return string
		*/
		public function getMultiple()
		{
			return $this->_multiple;
		}			
	}
?>