<?php
  /**
   * Class Message
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');


  class Message
  {
      public static $msgs = array();
      public static $showMsg;


      /**
       * Message::msgAlert()
       * 
       * @param mixed $msg
       * @param bool $print
       * @param bool $altholder
       * @return
       */
      public static function msgAlert($msg, $print = true, $altholder = false)
      {
          self::$showMsg = "<div class=\"yoyo icon message alert align-middle\"><i class=\"warning sign icon\"></i><i class=\"close icon\"></i>";
          self::$showMsg .= "<div class=\"content\"><div class=\"header\"> " . Lang::$word->ALERT . "</div><p>" . $msg . "</p></div></div>";

          if ($print == true) {
              print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          } else {
              return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          }
      }

      /**
       * Message::msgSingleAlert()
       * 
       * @param mixed $msg
       * @param bool $print
       * @return
       */
      public static function msgSingleAlert($msg, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo alert small icon message align-middle\"><i class=\"warning sign icon\"></i> <div class=\"content\">" . $msg . "</div></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }

      /**
       * Message::msgOk()
       * 
       * @param mixed $msg
       * @param bool $print
       * @param bool $altholder
       * @return
       */
      public static function msgOk($msg, $print = true, $altholder = false)
      {
          self::$showMsg = "<div class=\"yoyo icon message positive align-middle\"><i class=\"circle check icon\"></i><i class=\"close icon\"></i>";
          self::$showMsg .= "<div class=\"content\"><div class=\"header\"> " . Lang::$word->SUCCESS . "</div><p>" . $msg . "</p></div></div>";
          if ($print == true) {
              print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          } else {
              return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          }
      }

      /**
       * Message::msgSingleOk()
       * 
       * @param mixed $msg
       * @param bool $print
       * @return
       */
      public static function msgSingleOk($msg, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo positive small icon message align-middle\"><i class=\"circle check icon\"></i> <div class=\"content\">" . $msg . "</div></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }

      /**
       * Message::msgInfo()
       * 
       * @param mixed $msg
       * @param bool $print
       * @param bool $altholder
       * @return
       */
      public static function msgInfo($msg, $print = true, $altholder = false)
      {
          self::$showMsg = "<div class=\"yoyo icon message info align-middle\"><i class=\"info sign icon\"></i><i class=\"close icon\"></i>";
          self::$showMsg .= "<div class=\"content\"><div class=\"header\"> " . Lang::$word->INFO . "</div><p>" . $msg . "</p></div></div>";

          if ($print == true) {
              print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          } else {
              return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          }
      }

      /**
       * Message::msgSingleInfo()
       * 
       * @param mixed $msg
       * @param bool $print
       * @return
       */
      public static function msgSingleInfo($msg, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo info small icon message align-middle\"><i class=\"info sign icon\"></i> <div class=\"content\">" . $msg . "</div></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }

      /**
       * Message::msgError()
       * 
       * @param mixed $msg
       * @param bool $print
       * @param bool $altholder
       * @return
       */
      public static function msgError($msg, $print = true, $altholder = false)
      {
          self::$showMsg = "<div class=\"yoyo icon message negative align-middle\"><i class=\"circle minus icon\"></i><i class=\"close icon\"></i>";
          self::$showMsg .= "<div class=\"content\"><div class=\"header\"> " . Lang::$word->ERROR . "</div><p>" . $msg . "</p></div></div>";
          if ($print == true) {
              print ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          } else {
              return ($altholder) ? '<div id="alt-msgholder">' . self::$showMsg . '</div>' : self::$showMsg;
          }
      }

      /**
       * Message::msgSingleError()
       * 
       * @param mixed $msg
       * @param bool $print
       * @return
       */
      public static function msgSingleError($msg, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo negative icon small message align-middle\"><i class=\"circle minus icon\"></i> <div class=\"content\">" . $msg . "</div></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }

      /**
       * Message::msgStatus()
       * 
       * @return
       */
      public static function msgStatus()
      {
          self::$showMsg = "<div class=\"yoyo negative message\"><i class=\"close icon\"></i><div class=\"header\">" . Lang::$word->PROCCESS_ERR . "</div><div class=\"content\"><ul class=\"yoyo list\">";
          foreach (self::$msgs as $msg) {
              self::$showMsg .= "<li>" . $msg . "</li>\n";
          }
          self::$showMsg .= "</ul></div></div>";

          return self::$showMsg;
      }

      /**
       * Message::msgSingleStatus()
       * 
       * @return
       */
      public static function msgSingleStatus()
      {
          self::$showMsg = "<div class=\"yoyo small list\">";
		  $i = 1;
          foreach (self::$msgs as $msg) {
              self::$showMsg .= "<div class=\"item\"><b>" . $i . ".</b> " . $msg . "</div>\n";
			  $i++;
          }
          self::$showMsg .= "</div>";

          $json['type'] = 'error';
          $json['title'] = Lang::$word->PROCCESS_ERR;
          $json['message'] = self::$showMsg;
          print json_encode($json);
      }

      /**
       * Message::msgReply()
       * 
       * @param mixed $data
       * @param mixed $type
       * @param mixed $msg
       * @param bool $msg2
       * @return
       */
      public static function msgReply($data, $type, $msg, $msg2 = false)
      {
          $title = strtoupper($type);
          if ($data) {
              $json['type'] = $type;
              $json['title'] = Lang::$word->$title;
              $json['message'] = $msg;
          } else {
              $json['type'] = 'alert';
              $json['title'] = Lang::$word->ALERT;
              $json['message'] = ($msg2) ? $msg2 : Lang::$word->NOPROCCESS;
          }

          print json_encode($json);
      }

      /**
       * Message::msgModalReply()
       * 
       * @param mixed $data
       * @param mixed $type
       * @param mixed $msg
       * @param mixed $html
       * @return
       */
      public static function msgModalReply($data, $type, $msg, $html, $html2 ='', $html3 = '')
      {
          $title = strtoupper($type);
          if ($data) {
              $json['type'] = $type;
              $json['title'] = Lang::$word->$title;
			  $json['html'] = $html;
			  $html2 ? $json['html2'] = $html2 : null;
			  $html3 ? $json['html3'] = $html3 : null;
              $json['message'] = $msg;
          } else {
              $json['type'] = 'alert';
              $json['title'] = Lang::$word->ALERT;
			  $json['html'] = $html;
              $json['message'] = Lang::$word->NOPROCCESS;
          }

          print json_encode($json);
      }

	  /**
	   * Message::formatSuccessMessage()
	   * 
	   * @param mixed $name
	   * @param mixed $message
	   * @return
	   */
	  public static function formatSuccessMessage($name, $message)
	  {

		  return  str_replace("[NAME]", '<b>' . $name . '</b>', $message);
	  }
	  
      /**
       * Message::error()
       * 
       * @param mixed $msg
       * @param mixed $source
       * @return
       */
      public static function error($msg, $source)
      {
          if (DEBUG == true) {
              $html = "<div class=\"yoyo message negative\">";
              $html .= "<span>System ERROR!</span><br />";
              $html .= "DB Error: " . $msg . " <br /> More Information: <br />";
              $html .= "<ul class=\"error\">";
              $html .= "<li> Date : " . date("F j, Y, g:i a") . "</li>";
              $html .= "<li> Function: " . $source . "</li>";
              $html .= "<li> Script: " . $_SERVER['REQUEST_URI'] . "</li>";
              $html .= "<li>&lsaquo; <a href=\"javascript:history.go(-1)\"><strong>Go Back to previous page</strong></a></li>";
              $html .= '</ul>';
              $html .= '</div>';
          } else {
              $html = "<div class=\"msgError\" style=\"color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff\">";
              $html .= "<h4 style=\"font-size:18px;margin:0;padding:0\">Oops!!!</h4>";
              $html .= "<p>Something went wrong. Looks like the page you're looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>";
              $html .= '</div>';
          }
          print $html;
          exit(1);
      }

      /**
       * Message::invalid()
       * 
       * @param mixed $data
       * @param bool $print
       * @return
       */
      public static function invalid($data, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo negative message\"><i class=\"ban circle icon\"></i> " . Lang::$word->SYSTEM_ERR1 . " <em>{$data}</em></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }

      /**
       * Message::permission()
       * 
       * @param mixed $data
       * @param bool $print
       * @return
       */
      public static function permission($data, $print = true)
      {
          self::$showMsg = "<div class=\"yoyo negative message\"><i class=\"ban circle icon\"></i> " . Lang::$word->SYSTEM_ERR2 . " <em>{$data}</em></div>";

          if ($print == true) {
              print self::$showMsg;
          } else {
              return self::$showMsg;
          }
      }
	  
      /**
       * Message::ooops()
       * 
       * @return
       */
      public static function ooops()
      {
          $the_error = "<div class=\"msgError\" style=\"color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff\">";
          $the_error .= "<h4 style=\"font-size:18px;margin:0;padding:0\">Oops!!!</h4>";
          $the_error .= "<p>Something went wrong. Looks like the page you're looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>";
          $the_error .= "<p>&lsaquo; <a href=\"javascript:history.go(-1)\" style=\"color:#0084FF;\"><strong>Go Back to previous page</strong></a></p>";
          $the_error .= '</div>';
          print $the_error;
          die;
      }
  }