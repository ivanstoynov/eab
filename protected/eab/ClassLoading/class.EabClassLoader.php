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
		private $_finders;
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
			$this->_finders = array();
			$this->_pathsCache = array();
		}

		public function init()
		{
			spl_autoload_register(array($this, '_findAppFrameworkClass'));
		}
		/**
		 * Register handler
		 * 
		 * @param string 
		 * @param string
		 * @return void
		 */
		public function registerOtherClassFinder($category, $path)
		{
			$category = strtolower($category);
			$this->_finders[$category] = $path;
			switch($category){
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
			$this->_findClass($class, $this->_finders['controller']);
		}
		/**
		 * Find model class
		 *
		 * @return void
		 */
		private function _findModelClass($class)
		{
			$this->_findClass($class, $this->_finders['model']);
		}
		/**
		 * Find library class
		 *
		 * @return void
		 */
		private function _findLibraryClass($class)
		{
			$this->_findClass($class, $this->_finders['library']);
		}
		
		/**
		 * Find class
		 *
		 * @return void
		 */
		private function _findClass($class, $dirName)
		{
			$class = strtolower($class);
			if(!empty($this->_pathsCache[$class])){
				require_once $this->_pathsCache[$class];
				return;
			}
			
			$this->_fetchDirectory($dirName);
			if(!empty($this->_pathsCache[$class])){
				require_once $this->_pathsCache[$class];
			}
		}
		/**
		 * Fetch directory
		 *
		 * @return void
		 */
		private function _fetchDirectory($baseDir) {

			$dirFiles = scandir($baseDir);
			if(false === $dirFiles){
				// todo: throw exception
			}

			foreach($dirFiles as $file) {
				if($file == '.' || $file == '..') continue;
				
				$path = $baseDir.DIRECTORY_SEPARATOR.$file;
				if(is_file($path)){
					$file = strtolower($file);
					if('class.' !== substr($file, 0, 6)){
						continue;
					}
					$class = substr($file, 6, -4);
					$this->_pathsCache[$class] = $path;
				}
				elseif(is_dir($path)) {
					$this->__inspectDirectory($path);
				}
			}
		}
	}
?>