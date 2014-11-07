<?php

class EabArrayFileSettingsLoader extends EabFileSettingsLoader
{
	/**
	 * Constructor of class
	 *
	 * @param string
	 */
	public function __construct($filename)
	{
		parent::__construct($filename);
	}
	/**
	 * Factory method create concrete file settings loader
	 *
	 * @param string Possible values ('array', 'xml', 'json')
	 */
	public static function CreateLoader($laod_type='array')
	{
		switch(strtolower($laod_type)){
			case 'array' :
				return new EabArrayFileSettingsLoader();
				
			case 'xml' : 
				return new EabXmlFileSettingsLoader();
			
			case 'json' : 
				return new EabJsonFileSettingsLoader();
			
			default :
				throw new EabException("Incorrect load type!", EabExceptionCodes::INCORECT_TYPE_EXC);
		}
	}
	/**
	 * loadSettings implementation
	 */
	public function loadSettings()
	{
		if(!is_file($this->_filename)){
			throw new EabException('Config file "'.$this->_filename.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
		}
		
		$settings = include $this->_filename;
		return $settings;
	}
}
?>