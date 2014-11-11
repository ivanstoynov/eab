<?php
	/**
	 * Auto loader class
	 *
	 * @category   Loaders
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabAutoLoader
	{
		/**
		 * Framework directory
		 *
		 * @var string
		 */
		private $_fwDir;
		/**
		 * Handles
		 *
		 * @var array
		 */
		private $_loadersPaths;
		/**
		 * Classes paths
		 *
		 * @var array
		 */
		private $_pathsCache;

		/**
		 * Constructor of class
		 *
		 * @return void
		 */
		public function __construct($fwDir)
		{
			$this->_fwDir = $fwDir;
			$this->_loadersPaths = array();
			$this->_pathsCache = array();
		}
		/**
		 * Init default loader
		 * 
		 * @param string 
		 * @param string
		 * @return void
		 */
		public function register()
		{
			spl_autoload_register(array($this, '_findAppFrameworkClass'));
		}
		/**
		 * Register class loader
		 * 
		 * @param string ('controller', 'model', 'library')
		 * @param string
		 * @return void
		 */
		public function activateLoader($loaderType, $basePath)
		{
			$loaderType = strtolower($loaderType);
			$this->_loadersPaths[$loaderType] = $basePath;
			switch($loaderType){
				'controller' : 
					spl_autoload_register(array($this, '_findControllerClass'));
					break;
				'model' : 
					spl_autoload_register(array($this, '_findModelClass'));
					break;
				'library' : 
					spl_autoload_register(array($this, '_findLibraryClass'));
					break;
			}
		}
		/**
		 * Find application class
		 *
		 * @return void
		 */
		private function _findAppFrameworkClass($class)
		{
			$this->_findClass($class, $this->_fwDir);
		}
		/**
		 * Find controller class
		 *
		 * @return void
		 */
		private function _findControllerClass($class)
		{
			if(!empty($this->_loadersPaths['controller'])){
				$this->_findClass($class, $this->_loadersPaths['controller']);
			}
		}
		/**
		 * Find model class
		 *
		 * @return void
		 */
		private function _findModelClass($class)
		{
			if(!empty($this->_loadersPaths['model'])){
				$this->_findClass($class, $this->_loadersPaths['model']);
			}
		}
		/**
		 * Find library class
		 *
		 * @return void
		 */
		private function _findLibraryClass($class)
		{
			if(!empty($this->_loadersPaths['library'])){
				$this->_findClass($class, $this->_loadersPaths['library']);
			}
		}
		
		/**
		 * Find class
		 *
		 * @return void
		 */
		private function _findClass($class, $basePath)
		{
			$class = strtolower($class);
			if(!empty($this->_pathsCache[$class])){
				require_once $this->_pathsCache[$class];
				return;
			}
			
			$this->_fetchDirectory($basePath);
			if(!empty($this->_pathsCache[$class])){
				require_once $this->_pathsCache[$class];
			}
		}
		/**
		 * Fetch directory
		 *
		 * @return void
		 */
		private function _fetchDirectory($basePath) {

			$dirFiles = scandir($basePath);
			if(false === $dirFiles){
				// todo: throw exception
			}

			foreach($dirFiles as $file) {
				if($file == '.' || $file == '..') continue;
				
				$filePath = $basePath.DIRECTORY_SEPARATOR.$file;
				if(is_file($filePath)){
					$file = strtolower($file);
					if('class.' !== substr($file, 0, 6)){
						continue;
					}
					$class = substr($file, 6, -4);
					$this->_pathsCache[$class] = $filePath;
				}
				elseif(is_dir($filePath)) {
					$this->__inspectDirectory($filePath);
				}
			}
		}
	}
?>