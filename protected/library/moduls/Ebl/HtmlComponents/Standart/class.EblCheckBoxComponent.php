<?php

	include_once(dirname(__FILE__).'/../class.EblCheckableComponent.php');
	
	/**
	* @sience 1.0.1
	*
	* Class describe html check box element
	*
	* @author Ivan Stoyanov <iv44@yahoo.com>
	* @pakage Ebl
	* @subpakage HtmlComponents/Standard
	*/
	class EblCheckBoxComponent extends EblCheckableComponent
	{
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
		public function __construct($name, $label, $value, $checked = FALSE, $attributes = array())
		{
			parent::__construct($name, $label, $value, $checked, $attributes);
		}
		/**
		* Print checkbox as html
		*
		* @param array
		* 
		* @return void
		*/
		public function printHtml()
		{
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
			$input = '<input type="checkbox" ' . $attStr . ' />';
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
		* Print checkbox clearly, without label
		*
		* @return void
		*/
		public function printClearlyHtml()
		{
			if (TRUE === $this->getChecked()) {
				$this->addAttribute('checked', 'checked');
			}
			else {
				$this->removeAttribute('checked');
			}
			
			$attributesString = $this->getAttributesAsString();
			echo '<input type="checkbox" ' . $attributesString . ' />' . "\n";
		}
	}
?>