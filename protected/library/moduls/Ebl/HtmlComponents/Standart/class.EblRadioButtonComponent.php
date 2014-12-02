<?php

	include_once(dirname(__FILE__).'/../class.EblCheckableComponent.php');

	/**
	 * @sience 1.0.1
	 * Class describe html radio button
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblRadioButtonComponent extends EblCheckableComponent
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param string
		 * @param string
		 * @param boolean
		 * @param array
		 */
		public function __construct($name, $label, $value, $checked = FALSE, $attributes = array())
		{
			parent::__construct((string) $name, (string) $label, $value, (boolean) $checked, $attributes);
		}
		/**
		 * Display method - print radio button (with lable)
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes = array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(), $attributes));

			if ($this->getChecked() === TRUE) {
				$this->setAttribute('checked', 'checked');
			}
			else {
				$this->removeAttribute('checked');
			}
			
			$this->setAttribute('name', $this->getName());
			$this->setAttribute('value', strval($this->getValue()));
			$attStr = $this->getAttributesAsString();

			//$id=$this->getAttributeByKey('id');
			//$label='<label '.( !is_null($id) ? 'for='.$id : '').' >'.$this->getLabel().'</label>';

			$label = '<label>';
			$input = '<input type="radio" ' . $attStr . ' />';
			if ('left' === $this->getTextPosition()) {
				$label .= "\n\t" . $input . "\n\t" . $this->getLabel() . "\n";
			}
			else {
				$label .= "\n\t" . $this->getLabel() . "\n\t" . $input . "\n";
			}
			$label .= '</label>' . "\n";
			echo $label;
		}
		/**
		 * Print clear radio button, without label
		 *
		 * @param array
		 * @return void
		 */
		public function displayClearly($attributes = array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(), $attributes));

			if (TRUE === $this->_checked) {
				$this->setAttribute('checked', 'checked');
			}
			else {
				$this->removeAttribute('checked');
			}
			
			$attributesString = $this->getAttributesAsString();
			echo '<input type="radio" ' . $attributesString . ' />' . "\n";
		}
	}
?>