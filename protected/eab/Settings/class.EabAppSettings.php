<?php

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
		/** @var string Application configurations file */
		private $_appConfFile; // old fw_conf_file
		/** @var string How is configure application ('array','xml','json') */
		private $_appConfigureAs; // old fw_configure_as
		/** @var string pages config file */
		private $_pagesConfFile; // old head_conf_file
		/** @var string How is configure page */
		private $_pagesConfigureAs; // old head_configure_as
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
		public function __construct($settings=array())
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
		public function setProperties($props_vals=array())
		{
			foreach($props_vals as $k=$v){
				$prop = '_'.$this->$k;
				if(property_exists($prop)){
					$this->{$prop} = $v;
				}
			}
		}
		/**
		 * Load default settings
		 *
		 * @return void
		 */
		public function loadDefaultSettings()
		{
			$root_dir=dirname($_SERVER['SCRIPT_FILENAME']).'/';
			
			$this->_ds = '/';
			$this->_ps = '/';
			$this->_isDebugMode = true;
			$this->_urlPathKey = 'page';
			$this->_urlPathSep = '/';
			$this->_defaultLayout = 'master.layout.php';
			$this->_appConfFile = $root_dir.'protected/configs/app.conf.php';
			$this->_appConfigureAs = 'array';
			$this->_pagesConfFile = $root_dir.'protected/configs/pages.conf.php';
			$this->_pagesConfigureAs = 'array';
			$this->_modulesDir = $root_dir.'protected/library/moduls/';
			$this->_sectionsDir = $root_dir.'protected/sections/';
			$this->_controllersDir = $root_dir.'protected/controllers/';
			$this->_viewsDir = $root_dir.'protected/views/';
			$this->_layoutsDir = $root_dir.'protected/views/_layouts/';
			$this->_stylesDir = $root_dir.'public/styles/';
			$this->_jsDir = $root_dir.'public/js/';
			$this->_imagesDir = $root_dir.'public/images/';
		}
	}

?>