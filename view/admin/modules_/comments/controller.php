<?php
  /**
   * Comments
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'comments/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Item == */
      case "deleteComment":
          $res = Db::run()->delete(Comments::mTable, array("id" => Filter::$id));
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_CM_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Configuration == */
      case "processConfig":
          App::Comments()->processConfig();
      break;
      /* == Approve Comment == */
      case "approve":
          if(Db::run()->update(Comments::mTable, array("active" => 1), array("id" => Filter::$id))):
			  $json['type'] = "success";
			  print json_encode($json);
		  endif;
      break;
  endswitch;