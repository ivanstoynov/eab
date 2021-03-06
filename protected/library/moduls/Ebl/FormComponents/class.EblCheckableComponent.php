<?php

	include_once dirname(__FILE__) . '/class.EblFormComponent.php';

	/**
	* @sience 1.0.1
	*
	* Abstract Class describe checkable component
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	abstract class EblCheckableComponent extends EblFormComponent
	{
		/**
		* @var string
		*/
		private $_textPosition;
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
		* @param string $name
		* @param string $label
		* @param string $value
		* @param boolean $checked
		* @param array $attributes
		* 
		* @return void
		*/
		public function __construct($name, $label, $value = null, $checked = FALSE, $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->_label = (string) $label;
			$this->setValue($value);
			$this->_checked = (boolean) $checked;
			$this->_textPosition = 'left';
		}
		/**
		* Rewrite request handling
		*
		* @return void
		*/
		public function handleRequest()
		{
			$value = isset($_REQUEST[$this->getName()]) ? $_REQUEST[$this->getName()] : NULL;
			$this->setValue($value);
			if (isset($value)) {
				$this->_checked = TRUE;
			}
			else {
				$this->_checked = FALSE;
			}
			$this->updateValidatorValues();
		}
		/**
		* Set text position (setter)
		*
		* @param string $textPosition
		* 
		* @return EblCheckableComponent
		*/
		public function setTextPosition($textPosition)
		{
			$this->_textPosition = (string) $textPosition;
			return $this;
		}
		/**
		* Get text position (getter)
		*
		* @return void
		*/
		public function getTextPosition()
		{
			return (string) $this->_textPosition;
		}
		/**
		* Set label (setter)
		*
		* @param string $label
		* 
		* @return EblCheckableComponent
		*/
		public function setLabel($label)
		{
			$this->_label = (string) $label;
			return $this;
		}
		/**
		* Get label (getter)
		*
		* @return void
		*/
		public function getLabel()
		{
			return (string) $this->_label;
		}
		/**
		* Set text position (setter)
		*
		* @param boolean $checked
		* 
		* @return EblCheckableComponent
		*/
		public function setChecked($checked)
		{
			$this->_checked = (boolean) $checked;
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