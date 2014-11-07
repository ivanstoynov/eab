<?php

	include_once(dirname(__FILE__).'/class.EblHtmlComponent.php');

	/**
	 * @sience 1.0.1
	 *
	 * Abstract Class describe checkable component
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	abstract class EblCheckableComponent extends EblHtmlComponent
	{
		/**
		 * @var string
		 */
		private $_text_position;
		/**
		 * @var string
		 */
		private $_label;
		/**
		 * @var boolean
		 */
		private $_checked;

		
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param string
		 * @param string
		 * @param boolean
		 * @param array
		 */
		public function __construct($name,$label,$value,$checked=false,$attributes=array())
		{
			parent::__construct($attributes);
			$this->setName($name);
			$this->_label=$label;
			$this->setValue($value);
			$this->_checked=$checked;
			$this->_text_position='left';
		}
		/**
		 * Handle and set value from request
		 *
		 * @return void
		 */	
		public function handleRequestValue()
		{
			$val=$_REQUEST[$this->getName()];
			if($this->getValue()==$val){
				$this->_checked=true;
			}
		}
		/**
		 * Set text position (setter)
		 *
		 * @param string
		 * @return EblCheckableComponent
		 */
		public function setTextPosition($position)
		{
			$this->_text_position=$position;
			return $this;
		}
		/**
		 * Get text position (getter)
		 *
		 * @return void
		 */
		public function getTextPosition()
		{
			return $this->_text_position;
		}
		/**
		 * Set label (setter)
		 *
		 * @param string
		 * @return EblCheckableComponent
		 */
		public function setLabel($label)
		{
			$this->_label=strval($label);
			return $this;
		}
		/**
		 * Get label (getter)
		 *
		 * @return void
		 */
		public function getLabel()
		{
			return $this->_label;
		}
		/**
		 * Set text position (setter)
		 *
		 * @param boolean
		 * @return EblCheckableComponent
		 */
		public function setChecked($checked)
		{
			$this->_checked=(boolean)$checked;
			return $this;
		}
		/**
		 * Get checked (getter)
		 *
		 * @return void
		 */
		public function getChecked()
		{
			return $this->_checked;
		}
	}
?>