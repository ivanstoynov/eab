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
		private $_elems;
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
		 * @var integer
		 */
		private $_selectedIndex;
		/**
		 * @var string
		 */
		private $_selectedValue;

		
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name='', $elems = array())
		{
			parent::__construct();
			$this->setName((string) $name);
			$this->_elems = $elems;
			$this->_selectedIndex = NULL;
			$this->_selectedValue = NULL;
		}
		/**
		 * Abstract method to add element 
		 *
		 * @param string
		 * @param string
		 * @param bolean
		 * @param array
		 */
		abstract public function addElem($label, $value, $selected, $attributes = array());
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
			foreach ($this->_elems as $elem) {
				
				//$id=$rbtn_elem->getAttributeByKey('id');
				//if (is_null($id)) {
					//$id=$name.'_'.$i++;
					//$rbtn_elem->setAttribute('id',$id);
				//}
				$elem->setTextPosition($this->_textPosition);
				$elem->display();
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
			$this->_elems[] = $component;
		}
		/**
		 * Clear list
		 *
		 * @return void
		 */
		public function clear()
		{
			$this->_selectedValue = NULL;
			$this->_selectedIndex = NULL;
			foreach ($this->_elems as $elem) {
				$elem->setSelected(FALSE);
			}
		}

		/**
		 * Handle and set value from request
		 *
		 * @return void
		 */		
		public function handleRequestValue()
		{
			$this->clear();
			
			$name = $this->getName();
			$selectedValue = isset($_REQUEST[$name]) ? $_REQUEST[$name] : '';
			if (! is_array($selectedValue)) {
				$selectedIndex = array();
				foreach ($selectedValue as $k => $value) {
					$selectedValue[$k] = (string) $value;
					$i = 0;
					foreach ($this->_elems as $elem) {
						if ($elem->getValue() === $value) {
							$selectedIndex[] = $i;
						}
						$i++;
					}
				}
				$this->_selectedIndex = $selectedIndex;
			}
			else {
				$selectedValue = (string) $selectedValue;
				foreach ($this->_elems as $elem) {
					if ($elem->getValue() === $selectedValue) {
						$this->_selectedIndex = $i;
						break;
					}
					$i++;
				}
			}
		}
		/**
		 * Set selected index (setter)
		 *
		 * @param integer|array
		 * @return EblListComponent
		 */
		public function setSelectedIndex($selectedIndex)
		{
			$this->clear();

			if (is_array($selectedIndex)) {
				$selectedValues = array();
				// Mark elem as selected
				foreach ($selectedIndex as $k => $index) {
					$index = (int) $index;
					$selectedIndex[$k] = $index;
					foreach ($this->_elems as $elemIndex => $elem) {
						if ($elemIndex === $index) {
							$elem->setSelected(TRUE);
							$selectedValues[] = $elem->getValue();
						}
					}
				}
				$this->_selectedValue = ! empty($selectedValues) ? $selectedValues : NULL;
			}
			else{
				$selectedIndex = (int) $selectedIndex;
				foreach ($this->_elems as $elemIndex => $elem) {
					if ($elemIndex === $selectedIndex) {
						$elem->setSelected(TRUE);
						$this->_selectedValue = $elem->getValue();
						break;
					}
				}
			}
			
			$this->_selectedIndex = $index;
			return $this;
		}
		/**
		 * get selected index (getter)
		 *
		 * @return integer|array
		 */
		public function getSelectedIndex()
		{
			return $this->_selectedIndex;
		}
		/**
		 * Set selected value (setter)
		 *
		 * @param string|array
		 * @return EblListComponent
		 */
		public function setSelectedValue($selectedValue)
		{
			$this->clear();

			if (is_array($selectedValue)) {
				$selectedIndexes = array();
				foreach ($selectedValue as $k => $value) {
					$value = (string) $value;
					$selectedValue[$k] = $value;
					foreach ($this->_elems as $elemIndex => $elem) {
						if ($value === $elem->getValue()) {
							$elem->setSelected(TRUE);
							$selectedIndexes[] = $elemIndex;
						}
					}
				}
				$this->_selectedIndex = ! empty($selectedIndexes) ? $selectedIndexes : NULL;
			}
			else {
				$selectedValue = (string) $selectedValue;
				foreach ($this->_elems as $elemIndex => $elem) {
					if ($selectedValue === $elem->getValue()) {
						$elem->setSelected(TRUE);
						$this->_selectedIndex = $elemIndex;
						break;
					}
				}
			}
			
			$this->_selectedValue = $selectedValue;

			return $this;
		}
		/**
		 * get selected value (getter)
		 *
		 * @return string
		 */
		public function getSelectedValue()
		{
			return $this->_selectedValue;
		}
		/**
		 * Set elements (setter)
		 *
		 * @param array
		 * @return EblListComponent
		 */
		public function setElems($elems)
		{
			$this->_elems = $elems;
			return $this;
		}
		/**
		 * get selected index (getter)
		 *
		 * @return array
		 */
		public function getElems()
		{
			return $this->_elems;
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