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
		public function __construct($name,$text,$attributes=array())
		{
			$this->setText(strval($text));
			$this->setName(strval($name));
			parent::__construct($attributes);
		}
		/**
		 * Print textarea
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes=array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(),$attributes));

			$this->setAttribute('name',$this->getName());
			$att_str=$this->getAttributesAsString();
			echo '<textarea '.$att_str.'>'.strval($this->getText())."\n".'</textarea>';
		}
	}
?>