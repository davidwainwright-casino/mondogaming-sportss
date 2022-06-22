<?php
  /**
   * Gmaps
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'gmaps/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Map == */
      case "deleteMap":
          if($row = Db::run()->first(Gmaps::mTable, array("id", "plugin_id"), array("id" => Filter::$id))) :
              $res = Db::run()->delete(Gmaps::mTable, array("id" => $row->id));
			  if($prow = Db::run()->first(Plugins::mTable, array("id", "plugin_id"), array("plugalias" => $row->plugin_id))):
			     Db::run()->delete(Plugins::mTable, array("id" => $prow->id));
			     Db::run()->delete(Plugins::lTable, array("plug_id" => $prow->id));
			  endif;
			  File::deleteDirectory(FPLUGPATH . $row->plugin_id);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_GM_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Map == */
      case "processMap":
           App::Gmaps()->processMap();
      break;
      /* == Load Map == */
      case "loadMap":
           $row = Db::run()->select(Gmaps::mTable, null, array("id" => Filter::$id))->result();
		   print json_encode($row);
      break;
  endswitch;