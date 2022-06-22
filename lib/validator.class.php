<?php
  /**
   * Validator Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');


  class Validator
  {
      protected static $instance = null;
      protected $validation_rules = array();
      protected $filter_rules = array();
      protected $errors = array();
      protected static $fields = array();
      protected static $validation_methods = array();
      protected static $filter_methods = array();
	  
      public static $basic_tags =
          '<br><p><a><strong><b><i><em><img><blockquote><code><dd><dl><hr><h1><h2><h3><h4><h5><h6><label><ul><li><span><sub><sup>';

      public static $script_tags =
          '<script>';
		  
      public static $advanced_tags =
          '<iframe><body><html><section><article><video><audio><source><div><table><td><tr><th><tbody><thead><img><svg><figure><br><p><a><strong><b><i><em><img><blockquote><code><dd><dl><hr><h1><h2><h3><h4><h5><h6><label><ul><li><span><sub><sup><button><defs><path><clipPath><style><ellipse><circle><g><polyline><line><rect><polygon>';
		  
      /**
       * Validator::instance()
       * 
       * @return
       */
      public static function instance()
      {
          if (self::$instance === null) {
              self::$instance = new self();
          }
          return self::$instance;
      }

      /**
       * Validator::doValidate()
       * 
       * @param mixed $data
       * @param mixed $validators
       * @return
       */
      public function doValidate(array $data, array $validators)
      {
          $this->validation_rules($validators);

          if ($this->run($data) === false) {
              return $this->getErrors(false);
          } else {
              return (object) $data;
          }
      }

      /**
       * Validator::doFilter()
       * 
       * @param mixed $data
       * @param mixed $filters
       * @return
       */
      public function doFilter(array $data, array $filters)
      {
		  $result = $this->filter($data, $filters);
          return (object) $result;
      }

      /**
       * Validator::field()
       * 
       * @param mixed $key
       * @param mixed $array
       * @param mixed $default
       * @return
       */
      public static function field($key, array $array, $default = null)
      {
          if (!is_array($array)) {
              return null;
          }

          if (isset($array[$key])) {
              return $array[$key];
          } else {
              return $default;
          }
      }


      /**
       * Validator::validation_rules()
       * 
       * @param mixed $rules
       * @return
       */
      public function validation_rules(array $rules = array())
      {
          if (empty($rules)) {
              return $this->validation_rules;
          }

          $this->validation_rules = $rules;
      }

      /**
       * Validator::filter_rules()
       * 
       * @param mixed $rules
       * @return
       */
      public function filter_rules(array $rules = array())
      {
          if (empty($rules)) {
              return $this->filter_rules;
          }

          $this->filter_rules = $rules;
      }

      /**
       * Validator::run()
       * 
       * @param mixed $data
       * @param bool $check_fields
       * @return
       */
      public function run(array $data, $check_fields = false)
      {
          $data = $this->filter($data, $this->filter_rules());


          $validated = $this->validate($data, $this->validation_rules());

          if ($check_fields === true) {
              $this->check_fields($data);
          }

          if ($validated !== true) {
              return false;
          }

          return $data;
      }

      /**
       * Validator::check_fields()
       * 
       * @param mixed $data
       * @return
       */
      private function check_fields(array $data)
      {
          $ruleset = $this->validation_rules();

          $array = array();
          foreach ($ruleset as $k => $set) {
              if (isset($set[0])) {
                  $array[$k] = $set[0];
              }
          }

          $mismatch = array_diff_key($data, $array);
          $fields = array_keys($mismatch);

          foreach ($fields as $field) {
              $this->errors[] = array(
                  'field' => $field,
                  'value' => $data[$field],
                  'rule' => 'mismatch',
                  'param' => null,
                  'msg' => $field,
                  );
          }

      }

      /**
       * Validator::sanitize()
       * 
       * @param mixed $data
       * @param string $type
       * @param bool $trim
       * @return
       */
      public static function sanitize($data, $type = 'default', $trim = false)
      {
          switch ($type) {
              case "string":
                  return filter_var($data, FILTER_SANITIZE_STRING);
                  break;

              case "search":
			      $data = str_replace(array('_', '%'), array('', ''), $data);
                  return filter_var($data, FILTER_SANITIZE_STRING);
                  break;
				  
              case "email":
                  return filter_var($data, FILTER_SANITIZE_EMAIL);
                  break;

              case "url":
                  return filter_var($data, FILTER_SANITIZE_URL);
                  break;
				  
              case "alpha":
                  return preg_replace('/[^A-Za-z]/', '', $data);
                  break;

              case "alphanumeric":
                  return preg_replace('/[^A-Za-z0-9]/', '', $data);
                  break;

              case "chars":
                  return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
                  break;
				  
              case "emailalt":
                  return preg_replace('/[^a-zA-Z0-9\/_|@+ .-]/', '', $data);
                  break;
				  
              case "time":
                  return preg_replace('/[^0-9]/', '', $data);
                  break;

              case "date":
                  return preg_replace('/[^0-9\,-]/', '', $data);
                  break;

              case "file":
                  return preg_replace('/[^a-zA-Z0-9\/_ .-]/', '', $data);
                  break;
				  
              case "implode":
                  return preg_replace('/[^0-9\,]/', '', $data);
                  break;
				  
              case "int":
                  return filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                  break;

              case "float":
                  return filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                  break;

              case "db":
                  return preg_replace('/[^A-Za-z0-9_\-]/', '', $data);
                  break;

              case "spchar":
                  return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
                  break;
				    
              case "default":
                  $data = filter_var($data, FILTER_SANITIZE_STRING);
                  $data = trim($data);
                  $data = stripslashes($data);
                  $data = strip_tags($data);
                  $data = str_replace(array(
                      '‘',
                      '’',
                      '“',
                      '”'), array(
                      "'",
                      "'",
                      '"',
                      '"'), $data);
                  if ($trim)
                      $data = substr($data, 0, $trim);
                  return $data;
                  break;
          }
      }

	  /**
	   * Validator::censored()
	   *
	   * @param mixed $string
	   * @param mixed $blacklist
	   * @return
	   */
	  public static function censored($string, $blacklist)
	  {
		  $array = explode(",", $blacklist);
		  reset($array);
	      if(count($array) > 1)  {
			  foreach ($array as $row) {
				  $string = preg_replace("`$row`", "***", $string);
			  }
		  }
		  return $string;
	  }
	  
	  /**
	   * Validator::compareNumbers()
	   * 
	   * @param mixed $float1
	   * @param mixed $float2
	   * @param string $operator
	   * @return
	   */
	  public static function compareNumbers($float1, $float2, $operator='=')  
	  {  
		  // Check numbers to 5 digits of precision  
		  $epsilon = 0.00001;  
			
		  $float1 = (float)$float1;  
		  $float2 = (float)$float2;  
			
		  switch ($operator)  
		  {  
			  // equal  
			  case "=":  
			  case "eq":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return true;  
				  }  
				  break;    
			  // less than  
			  case "<":  
			  case "lt":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return false;  
				  } else {  
					  if ($float1 < $float2) {  
						  return true;  
					  }  
				  }  
				  break;    
			  // less than or equal  
			  case "<=":  
			  case "lte":  
				  if (self::compareNumbers($float1, $float2, '<') || self::compareNumbers($float1, $float2, '=')) {  
					  return true;  
				  }  
				  break;    
			  // greater than  
			  case ">":  
			  case "gt":  
				  if (abs($float1 - $float2) < $epsilon) {  
					  return false;  
				  } else {  
					  if ($float1 > $float2) {  
						  return true;  
					  }  
				  }  
				  break;    
			  // greater than or equal  
			  case ">=":  
			  case "gte":  
				  if (self::compareNumbers($float1, $float2, '>') || self::compareNumbers($float1, $float2, '=')) {  
					  return true;  
				  }  
				  break;    
			
			  case "<>":  
			  case "!=":  
			  case "ne":  
				  if (abs($float1 - $float2) > $epsilon) {  
					  return true;  
				  }  
				  break;    
			  default:  
				  die("Unknown operator '".$operator."' in compareNumbers()");    
		  }  
			
		  return false;  
	  } 

      /**
       * Validator::truncate()
       * 
       * @param mixed $string
       * @param mixed $length
       * @param bool $ellipsis
       * @return
       */
      public static function truncate($string, $length, $ellipsis = true)
      {
          $wide = mb_strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));
          $length = round($length - $wide * 0.2);
          $clean_string = preg_replace('/&[^;]+;/', '-', $string);
          if (mb_strlen($clean_string) <= $length)
              return $string;
          $difference = $length - mb_strlen($clean_string);
          $result = mb_substr($string, 0, $difference);
          if ($result != $string and $ellipsis) {
              $result = self::add_ellipsis($result);
          }
          return $result;
      }

      /**
       * Validator::add_ellipsis()
       * 
       * @param mixed $string
       * @return
       */
      public static function add_ellipsis($string)
      {
          $string = mb_substr($string, 0, mb_strlen($string) - 3);
          return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
      }

      /**
       * Validator::alphaBits()
       * 
       * @param bool $all
       * @param mixed $vars
       * @param string $class
       * @return
       */
      public static function alphaBits($all, $vars, $class = "small pagination menu")
      {
          if (!empty($all)) {
              $parts = explode("/", $all);
              $vars = str_replace(" ", "", $vars);
              $c_vars = explode(",", $vars);
              $newParts = array();
              foreach ($parts as $val) {
                  $val_parts = explode("=", $val);
                  if (!in_array($val_parts[0], $c_vars)) {
                      array_push($newParts, $val);
                  }
              }
              if (count($newParts) != 0) {
                  $qs = "/" . implode("/", $newParts);
              } else {
                  return false;
              }

              $html = '';
              $charset = explode(",", Lang::$word->CHARSET);
              $html .= "<div class=\"$class\">\n";
              foreach ($charset as $key) {
                  $active = ($key == self::get('letter')) ? ' active' : null;
                  $html .= "<a class=\"item$active\" href=\"" . $all . "?letter=" . $key . "\">" . $key . "</a>\n";
              }
              $active = ($key == !self::get('letter')) ? ' active' : null;
              $html .= "<a class=\"item$active\" href=\"" . $all . "\">" . Lang::$word->ALL . "</a>\n";
              $html .= "</div>\n";
              unset($key);

              return $html;
          } else {
              return false;
          }
      }

      /**
       * Validator::cleanOut()
       * 
       * @param mixed $text
       * @return
       */
      public static function cleanOut($data)
      {

          $data = strtr($data, array(
              '\r\n' => "",
              '\r' => "",
              '\n' => ""));
          $data = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
          return stripslashes(trim($data));
      }


      /**
       * Validator::arrayClean()
       * 
       * @param mixed $data
       * @return
       */
      public static function arrayClean(array $data)
      {
          foreach ($data as $k => $v) {
              $data[$k] = filter_var($v, FILTER_SANITIZE_STRING);
          }

          return $data;
      }
	  
      /**
       * Validator::getChecked()
       * 
       * @param mixed $row
       * @param mixed $status
       * @return
       */
      public static function getChecked($row, $status)
      {
          if ($row == $status) {
              echo "checked=\"checked\"";
          }
      }

      /**
       * Validator::getSelected()
       * 
       * @param mixed $row
       * @param mixed $status
       * @return
       */
      public static function getSelected($row, $status)
      {
          if ($row == $status) {
              echo "selected=\"selected\"";
          }
      }

      /**
       * Validator::getActive()
       * 
       * @param mixed $row
       * @param mixed $status
       * @return
       */
      public static function getActive($row, $status)
      {
          if ($row == $status) {
              echo "active";
          }
      }
	  
      /**
       * Validator::request()
       * 
       * @param mixed $var
       * @return
       */
      public static function request($var)
      {
          if (isset($_REQUEST[$var]))
              return $_REQUEST[$var];
      }
	  
      /**
       * Validator::post()
       * 
       * @param mixed $var
       * @return
       */
      public static function post($var)
      {
          if (isset($_POST[$var]))
              return $_POST[$var];
      }

      /**
       * Validator::isPostSet()
       * 
       * @param mixed $key
	   * @param mixed $val
       * @return
       */
      public static function isPostSet($key, $val)
      {
          return (isset($_POST[$key]) and $isPostSet[$key] == $val) ? true : false;
      }
      /**
       * Validator::notEmptyPost()
       * 
       * @param mixed $var
       * @return
       */
      public static function notEmptyPost($var)
      {
          if (!empty($_POST[$var]))
              return $_POST[$var];
      }
	  
      /**
       * Validator::get()
       * 
       * @param mixed $var
       * @return
       */
      public static function get($var)
      {
          if (isset($_GET[$var]))
              return $_GET[$var];
      }

      /**
       * Validator::isGetSet()
       * 
       * @param mixed $key
	   * @param mixed $val
       * @return
       */
      public static function isGetSet($key, $val)
      {
          return (isset($_GET[$key]) and $_GET[$key] == $val) ? true : false;
      }
	  
      /**
       * Validator::notEmptyGet()
       * 
       * @param mixed $var
       * @return
       */
      public static function notEmptyGet($var)
      {
          if (!empty($_GET[$var]))
              return $_GET[$var];
      }
	  
	  /**
	   * Validator::has()
	   * 
	   * @param mixed $value
	   * @param mixed $string
	   * @return
	   */
	  public static function has($value, $string = '-/-')
	  {
		  return ($value) ? $value : $string;
	  }
	  
      /**
       * Validator::phpself()
       * 
       * @return
       */
      public static function phpself()
      {
          return htmlspecialchars($_SERVER['PHP_SELF']);
      }

      /**
       * Validator::checkPost()
       * 
       * @param mixed $index
       * @param mixed $msg
       * @return
       */
      public static function checkPost($index, $msg)
      {

          if (empty($_POST[$index]))
              Message::$msgs[$index] = $msg;
      }

      /**
       * Validator::checkSetPost()
       * 
       * @param mixed $index
       * @param mixed $msg
       * @return
       */
      public static function checkSetPost($index, $msg)
      {

          if (!isset($_POST[$index]))
              Message::$msgs[$index] = $msg;
      }
	  
      /**
       * Validator::errors()
       * 
       * @return
       */
      public function errors()
      {
          return $this->errors;
      }

      /**
       * Validator::validate()
       * 
       * @param mixed $input
       * @param mixed $ruleset
       * @return
       */
      public function validate(array $input, array $ruleset)
      {
          $this->errors = array();

          foreach ($ruleset as $field => $rulset) {

              $rules = explode('|', $rulset[0]);

              if (in_array('required', $rules) || (isset($input[$field]) && !is_array($input[$field]))) {
                  foreach ($rules as $rule) {
                      $method = null;
                      $param = null;

                      if (strstr($rule, ',') !== false) {
                          $rule = explode(',', $rule);
                          $method = 'validate_' . $rule[0];
                          $param = $rule[1];
                          $rule = $rule[0];
                          $msg = $rulset[1];
                      } else {
                          $method = 'validate_' . $rule;
                          $msg = $rulset[1];
                      }

                      if (is_callable(array($this, $method))) {
                          $result = $this->$method($field, $input, $param, $msg);

                          if (is_array($result)) {
                              $this->errors[] = $result;
                          }
                      } elseif (isset(self::$validation_methods[$rule])) {

                          $result = call_user_func(self::$validation_methods[$rule], $field, $input, $param, $msg);

                          if ($result === false) {
                              $this->errors[] = array(
                                  'field' => $field,
                                  'value' => $input,
                                  'rule' => self::$validation_methods[$rule],
                                  'param' => $param,
                                  'msg' => $msg,
                                  );
                          }

                      } else {
                          Debug::AddMessage("warnings", '<i>Warning</i>', 'Validator method ' . $method . ' does not exist', 'session');
                      }
                  }
              }
          }

          return (count($this->errors) > 0) ? $this->errors : true;
      }

      /**
       * Validator::getErrors()
       * 
       * @return
       */
      public function getErrors()
      {

          foreach ($this->errors as $e) {
              $field = $e['field'];
              $param = $e['param'];
              $msg = $e['msg'];
              if (array_key_exists($e['field'], self::$fields)) {
                  $field = self::$fields[$e['field']];
              }

              switch ($e['rule']) {
                  case 'mismatch':
                      Message::$msgs[$field] = "There is no validation rule for \"$msg\"";
                      break;
                  case 'validate_required':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R100;
                      break;
                  case 'validate_email':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R18;
                      break;
                  case 'validate_max_len':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[X]", $param, Lang::$word->FIELD_R1);
                      break;
                  case 'validate_min_len':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[X]", $param, Lang::$word->FIELD_R2);
                      break;
                  case 'validate_exact_len':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[X]", $param, Lang::$word->FIELD_R7);
                      break;
                  case 'validate_alpha':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R8;
                      break;
                  case 'validate_string':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R8;
                      break;
                  case 'validate_alpha_numeric':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R9;
                      break;
                  case 'validate_alpha_dash':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_10;
                      break;
                  case 'validate_numeric':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R5;
                      break;
                  case 'validate_integer':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R6;
                      break;
                  case 'validate_boolean':
                      Message::$msgs[$field] = "The \"$msg\" field may only contain a true or false value";
                      break;
                  case 'validate_float':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R19;
                      break;
                  case 'validate_valid_url':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R14;
                      break;
                  case 'validate_url_exists':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R15;
                      break;
                  case 'validate_valid_ip':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R16;
                      break;
                  case 'validate_valid_cc':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R17;
                      break;
                  case 'validate_contains':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[VAL]", implode(', ', $param), Lang::$word->FIELD_R11);
                      break;
                  case 'validate_contains_list':
                      Message::$msgs[$field] = "The \"$msg\" field needs to contain a value from its drop down list";
                      break;
                  case 'validate_doesnt_contain_list':
                      Message::$msgs[$field] = "The \"$msg\" field contains a value that is not accepted";
                      break;
                  case 'validate_date':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R4;
                      break;
                  case 'validate_time':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R4_1;
                      break;
                  case 'validate_min_numeric':
                      Message::$msgs[$field] = "The \"$msg\" field needs to be a numeric value, equal to, or higher than $param";
                      break;
                  case 'validate_max_numeric':
                      Message::$msgs[$field] = "The \"$msg\" field needs to be a numeric value, equal to, or lower than $param";
                      break;
                  case 'validate_starts':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[VAL]", $param, Lang::$word->FIELD_R12);
                      break;
                  case 'validate_extension':
                      Message::$msgs[$field] = "The \"$msg\" field can have the following extensions $param";
                      break;
                  case 'validate_required_file':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R100;
                      break;
                  case 'validate_equalsfield':
                      Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . str_replace("[VAL]", $param, Lang::$word->FIELD_R13);
                      break;
                  case 'validate_min_age':
                      Message::$msgs[$field] = "The \"$msg\" field needs to have an age greater than or equal to $param";
                      break;
                  default:
                  Message::$msgs[$field] = Lang::$word->FIELD_R0 . ' "' . $msg . '" ' . Lang::$word->FIELD_R3;
                      break;
              }
          }
      }

      /**
       * Validator::filter()
       * 
       * @param mixed $input
       * @param mixed $filterset
       * @return
       */
      public function filter(array $input, array $filterset)
      {
          foreach ($filterset as $field => $filters) {
              if (!array_key_exists($field, $input)) {
                  continue;
              }

              $filters = explode('|', $filters);

              foreach ($filters as $filter) {
                  $params = null;

                  if (strstr($filter, ',') !== false) {
                      $filter = explode(',', $filter);

                      $params = array_slice($filter, 1, count($filter) - 1);

                      $filter = $filter[0];
                  }

                  if (is_callable(array($this, 'filter_' . $filter))) {
                      $method = 'filter_' . $filter;
                      $input[$field] = $this->$method($input[$field], $params);
                  } elseif (function_exists($filter)) {
                      $input[$field] = $filter($input[$field]);
                  } elseif (isset(self::$filter_methods[$filter])) {
                      $input[$field] = call_user_func(self::$filter_methods[$filter], $input[$field], $params);
                  } else {
                      Debug::AddMessage("warnings", '<i>Warning</i>', 'Filter method ' . $filter . ' does not exist', 'session');
                  }
              }
          }

          return $input;
      }

      /**
       * Validator::filter_rmpunctuation()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_rmpunctuation($value, $params = null)
      {
          return preg_replace("/(?![.=$'€%-])\p{P}/u", '', $value);
      }

      /**
       * Validator::filter_string()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_string($value, $params = null)
      {
		  return filter_var($value, FILTER_SANITIZE_STRING);
      }

      /**
       * Validator::filter_urlencode()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_urlencode($value, $params = null)
      {
          return filter_var($value, FILTER_SANITIZE_ENCODED);
      }

      /**
       * Validator::filter_htmlencode()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_htmlencode($value, $params = null)
      {
          return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
      }

      /**
       * Validator::filter_email()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_email($value, $params = null)
      {
          return filter_var($value, FILTER_SANITIZE_EMAIL);
      }

      /**
       * Validator::filter_numbers()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_numbers($value, $params = null)
      {
          return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
      }

      /**
       * Validator::filter_floats()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_floats($value, $params = null)
      {
          return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
      }

      /**
       * Validator::filter_limit()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_limit($value, $params = null)
      {
          return mb_substr(trim($value), 0, 200);
      }

      /**
       * Validator::filter_script_tags()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_script_tags($value, $params = null)
      {
		  return strip_tags($value, self::$script_tags);
      }
	  
      /**
       * Validator::filter_basic_tags()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_basic_tags($value, $params = null)
      {
		  return strip_tags($value, self::$basic_tags);
      }

      /**
       * Validator::filter_advanced_tags()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_advanced_tags($value, $params = null)
      {
          return strip_tags($value, self::$advanced_tags);
      }
	  
      /**
       * Validator::filter_whole_number()
       * 
       * @param mixed $value
       * @param mixed $params
       * @return
       */
      protected function filter_whole_number($value, $params = null)
      {
          return intval($value);
      }

      /**
       * Validator::validate_contains()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_contains($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field])) {
              return;
          }

          $param = trim(strtolower($param));

          $value = trim(strtolower($input[$field]));

          if (preg_match_all('#\'(.+?)\'#', $param, $matches, PREG_PATTERN_ORDER)) {
              $param = $matches[1];
          } else {
              $param = explode(chr(32), $param);
          }

          if (in_array($value, $param)) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $value,
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_contains_list()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_contains_list($field, $input, $param = null, $msg = null)
      {
          $param = trim(strtolower($param));
          $value = trim(strtolower($input[$field]));
          $param = explode(';', $param);


          if (in_array($value, $param)) {
              return;
          } else {
              return array(
                  'field' => $field,
                  'value' => $value,
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_doesnt_contain_list()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_doesnt_contain_list($field, $input, $param = null, $msg = null)
      {
          $param = trim(strtolower($param));

          $value = trim(strtolower($input[$field]));

          $param = explode(';', $param);

          if (!in_array($value, $param)) { // valid, return nothing
              return;
          } else {
              return array(
                  'field' => $field,
                  'value' => $value,
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_required()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_required($field, $input, $param = null, $msg = null)
      {
          if (isset($input[$field]) && ($input[$field] === false || $input[$field] === 0 || $input[$field] === 0.0 || $input[$field] === '0' ||
              !empty($input[$field]))) {
              return;
          }

          return array(
              'field' => $field,
              'value' => null,
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_email()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_email($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!filter_var($input[$field], FILTER_VALIDATE_EMAIL)) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_max_len()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_max_len($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field])) {
              return;
          }

          if (function_exists('mb_strlen')) {
              if (mb_strlen($input[$field]) <= (int)$param) {
                  return;
              }
          } else {
              if (strlen($input[$field]) <= (int)$param) {
                  return;
              }
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_min_len()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_min_len($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field])) {
              return;
          }

          if (function_exists('mb_strlen')) {
              if (mb_strlen($input[$field]) >= (int)$param) {
                  return;
              }
          } else {
              if (strlen($input[$field]) >= (int)$param) {
                  return;
              }
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => null,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_exact_len()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_exact_len($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field])) {
              return;
          }

          if (function_exists('mb_strlen')) {
              if (mb_strlen($input[$field]) == (int)$param) {
                  return;
              }
          } else {
              if (strlen($input[$field]) == (int)$param) {
                  return;
              }
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_alpha()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_alpha($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!preg_match('/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input[$field]) !== false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_alpha_numeric()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_alpha_numeric($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i', $input[$field]) !== false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_alpha_dash()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_alpha_dash($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!preg_match('/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ_-])+$/i', $input[$field]) !== false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_alpha_space()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_alpha_space($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!preg_match("/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s])+$/i", $input[$field]) !== false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_string()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_string($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (filter_var($input[$field], FILTER_SANITIZE_STRING) === false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }
	  
      /**
       * Validator::validate_numeric()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_numeric($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!is_numeric($input[$field])) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_integer()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_integer($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (filter_var($input[$field], FILTER_VALIDATE_INT) === false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_boolean()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_boolean($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field]) && $input[$field] !== 0) {
              return;
          }

          if ($input[$field] === true || $input[$field] === false) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_float()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_float($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (filter_var($input[$field], FILTER_VALIDATE_FLOAT) === false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_valid_url()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_url($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!filter_var($input[$field], FILTER_VALIDATE_URL)) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_url_exists()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_url_exists($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $url = parse_url(strtolower($input[$field]));

          if (isset($url['host'])) {
              $url = $url['host'];
          }

          if (function_exists('checkdnsrr')) {
              if (checkdnsrr($url) === false) {
                  return array(
                      'field' => $field,
                      'value' => $input[$field],
                      'rule' => __function__,
                      'param' => $param,
                      'msg' => $msg,
                      );
              }
          } else {
              if (gethostbyname($url) == $url) {
                  return array(
                      'field' => $field,
                      'value' => $input[$field],
                      'rule' => __function__,
                      'param' => $param,
                      'msg' => $msg,
                      );
              }
          }
      }

      /**
       * Validator::validate_valid_ip()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_ip($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!filter_var($input[$field], FILTER_VALIDATE_IP) !== false) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_valid_ipv4()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_ipv4($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_valid_ipv6()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_ipv6($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!filter_var($input[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_valid_cc()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_cc($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $number = preg_replace('/\D/', '', $input[$field]);

          if (function_exists('mb_strlen')) {
              $number_length = mb_strlen($number);
          } else {
              $number_length = strlen($number);
          }

          $parity = $number_length % 2;

          $total = 0;

          for ($i = 0; $i < $number_length; ++$i) {
              $digit = $number[$i];

              if ($i % 2 == $parity) {
                  $digit *= 2;

                  if ($digit > 9) {
                      $digit -= 9;
                  }
              }

              $total += $digit;
          }

          if ($total % 10 == 0) {
              return; // Valid
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_iban()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_iban($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          static $character = array(
              'A' => 10,
              'C' => 12,
              'D' => 13,
              'E' => 14,
              'F' => 15,
              'G' => 16,
              'H' => 17,
              'I' => 18,
              'J' => 19,
              'K' => 20,
              'L' => 21,
              'M' => 22,
              'N' => 23,
              'O' => 24,
              'P' => 25,
              'Q' => 26,
              'R' => 27,
              'S' => 28,
              'T' => 29,
              'U' => 30,
              'V' => 31,
              'W' => 32,
              'X' => 33,
              'Y' => 34,
              'Z' => 35,
              'B' => 11);

          if (!preg_match("/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/", $input[$field])) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }

          $iban = str_replace(' ', '', $input[$field]);
          $iban = substr($iban, 4) . substr($iban, 0, 4);
          $iban = strtr($iban, $character);

          if (bcmod($iban, 97) != 1) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_date()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_date($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $err = date_parse($input[$field]);

          if ($err['warning_count'] == 1 or $err['error_count'] == 1) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }

      }

      /**
       * Validator::validate_time()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_time($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $err = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $input[$field]);

          if (!$err) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }

      }
	  
      /**
       * Validator::validate_min_age()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_min_age($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $cdate1 = new DateTime(date('Y-m-d', strtotime($input[$field])));
          $today = new DateTime(date('d-m-Y'));

          $interval = $cdate1->diff($today);
          $age = $interval->y;

          if ($age <= $param) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_max_numeric()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_max_numeric($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (is_numeric($input[$field]) && is_numeric($param) && ($input[$field] <= $param)) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_min_numeric()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_min_numeric($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field])) {
              return;
          }

          if (is_numeric($input[$field]) && is_numeric($param) && ($input[$field] >= $param)) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_starts()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_starts($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (strpos($input[$field], $param) !== 0) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_required_file()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_required_file($field, $input, $param = null, $msg = null)
      {
          if ($input[$field]['error'] !== 4) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_extension()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_extension($field, $input, $param = null, $msg = null)
      {
          if ($input[$field]['error'] !== 4) {
              $param = trim(strtolower($param));
              $allowed_extensions = explode(';', $param);

              $path_info = pathinfo($input[$field]['name']);
              $extension = $path_info['extension'];

              if (in_array($extension, $allowed_extensions)) {
                  return;
              }

              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_equalsfield()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_equalsfield($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if ($input[$field] == $input[$param]) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::validate_guidv4()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_guidv4($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (preg_match("/\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/", $input[$field])) {
              return;
          }

          return array(
              'field' => $field,
              'value' => $input[$field],
              'rule' => __function__,
              'param' => $param,
              'msg' => $msg,
              );
      }

      /**
       * Validator::trimScalar()
       * 
       * @param mixed $value
       * @return
       */
      private function trimScalar($value)
      {
          if (is_scalar($value)) {
              $value = trim($value);
          }

          return $value;
      }

      /**
       * Validator::validate_regex()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_regex($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          $regex = $param;
          if (!preg_match($regex, $input[$field])) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }

      /**
       * Validator::validate_valid_json_string()
       * 
       * @param mixed $field
       * @param mixed $input
       * @param mixed $param
       * @param mixed $msg
       * @return
       */
      protected function validate_valid_json_string($field, $input, $param = null, $msg = null)
      {
          if (!isset($input[$field]) || empty($input[$field])) {
              return;
          }

          if (!is_string($input[$field]) || !is_object(json_decode($input[$field]))) {
              return array(
                  'field' => $field,
                  'value' => $input[$field],
                  'rule' => __function__,
                  'param' => $param,
                  'msg' => $msg,
                  );
          }
      }
  }