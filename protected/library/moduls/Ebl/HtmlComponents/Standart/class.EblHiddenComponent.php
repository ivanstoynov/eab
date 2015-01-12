<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	* @sience 1.0.1
	* Class describe html hiden field
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	class EblHiddenComponent extends EblHtmlComponent
	{
		/**
		* Constructor of class
		*
		* @param string
		* @param string
		* @param array
		*/
		public function __construct($name, $value, $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->setValue($value);
		}
		/**
		* Print hidden field
		*
		* @param array
		* @return void
		*/
		public function printHtml()
		{
			$this->addAttribute('name', $this->getName());
			$this->addAttribute('value', $this->getValue());
			$attributesString = $this->getAttributesAsString();
			echo '<input type="hidden" ' . $attributesString . ' />' . "\n";
		}
	}
?>