<?php

abstract class EabArrayFileSettingsLoader
{
	/**
	 * @var string
	 */
	protected $_filename;

	/**
	 * Constructor of class
	 *
	 * @param string
	 */
	public function __construct($filename)
	{
		$this->_filename=$filename;
	}
	/**
	 * Factory method create concrete file settings loader
	 *
	 * @param string Possible values ('array', 'xml', 'json')
	 * @return EabArrayFileSettingsLoader
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
	 * Load settings and return them as array
	 * 
	 * @return array
	 */
	abstract public function loadSettings();
	/**
	 * Get filename (getter)
	 * 
	 * @return array
	 */
	public function getFilename()
	{
		
	}
}
?>