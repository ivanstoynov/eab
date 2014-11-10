<?php
	/**
	 * Array file settings loader
	 *
	 * @category   SettingsLoaders
	 * @package    Eab\Settings
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabArrayFileSettingsLoader extends EabFileSettingsLoader
	{
		/**
		 * Constructor of class
		 *
		 * @param string
		 */
		public function __construct($fileName)
		{
			parent::__construct($fileName);
		}
		/**
		 * loadSettings implementation
		 * 
		 * @return array
		 */
		public function loadSettings()
		{
			$fileName = $this->getfileName();
			if(!$fileName){
				throw new EabException('Config file "'.$fileName.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			
			$settings = include $fileName;
			return $settings;
		}
	}
?>