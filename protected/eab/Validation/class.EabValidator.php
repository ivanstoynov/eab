<?php


	class EabValidator
	{
		private $_availableRules;
		
		private $_validationErrors;
		
		private $_fileds;
		
		private $_rules;
		
		private $_messages;
		
		/**
		* 
		* @var EabValidationErrors
		* 
		*/
		private $_errors;
		
		
		public function __construct()
		{
			$this->_availableRules = $this->getAvailableRules();
			$this->_validationErrors array();
		}
		
		public function getAvailableRules()
		{
			return array(
				'required',
				'min',
				'max',
				'email',
				'in',
				'not_in',
				'same',
				'regexp',
				'numeric',
				'integer'
			);
		}
		
		public function prepare($fileds = array(), $rules = array())
		{
			foreach ($fileds as $fieldName => $value){
				$this->_fileds[$fieldName] = $value;
			}
			
			foreach ($rules as $filedName => $rule){
				$this->_rules[$filedName] = $value;
			}
		}
		
		public function addField($fieldName, $fileldValue)
		{
			$this->_fileds[$fieldName] = $fileldValue;
		}
		
		public function addRule($fieldName, $rule)
		{
			$this->_rules[$fieldName] = $rule;
		}
		
		
		public function validate()
		{
			foreach($this->_rules as $filedName => $rulesString){
				if (! isset($this->_fileds[$filedName])){
					throw new EabException('Validation field not found!', EabExceptionCodes::UNKNOWN_EXC);
				}
				
				$filedValue = $this->_fileds[$filedName];
				
				$rulesArray = explode('|', $rulesString);
				foreach ($rulesArray as $ruleString) {
					
					$ruleArray = explode(':', $ruleString);
					$ruleKind = $ruleArray[0];
					$ruleExpression = ! empty($ruleArray[1]) ? $ruleArray[1] : null;
					
					switch($ruleKind) {
						case : 'required'
							$this->_validateRequired($filedName, $filedValue);
							break;
						case : 'between'
							break;
						case : 'min'
							break;
						case : 'max'
							break;
						case : 'email'
							break;
						case : 'in'
							break;
						case : 'not_in'
							break;
						case : 'same'
							break;
						case : 'regexp'
							break;
						case : 'numeric'
							break;
						case : 'integer'
					}
				}
			}
		}
		
		private function _validateRequired($field, $value)
		{
			if (empty($value)) {
				$err = '';
				if (! empty($this->_messages[$field . '.required'])) {
					$err = $this->_messages[$field . '.required'];
				}
				else {
					$err = 'required field!';
				}
				
				$this->_errors[$field][] = $err;
			}
		}
		
	}
?>