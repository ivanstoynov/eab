<?php

	include_once(dirname(__FILE__).'/class.EblListComponent.php');
	include_once(dirname(__FILE__).'/class.EblRadioButtonComponent.php');
	
	/**
	 * @sience 1.0.1
	 * Class describe html list of radio buttons
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblRadioButtonList extends EblListComponent
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name='', $elems=array())
		{
			parent::__construct($name,$elems);
			$this->_selected_index=null;
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
		public function addElem($label,$value,$checked,$attributes=array())
		{
			$rbtn=new EblRadioButtonComponent($this->getName(),$label,$value,$checked,$attributes);
			$this->addComponent($rbtn);
		}
		/**
		 * Add radio button to list
		 *
		 * @param EblRadioButton
		 * @return void
		 */
		public function addRadioButton(EblRadioButton $rbtn)
		{
			$this->addComponent($rbtn);
		}
	}
?>