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
		private $_appSettingsFile;
		/** @var string How is configure application ('array','xml','json') */
		private $_appSettingsFileFormat;
		/** @var string pages config file */
		private $_pagesSettingsFile;
		/** @var string How is configure page */
		private $_pagesSettingsFileFormat;
		
		/**
		 * Constructor of class
		 */
		private function __construct()
		{
			$this->_appSettings = new EabAppSettings();
			$this->_dbAdapter = new EabDbAdapter();
			
			$ds = DIRECTORY_SEPARATOR;
			$root_dir = dirname($_SERVER['SCRIPT_FILENAME']).$ds;
			$this->_appSettingsFile = $root_dir.'protected'.$ds.'configs'.$ds.'app.conf.php';
			$this->_appSettingsFileFormat = 'array';
			$this->_pagesSettingsFile = $root_dir.'protected'.$ds.'configs'.$ds.'pages.conf.php';
			$this->_pagesSettingsFileFormat = 'array';
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

			
			$this->_dbAdapter->setSettings();
			
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
			if( $this->_appSettingsFile){
				$fileSettingsLoader = EabFileSettingsLoader::CreateLoader($this->_appSettingsFile, $this->_appSettingsFileFormat);
				$settings = $fileSettingsLoader->loadSettings();
				$this->_appSettings->setSettings($settings);
			}
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

			$controllerPath = '';
			$ds = $this->_appSettings->getDs();
			$expCnt = count($exp);
			$i = 0;
			if($expCnt > 2){
				while($i < ($expCnt-2)){
					$controllerPath.= $exp[$i].$ds;
					$i++;
				}
			}
			
			$controllerDir = $this->closeDirPath($this->_appSettings->getControllersDir()).$controllerPath;

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

			$layout = $this->_getAppLayout();
			$controllerInstance->setLayout($layout);

			$content = $controllerInstance->executeAction($this->_actionName);

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
			$defaultLayout = Eab::app()->getAppSettings()->getDefaultLayout();
			$layout = new EabLayout($defaultLayout);

			if(empty($this->_pagesSettingsFile)){
				return $layout;
			}

			$fileSettingsLoader = EabFileSettingsLoader::CreateLoader($this->_pagesSettingsFile, $this->_pagesSettingsFileFormat);
			$pagesSettings = $fileSettingsLoader->loadSettings();

			if(!empty($pagesSettings['__defaults__'])){
				// Set default values
				$defSettings = $pagesSettings['__defaults__'];
				$layout->getLayoutHead()->setTitle($defSettings['title']);
				$layout->getLayoutHead()->setMetaTags($defSettings['metaTags']);
				$layout->getLayoutHead()->setStyles($defSettings['styles']);
				$layout->getLayoutHead()->setJs($defSettings['js']);
			}
			
			$ctrl = $this->_controllerPath.$this->_controllerName;
			if(!empty($pagesSettings['__controllers__'][$ctrl])){
				$ctrlSettings = $pagesSettings['__controllers__'][$ctrl];
				if(!empty($ctrlSettings['__defaults__'])){
				    // Set controller default values
					$defaultCtrlSettings = $ctrlSettings['__defaults__'];
					$layout->getLayoutHead()->setTitle($defaultCtrlSettings['title']);
					$layout->getLayoutHead()->setMetaTags($defaultCtrlSettings['metaTags']);
					$layout->getLayoutHead()->setStyles($defaultCtrlSettings['styles']);
					$layout->getLayoutHead()->setJs($defaultCtrlSettings[$ctrl]['js']);
				}

				$act = $this->_actionName;
				if(!empty($ctrlSettings[$act])){
					// Set action values
					$actSettings = $ctrlSettings[$act];
					$layout->getLayoutHead()->setTitle($actSettings['title']);
					$layout->getLayoutHead()->setMetaTags($actSettings['metaTags']);
					$layout->getLayoutHead()->setStyles($actSettings['styles']);
					$layout->getLayoutHead()->setJs($actSettings['js']);
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
		 * CLose dir path(end with \ or /)
		 *
		 * @param string
		 * @return string
		 */
		public function closeDirPath($dir)
		{
			$ds = $this->_appSettings->getDs();
			$ch = substr($dir, -1);
			if( '/' != $ch && '\\' != $ch) {
				$dir.= $ds;
			}
			return $dir;
		}
		/**
		 * Debug data method
		 *
		 * @param mixed
		 * @return void
		 */
		public static function debug($data)
		{
			echo '<pre>';
			echo print_r($data, true);
			echo '</pre>';
		}
		/**
		 * getAppSettings (getter)
		 *
		 * @return EabAppSettings
		 */
		public function getAppSettings()
		{
			return $this->_appSettings;
		}
		/**
		 * getDbAdapter (getter)
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
		 * getControllerFile (getter)
		 *
		 * @return string
		 */
		public function getControllerFile()
		{
			return $this->_controllerFile;
		}
		/**
		 * getActionName (getter)
		 *
		 * @return string
		 */
		public function getActionName()
		{
			return $this->actionName;
		}		
		/**
		 * setAppSettingsFile (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setAppSettingsFile($file)
		{
			$this->_appSettingsFile = $file;
			return $this;
		}
		/**
		 * getAppSettingsFile (getter)
		 *
		 * @return string
		 */
		public function getAppSettingsFile()
		{
			return $this->_appSettingsFile;
		}
		/**
		 * setAppSettingsFileFormat (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setAppConfigureAs($format)
		{
			$this->_appSettingsFileFormat = $format;
			return $this;
		}
		/**
		 * getAppSettingsFileFormat (getter)
		 *
		 * @return string
		 */
		public function getAppSettingsFileFormat()
		{
			return $this->_appSettingsFileFormat;
		}
		/**
		 * setPagesSettingsFile (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setPagesSettingsFile($file)
		{
			$this->_pagesSettingsFile = $file;
			return $this;
		}
		/**
		 * getPagesSettingsFile (getter)
		 *
		 * @return string
		 */
		public function getPagesSettingsFile()
		{
			return $this->_pagesSettingsFile;
		}
		/**
		 * setPagesSettingsFileFormat (setter)
		 *
		 * @param string
		 * @return Eab
		 */
		public function setPagesSettingsFileFormat($format)
		{
			$this->_pagesSettingsFileFormat = $format;
			return $this;
		}
		/**
		 * getPagesSettingsFileFormat (getter)
		 *
		 * @return string
		 */
		public function getPagesSettingsFileFormat()
		{
			return $this->_pagesSettingsFileFormat;
		}
	}
?>