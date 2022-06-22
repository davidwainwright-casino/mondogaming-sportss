<?php

  /**
   * Logger Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Logger
  {
      const lTable = "activity";


      /**
       * Logger::__construct()
       * 
       * @return
       */
      function __construct(){}


      /**
       * Logger::loginAgain()
       * 
       * @param mixed $remain
       * @return
       */
      public static function loginAgain(&$remain)
      {
		  $core = App::Core();
          $remain = 0;
          $time = self::getTime();
          $var = self::getRecord();
          if (!$var)
              return true;
          if ($var->failed < $core->attempt)
              return true;
          if (($time - $var->failed_last) > $core->flood) {
              self::deleteRecord();
              return true;
          }
          $remain = $core->flood - ($time - $var->failed_last);
          return false;
      }


      /**
       * Logger::setFailedLogin()
       * 
       * @return
       */
      public static function setFailedLogin()
      {
          self::setRecord(self::getTime());
      }


      /**
       * Logger::getTime()
       * 
       * @return
       */
      private static function getTime()
      {
          return time();
      }


      /**
       * Logger::getRecord()
       * 
       * @return
       */
      private static function getRecord()
      {

          $row = Db::run()->first(self::lTable, null, array("ip" => Url::getIP(), "type" => "user"));

          return ($row) ? $row : 0;
      }


      /**
       * Logger::setRecord()
       * 
       * @param mixed $failed_last
       * @return
       */
      private static function setRecord($failed_last)
      {

          if ($row = self::getRecord()) {
              Db::run()->pdoQuery("UPDATE `" . self::lTable . "` SET failed_last = " . $failed_last . ", failed = failed + 1 WHERE id = " . $row->id . ";");
          } else {
              $data = array(
                  'ip' => Url::getIP(),
                  'type' => "user",
                  'failed' => 1,
                  'failed_last' => $failed_last,
                  'importance' => 1,
                  'username' => "Guest",
                  'message' => "Possible Brute force attack",
                  );

              Db::run()->insert(self::lTable, $data);
          }
      }


      /**
       * Logger::writeLog()
       * 
       * @param mixed $message
       * @param string $type
       * @param int $imp
       * @return
       */
      public static function writeLog($message, $type = 'content', $imp = 0)
      {

          if (App::Core()->logging) {
              $data = array(
                  'user_id' => App::Auth()->uid,
                  'username' => App::Auth()->name,
                  'ip' => Url::getIP(),
                  'type' => $type,
                  'message' => $message,
                  'importance' => $imp
				  );

              Db::run()->insert(self::lTable, $data);
          }
      }
	  
      /**
       * Logger::deleteRecord()
       * 
       * @return
       */
      private static function deleteRecord()
      {
          Db::run()->delete(self::lTable, array("ip" => Url::getIP(), "type" => "user"));
      }
  }