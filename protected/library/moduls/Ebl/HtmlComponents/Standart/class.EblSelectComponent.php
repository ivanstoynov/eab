<?php

	include_once(dirname(__FILE__).'/class.EblListComponent.php');
	include_once(dirname(__FILE__).'/class.EblOptionComponent.php');
	
	/**
	 * Class describe html select element
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblSelectComponent extends EblListComponent
	{
		/**
		 * Constructor of class
		 * 
		 * @param string
		 * @param array
		 * @param array
		 */
		public function __construct($name, $options = array(), $attributes = array())
		{
			parent::__construct($attributes);
			$this->setName((string) $name);
			$this->setElems($options);
			$this->_selectedIndex = NULL;
			$this->_selectedValue = NULL;			
		}
		/**
		 * Add option element
		 * 
		 * @param EblOptionComponent
		 * @return EblSelectComponent;
		 */
		public function addOption(EblOptionComponent $option)
		{
			$this->addElem($option);
			return $this;
		}
		/**
		 * Display method - print the select element
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes = array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(), $attributes));
			
			$this->setAttribute('name', $this->getName());
			$attributesString = $this->getAttributesAsString();
			
			echo '<select ' . $attributesString . ' />' . "\n";
			foreach ($this->getElems() as $option) {
				$option->display();
			}
			echo '</select>' . "\n";
		}
		/**
		 * Abstract method to add element 
		 *
		 * @param string
		 * @param string
		 * @param bolean
		 * @param array
		 */
		public function addElem($label, $value, $selected, $attributes = array())
		{
			$option = new EblOptionComponent($value, (string) $label, (boolean)$selected, $attributes);
			$this->addElem($option);
		}
		/**
		 * Set options (setter)
		 *
		 * @param array
		 * @return EblSelectComponent
		 */
		public function setOptions($options)
		{
			$this->setElems($options);
			return $this;
		}
		/**
		 * Get options (getter)
		 *
		 * @return array
		 */
		public function getOptions()
		{
			return $this->getElems();
		}
	}
?>