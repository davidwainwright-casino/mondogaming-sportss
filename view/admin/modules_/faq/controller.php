<?php
  /**
   * F.A.Q.
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */

  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'faq/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Event == */
      case "deleteFaq":
          $res = Db::run()->delete(Faq::mTable, array("id" => Filter::$id));
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_FAQ_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
      /* == Delete deleteCategory == */
      case "deleteCategory":
          $res = Db::run()->delete(Faq::cTable, array("id" => Filter::$id));

          $message = str_replace("[NAME]", $title, Lang::$word->_MOD_FAQ_CAT_DEL_OK);
		  Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Faq == */
      case "processFaq":
          App::Faq()->processFaq();
      break;
      /* == Process Category == */
      case "processCategory":
          App::Faq()->processCategory();
      break;
      /* == Sort Categories == */
      case "sortCategories":
		  $jsonstring = $_POST['sortlist'];
		  $jsonDecoded = json_decode($jsonstring, true, 12);
		  $result = Utility::parseJsonArray($jsonDecoded);
		  $i = 0;
		  $query = "UPDATE `" . Faq::cTable . "` SET `sorting` = CASE ";
		  $idlist = '';
		  foreach ($result as $item):
			  $i++;
			  $query .= " WHEN id = " . $item['id'] . " THEN " . $i . " ";
			  $idlist .= $item['id'] . ',';
		  endforeach;
		  $idlist = substr($idlist, 0, -1);
		  $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
		  Db::run()->pdoQuery($query);
      break;
      /* == Sort Items == */
      case "sortItems":
		  $i = 0;
		  $query = "UPDATE `" . Faq::mTable . "` SET `sorting` = CASE ";
		  $idlist = '';
		  foreach ($_POST['sorting'] as $item):
			  $i++;
			  $query .= " WHEN id = " . $item . " THEN " . $i . " ";
			  $idlist .= $item . ',';
		  endforeach;
		  $idlist = substr($idlist, 0, -1);
		  $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
		  Db::run()->pdoQuery($query);
      break;
  endswitch;