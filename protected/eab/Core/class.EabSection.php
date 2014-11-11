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
		public function __construct()
		{
			parent::__construct();
		}
		
		abstract public function display();
		
		final public function render()
		{
			$this->display();
		}

		protected final function renderHtml($file,$full_path=false)
		{
			if(!$full_path){
				$backtrace=debug_backtrace();
				$class=__CLASS__;
				if(!($backtrace[0]['object'] instanceof $class)){
					throw new EabException('Unknown object in backtrace!', EabExceptionCodes::UNKNOWN_EXC);
				}
				$ds=EabConfigurator::Instance()->get('ds');
				$file=dirname($backtrace[0]['file']).$ds.$file;
			}
			
			if(!is_file($file)){
				throw new EabException('File "'.$file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			include($file);
		}

		public static function Create($section_name)
		{
			$ds=EabConfigurator::Instance()->get('ds');
			$section_file=Eab::NormalizeDir(EabConfigurator::Instance()->get('sections_dir')).$section_name.$ds.'class.'.$section_name.'.php';
			if(!is_file($section_file)){
				throw new EabException('File "'.$section_file.'" is not valid file!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}

			include_once($section_file);
			$section_class=$section_name.'Section';
			if(!class_exists($section_class)){
				throw new EabException('Section class "'.$section_class.'" can not be found!', EabExceptionCodes::CLASS_NOT_FOUND_EXC);
			}

			$section_instance=new $section_class();
			$class=__CLASS__;
			if(!($section_instance instanceof $class)){
				throw new EabException('Section class "'.$section_class.'" must be instance of '.$class.'!', EabExceptionCodes::CLASS_NOT_FOUND_EXC);
			}

			return $section_instance;
		}
	}
?>