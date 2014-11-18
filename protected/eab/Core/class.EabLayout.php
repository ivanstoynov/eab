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
		/**
		 * Layout (file name)
		 * 
		 * @var string
		 */
		private $_layoutFile;
		/**
		 * Layout content
		 * 
		 * @var string
		 */
		private $_content;
		/**
		 * Layout head
		 * 
		 * @var EabLayoutHead
		 */
		private $_layoutHead;

		/**
		 * Constructor of class
		 *
		 * @param string
		 */
		public function __construct($layoutFile)
		{
			parent::__construct();
			$this->_layoutFile = $layoutFile;
			$this->_content = '';
			$this->_layoutHead = new EabLayoutHead();
		}
		public function render()
		{
			$layoutFile = Eab::normalizeDir(EabConfigurator::Instance()->get('layouts_dir')).$this->_layoutFile;
			if(!is_file($layoutFile)){
				throw new EabException('Layout file "'.$layoutFile.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			include_once($layoutFile);
		}
		/**
		 * Get content (getter)
		 *
		 * @return string
		 */
		public function getContent()
		{
			return $this->_content;
		}
		/**
		 * Set content (setter)
		 *
		 * @param string
		 * @return EabLayout
		 */
		public function setContent($content)
		{
			$this->_content = $content;
			return $this;
		}
		/**
		 * Get layoutHead (getter)
		 *
		 * @return EabLayoutHead
		 */
		public function getLayoutHead()
		{
			return $this->_layoutHead;
		}
		/**
		 * Set layoutHead (setter)
		 *
		 * @param EabLayoutHead
		 * @return EabLayout
		 */
		public function setLayoutHead(EabLayoutHead $layoutHead)
		{
			$this->_layoutHead = $layoutHead;
			return $this;
		}
	}
?>