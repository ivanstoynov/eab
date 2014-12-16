<?php

	include_once(dirname(__FILE__).'/../class.EblHtmlComponent.php');

	/**
	 * @sience 1.0.1
	 *
	 * Abstract class describe html list component
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage HtmlComponents/Standard
	 */
	abstract class EblListComponent extends EblHtmlComponent
	{
		/**
		 * @var array
		 */
		private $_elements;
		/**
		 * Position of label (left|right)
		 *
		 * @var string
		 */
		private $_textPosition;
		/**
		 * Position of label (horizontal|vertical)
		 *
		 * @var string
		 */
		private $_direction;

		
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name = '', $elements = array())
		{
			parent::__construct();
			$this->setName((string) $name);
			$this->_elements = $elements;
		}
		/**
		 * Abstract method to add element 
		 *
		 * @param string
		 * @param string
		 * @param bolean
		 * @param array
		 */
		abstract public function addElement($label, $value, $selected, $attributes = array());
		/**
		 * Display method - print the list
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes = array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(), $attributes));
			
			$atts = $this->getAttributes();
			if (empty($atts['class'])) {
				$class = 'listCompPanel';
				$direction = strtolower($this->_direction);
				if ($direction === 'vertical') {
					$class .= ' listCompVertical';
				}
				else {
					$class .= ' listCompHorizontal';
				}
				$this->setAttribute('class', $class);
			}

			$name = $this->getName();
			$pos = strpos($name, '[');
			if (FALSE !== $pos) {
				$name = substr($name, 0, $pos);
			}

			//$i = 1;
			$attributesString = $this->getAttributesAsString();

			echo '<div ' . $attributesString . '>' . "\n";
			foreach ($this->_elements as $element) {
				
				//$id=$rbtn_elem->getAttributeByKey('id');
				//if (is_null($id)) {
					//$id=$name.'_'.$i++;
					//$rbtn_elem->setAttribute('id',$id);
				//}
				$element->setTextPosition($this->_textPosition);
				$element->display();
			}
			echo '</div>' . "\n";
		}
		/**
		 * Add component to list
		 *
		 * @param EblHtmlComponent
		 * @return void
		 */
		public function addComponent(EblHtmlComponent $component)
		{
			$this->_elements[] = $component;
		}
		/**
		 * Clear list
		 *
		 * @return void
		 */
		public function clear()
		{
			$this->setValue(NULL);
			
			foreach ($this->_elements as $element) {
				$element->setSelected(FALSE);
			}
		}

		/**
		 * Handle and set value from request
		 *
		 * @return void
		 */		
		public function handleRequestValue()
		{
			$name = $this->getName();
			$value = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
			if (is_array($value)) {
				foreach ($value as $k => $val) {
					$value[$k] = (string) $val;
					foreach ($this->_elements as $element) {
						if ($element->getValue() === $val) {
							$element->setSelected(TRUE);
						}
						else {
							$element->setSelected(FALSE);
						}
					}
				}
			}
			else {
				$value = (string) $value;
				foreach ($this->_elements as $element) {
					if ($element->getValue() === $value) {
						$element->setSelected(TRUE);
					}
					else {
						$element->setSelected(FALSE);
					}
				}
			}
			$this->setValue($value);
		}
		/**
		 * Set elements (setter)
		 *
		 * @param array
		 * @return EblListComponent
		 */
		public function setElements($elements)
		{
			$this->_elements = $elements;
			return $this;
		}
		/**
		 * get selected index (getter)
		 *
		 * @return array
		 */
		public function getElements()
		{
			return $this->_elements;
		}
		/**
		 * Set Text position (setter)
		 *
		 * @todo throw exception if value is not valid
		 * @param string
		 * @return EblListComponent
		 */		
		public function setTextPosition($position)
		{
			$position = strtolower((string) $position);
			if (! in_array($position, array('left', 'right'))) {
				// todo: throw exception
			}

			$this->_textPosition = $position;
			return $this;
		}
		/**
		 * get text position (getter)
		 *
		 * @return string
		 */
		public function getTextPosition()
		{
			return $this->_textPosition;
		}
		/**
		 * Set direction (setter)
		 *
		 * @todo throw exception if value is not valid
		 * @param string
		 * @return EblListComponent
		 */	
		public function setDirection($direction)
		{
			$direction = strtolower((string) $direction);
			if (! in_array($direction, array('horizontal', 'vertical'))) {
				// todo: throw exception
			}
			$this->_direction = strtolower($direction);
		}
		/**
		 * get direction (getter)
		 *
		 * @return string
		 */
		public function getDirection()
		{
			return $this->_direction;
		}
	}
?>