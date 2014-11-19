<?php
	/**
	 * EabImporter class
	 *
	 * @category   Core
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabModulsImporter
	{
		public static function import($file, $fullPath = false)
		{
			$ds = Eab::app()->getAppSettings()->getDs();
			$file = trim($file);
			$file = str_replace(":", $ds, $file);
			if($fullPath == true){
				if(substr($file,0,7)=='http://'||substr($file,0,8)=='https://'){
					throw new EabException('No access to file "'.$file.'"!', EabExceptionCodes::ACCESS_DENIDED_EXC);
				}
			}
			else{
				$modulsDir = Eab::app()->closeDirPath(Eab::app()->getAppSettings()->getModulesDir());
				$file = $modulsDir.$file;
			}
			if(substr($file, -1) === '*'){
				$dir = substr($file, 0, -1);
				self::IncludeDirFiles($dir);
			}
			elseif(is_file($file)){
				if(substr($file, -4) != '.php') {
					$file.= '.php';
				}
				include_once($file);
			}
		}

		private static function IncludeDirFiles($dir)
		{
			$ds = Eab::app()->getAppSettings()->getDs();
			$ch = substr($dir, -1);
			if(($ds != $ch) && ($ch != '/') && ($ch != '\\')){
				$dir.= $ds;
			}
			
			if(!is_dir($dir)){
				throw new EabException('Directory "'.$dir.'" not be valid!');
			}
			$handler = opendir($dir);
			if(false === $handler){
				throw new EabException('Directory "'.$dir.'" can not be opened!');
			}
			while($file = readdir($handler)){
				if($file == '.' || $file == '..') {
					continue;
				}
				$file = $dir.$file;
				if(is_dir($file)){
					self::IncludeDirFiles($file);
				}
				else{
					if(substr($file, -4) != '.php') {
						$file.= '.php';
					}
					include_once($file);
				}
			}
		}
	}

?>