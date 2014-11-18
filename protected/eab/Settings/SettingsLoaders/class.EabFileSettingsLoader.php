<?php
	/**
	 * File settings loader class
	 *
	 * @category   Settings
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */	
	abstract class EabFileSettingsLoader
	{
		/**
		 * @var string
		 */
		protected $_fileName;
	
		/**
		 * Constructor of class
		 *
		 * @param string
		 */
		public function __construct($fileName)
		{
			$this->_fileName = $fileName;
		}
		/**
		 * Factory method create concrete file settings loader
		 *
		 * @param string Possible values ('array', 'xml', 'json')
		 * @return EabArrayFileSettingsLoader
		 */
		public static function CreateLoader($fileName, $laodType = 'array' )
		{
			switch(strtolower($laodType)){
				case 'array' :
					return new EabArrayFileSettingsLoader($fileName);
					
				case 'xml' : 
					return new EabXmlFileSettingsLoader($fileName);
				
				case 'json' : 
					return new EabJsonFileSettingsLoader($fileName);
				
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
		 * Get file name (getter)
		 * 
		 * @return array
		 */
		public function getFilename()
		{
			return $this->_fileName;
		}
	}
?>