<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
 
  define("_WOJO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'carousel/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::post('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Player == */
      case "deletePlayer":
		  $res = Db::run()->delete(Carousel::mTable, array("id" => Filter::$id));
		  if($row = Db::run()->first(Plugins::mTable, array("id", "plugalias"), array("plugin_id" => Filter::$id, "groups" => "carousel"))) :
		      Db::run()->delete(Content::lTable, array("plug_id" => $row->id));
			  Db::run()->delete(Plugins::mTable, array("id" => $row->id));
			  
			  File::deleteDirectory(FPLUGPATH . $row->plugalias);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_PLG_YPL_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);

          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Player == */
      case "processPlayer":
          App::Carousel()->processPlayer();
      break;
  endswitch;