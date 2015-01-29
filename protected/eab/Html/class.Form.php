<?php
	/**
	* Form class - create form elements
	*
	* @category   Form
	* @package    Eab
	* @author     Ivan Stoyanov <iv44@yahoo.com>
	* @copyright  2010-2014 Stoqnoff
	* @since      1.0.0
	*/
	class Form
	{
		private static function _attributesToString($attributes = null)
		{
			if (empty($attributes)) {
				return '';
			}
			
			if (is_array($attributes)) {
				$attStr = ' ';
				foreach ($attStr as $k => $v) {
					$attStr. = htmlspecialchars($k) . '=' . htmlspecialchars($v). ' ';
				}
				return trim($attStr);
			}
			else {
				throw new EabException('Invalid atributes!', EabExceptionCodes::INCORECT_TYPE_EXC);
			}
		}
		/**
		* Create input field
		* 
		* @param array $attributes
		* 
		* @return void
		*/
		private static function _makeInput($attributes)
		{
			$attStr = self::_attributesToString($attributes);
			echo '<input ' . $attStr . ' />' . "\n";
		}
		/**
		* Create text field
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/
		public static function text($name, $value, $attributes = array())
		{
			$attributes['type'] = 'text';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create password field
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/
		public static function password($name, $value, $attributes = array())
		{
			$attributes['type'] = 'password';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create checkbox field
		* 
		* @param string $name
		* @param sring $value
		* @param boolean $selected
		* @param array $attributes
		* 
		* @return void
		*/
		public static function checkbox($name, $value, $selected, $attributes = array())
		{
			if ($selected) {
				$attributes['selected'] = 'selected';
			}
			elseif (isset($attributes['selected'])) {
				unset($attributes['selected']);
			}
			$attributes['type'] = 'checkbox';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create radio field
		* 
		* @param string $name
		* @param sring $value
		* @param boolean $selected
		* @param array $attributes
		* 
		* @return void
		*/
		public static function radio($name, $value, $selected, $attributes = array())
		{
			if ($selected) {
				$attributes['selected'] = 'selected';
			}
			elseif (isset($attributes['selected'])) {
				unset($attributes['selected']);
			}
			$attributes['type'] = 'radio';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create submit button
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/
		public static function submit($name, $value, $attributes = array())
		{
			$attributes['type'] = 'submit';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create button
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/
		public static function button($name, $value, $attributes = array())
		{
			$attributes['type'] = 'button';
			$attributes['name'] = htmlspecialchars($name);
			$attributes['value'] = htmlspecialchars($value);
			self::_makeInput($attributes);
		}
		/**
		* Create file input
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/
		public static function file($name, $attributes = array())
		{
			$attributes['type'] = 'file';
			$attributes['name'] = htmlspecialchars($name);
			self::_makeInput($attributes);
		}
		/**
		* Create textarea button
		* 
		* @param string $name
		* @param sring $value
		* @param array $attributes
		* 
		* @return void
		*/		
		public static function textarea($name, $value, $attributes = array())
		{
			$attStr = self::attributesToString($attributes);
			echo '<textarea name="' . htmlspecialchars($name) . '"' . $attStr . '>' 
			     . htmlspecialchars($value) 
			     . '</textarea>' . "\n";
		}

		/**
		* Create dropdown list
		* 
		* @param string $name
		* @param array $options
		* @param string $value
		* @param array $attributes
		* 
		* @return
		*/
		public static function select($name, $options, $value, $attributes = array())
		{
			$attributes['name'] = $name;
			$attStr = self::_attributesToString($attributes);
			echo '<select ' . $attStr . ' >' . "\n";
			foreach ($options as $k => $v) {
				$selected = ($value === $k) ? ' selected="selected" ' : '';
				echo "    " . '<option value="' . htmlspecialchars($k) . '" ' . $selected .'>' .
				htmlspecialchars($v) .
				'</option>' . "\n";
			}
			echo '</select>' . "\n";
		}
		/**
		* Create dropdown list
		* 
		* @param string $name
		* @param array $options
		* @param string $value
		* @param array $attributes
		* 
		* @return
		*/
		public static function selectOfRangeM($name, $from, $to, $value, $attributes = array())
		{
			$options = array();
			if( $from < $to) {
				for ($i = $from; $i <= $to; $i++) {
					$options[$i] = $i;
				}
			}
			else {
				for ($i = $to; $i >= $from; $i--) {
					$options[$i] = $i;
				}
			}
		}
		/**
		* Generate random token
		* 
		* @return string
		*/
		public static function token()
		{
			$token = '';
			for ($i = 0; $i < 100; $i++){
				$n = rand(65, 127);
				if ($i % 10 === 0) {
					$token = md5($token . microtime() . chr($n));
				}
				else {
					$token .= chr($n);
				}
			}
			$token = md5($token . microtime());
		}
	}
?>