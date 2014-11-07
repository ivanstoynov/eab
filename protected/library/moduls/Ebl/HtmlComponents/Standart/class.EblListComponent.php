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
		private $_elems=array();
		/**
		 * Position of label (left|right)
		 *
		 * @var string
		 */
		private $_text_position;
		/**
		 * Position of label (horizontal|vertical)
		 *
		 * @var string
		 */
		private $_direction;
		/**
		 * @var integer
		 */
		private $_selected_index;
		/**
		 * @var string
		 */
		private $_selected_value;

		
		/**
		 * Constructor of class
		 *
		 * @param string
		 * @param array
		 */
		public function __construct($name='', $elems=array())
		{
			parent::__construct();
			$this->setName($name);
			$this->_elems=$elems;
			$this->_selected_index=null;
			$this->_selected_value=null;
		}
		/**
		 * Abstract method to add element 
		 *
		 * @param string
		 * @param string
		 * @param bolean
		 * @param array
		 */
		abstract public function addElem($label,$value,$checked,$attributes=array());
		/**
		 * Display method - print the list
		 *
		 * @param array
		 * @return void
		 */
		public function display($attributes=array())
		{
			// Append new attributes
			$this->setAttributes(array_merge($this->getAttributes(),$attributes));
			$atts = $this->getAttributes();

			if(empty($atts['class'])){
				$class='listCompPanel';
				$direction=strtolower($this->_direction);
				if($direction=='vertical'){
					$class.=' listCompVertical';
				}
				else{
					$class.=' listCompHorizontal';
				}
				$this->setAttribute('class',$class);
			}

			$name=$this->getName();
			$pos=strpos($name,'[');
			if(false!==$pos){
				$name=substr($name,0,$pos);
			}

			$i=1;
			$att_str=$this->getAttributesAsString();

			echo '<div '.$att_str.'>'."\n";
			foreach($this->_elems as $elem){
				
				//$id=$rbtn_elem->getAttributeByKey('id');
				//if(is_null($id)){
					//$id=$name.'_'.$i++;
					//$rbtn_elem->setAttribute('id',$id);
				//}
				$elem->setTextPosition($this->_text_position);
				$elem->display();
			}
			echo '</div>'."\n";
		}
		/**
		 * Add component to list
		 *
		 * @param EblHtmlComponent
		 * @return void
		 */
		public function addComponent(EblHtmlComponent $comp)
		{
			$this->_elems[]=$comp;
		}
		/**
		 * Clear list
		 *
		 * @return void
		 */
		public function clear()
		{
			$this->_selected_value=null;
			$this->_selected_index=null;
			foreach($this->_elems as $elem){
				$elem->setSelected(false);
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
			$i=0;
			// Mark elem as selected
			foreach($this->_elems as $elem){
				if($i==$index){
					$elem->setSelected(false);
				}
				$i++;
			}
			
			//if($index<$i){
				$this->_selected_index=$index;
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
			return $this->_selected_index;
		}
		/**
		 * Set selected value (setter)
		 *
		 * @param string
		 * @return EblListComponent
		 */
		public function setSelectedValue($value)
		{
			$found=false;
			// Mark elem as selected
			foreach($this->_elems as $elem){
				if($value==$elem->getValue()){
					$elem->setSelected(false);
					$found=true;
				}
			}
			//if($found){
				$this->_selected_value=$value;
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
			return $this->_selected_value;
		}
		/**
		 * Set elements (setter)
		 *
		 * @param array
		 * @return EblListComponent
		 */
		public function setElems($elems)
		{
			$this->_elems=$elems;
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
			$position=strtolower($position);
			if(!in_array($position,array('left','right'))){
				// todo: throw exception
			}

			$this->_text_position=$position;
			return $this;
		}
		/**
		 * get text position (getter)
		 *
		 * @return string
		 */
		public function getTextPosition()
		{
			return $this->_text_position;
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
			$direction=strtolower($direction);
			if(!in_array($direction,array('horizontal','vertical'))){
				// todo: throw exception
			}
			$this->_direction=strtolower($direction);
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