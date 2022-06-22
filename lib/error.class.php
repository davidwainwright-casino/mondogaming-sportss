<?php
  /**
   * Error Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');

  class wError
  {

      private $errorConstants = array(
          1 => 'Error',
          2 => 'Warning',
          4 => 'Parse error',
          8 => 'Notice',
          16 => 'Core Error',
          32 => 'Core Warning',
          256 => 'User Error',
          512 => 'User Warning',
          1024 => 'User Notice',
          2048 => 'Strict',
          4096 => 'Recoverable Error',
          8192 => 'Deprecated',
          16384 => 'User Deprecated',
          32767 => 'All');


      /**
       * wError::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          set_error_handler(array($this, 'errorHandler'));
          register_shutdown_function(array($this, 'fatalErrorShutdownHandler'));
          set_exception_handler(array($this, 'exceptionHandler'));

      }

      /**
       * wError::run()
       * 
       * @return
       */
      public static function run()
      {
          return DEBUG ? new self() : false;
      }

	  
      /**
       * wError::exceptionHandler()
       * 
       * @param mixed $exception
       * @return
       */
      public function exceptionHandler($exception)
      {
          $message = $exception->getMessage() . ' [code: ' . $exception->getCode() . '] [file: ' . $exception->getFile(). '] [line: ' . $exception->getLine(). ']';
		  
		  Message::msgSingleError($message);
		  Debug::AddMessage("warnings", '<i>Exception</i>', $message, "session");
				  
      }
	  
      /**
       * wError::errorHandler()
       * 
       * @param mixed $errno
       * @param mixed $errstr
       * @param mixed $errfile
       * @param mixed $errline
       * @return
       */
      public function errorHandler($errno, $errstr, $errfile, $errline)
      {
          $errString = (array_key_exists($errno, $this->errorConstants)) ? $this->errorConstants[$errno] : $errno;

          switch ($errno) {
              case 512:
			  case 1:
			  case 4:
			  case 16:
			  case 32:
			  case 4096:
				  Message::msgSingleError($errString . ': ' . $errstr);
                  Debug::AddMessage("errors", '<i>ERROR</i>', $errString . ' [' . $errno . ']: ' . $errstr . ' in ' . $errfile . ' on line ' . $errline, "session");
                  break;
				  
              default:
                  Message::msgSingleError($errString . ': ' . $errstr);
                  Debug::AddMessage("warnings", '<i>NOTICE</i>', $errString . ' [' . $errno . ']: ' . $errstr . ' in ' . $errfile . ' on line ' . $errline, "session");
                  break;
			  
				  
          }

      }

      /**
       * wError::parseBackTrace()
       * 
       * @param array $backTrace
       * @param mixed $type
       * @return
       */
      private static function parseBackTrace($backTrace, $type)
      {
          if ($type == 'error') {
              unset($backTrace[0]);
          }
          $backTrace = array_reverse($backTrace);
          if (count($backTrace) < 1) {
              return;
          }
          $string = '';
          foreach ($backTrace as $key => $value) {
			  $string = 'in ' . $value['file'] . ' on line ' . $value['line'];
          }
          return $string;
	
      }
	  
      /**
       * wError::fatalErrorShutdownHandler()
       * 
       * @return
       */
      function fatalErrorShutdownHandler()
      {
          $last_error = error_get_last();
          if ($last_error['type'] === E_ERROR) {
              Debug::AddMessage("errors", '<i>FATAL</i>', $last_error['message'] . ' in ' . $last_error['file'] . ' on line ' . $last_error['line'], "session");

          }
      }

  }