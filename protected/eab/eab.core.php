<?php

	class EabExceptionCodes
	{
		const DB_EXC = 101;
		const FILE_NOT_FOUND_EXC = 102;
		const CLASS_NOT_FOUND_EXC = 103;
		const METHOD_NOT_FOUND_EXC = 104;
		const PAGE_NOT_FOUND = 105;
		const PROPERTY_NOT_FOUND_EXC = 106;
		const INCORECT_TYPE_EXC = 107;

		// hack maby
		const ACCESS_DENIDED_EXC = 201;
		const INCORECT_URL_EXC = 202;

		const CONTROLLER_NOT_FOUND_EXC = 301;
		const ACTION_NOT_FOUND_EXC = 302;
		const CONFIG_FILE_EXC = 303;

		const UNKNOWN_EXC = 501;
	}

	class EabException extends Exception
	{
		public function __constuct($msg, $code)
		{
			if('debug'==strtolower(EabConfigurator::get('mode'))){
				$msg = $this->glueFooterToMsg($msg);
			}
			parent::__constuct($msg, $code);
		}
		
		private function glueFooterToMsg($msg)
		{
			// @TODO: da dobavq footera na exceptiona
			return $msg;
		}
	}
	
	
	

	/**
	 * Singleton configuration class
	 */
	class EabConfigurator
	{
		/**
		 * @var EabConfigurator
		 */
		private static $s_instance;
		/**
		 * @var assoc_array
		 */
		private $_configs;

		
		private function __construct()
		{
			$this->loadConfigs();
		}
		private function __clone(){}
		
		/**
		 * Singleton instance
		 *
		 * @return EabConfigurator
		 */
		public static function Instance()
		{
			if( !isset(self::$s_instance) ){
				self::$s_instance=new self();
			}
			return self::$s_instance;
		}
		
		private function loadConfigs()
		{
			$this->setConfigs($this->getDefaults());
			$conf_file=$this->_configs['fw_conf_file'];
			if(is_file($conf_file)){
				$configs=self::GetFileConfigs($conf_file,$this->_configs['fw_configure_as']);
				if(!isset($configs)||!is_array($configs)){
					throw new EabException('Config file "'.$conf_file.'" must be return array!', EabExceptionCodes::CONFIG_FILE_EXC);
				}
				$this->setConfigs($configs);
			}
		}
		
		/**
		 * @return assoc_array
		 */
		public function getDefaults()
		{
			$root_dir=dirname($_SERVER['SCRIPT_FILENAME']).'/';
			return array(
				'ds'=>'/',
				'ps'=>'/',
				'mode'=>'DEBUG',
				'url_path_key'=>'page',
				'url_path_sep'=>'/',
				'moduls_dir'=>$root_dir.'protected/library/moduls/',
				'sections_dir'=>$root_dir.'protected/sections/',
				'controllers_dir'=>$root_dir.'protected/controllers/',
				'views_dir'=>$root_dir.'protected/views/',
				'layouts_dir'=>$root_dir.'protected/views/_layouts/',
				'default_layout'=>'master.layout.php',
				'ajax_dir'=>$root_dir.'protected/ajax/',
				'fw_conf_file'=>$root_dir.'protected/configs/fw.conf.php',
				'fw_configure_as'=>'array',
				'head_conf_file'=>$root_dir.'protected/configs/head.conf.php',
				'head_configure_as'=>'array',
				'styles_dir'=>$root_dir.'public/styles/',
				'js_dir'=>$root_dir.'public/js/',
				'images_dir'=>$root_dir.'public/images/',
				'default_site_title'=>'My easy application framework'
			);
		}

		/**
		 * @return assoc_array
		 */
		public static function GetFileConfigs($file, $configure_as="array")
		{
			if(!is_file($file)){
				throw new EabException('Config file "'.$file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			
			$configure_as=strtolower($configure_as);
			if($configure_as=="array"){
				// File must be return array
				$return = include $file;
				return $return;
			}
			elseif($configure_as=="xml"){
				// Xml parse here
				// return array();
			}
			else{
				throw new EabException('Unknown configuration type "'.$configure_as.'"!', EabExceptionCodes::INCORECT_TYPE_EXC);
			}
		}
		
		/**
		 * @param assoc_array
		 */
		public function setConfigs($configs)
		{
			if(!isset($configs)||!is_array($configs) ){
				throw new EabException('Input param must be array! Type:  "'.gettype($configs).'"!', EabExceptionCodes::INCORECT_TYPE_EXC);
			}
			foreach($configs as $k=>$v){
				$this->_configs[$k]=$v;
			}
		}
		/**
		 * @return assoc_array
		 */
		public function getConfigs()
		{
			return $this->_configs;
		}
		
		public function reset()
		{
			$this->_configs=$this->getDefaults();
		}
		/**
		 * @param string
		 * @param mixed
		 */
		public function set($key, $val)
		{
			$this->_configs[$key]=$val;
			return $this;
		}
		/**
		 * @param string
		 * @return mixed
		 */
		public function get($key)
		{
			return isset($this->_configs[$key])?$this->_configs[$key]:null;
		}
	}

	class EabImporter
	{
		public static function import($file, $fullPath=false)
		{
			$ds=EabConfigurator::Instance()->get('ds');
			$file=trim($file);
			$file=str_replace(":",$ds,$file);

			if($fullPath==true){
				if(substr($file,0,7)=='http://'||substr($file,0,8)=='https://'){
					throw new EabException('No access to file "'.$file.'"!', EabExceptionCodes::ACCESS_DENIDED_EXC);
				}
			}
			else{
				$moduls_dir=Eab::NormalizeDir(EabConfigurator::Instance()->get('moduls_dir'));
				$file = $moduls_dir.$file;
			}

			if(substr($file,-1)=='*'){
				$dir=substr($file,0,-1);
				self::IncludeDirFiles($dir);
			}
			elseif(is_file($file)){
				if(substr($file,-4)!='.php') $file.='.php';
				include_once($file);
			}
		}

		private static function IncludeDirFiles($dir)
		{
			$ds=EabConfigurator::Instance()->get('ds');
			$ch=substr($dir,-1);
			if($ds!=$ch&&$ch!='/'&&$ch!='\\')$dir.= $ds;
			
			if(!is_dir($dir)){
				throw new EabException('Directory "'.$dir.'" not be valid!');
			}
			$handler=opendir($dir);
			if(false===$handler){
				throw new EabException('Directory "'.$dir.'" can not be opened!');
			}
			while($file=readdir($handler)){
				if($file=='.'||$file=='..') continue;
				$file=$dir.$file;
				if(is_dir($file)){
					self::IncludeDirFiles($file);
				}
				else{
					if(substr($file,-4)!='.php') $file.='.php';
					include_once($file);
				}
			}
		}
	}
	
	/**
	 * EabAssigner (Easy application interface assigner)
	 */
	class EabAssigner
	{
		private $_assigns=array();
		
		public function __construct()
		{
			$this->_assigns=array();
		}
		public final function assign($key,$val)
		{
			$this->assigns[$key]=$val;
		}
		public final function getAssignes($key)
		{
			return isset($this->assigns[$key])?$this->assigns[$key]:null;
		}
		public function __get($prop)
		{
			if(isset($this->_assigns[$prop])){
				return $this->_assigns[$prop];
			}
			else{
				throw new EabException("Property not found!", EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}
		}
		public function __set($prop,$args)
		{
			$this->_assigns[$prop]=$args;
		}
	}

	/**
	 * Eab (Easy Application Builder)
	 */
	class Eab
	{
		public function __constuct($configs=array())
		{
			EabConfigurator::Instance()->reset();
			if(!empty($configs) ){
				EabConfigurator::Instance()->setConfigs($configs);
			}
		}
		
		public function run()
		{
			$controller_details=$this->getControllerDetails();

			$controller_name=$controller_details['controller_name'];
			$controller_file=$controller_details['controller_file'];
			$action_name=$controller_details['action_name'];
		
			$content = EabController::RunController($controller_name,$controller_file,$action_name);
		}
		
		private function getControllerDetails()
		{
			$url_path_key=EabConfigurator::Instance()->get('url_path_key');
			$url_path_sep=EabConfigurator::Instance()->get('url_path_sep');

			$page=!empty($_REQUEST[$url_path_key])?$_REQUEST[$url_path_key]:'index'.$url_path_sep.'index';
			$exp=explode($url_path_sep, $page);

			$controller_dir=Eab::NormalizeDir(EabConfigurator::Instance()->get('controllers_dir'));
			$ds=EabConfigurator::Instance()->get('ds');
			$exp_cnt=count($exp);
			$i=0;
			if($exp_cnt>2){
				while($i<$exp_cnt-2){
					$controller_dir.$ds.$exp[$i];
					$i++;
				}
			}
			
			$controller_name=!empty($exp[$i])?ucfirst($exp[$i]):'Index';
			$action_name=!empty($exp[$i+1])?$exp[$i+1]:'index';
			
			$controller_file=$controller_dir.'class.'.$controller_name.'controller.php';
			
			return array(
				'controller_name'=>$controller_name,
				'controller_file'=>$controller_file,
				'action_name'=> $action_name
			);
		}
		
		public static function Redirect($url)
		{
			header('Location: '.$url);
			exit;
		}
		
		public static function RedirectToControler($controler,$action, $params)
		{
			//EabController::RunController($controller_name,$controller_file,$action_name);
		}

		public static function NormalizeDir(&$dir)
		{
			$ds=EabConfigurator::Instance()->get('ds');
			$ch=substr($dir,-1);
			if($ds!=$ch)$dir.= $ds;
			return $dir;
		}

		public static function debug($data)
		{
			echo '<pre>';
			print_r($data);
			echo '</pre>';
		}
	}
	
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
				$backtrace=debug_backtrace();
				$class=__CLASS__;
				$cnt=0;
				foreach($backtrace as $trace){
					if(!empty($trace['class']) && ($trace['object'] instanceof $class)){
						if($cnt++==1){
							$view = $trace['function'].'.view.php';
							break;
						}
					}
				}
				if(!$view){
					throw new EabException('View has been incorrect!', EabExceptionCodes::UNKNOWN_EXC);
				}
			}
			
			$views_dir=Eab::NormalizeDir(EabConfigurator::Instance()->get('views_dir'));
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
		
		public static function RunController($controller_name,$controller_file,$action_name)
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
	
	/**
	 * EabLayout (Easy application interface layout)
	 */
	class EabLayout extends EabAssigner
	{
		private $_content;
		private $_headData;
		private $_layout;

		
		public function __construct($layout=false)
		{
			parent::__construct();
			if(false!==$layout){
				$this->_layout=$layout;
			}
			else{
				$this->_layout=EabConfigurator::Instance()->get('default_layout');
			}
		}
		public function render()
		{
			$layout_file=Eab::NormalizeDir(EabConfigurator::Instance()->get('layouts_dir')).$this->_layout;
			if(!is_file($layout_file)){
				throw new EabException('Layout file "'.$layout_file.'" not found!', EabExceptionCodes::FILE_NOT_FOUND_EXC);
			}
			include_once($layout_file);
		}
		
		public function displayHeadTitle()
		{
			echo '<title>'.$this->_headData['title'].'</title>';
		}
		public function displayHeadMetaTags()
		{
			if(!empty($this->_headData['meta_tags'])&&is_array($this->_headData['meta_tags'])){
				foreach($this->_headData['meta_tags'] as $tag_data){
					$attr=' ';
					foreach($tag_data as $k=>$v){
						$attr.=$k.'="'.$v.'" ';
					}
					echo '<meta '.$attr.' />';
				}
			}
		}
		public function displayHeadJs()
		{
		}
		public function displayHeadStyles()
		{
		}

		public function getContent()
		{
			return $this->_content;
		}
		public function setContent($content)
		{
			$this->_content = $content;
		}
		public function getHeadData()
		{
			return $this->_headData;
		}
		public function setHeadData($head_data)
		{
			$this->_headData = $head_data;
			return $this;
		}
	}
	
	/**
	 * EabSection (Easy application builder section)
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
	
	/**
	 * EabModel (Easy application builder section)
	 */
	class EabModel extends EabAssigner
	{
		private $_db;
		private $_table_name;
		private $_table_columns;
		private $_pk_column;

		public function __construct()
		{
			$this->_pk_column='id';
			$this->_table_columns=array();
			if(empty($this->_table_name)){
				$this->_loadTableNameFromClass();
			}
			if(empty($this->_table_columns)){
				$this->_loadTableColumnsFromDb();
			}
		}
		
		private function _loadTableNameFromClass()
		{
			$class=strtolower(get_class($this));
			if(substr($class,-5,5)!= 'model'){
				throw new EabException('Model class must be ended with "model"!', EabExceptionCodes::UNKNOWN_EXC);
			}
			$this->_table_name=$class.'s';
		}
		
		private function _loadTableColumnsFromDb()
		{
			//$result = mysql_query("SHOW TABLES LIKE 'myTable'");
			//$sql="SHOW COLUMNS FROM authors";
		}

		public function load()
		{
			$stmt = $this->createPkStatement();
			if(!$stmt){
				return false;
			}

			$sql="SELECT * FROM `".$this->_table_name."` WHERE ".$stmt." LIMIT 1";
			$row=$this->_db->fetchRow($sql);
			$this->loadFromArray($row);

			return true;
		}

		public function save()
		{
			$stmt = $this->createPkStatement();
			if(!$stmt){
				$sql="INSERT INTO `".$this->_table_name."` (`".implode('`,`',$this->_table_columns)."`) VALUES \n";
				$sql.="(";
				foreach($this->_table_columns as $col){
					if(is_null($this->{$col})) $sql.="null,";
					else $sql.="'".stripslashes($this->{$col})."',";
				}
				$sql.=substr($values,0,-1).")";
				$this->_db->exec($sql);

				if(strtolower($this->_pk_column)=='id'){
					$this->id=$this->_db->lastInsertId();
				}
			}
			else{
				$sql="UPDATE `".$this->_table_name."` SET \n";
				foreach($this->_table_columns as $col){
					if(!is_null($this->{$col})){
						$sql.="'".stripslashes($this->{$col})."',";
					}
				}

				$sql.=substr($values,0,-1)." WHERE ".$stmt." LIMIT 1";
				$this->_db->exec($sql);
			}
		}
		public function delete()
		{
			$sql="DELETE FROM `".$this->_table_name."` WHERE ".$where_expr." LIMIT 1";
			$this->_db->exec($sql);
		}
		
		public function find($criterias=array())
		{
			if(!empty($criterias['columns'])){
				if(is_array($criterias['columns'])){
					$sql='SELECT `'.implode('`,`',$criterias['columns']).'` FROM ';
				}
				else{
					$sql='SELECT '.$criterias['columns'].' FROM ';
				}
			}
			else{
				$sql='SELECT * FROM `'.$this->_table_name.'` ';
			}
			
			if(!empty($criterias['where'])){
				$sql.="\nWHERE ".$criterias['where'].' ';
			}
			
			if(!empty($criterias['order'])){
				$sql.="\nORDER BY ".$criterias['order'].' ';
			}
			
			if(!empty($criterias['having'])){
				$sql.="\nHAVING ".$criterias['having'].' ';
			}

			if(!empty($criterias['limit'])){
				$sql.="\nLIMIT ".$criterias['limit'].' ';
			}
			
			$class=get_class($this);
			$models=array();
			$res=$this->_db->query($sql);
			while($row=$res->fetchRow()){
				$model=new $class();
				$model->loadFromArray($row);
				$models[]=$model;
			}
			
			return $models;
		}

		private function createPkStatement()
		{
			if(empty($this->_pk_column)){
				throw new EabException('Primary key column for model "'.get_class($this).'" is empty!', EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
			}

			$stmt='';
			if(is_array($this->_pk_column)){
				if(empty($id)){
					throw new EabException('Key column is empty"'.$this->_pk_column.'" for model "'.get_class($this).'"!', EabExceptionCodes::PROPERTY_NOT_FOUND_EXC);
				}
				foreach($this->_pk_column as $col){
					$stmt=$col."='".addslashes($this->{$col})."'";
				}
				$stmt=substr($stmt,0,-1);
			}
			else{
				$stmt=$this->_pk_column."='".addslashes($this->{$this->_pk_column})."'";
			}

			return $stmt;
		}
		
		public function loadFromArray($data)
		{
			foreach($data as $col=>$val){
				$this->assign($col,$val);
			}
		}
		
		public function setPkColumn($column)
		{
			$this->_pk_column=$column;
		}
		public function getPkColumn()
		{
			return $this->_pk_column;
		}
		public function setTableName($table_name)
		{
			$this->_table_name=$table_name;
		}
		public function getTableName()
		{
			return $this->_table_name;
		}
		public function setTableColumn($table_columns)
		{
			$this->_table_columns=$table_columns;
		}
		public function getTableColumn()
		{
			return;$this->_table_columns;
		}
	}
	
	class EabRequest
	{
		private $postVars=array();
		private $getVars=array();
		private $requestVars=array();
		private $coociesVars=array();

		public function __constuct()
		{
			$this->loadVars();
		}
		public function loadVars()
		{
			$this->postVars=$_POST;
			$this->getVars=$_GET;
			$this->requestVars=$_REQUEST;
			$this->coociesVars=$_COOCIES;
		}
		public function constructUrl($url, $args=array())
		{
		}
	}

?>
