<?php

	/**
	 * Class describe html element validator
	 *
	 * @author Ivan Stoyanov <iv44@yahoo.com>
	 * @pakage Ebl
	 * @subpakage Validation
	 */
	class EblElemValidator
	{
		/**
		 * @var string
		 */
		private $_filed;
		/**
		 * @var array
		 */
		private $_rules;
		/**
		 * @var array
		 */
		private $_errors;
		/**
		 * @var string
		 */
		private $_value;
		/**
		 * @var string
		 */
		private $_custorm_errors_view_callback;

		
		/**
		 * Constructor of class
		 * 
		 * @param string
		 * @param array
		 */
		public function __construct($field, $rules = array())
		{
			$this->_field = $field;
			$this->_rules = $rules;
			$this->_errors = array();
			$this->_value = null;

			$this->_custorm_errors_view_callback = null;
		}
		/**
		 * Set rules (setter)
		 *
		 * @param array
		 * @return EblElemValidator
		 */
		public function setRules($rules)
		{
			$this->_rules = $rules;
			return $this;
		}
		/**
		 * Add rule. Rules have follow syntax: array('type', 'expression', 'err_msg')
		 *
		 * @param array
		 * @return EblElemValidator
		 */
		public function addRule($rule)
		{
			$this->_rules[] = $rule;
			return $this;
		}
		/**
		 * Clear rules
		 *
		 * @param array
		 * @return EblElemValidator
		 */
		public function clearRules()
		{
			$this->_rules[] = array();
		}
		/**
		 * Get errors (getter)
		 *
		 * @param array
		 * @return EblElemValidator
		 */
		public function getErrors()
		{
			return $this->_errors;
		}
		/**
		 * Set custom_errors_view callback (setter)
		 *
		 * @param callable
		 * @return EblElemValidator
		 */
		public function setCustomErrorsView($callback)
		{
			$this->_custorm_errors_view_callback = $callback;
			return $this;
		}
		/**
		 * Validate html field. If element is valid return true, otherwise 
		 * return false and set errors.
		 *
		 * @return boolean
		 */
		public function validate()
		{
			$this->_value = trim(isset($_REQUEST[$this->_field])?$_REQUEST[$this->_field]:'');
			$this->_errors = array();
		
			foreach($this->_rules as $rule){
			
				$type = strtolower(array_shift($rule));
				$expr = !empty($rule) ? array_shift($rule) : null;
				$err = !empty($rule) ? array_shift($rule) : null;
				
				switch($type){
					case 'required' : 
						$this->_validateRequired($expr, $err);
						break;
					case 'numeric' : 
						$this->_validateNumeric($expr, $err);
						break;
					case 'equal' : 
					case 'eq' :
					case '=' :
						$this->_validateEqual($expr, $err);
						break;
					case 'range' : 
					case 'between' : 
						$this->_validateRange($expr, $err);
						break;
					case 'length' : 
						$this->_validateLength($expr, $err);
						break;
					case 'email' : 
						$this->_validateEmail($expr, $err);
						break;
					case 'regexp' :
						$this->_validateRegexp($expr, $err);
						break;
					case 'custom' : 
					case 'callback' : 
						$this->_validateCustom($expr, $err);
						break;
				}
			}

			return $this->isValid();
		}
		/**
		 * Validate this field. If element is valid return true, 
		 * otherwise return false and set errors.
		 *
		 * @return boolean
		 */
		public function isValid()
		{
			return empty($this->_errors);
		}
		/**
		 * Print validation errors 
		 *
		 * @return void
		 */
		public function displayErrors()
		{
			if(empty($this->_errors)) return;

			if(!empty($this->_custorm_errors_view_callback)){
				call_user_func($this->_custorm_errors_view_callback, array($this->_errors));
			}
			else{
				echo "\t\t".'<ul class="formErrorPanel">'."\n";
				foreach($this->_errors as $error){
					echo "\t\t\t".'<li>'.$error.'</li>'."\n";
				}
				echo "\t\t".'</ul>';
			}
		}
		/**
		 * Check required validation
		 * 
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateRequired($expr, $err)
		{
			if(empty($this->_value)){
				$this->_errors[] = !empty($err) ? $err : 'Required filed!';
			}
		}
		/**
		 * Check numeric validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateNumeric($expr, $err)
		{
			if(!is_numeric($this->_value)){
				$this->_errors[] = !empty($err) ? $err : 'Value must be numeric!';
			}
		}
		/**
		 * Check equal validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateEqual($expr, $err)
		{
			if($this->_value != $expr){
				$this->_errors[] = !empty($err) ? $err : 'Value must be numeric!';
			}
		}
		/**
		 * Check range validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateRange($expr, $err)
		{
			$expl = explode(':', $expr);
			$from = trim($expl[0]);
			$to = trim($expl[1]);
			$val =floatval($this->_value);
			if($from != '' && $to != ''){
				if($val < floatval($from) || $val > floatval($to)){
					$this->_errors[]=!empty($err)?$err:'Value must be in interval['.$from.';'.$to.']!';
				}
			}
			elseif($from != ''){
				if($val < floatval($from)){
					$this->_errors[] = !empty($err) ? $err : 'Value must be greater or equal then '.$from.'!';
				}
			}
			else{
				if($val > floatval($to)){
					$this->_errors[] = !empty($err) ? $err : 'Value must be less or equal then '.$to.'!';
				}
			}
		}
		/**
		 * Check length validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateLength($expr, $err)
		{
			$expl = explode(':', $expr);
			$min = trim($expl[0]);
			$max = trim($expl[1]);
			$length = strlen($this->_value);

			if($min != '' && $max != ''){
				if($length < $min || $length > $max){

					$this->_errors[] = !empty($err) ? $err : 'Length must be in interval['.$min.';'.$max.']!';
				}
			}
			elseif($from != ''){
				if($length < $min){
					$this->_errors[] = !empty($err) ? $err : 'Min length is '.$min.'!';
				}
			}
			else{
				if($length > $max){
					$this->_errors[] = !empty($err) ? $err : 'Max length is '.$max.'!';
				}
			}
		}
		/**
		 * Check email validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateEmail($expr, $err)
		{
			if( function_exists('filter_var') ){
				if(!filter_var($this->_value, FILTER_VALIDATE_EMAIL)){
					$this->_errors[] = !empty($err) ? $err : 'Invalid e-mail address!';
				}
			}
			else{
				$expr = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
				if(!$err){
					$err = 'Invalid e-mail address!';
				}
				$this->validateRegexp($expr, $err);
			}
		}
		
		/**
		 * Check regexp validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateRegexp($expr, $err)
		{
			$result = preg_match($expr, $this->_value);
			if(0 === $result){
				$this->_errors[] = !empty($err) ? $err : 'Invalid value!';
			}
			elseif(false === $result){
				$this->_errors[] = !empty($err) ? $err : 'Invalid regexp pattern!';
			}
		}
		/**
		 * Check custom validation
		 *
		 * @param string
		 * $param string
		 * @return void
		 */
		private function _validateCustom($expr, $err)
		{
			if(isset($rule['callback'])){
				$result = call_user_func($expr, $this->_value);
				if(false == $result){
					$this->_errors[] = !empty($err) ? $err : "Invalid value!";
				}
			}
		}
	}
?>