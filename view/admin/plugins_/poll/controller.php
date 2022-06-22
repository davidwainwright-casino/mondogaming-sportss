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
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'poll/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::post('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Poll == */
      case "deletePoll":
		  $res = Db::run()->delete(Poll::qTable, array("id" => Filter::$id));
		  Db::run()->delete(Poll::oTable, array("question_id" => Filter::$id));
		  Db::run()->pdoQuery("DELETE FROM `" . Poll::vTable . "` WHERE option_id IN(SELECT id FROM `" . Poll::oTable . "` WHERE question_id=" . Filter::$id . ");");
		  if($row = Db::run()->first(Plugins::mTable, array("id", "plugalias"), array("plugin_id" => Filter::$id, "groups" => "poll"))) :
		      Db::run()->delete(Content::lTable, array("plug_id" => $row->id));
			  Db::run()->delete(Plugins::mTable, array("id" => $row->id));
			  
			  File::deleteDirectory(FPLUGPATH . $row->plugalias);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_PLG_PL_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Poll == */
      case "processPoll":
          App::Poll()->processPoll();
      break;
      /* == Update Option == */
      case "updateOption":
          if(Db::run()->update(Poll::oTable, array("value" => Validator::sanitize($_POST['value'])), array("id" => Filter::$id))) :
		     print 1;
		  endif;
      break;
      /* == Delete Option == */
      case "deleteOption":
          if(Db::run()->delete(Poll::oTable, array("id" => Filter::$id))) :
		     Db::run()->delete(Poll::vTable, array("option_id" => Filter::$id));
		     print 1;
		  endif;
      break;
  endswitch;