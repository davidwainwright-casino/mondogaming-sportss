<?php
  /**
   * Session Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
	  
  class Session
  {

      protected $_defaultSessionName = 'yoyo_framework';
      protected $_defaultSessionPrefix = 'yoyo_';
      protected $_prefix = '';
      protected $_cookieMode = 'allow';


      /**
       * Session::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          if ($this->_cookieMode !== 'only') {
              $this->_setCookieMode($this->_cookieMode);
          }

          $this->setSessionName('yoyo_' . INSTALL_KEY);
          $this->_openSession();
      }


      /**
       * Session::set()
       * 
       * @param mixed $name
       * @param mixed $value
	   * @param mixed $cookie
       * @return
       */
      public function set($name, $value, $cookie = false)
      {

          ($cookie == true) ? setcookie($name, $value, time() + 86400 * 300, '/') : $_SESSION[$this->_prefix . $name] = $value;
      }

      /**
       * Session::setKey()
       * 
       * @param mixed $name
       * @param mixed $key
	   * @param mixed $value
       * @return
       */
      public function setKey($name, $key, $value)
      {

          $_SESSION[$this->_prefix . $name][$key] = $value;
      }
	  
      /**
       * Session::get()
       * 
       * @param mixed $name
       * @param string $default
       * @return
       */
      public function get($name, $default = '')
      {
          return isset($_SESSION[$this->_prefix . $name]) ? $_SESSION[$this->_prefix . $name] : $default;
      }

      /**
       * Session::getCookie()
       * 
       * @param mixed $name
       * @return
       */
      public static function getCookie($name)
      {
          return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
      }
	  
      /**
       * Session::remove()
       * 
       * @param mixed $name
       * @return
       */
      public function remove($name)
      {
          if (isset($_SESSION[$this->_prefix . $name])) {
              unset($_SESSION[$this->_prefix . $name]);
              return true;
          }

          return false;
      }

      /**
       * Session::removeCookie()
       * 
       * @param mixed $name
       * @return
       */
      public static function removeCookie($name)
      {
		  if (isset($_COOKIE[$name])) {
			  unset($_COOKIE[$name]);
			  setcookie($name, '', time() - 3600, '/');
			  return true;
		  }
    
          return false;
      }

      /**
       * Session::cookieExists()
       * 
       * @param mixed $name
	   * @param mixed $value
       * @return
       */
      public static function cookieExists($name, $value)
      {
          return (isset($_COOKIE[$name]) and $_COOKIE[$name] == $value) ? true : false;
      }
	  
      /**
       * Session::isExists()
       * 
       * @param mixed $name
       * @return
       */
      public function isExists($name)
      {
          return isset($_SESSION[$this->_prefix . $name]) ? true : false;
      }

      /**
       * Session::setSessionName()
       * 
       * @param mixed $value
       * @return
       */
      public function setSessionName($value)
      {
          if (empty($value))
              $value = $this->_defaultSessionName;
			  
          session_name($value);
      }


      /**
       * Session::setSessionPrefix()
       * 
       * @param mixed $value
       * @return
       */
      public function setSessionPrefix($value)
      {
          if (empty($value))
              $value = $this->_defaultSessionPrefix;
			  
          $this->_prefix = $value;
      }


      /**
       * Session::getSessionName()
       * 
       * @return
       */
      public static function getSessionName()
      {
          return session_name();
      }


      /**
       * Session::getTimeout()
       * 
       * @return
       */
      public static function getTimeout()
      {
          return (int)ini_get('session.gc_maxlifetime');
      }

      /**
       * Session::endSession()
       * 
       * @return
       */
      public function endSession()
      {
          if (session_id() !== '') {
			  session_destroy();
          }
      }


      /**
       * Session::closeSession()
       * 
       * @return
       */
      public function closeSession()
      {
          return true;
      }

      /**
       * Session::captcha()
       * 
       * @return
       */
      public static function captcha()
      {

		  App::Session()->set('wcaptcha', mt_rand(10000, 99999));
		  return App::Session()->get('wcaptcha');
      }
	  
      /**
       * Session::getCookieMode()
       * 
       * @return
       */
      public function getCookieMode()
      {
          if (ini_get('session.use_cookies') === '0') {
              return 'none';
          } elseif (ini_get('session.use_only_cookies') === '0') {
			  return 'allow';
		  } else {
			  return 'only';
		  }
      }

      /**
       * Session::_openSession()
       * 
       * @return
       */
      private function _openSession()
      {
          if (strlen(session_id()) < 1) {
			  session_start();
              //session_regenerate_id();
          }

          if (DEBUG && session_id() == '') {
              Debug::addMessage('errors', 'session', 'Failed to start session');
          }
      }

      /**
       * Session::_setCookieMode()
       * 
       * @param string $value
       * @return
       */
      private function _setCookieMode($value = '')
      {
          if ($value === 'none') {
              ini_set('session.use_cookies', '0');
              ini_set('session.use_only_cookies', '0');
          } elseif ($value === 'allow') {
              ini_set('session.use_cookies', '1');
              ini_set('session.use_only_cookies', '0');
          } elseif ($value === 'only') {
              ini_set('session.use_cookies', '1');
              ini_set('session.use_only_cookies', '1');
          } else {
              Debug::addMessage('warnings', 'session_cookie_mode', 'HttpSession.cookieMode can only be "none", "allow" or "only".');
          }
      }

  }