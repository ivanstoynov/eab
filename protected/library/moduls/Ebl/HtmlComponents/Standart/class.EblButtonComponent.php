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
		 * @param string
		 * @param string
		 * @param array
		 */
		public function __construct($name,$value,$attributes=array())
		{
			parent::__construct($attributes);
			$this->setName($name);
			$this->setValue($value);
		}
		/**
		 * Display method - print the button
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes=array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(),$attributes));

			$this->setAttribute('name',$this->getName());
			$this->setAttribute('value',$this->getValue());
			$att_str=$this->getAttributesAsString();
			echo '<input type="button" '.$att_str.' />'."\n";
		}
	}
?>