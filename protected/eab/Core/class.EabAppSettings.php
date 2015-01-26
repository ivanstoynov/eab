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
		/**
		* Directory separator
		* 
		* @var string
		*/
		private $_ds;
		/**
		* Path separator
		* 
		* @var string
		*/
		private $_ps;
		/**
		* Is debug mode
		* 
		* @var boolean
		*/
		private $_isDebugMode;
		/**
		* Key of url path
		* 
		* @var string
		*/
		private $_urlPathKey;
		/**
		* Separator of url path
		* 
		* @var string
		*/
		private $_urlPathSep;
		/**
		* Default layout
		* 
		* @var string
		*/
		private $_defaultLayout;
		/**
		* Modules directory path
		* 
		* @var string
		*/
		private $_modulesDir;
		/**
		* Sections directory path
		* 
		* @var string
		*/
		private $_sectionsDir;
		/**
		* Controllers directory path
		* 
		* @var string
		*/
		private $_controllersDir;
		/**
		* Models directory path
		* 
		* @var string
		*/
		private $_modelsDir;
		/**
		* Views directory path
		* 
		* @var string
		*/
		private $_viewsDir;
		/**
		* Layouts directory path
		* 
		* @var string
		*/
		private $_layoutsDir;
		/**
		* Styles directory path
		* 
		* @var string
		*/
		private $_stylesDir;
		/**
		* Js directory path
		* 
		* @var string
		*/
		private $_jsDir;
		/**
		* Images directory path
		* 
		* @var string
		*/
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
		* @param array $settings
		* 
		* @return void
		*/
		public function setSettings($settings = array())
		{
			foreach ($settings as $k => $v) {
				$prop = '_'.$k;
				if (property_exists($this, $prop)) {
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
			$this->_isDebugMode = TRUE;
			$this->_urlPathKey = 'page';
			$this->_urlPathSep = '/';
			$this->_defaultLayout = 'master.layout.php';
			$this->_modulesDir = $root_dir . 'protected' . $ds . 'library' . $ds.'moduls' . $ds;
			$this->_sectionsDir = $root_dir . 'protected' . $ds . 'sections' . $ds;
			$this->_controllersDir = $root_dir . 'protected' . $ds . 'controllers' . $ds;
			$this->_modelsDir = $root_dir . 'protected' . $ds . 'models' . $ds;
			$this->_viewsDir = $root_dir . 'protected' . $ds.'views' . $ds;
			$this->_layoutsDir = $root_dir . 'protected' . $ds . 'views' . $ds . '_layouts' . $ds;
			$this->_stylesDir = $root_dir . 'public' . $ds . 'styles' . $ds;
			$this->_jsDir = $root_dir . 'public' . $ds . 'js' . $ds;
			$this->_imagesDir = $root_dir . 'public' . $ds . 'images' . $ds;
		}
		/**
		* Add setting
		*
		* @param string $settingKey
		* @param string $value
		* 
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
		* @param string $func
		* @param array $args
		* @return EabAppSettings
		*/
		public function __call($func, $args)
		{
			$prefix = strtolower(substr($func, 0, 3));
			$suffix = lcfirst(substr($func, 3, strlen($func)));
			if (($prefix === 'get' || $prefix === 'set') && property_exists($this, '_'.$suffix) ) {
				$prefix = strtolower(substr($func, 0, 3));

				if ('get' === $prefix) {
					return $this->{'_'.$suffix};
				}
				else{
					if (empty($args)) {
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