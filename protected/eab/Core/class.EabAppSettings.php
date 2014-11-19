<?php
	/**
	 * Class with application settings
	 *
	 * @category   Settings
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabAppSettings
	{
		/** @var string Directory separator */
		private $_ds;
		/** @var string Path separator */
		private $_ps;
		/** @var boolean Is debug mode */
		private $_isDebugMode;
		/** @var string Key of url path */
		private $_urlPathKey;
		/** @var string Separator of url path */
		private $_urlPathSep;
		/** @var string Default layout */
		private $_defaultLayout;
		/** @var string Modules directory path */
		private $_modulesDir; // old moduls
		/** @var string Sections directory path */
		private $_sectionsDir;
		/** @var string Controllers directory path */
		private $_controllersDir;
		/** @var string Views directory path */
		private $_viewsDir;
		/** @var string Layouts directory path */
		private $_layoutsDir;
		/** @var string Styles directory path */
		private $_stylesDir;
		/** @var string Js directory path */
		private $_jsDir;
		/** @var string Images directory path */
		private $_imagesDir;
	
	
		/**
		 * Constructor of class
		 *
		 * @param array
		 */
		public function __construct($settings = array())
		{
			$this->loadDefaultSettings();
			$this->setProperties($settings);
		}
		/**
		 * Set class properties from assoc array
		 *
		 * @param array
		 * @return void
		 */
		public function setSettings($settings = array())
		{
			foreach($settings as $k => $v){
				$prop = '_'.$this->$k;
				if(property_exists($prop)){
					$this->{$prop} = $v;
				}
			}
		}
		/**
		 * Reset settings (to default)
		 *
		 * @return void
		 */
		public function reset()
		{
			$this->loadDefaultSettings();
		}
		/**
		 * Load default settings
		 *
		 * @return void
		 */
		public function loadDefaultSettings()
		{
			$ds = DIRECTORY_SEPARATOR;
			$root_dir = dirname($_SERVER['SCRIPT_FILENAME']).$ds;
			
			$this->_ds = DIRECTORY_SEPARATOR;
			$this->_ps = PATH_SEPARATOR;
			$this->_isDebugMode = true;
			$this->_urlPathKey = 'page';
			$this->_urlPathSep = '/';
			$this->_defaultLayout = 'master.layout.php';
			$this->_modulesDir = $root_dir.'protected'.$ds.'library'.$ds.'moduls'.$ds;
			$this->_sectionsDir = $root_dir.'protected'.$ds.'sections'.$ds;
			$this->_controllersDir = $root_dir.'protected'.$ds.'controllers'.$ds;
			$this->_viewsDir = $root_dir.'protected'.$ds.'views'.$ds;
			$this->_layoutsDir = $root_dir.'protected/views/_layouts/';
			$this->_stylesDir = $root_dir.'public/styles/';
			$this->_jsDir = $root_dir.'public/js/';
			$this->_imagesDir = $root_dir.'public/images/';
		}
		/**
		 * Add setting
		 *
		 * @param string
		 * @param string
		 * @return EabAppSettings
		 */
		public function addSetting($settingKey, $value)
		{
			$this->{$settingKey} = $value;
			return $this;
		}
		/**
		 * magic method __call use to create getter and setter methods
		 *
		 * @param string
		 * @param array
		 * @return EabAppSettings
		 */
		public function __call($func, $args)
		{
			$prefix = strtolower(substr($func, 0, 3));
			$suffix = lcfirst(substr($func,3, strlen($func)));
			if(($prefix == 'get' || $prefix == 'set') && property_exists($this, '_'.$suffix) ){
				$prefix = strtolower(substr($func, 0, 3));

				if('get' == $prefix){
					return $this->{'_'.$suffix};
				}
				else{
					if(empty($args)){
						// todo: exception here
					}
					$this->{$suffix} = reset($args);
					return $this;
				}
			}
			else {
				// todo: exception here
			}
		}
	}
?>