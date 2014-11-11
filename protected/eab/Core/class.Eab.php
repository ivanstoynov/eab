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
		/** @var string Controller file */
		private $_controllerFile;
		/** @var string Action name */
		private $_actionName;
		/** @var string Application configurations file */
		private $_appConfFile; // old fw_conf_file
		/** @var string How is configure application ('array','xml','json') */
		private $_appConfigureAs; // old fw_configure_as
		/** @var string pages config file */
		private $_pagesConfFile; // old head_conf_file
		/** @var string How is configure page */
		private $_pagesConfigureAs; // old head_configure_as
		
		
		private function __constuct()
		{
			$this->_appSettings = new EabAppSettings();
			$this->_dbAdapter = new EabDbAdapter();
			
			$root_dir = dirname($_SERVER['SCRIPT_FILENAME']).'/';
			$this->_appConfFile = $root_dir.'protected/configs/app.conf.php';
			$this->_appConfigureAs = 'array';
			$this->_pagesConfFile = $root_dir.'protected/configs/pages.conf.php';
			$this->_pagesConfigureAs = 'array';
		}
		
		private function loadAppSettings()
		{
			// todo:
		}
		
		public static function app()
		{
			if(null === self::$_instace){
				self::$_instace = new self();
			}
			return self::$_instace;
		}
		
		
		public function run()
		{
			
			//$this->_appSettings->
			
			// Pharse url to load controller and action data
			$this->_pharseControllersPath();
			// Run controller
			$this->__runController();
		}
		
		private function _pharseControllersPath()
		{
			$urlPathKey = $this->_appSettings->getUrlPathKey();
			$urlPathSep = $this->_appSettings->getUrlPathSep();

			$page = !empty($_REQUEST[$url_path_key]) ? $_REQUEST[$urlPathKey] : 'index'.$urlPathSep.'index';
			$exp = explode($urlPathSep, $page);

			$controllerDir = $this->_appSettings->getControllersDir();
			$ds = $this->_appSettings->getDs();
			$expCnt = count($exp);
			$i=0;
			if($expCnt > 2){
				while($i < ($expCnt-2)){
					$controllerDir.$ds.$exp[$i];
					$i++;
				}
			}
			
			$this->_controllerName = !empty($exp[$i]) ? ucfirst($exp[$i]) : 'IndexController';
			$this->_controllerFile = !empty($exp[$i+1]) ? strtolower($exp[$i+1]) : 'index';
			$this->_actionName = $controllerDir.'class.'.$this->_controllerName.'.php';
		}
		
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
			
			if(!method_exists($controllerInstance, $this->_actionName)){
				throw new EabException('Class "'.$controllerClass.'" not have method "'.$controllerClass.'::'.$this->_actionName.'()"!', EabExceptionCodes::ACTION_NOT_FOUND_EXC);
			}

			$controllerInstance->loadDefaultLayout($controller_name, $action_name);
			ob_start();
			$controllerInstance->beforeAction();
			$controllerInstance->$this->_actionName;
			$controllerInstance->afterAction();
			$content = ob_get_contents();
			ob_clean();
			
			$layout = $controllerInstance->getLayout();
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
		
		public static function redirect($url)
		{
			header('Location: '.$url);
			exit;
		}
		
		public static function normalizeDir(&$dir)
		{
			$ch = substr($dir, -1);
			if('/' != $ch && '\\' != $ch) {
				$dir.= $ds;
			}
			return $dir;
		}

		public static function debug($data)
		{
			echo '<pre>';
			print_r($data);
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