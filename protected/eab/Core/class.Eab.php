<?php
	/**
	 * Eab (Easy Application Builder)
	 */
	class Eab
	{
		/** @var Eab Static instace */
		private static $_instace;
		/** @var EabAppSettings Application settings */
		private $_appSettings;
		/** @var EabDbAdapter Database addapter */
		private $_dbAdapter;
		/** @var string Controller name */
		private $_controllerName;
		/** @var string Controller path */
		private $_controllerPath;
		/** @var string Controller file */
		private $_controllerFile;
		/** @var string Action name */
		private $_actionName;
		/** @var string Application configurations file */
		private $_appSettingsFile; // old fw_conf_file
		/** @var string How is configure application ('array','xml','json') */
		private $_appSettingsFormat; // old fw_configure_as
		/** @var string pages config file */
		private $_pagesSettingsFile; // old head_conf_file
		/** @var string How is configure page */
		private $_pagesSettingsFormat; // old head_configure_as
		
		/**
		 * Constructor of class
		 */
		private function __construct()
		{
			$this->_appSettings = new EabAppSettings();
			$this->_dbAdapter = new EabDbAdapter();
			
			$root_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/';
			$this->_appSettingsFile = $root_dir.'protected/configs/app.conf.php';
			$this->_appSettingsFormat = 'array';
			//$this->_pagesSettingsFile = $root_dir.'protected/configs/pages.conf.php';
			$this->_pagesSettingsFormat = 'array';
		}
		/**
		 * Override __clone() magic method as private
		 */
		private function __clone(){}
		/**
		 * Get singleton instance
		 *
		 * @return Eab
		 */
		public static function app()
		{
			if(null === self::$_instace){
				self::$_instace = new self();
			}
			return self::$_instace;
		}
		/**
		 * Run application
		 *
		 * @return void
		 */
		public function run()
		{
			
			$this->_loadAppSettings();
			
			// Pharse url to load controller and action data
			$this->_pharseControllersPath();
			// Run controller
			$this->_runController();
		}
		/**
		 * Load application settings
		 *
		 * @return void
		 */
		private function _loadAppSettings()
		{
			// todo:
		}		
		/**
		 * Pharse controller pah and foun controler name, controler file
		 * and action name
		 *
		 * @return void
		 */
		private function _pharseControllersPath()
		{
			$urlPathKey = $this->_appSettings->getUrlPathKey();
			$urlPathSep = $this->_appSettings->getUrlPathSep();

			$page = !empty($_REQUEST[$urlPathKey]) ? $_REQUEST[$urlPathKey] : 'index'.$urlPathSep.'index';
			
			$exp = explode($urlPathSep, $page);

			$controllerDir = $this->normalizeDir($this->_appSettings->getControllersDir());
			$controllerPath = '';
			$ds = $this->_appSettings->getDs();
			$expCnt = count($exp);
			$i=0;
			if($expCnt > 2){
				while($i < ($expCnt-2)){
					$controllerDir.= $exp[$i].$ds;
					$controllerPath.= $exp[$i].$ds;
					$i++;
				}
			}
			
			$this->_controllerName = !empty($exp[$i]) ? ucfirst($exp[$i]).'Controller' : 'IndexController';
			$this->_controllerPath = $controllerPath;
			$this->_controllerFile = $controllerDir.'class.'.$this->_controllerName.'.php';
			$this->_actionName = !empty($exp[$i+1]) ? strtolower($exp[$i+1]) : 'index';
			
		}
		/**
		 * Run application controller
		 * 
		 * @return void
		 */
		private function _runController()
		{
			if(!is_file($this->_controllerFile)){
				throw new EabException('File "'.$this->_controllerFile.'" is not valid file!', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			include_once($this->_controllerFile);
			$controllerClass = $this->_controllerName;
			if(!class_exists($controllerClass)){
				throw new EabException('Class "'.$controllerClass.'" can not be found!', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			$controllerInstance = new $controllerClass();
			if(!($controllerInstance instanceof EabController)){
				throw new EabException('Class "'.$controllerClass.'" must be instance of EabController !', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			$controllerInstance->loadDefaultLayout($controller_name, $action_name);
			
			$layout = $this->_getAppLayout();
			$controllerInstance->setLayout($layout);
			$content = $controllerInstance->executeAction();

			if(!empty($layout)){
				if(!($layout instanceof EabLayout)){
					throw new EabException('Layout must be instance of EabLayout!', EabExceptionCodes::INCORECT_TYPE_EXC);
				}
				$layout->setContent($content);
				ob_start();
				$layout->render();
				$content = ob_get_contents();
				ob_clean();
			}
			echo $content;
		}
		/**
		 * Get layout (and load settings)
		 * 
		 * return EabLayout
		 */
		private function _getAppLayout()
		{
			$layout = new EabLayout();
			
			if(empty($this->_pagesSettingsFile)){
				return $layout;
			}
			
			$fileSettingsLoader = EabFileSettingsLoader::CreateLoader($this->_pagesSettingsFile, $this->_pagesSettingsFormat);
			$pagesSettings = $fileSettingsLoader->loadSettings();
			
			if(!empty($pagesSettings['_defaults'])){
				$layout->getHeadData()->setTitle($pagesSettings['_defaults']['title']);
				$layout->getHeadData()->setMetaTags($pagesSettings['_defaults']['metaTags']);
				$layout->getHeadData()->setStyles($pagesSettings['_defaults']['styles']);
				$layout->getHeadData()->setJs($pagesSettings['_defaults']['js']);
			}
			
			$ctrl = $this->_controllerPath.$this->_controllerName;
			if(!empty($pagesSettings['_controllers'][$ctrl])){
				$ctrollerSettings = $pagesSettings['_controllers'][$ctrl];
				$layout->getHeadData()->setTitle($ctrollerSettings['title']);
				$layout->getHeadData()->setMetaTags($ctrollerSettings['metaTags']);
				$layout->getHeadData()->setStyles($ctrollerSettings['styles']);
				$layout->getHeadData()->setJs($ctrollerSettings[$ctrl]['js']);
			
				$act = $this->_actionName;
				if(!empty($ctrollerSettings[$act])){
					$actionSettings = $ctrollerSettings;
					$layout->getHeadData()->setTitle($actionSettings['title']);
					$layout->getHeadData()->setMetaTags($actionSettings['metaTags']);
					$layout->getHeadData()->setStyles($actionSettings['styles']);
					$layout->getHeadData()->setJs($actionSettings['js']);
				}
			}

			return $layout;
		}
		/**
		 * Redirect
		 *
		 * @param string $url
		 */
		public function redirect($url)
		{
			header('Location: '.$url);
			exit;
		}
		/**
		 * Normalize directory (end with \ or /)
		 *
		 * @param string
		 * @return string
		 */
		public function normalizeDir(&$dir)
		{
			$ds = $this->_appSettings->getDs();
			$ch = substr($dir, -1);
			if('/' != $ch && '\\' != $ch) {
				$dir.= $ds;
			}
			return $dir;
		}

		public static function debug($data)
		{
			echo '<pre>';
			echo print_r($data, true);
			echo '</pre>';
		}
		/**
		 * Get appSettings (getter)
		 *
		 * @return EabAppSettings
		 */
		public function getAppSettings()
		{
			return $this->_appSettings;
		}
		/**
		 * Get controllerName (getter)
		 *
		 * @return EabDbAdapter
		 */
		public function getDbAdapter()
		{
			return $this->_dbAdapter;
		}
		/**
		 * Get controllerName (getter)
		 *
		 * @return string
		 */
		public function getControllerName()
		{
			return $this->_controllerName;
		}
		/**
		 * Get controllerFile (getter)
		 *
		 * @return string
		 */
		public function getControllerFile()
		{
			return $this->_controllerFile;
		}
		/**
		 * Get actionName (getter)
		 *
		 * @return string
		 */
		public function getActionName()
		{
			return $this->actionName;
		}		
		/**
		 * Set appConfFile (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setAppConfFile($file)
		{
			$this->_appConfFile = $file;
			return $this;
		}
		/**
		 * Get appConfFile (getter)
		 *
		 * @return string
		 */
		public function getAppConfFile()
		{
			return $this->_appConfFile;
		}
		/**
		 * Set appConfigureAs (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setAppConfigureAs($configureAs = 'array')
		{
			$this->_appConfigureAs = $configureAs;
			return $this;
		}
		/**
		 * Get appConfigureAs (getter)
		 *
		 * @return string
		 */
		public function getAppConfigureAs()
		{
			return $this->_appConfigureAs;
		}
		/**
		 * Set pagesConfFile (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setPagesConfFile($file)
		{
			$this->_pagesConfFile = $file;
			return $this;
		}
		/**
		 * Get pagesConfFile (getter)
		 *
		 * @return string
		 */
		public function getPagesConfFile()
		{
			return $this->_pagesConfFile;
		}
		/**
		 * Set pagesConfigureAs (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setPagesConfigureAs($configureAs = 'array')
		{
			$this->_pagesConfigureAs = $configureAs;
			return $this;
		}
		/**
		 * Get pagesConfigureAs (getter)
		 *
		 * @return string
		 */
		public function getPagesConfigureAs()
		{
			return $this->_pagesConfigureAs;
		}
	}
?>