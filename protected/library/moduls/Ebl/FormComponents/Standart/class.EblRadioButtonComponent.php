<?php

	include_once dirname(__FILE__) . '/../class.EblCheckableComponent.php';

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
			parent::__construct($name, $label, $value, (boolean) $checked, $attributes);
		}
		/**
		 * Print element html
		 *
		 * @return void
		 */
		public function render()
		{
			if ($this->getChecked() === TRUE) {
				$this->addAttribute('checked', 'checked');
			}
			else {
				$this->removeAttribute('checked');
			}
			
			$this->addAttribute('name', $this->getName());
			$this->addAttribute('value', strval($this->getValue()));
			$attributesString = $this->getAttributesAsString();

			//$id=$this->getAttributeByKey('id');
			//$label='<label '.( !is_null($id) ? 'for='.$id : '').' >'.$this->getLabel().'</label>';

			$label = '<label>';
			$input = '<input type="radio" ' . $attributesString . ' />';
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
		 * @return void
		 */
		public function printClearlyHtml()
		{
			if (TRUE === $this->_checked) {
				$this->addAttribute('checked', 'checked');
			}
			else {
				$this->removeAttribute('checked');
			}
			
			$attributesString = $this->getAttributesAsString();
			echo '<input type="radio" ' . $attributesString . ' />' . "\n";
		}
	}
?>