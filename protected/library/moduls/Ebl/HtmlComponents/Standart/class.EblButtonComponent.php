<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	* @sience 1.0.1
	* Class describe html button
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	class EblButtonComponent extends EblHtmlComponent
	{
		/**
		* Constructor of class
		* 
		* @param string $name
		* @param string $value
		* @param array $attributes
		* 
		* @return void
		*/
		public function __construct($name, $value, $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->setValue($value);
		}
		/**
		* Print button as html
		*
		* @return void
		*/
		public function printHtml()
		{
			$this->addAttribute('name', $this->getName());
			$this->addAttribute('value', $this->getValue());
			$attributesString = $this->getAttributesAsString();
			echo '<input type="button" ' . $attributesString . ' />' . "\n";
		}
	}
?>