<?php
	/**
	 * Base controller class
	 *
	 * @category   Core
	 * @package    Eab
	 * @author     Ivan Stoyanov <iv44@yahoo.com>
	 * @copyright  2010-2014 Stoqnoff
	 * @since      1.0.0
	 */
	class EabController extends EabAssigner
	{
		private $_layout;


		public function __constuct()
		{
			parent::__construct();
			$this->loadDefaultLayout();
		}
		public function beforeAction()
		{
		}
		public function afterAction()
		{
		}
		public final function renderView($view=false)
		{
			if(!$view){
				$backtrace = debug_backtrace();
				$class = __CLASS__;
				$cnt = 0;
				foreach($backtrace as $trace){
					if(!empty($trace['class']) && ($trace['object'] instanceof $class)){
						if($cnt++ == 1){
							$view = $trace['function'].'.view.php';
							break;
						}
					}
				}
				if(!$view){
					throw new EabException('View has been incorrect!', EabExceptionCodes::UNKNOWN_EXC);
				}
			}
			
			$views_dir = Eab::NormalizeDir(EabConfigurator::Instance()->get('views_dir'));
			$view_file = $views_dir.$view;
			if(!is_file($view_file)){
				throw new EabException('View file "'.$view_file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			
			include_once($view_file);
		}
		
		public final function renderPartial($partial, $data=array())
		{
			/*$backtrace=debug_backtrace();
			$class=__CLASS__;
			foreach($backtrace as $trace){
				if(!empty($trace['class']) && ($trace['object'] instanceof $class)){
					$view = $trace['function'].'.view.php';
					break;
				}
			}*/
			//$partial=
			// TODO:
		}
		
		public static function RunController($controller_name, $controller_file, $action_name)
		{
			if(!is_file($controller_file)){
				throw new EabException('File "'.$controller_file.'" is not valid file!', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			include_once($controller_file);
			$controller_class=$controller_name.'Controller';
			if(!class_exists($controller_class)){
				throw new EabException('Class "'.$controller_class.'" can not be found!', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			$controller_instance=new $controller_class();
			$class=__CLASS__;
			if(!($controller_instance instanceof $class)){
				throw new EabException('Class "'.$controller_class.'" must be instance of EabController !', EabExceptionCodes::CONTROLLER_NOT_FOUND_EXC);
			}
			
			if(!method_exists($controller_instance,$action_name)){
				throw new EabException('Class "'.$controller_class.'" not have method "'.$controller_class.'::'.$action_name.'()"!', EabExceptionCodes::ACTION_NOT_FOUND_EXC);
			}

			$controller_instance->loadDefaultLayout($controller_name, $action_name);
			ob_start();
			$controller_instance->beforeAction();
			$controller_instance->$action_name();
			$controller_instance->afterAction();
			$content=ob_get_contents();
			ob_clean();
			
			$layout=$controller_instance->getLayout();
			if(!empty($layout)){
				if(!($layout instanceof EabLayout)){
					throw new EabException('$layout must be instance of FwLayout!', EabExceptionCodes::INCORECT_TYPE_EXC);
				}
				$layout->setContent($content);
				ob_start();
				$layout->render();
				$content=ob_get_contents();
				ob_clean();
			}
			echo $content;
		}
		
		public final function isAjax($script)
		{
			return isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		}
		public final function loadDefaultLayout($controller_name, $action_name)
		{
			$this->_layout = new EabLayout();
			
			$head_data=array('title'=>EabConfigurator::Instance()->get('default_site_title'));

			$this->_layout->setHeadData($head_data);

			$conf_file=EabConfigurator::Instance()->get('head_conf_file');
			$configure_as=EabConfigurator::Instance()->get('head_configure_as');
			if(is_file($conf_file)){
			
				$head_data=EabConfigurator::GetFileConfigs($conf_file,$configure_as);
				if(!isset($head_data)||!is_array($head_data)){
					throw new EabException('Config file "'.$conf_file.'" must be return array!', EabExceptionCodes::CONFIG_FILE_EXC);
				}
				if(!empty($head_data[$controler_name]) && !empty($head_data[$controler_name][$action_name])){
					$this->_layout->setHeadData($head_data[$controler_name][$action_name]);
				}
			}
		}
		public final function getlayout()
		{
			return $this->_layout;
		}
		public final function setLayout($layout)
		{
			$this->_layout=$layout;
		}
	}
?>