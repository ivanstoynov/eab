<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	 * @sience 1.0.1
	 * Class describe html upload field
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblUploadComponent extends EblHtmlComponent
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name, $attributes = array())
		{
			parent::__construct($attributes);
			$this->setName((string) $name);
		}
		/**
		 * Display method - print the upload field
		 *
		 * @param array
		 * @return void
		 */
		public function printHtml()
		{
			$this->setAttribute('name', $this->_name);
			$attributesString = $this->getAttributesAsString();
			echo '<input type="file" ' . $attributesString . ' />' . "\n";
		}
	}
?>