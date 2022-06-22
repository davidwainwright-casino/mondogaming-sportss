<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */

  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'rss/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::post('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Rss == */
      case "deleteRss":
		  $res = Db::run()->delete(Rss::mTable, array("id" => Filter::$id));
		  if($row = Db::run()->first(Plugins::mTable, array("id", "plugalias"), array("plugin_id" => Filter::$id, "groups" => "rss"))) :
		      Db::run()->delete(Content::lTable, array("plug_id" => $row->id));
			  Db::run()->delete(Plugins::mTable, array("id" => $row->id));
			  
			  File::deleteDirectory(FPLUGPATH . $row->plugalias);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_PLG_RSS_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);

          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Rss == */
      case "processRss":
          App::Rss()->processRss();
      break;
  endswitch;