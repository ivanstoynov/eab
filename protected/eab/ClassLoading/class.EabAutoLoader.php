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
		* Directory separator
		*
		* @var string
		*/
		private $_ds;
		/**
		* Registred paths
		*
		* @var array
		*/
		private $_registredPaths;
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
		public function __construct()
		{
			$this->_registredPaths = array();
			$this->_pathsCache = array();
			$this->_ds = DIRECTORY_SEPARATOR;
			spl_autoload_register(array($this, '_autoloadHandler'));
		}
		/**
		* Register autoload path
		* 
		* @param string
		* @return void
		*/
		public function registerAutoloadPath($path)
		{
			$this->_registredPaths[] = $path;
		}
		/**
		* Find application class
		*
		* @return void
		*/
		private function _autoloadHandler($class)
		{
			foreach ($this->_registredPaths as $path) {
				if ($this->_findAndLoadClass($class, $path)) {
					break;
				}
			}
		}
		
		/**
		* Find and load class
		*
		* @param string 
		* @param string
		* @return boolean
		*/
		private function _findAndLoadClass($class, $path)
		{
			$class = strtolower($class);
			if (! empty($this->_pathsCache[$class])) {
				require_once $this->_pathsCache[$class];
				return TRUE;
			}
			
			$this->_fetchDirectory($path);
			if (! empty($this->_pathsCache[$class])) {
				require_once $this->_pathsCache[$class];
				return TRUE;
			}
			
			return FALSE;
		}
		/**
		* Fetch directory
		*
		* @return void
		*/
		private function _fetchDirectory($basePath) {

			$basePath = rtrim($basePath, "\\/");
			
			$dirFiles = scandir($basePath);
			if (FALSE === $dirFiles) {
				// todo: throw exception
			}

			foreach ($dirFiles as $file) {
				if ($file === '.' || $file === '..') {
					continue;
				}
				
				$filePath = $basePath . $this->_ds . $file;
				if (is_file($filePath)) {
					$file = strtolower($file);
					if ('class.' === substr($file, 0, 6)) {
						$class = substr($file, 6, -4);
						$this->_pathsCache[$class] = $filePath;
					}
					elseif ('interface.' === substr($file, 0, 10)) {
						$class = substr($file, 10, -4);
						$this->_pathsCache[$class] = $filePath;
					}
					elseif ('trait.' === substr($file, 0, 6)) {
						$class = substr($file, 6, -4);
						$this->_pathsCache[$class] = $filePath;
					}					
				}
				elseif (is_dir($filePath)) {
					$this->_fetchDirectory($filePath);
				}
			}
		}
	}
?>