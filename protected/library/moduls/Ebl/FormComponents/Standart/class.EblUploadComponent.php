<?php

	include_once dirname(__FILE__) . '/../class.EblFormComponent.php';

	/**
	 * @sience 1.0.1
	 * Class describe html upload field
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblUploadComponent extends EblFormComponent
	{
		 /**
		 * Constructor of class
		 * 
		 * @param string $name
		 * @param array $attributes
		 * 
		 * @return void
		 */
		public function __construct($name, $attributes = array())
		{
			parent::__construct($name, $attributes);
		}
		/**
		 * Print upload field html
		 *
		 * @return void
		 */
		public function render()
		{
			$this->addAttribute('name', $this->_name);
			$attributesString = $this->getAttributesAsString();
			echo '<input type="file" ' . $attributesString . ' />' . "\n";
		}
	}
?>