<?php

	include_once(dirname(__FILE__).'/class.EblListComponent.php');
	include_once(dirname(__FILE__).'/class.EblCheckBoxComponent.php');

	/**
	 * @sience 1.0.1
	 * Class describe html check box list
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblCheckBoxList extends EblListComponent
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name = '', $elems = array())
		{
			parent::__construct($name, $elems);
		}
		/**
		 * Add element to list
		 *
		 * @param string
		 * @param string
		 * @param boolean
		 * @param array
		 * @return void
		 */		
		public function addElement($label, $value, $selected, $attributes = array())
		{
			$cbx = new EblCheckBoxComponent($this->getName(), $label, $value, (boolean) $selected, $attributes);
			$this->addComponent($cbx);
		}
		/**
		 * Add check box to list
		 *
		 * @param EblCheckBox
		 * @return void
		 */
		public function addCheckBox(EblCheckBox $cbx)
		{
			$this->addComponent($cbx);
		}
	}
?>