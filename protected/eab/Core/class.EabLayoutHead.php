<?php
	/**
	 * LayoutHead class
	 *
	 * @category   Core
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabLayoutHead
	{
		/**
		 * Application title
		 * 
		 * @var string
		 */
		private $_title;
		/**
		 * Meta tags
		 * 
		 * @var array
		 */
		private $_metaTags;
		/**
		 * Styles
		 * 
		 * @var string
		 */
		private $_styles;
		/**
		 * JavaScripts
		 * 
		 * @var string
		 */
		private $_js;
		
		/**
		 * Constructor of class
		 *
		 * @param string
		 */
		public function __construct()
		{
			$this->_title = '';
			$this->_metaTags = array();
			$this->_styles = array();
			$this->_js = array();
		}
		/**
		 * Display title
		 *
		 * @return void
		 */
		public function displayTitle()
		{
			echo "\t".'<title>'.$this->_headData['title'].'</title>'."\n";
		}
		/**
		 * Display meta tags
		 *
		 * @return void
		 */
		public function displayMetaTags()
		{
			if(empty($this->_metaTags) || !is_array($this->_metaTags)){
				return;
			}

			foreach($this->_metaTags as $tagData){
				$attr = ' ';
				foreach($tagData as $k => $v){
					$attr.= $k.'="'.$v.'" ';
				}
				echo "\t".'<meta '.$attr.' />'."\n";
			}
		}
		/**
		 * Display styles
		 *
		 * @return void
		 */
		public function displayStyles()
		{
			if(empty($this->_styles)){
				return;
			}
			
			$styles = $this->_styles;
			if(!is_array($styles)){
				$styles = array($styles);
			}
			
			foreach($styles as $style){
				if(!is_array($style)){
					echo "\t".'<link rel="stylesheet" href="'.$style.'" type="text/css" media="all" />'."\n";	
				}
				else{
					$attr = ' ';
					foreach($style as $k => $v){
						$attr.= $k.'="'.$v.'" ';
					}
					echo "\t".'<link '.$attr.' />'."\n";	
				}
			}
		}
		/**
		 * Display javascripts
		 *
		 * @return void
		 */
		public function displayJs()
		{
			if(empty($this->_js)){ 
				return;
			}

			$js = $this->_js;
			if(!is_array($js)){
				$js = array($js);
			}

			foreach($js as $jsSrc){
				echo "\t".'<script type="text/javascript" src="'.$jsSrc.'"></script>'."\n";	
			}
		}
		/**
		 * Get title (getter)
		 *
		 * @return string
		 */
		public function getTitle()
		{
			return $this->_title;
		}
		/**
		 * Set title (setter)
		 *
		 * @param string
		 * @return EabLayoutHead
		 */
		public function setTitle($title)
		{
			$this->_title = $title;
			return $this;
		}
		/**
		 * Get meta tags (getter)
		 *
		 * @return array
		 */
		public function getMetaTags()
		{
			return $this->_metaTags;
		}
		/**
		 * Set meta tags (setter)
		 *
		 * @param array
		 * @return EabLayoutHead
		 */
		public function setMetaTags($metaTags)
		{
			$this->_metaTags = $metaTags;
			return $this;
		}
		/**
		 * Get styles (getter)
		 *
		 * @return array
		 */
		public function getStyles()
		{
			return $this->_styles;
		}
		/**
		 * Set styles (setter)
		 *
		 * @param array
		 * @return EabLayoutHead
		 */
		public function setMetaTags($styles)
		{
			$this->_styles = $styles;
			return $this;
		}
		/**
		 * Get js (getter)
		 *
		 * @return array
		 */
		public function getJs()
		{
			return $this->_js;
		}
		/**
		 * Set js (setter)
		 *
		 * @param array
		 * @return EabLayoutHead
		 */
		public function setJs($js)
		{
			$this->_js = $js;
			return $this;
		}
	}
?>