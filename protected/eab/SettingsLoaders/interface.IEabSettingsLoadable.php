<?php
	/**
	* IEabSettingsLoadable interface
	*
	* @category   SettingsLoaders
	* @package    Eab\SettingsLoaders
	* @author     Ivan Stoyanov <iv44@yahoo.com>
	* @copyright  2010-2014 Stoqnoff
	* @since      1.0.0
	*/	
	interface IEabSettingsLoadable
	{
		/**
		* Load settings and return them as array
		* 
		* @return array
		*/
		public function loadSettings();
	}
?>