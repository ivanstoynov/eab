<?php
	/**
	 * Class describe html select element
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblOptionComponent extends EblHtmlComponent
	{
		/**
		 * @var boolean
		 */
		private $_selected;

		
		/**
		 * Constructor of class
		 * 
		 * @param string
		 * @param string
		 * @param boolean
		 * @param array
		 */
		public function __construct($value, $text, $selected = false, $attributes = array())
		{
			parent::__construct($attributes);
			$this->setValue((string) $value);
			$this->setText((string) $text);
			$this->setSelected((boolean) $selected);
		}
		/**
		 * Display method - print the option element
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes = array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(), $attributes));

			if ($this->getSelected() === TRUE) {
				$this->setAttribute('selected', 'selected');
			}
			else{
				$this->removeAttribute('selected');
			}
			
			$this->setAttribute('value', strval($this->getValue()));
			$attributesString = $this->getAttributesAsString();
			echo '<option ' . $attributesString . '>' . $this->getText() . '</option>' . "\n";
		}
		/**
		 * Rewrite empty
		 *
		 * @return void
		 */
		public function handleRequestValue()
		{
		}
		/**
		 * Set selected (setter)
		 *
		 * @param string
		 * @return EblOptionComponent
		 */
		public function setSelected($selected)
		{
			$this->_selected = (boolean) $selected;
			return $this;
		}
		/**
		 * Get selected (getter)
		 *
		 * @return void
		 */
		public function getSelected()
		{
			return $this->_selected;
		}
	}
?>