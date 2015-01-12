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
	abstract class EblChekableList extends EblHtmlComponent
	{
		/**
		* @var array
		*/
		protected $_elements;
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
		* Is multiple list
		* 
		* @var boolean
		*/
		private $_multiple;

		
		/**
		* Constructor of class
		*
		* @param string $name
		* @param array $elements
		* @param array $attributes
		*/
		public function __construct($name, $elements = array(), $attributes = array())
		{
			parent::__construct($name, $attributes);
			$this->_elements = $elements;
			$this->_multiple = FALSE;
		}
		/**
		* Add element 
		*
		* @param EblCheckableComponent $element
		* @return void
		*/
		public function addElement(EblCheckableComponent $element)
		{
			$this->_elements[] = $element;
		}
		/**
		 * Display method - print the list
		 *
		 * @param array
		 * @return void
		 */
		public function printHtml()
		{
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
				$this->addAttribute('class', $class);
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
					//$rbtn_elem->addAttribute('id',$id);
				//}
				
				if (TRUE === $this->_isMultiple ) {
					$element->setName($name . '[]');	
				}
				else {
					$element->setName($name);
				}

				$element->setTextPosition($this->_textPosition);
				$element->printHtml();
			}
			echo '</div>' . "\n";
		}
		/**
		* Add component to list
		*
		* @param EblHtmlComponent $component
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
		* Rewrite request handling
		*
		* @return void
		*/
		public function handleRequest()
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
		* @param array $elements
		* @return EblChekableList
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
		* @param string $position
		* @return EblChekableList
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
		* @param string $direction
		* @return EblChekableList
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
		/**
		* Set is multiple component
		*
		* @param boolean $multiple
		* @return EblChekableList
		*/		
		protected function setMultiple($multiple)
		{
			$this->_multiple = $multiple;
			return $this;
		}
		/**
		* get text position (getter)
		*
		* @return string
		*/
		protected function getMultiple()
		{
			return $this->_multiple;
		}		
	}
?>