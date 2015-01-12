<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	 * @sience 1.0.1
	 * Class describe html textarea element
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblTextareaComponent extends EblHtmlComponent
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param string
		 * @param array
		 */
		public function __construct($name, $text, $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->setText((string) $text);
		}
		/**
		 * Print textarea
		 *
		 * @param array
		 * @return void
		 */
		public function printHtml()
		{
			$this->addAttribute('name', $this->getName());
			$attributesString = $this->getAttributesAsString();
			echo '<textarea ' . $attributesString . '>' . $this->getText() . "\n" . '</textarea>';
		}
	}
?>