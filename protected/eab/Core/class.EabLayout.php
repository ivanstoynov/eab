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

		
		public function __construct($layout=false)
		{
			parent::__construct();
			if(false!==$layout){
				$this->_layout=$layout;
			}
			else{
				$this->_layout=EabConfigurator::Instance()->get('default_layout');
			}
		}
		public function render()
		{
			$layout_file=Eab::NormalizeDir(EabConfigurator::Instance()->get('layouts_dir')).$this->_layout;
			if(!is_file($layout_file)){
				throw new EabException('Layout file "'.$layout_file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			include_once($layout_file);
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