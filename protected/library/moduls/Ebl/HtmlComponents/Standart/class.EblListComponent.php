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
			$this->setName($name);
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
		abstract public function addElem($label, $value, $checked, $attributes = array());
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

			if (empty($atts['class'])){
				$class = 'listCompPanel';
				$direction = strtolower($this->_direction);
				if ($direction === 'vertical'){
					$class .= ' listCompVertical';
				}
				else {
					$class .= ' listCompHorizontal';
				}
				$this->setAttribute('class', $class);
			}

			$name = $this->getName();
			$pos = strpos($name, '[');
			if (FALSE !== $pos){
				$name = substr($name, 0, $pos);
			}

			$i = 1;
			$attributesString = $this->getAttributesAsString();

			echo '<div ' . $attributesString . '>' . "\n";
			foreach($this->_elems as $elem){
				
				//$id=$rbtn_elem->getAttributeByKey('id');
				//if(is_null($id)){
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
			foreach ($this->_elems as $elem){
				$elem->setSelected(FALSE);
			}
		}
		/**
		 * Set selected index (setter)
		 *
		 * @param integer
		 * @return EblListComponent
		 */
		public function setSelectedIndex($index)
		{
			$index = (int) $index;
			$i = 0;
			// Mark elem as selected
			foreach ($this->_elems as $elem){
				if ($i === $index){
					$elem->setSelected(TRUE);
				}
				$i++;
			}
			
			//if($index<$i){
				$this->_selectedIndex = $index;
			//}
			return $this;
		}
		/**
		 * get selected index (getter)
		 *
		 * @return integer
		 */
		public function getSelectedIndex()
		{
			return $this->_selectedIndex;
		}
		/**
		 * Set selected value (setter)
		 *
		 * @param string
		 * @return EblListComponent
		 */
		public function setSelectedValue($value)
		{
			$found = FALSE;
			// Mark elem as selected
			foreach ($this->_elems as $elem){
				if ($value === $elem->getValue()) {
					$elem->setSelected(FALSE);
					$found = TRUE;
				}
			}
			//if($found){
				$this->_selectedValue = $value;
			//}
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
			$position = strtolower($position);
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
			$direction = strtolower($direction);
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