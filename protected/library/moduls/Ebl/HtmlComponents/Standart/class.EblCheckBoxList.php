<?php

	include_once(dirname(__FILE__).'/class.EblChekableList.php');
	include_once(dirname(__FILE__).'/class.EblCheckBoxComponent.php');

	/**
	* @sience 1.0.1
	* Class describe html check box list
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	class EblCheckBoxList extends EblChekableList
	{
		/**
		* Constructor of class
		*
		* @param string
		* @param array
		*/
		public function __construct($name, $elements = array(), $attributes = array())
		{
			parent::__construct($name, $elements, $attributes);
			parent::setMultiple(TRUE);
		}
	}
?>