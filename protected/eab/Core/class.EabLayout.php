<?php
	/**
	 * Layout class
	 *
	 * @category   Core
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabLayout extends EabAssigner
	{
		private $_content;
		private $_headData;
		private $_layout;

		
		public function __construct($layout)
		{
			parent::__construct();
			$this->_layout = $layout;
		}
		public function render()
		{
			$layoutFile = Eab::normalizeDir(EabConfigurator::Instance()->get('layouts_dir')).$this->_layout;
			if(!is_file($layoutFile)){
				throw new EabException('Layout file "'.$layoutFile.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			include_once($layoutFile);
		}
		
		public function displayHeadTitle()
		{
			echo '<title>'.$this->_headData['title'].'</title>';
		}
		public function displayHeadMetaTags()
		{
			if(!empty($this->_headData['meta_tags'])&&is_array($this->_headData['meta_tags'])){
				foreach($this->_headData['meta_tags'] as $tag_data){
					$attr=' ';
					foreach($tag_data as $k=>$v){
						$attr.=$k.'="'.$v.'" ';
					}
					echo '<meta '.$attr.' />';
				}
			}
		}
		public function displayHeadJs()
		{
		}
		public function displayHeadStyles()
		{
		}

		public function getContent()
		{
			return $this->_content;
		}
		public function setContent($content)
		{
			$this->_content = $content;
		}
		public function getHeadData()
		{
			return $this->_headData;
		}
		public function setHeadData($head_data)
		{
			$this->_headData = $head_data;
			return $this;
		}
	}
?>