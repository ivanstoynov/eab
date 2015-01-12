<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	 * @sience 1.0.1
	 * Class describe html text field
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblTextComponent extends EblHtmlComponent
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
		 * Display method - print the text field
		 *
		 * @param array
		 * @return void
		 */
		public function printHtml()
		{
			$value = $this->getValue();
			
			$this->addAttribute('name', $this->getName());
			$this->addAttribute('value', $value);
			$attributesString = $this->getAttributesAsString();
			echo '<input type="text" ' . $attributesString . ' />' . "\n";
		}
	}
?>