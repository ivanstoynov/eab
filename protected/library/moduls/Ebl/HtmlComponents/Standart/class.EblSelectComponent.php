<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	 * Class describe html select element
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	class EblSelectComponent extends EblHtmlComponent
	{
		/**
		 * @var array
		 */
		private $_options=array();
		/**
		 * @var integer|null
		 */
		private $_selectedIndex;


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
			$this->setName($name);
			$this->_options = $options;
			$this->_selectedIndex = NULL;
		}
		/**
		 * Add option element
		 * 
		 * @param EblOptionComponent
		 * @return EblSelectComponent;
		 */
		public function addOption(EblOptionComponent $option)
		{
			$this->_options[] = $option;
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
			$attStr = $this->getAttributesAsString();
			
			echo '<select ' . $attStr . ' />' . "\n";
			foreach ($this->_options as $option){
				$option->display();
			}
			echo '</select>' . "\n";
		}
		/**
		 * Handle and set value from request
		 *
		 * @return void
		 */		
		public function handleRequestValue()
		{
			$val = $_REQUEST[$this->getName()];
			if (is_array($val)){
				foreach ($val as $v){
					$i = 0;
					foreach ($this->_options as $option){
						if ($options->getValue() === $v){
							$this->_selectedIndex = $i;
						}
						$i++;
					}
				}
			}
			else{
				$i = 0;
				foreach ($this->_options as $option){
					if ($options->getValue() === $val){
						$this->_selectedIndex = $i;
					}
					$i++;
				}
			}
		}
		/**
		 * Get selected index (getter)
		 *
		 * @return void
		 */
		public function getSelectedIndex()
		{
			return $this->_selectedIndex;
		}
		/**
		 * Set options (setter)
		 *
		 * @param array
		 * @return EblSelectComponent
		 */
		public function setOptions($options)
		{
			$this->_options=$options;
			return $this;
		}
		/**
		 * Get options (getter)
		 *
		 * @return void
		 */
		public function getOptions()
		{
			return $this->_options;
		}
	}
	
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

			if($this->getSelected() === TRUE){
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
		 * Empty method
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