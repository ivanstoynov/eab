<?php

	include_once(dirname(__FILE__).'/class.EblChekableList.php');
	include_once(dirname(__FILE__).'/class.EblRadioButtonComponent.php');
	
	/**
	 * @sience 1.0.1
	 * Class describe html list of radio buttons
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblRadioButtonList extends EblChekableList
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
			parent::setMultiple(FALSE);
		}
	}
?>