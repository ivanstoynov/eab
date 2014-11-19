<?php
	/**
	 * Abstract section class
	 *
	 * @category   Core
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	abstract class EabSection extends EabAssigner
	{
		/**
		 * Constructor of class
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * Render section (Abstract method - must be override)
		 *
		 * @return void
		 */
		abstract public function render();

		/**
		 * Render htm section file
		 *
		 * @param string $file
		 * @param boolean $isFullPath
		 */
		protected final function renderHtml($file, $isFullPath = false)
		{
			if(!$isFullPath){
				$backtrace = debug_backtrace();
				if(!($backtrace[0]['object'] instanceof self)){
					throw new EabException('Unknown object in backtrace!', EabExceptionCodes::UNKNOWN_EXC);
				}
				
				$ds = Eab::app()->getAppSettings()->getDs();
				$file = dirname($backtrace[0]['file']).$ds.$file;
			}
			
			if(!is_file($file)){
				throw new EabException('File "'.$file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			
			require $file;
		}
		/**
		 * Create section instance
		 *
		 * @param string $sectionName Section class
		 * @return EabSection
		 */
		public static function Create($sectionName)
		{
			$ds = Eab::app()->getAppSettings()->getDs();

			$sectionFile = Eab::app()->closeDirPath(Eab::app()->getAppSettings()->getSectionsDir()).$sectionName.$ds.'class.'.$sectionName.'.php';
			if(!is_file($sectionFile)){
				throw new EabException('File "'.$sectionFile.'" is not valid file!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}

			include_once($sectionFile);
			$sectionClass = $sectionName.'Section';
			if(!class_exists($sectionClass)){
				throw new EabException('Section class "'.$sectionClass.'" can not be found!', EabExceptionCodes::CLASS_NOT_FOUND_EXC);
			}

			$sectionInstance = new $sectionClass();

			if(!($sectionInstance instanceof self)){
				throw new EabException('Section class "'.$sectionClass.'" must be instance of '.__CLASS__.'!', EabExceptionCodes::CLASS_NOT_FOUND_EXC);
			}

			return $sectionInstance;
		}
	}
?>