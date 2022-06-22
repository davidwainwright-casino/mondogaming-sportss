<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  define("_YOYO", true);
  require_once("../../init.php");
	  
  if (!App::Auth()->is_Admin())
      exit;
	  
  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::post('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete Actions == */
  switch ($delete):
          /* == Delete Custom Field == */
      case "deleteField":
          if ($row = Db::run()->delete(Content::cfTable, array("id" => Filter::$id))):
              Db::run()->delete(Content::cfdTable, array("field_id" => Filter::$id));
              $json['type'] = "success";
          endif;

          $json['title'] = Lang::$word->SUCCESS;
          $json['message'] = str_replace("[NAME]", $title, Lang::$word->CF_DEL_OK);
          print json_encode($json);
          break;
		  
          /* == Delete Database == */
      case "deleteBackup":
          File::deleteFile(UPLOADS . '/backups/' . $title);
          Message::msgReply(true, 'success', str_replace("[NAME]", $title, Lang::$word->DBM_DEL_OK));
          break;

          /* == Delete Module == */
      case "deleteModule":
          if ($row = Db::run()->delete(Modules::mTable, array("id" => Filter::$id))):
              $json['type'] = "success";
          endif;

          $json['title'] = Lang::$word->SUCCESS;
          $json['message'] = str_replace("[NAME]", $title, Lang::$word->MDL_DEL_OK);
          print json_encode($json);
          break;

          /* == Delete Language == */
      case "deleteLanguage":
          if ($row = Db::run()->first(Lang::lTable, array("id", "abbr"), array("id" => Filter::$id))):
              if ($row->abbr == Core::$language):
                  $json['type'] = "error";
                  $json['title'] = Lang::$word->ERROR;
                  $json['message'] = Lang::$word->LG_INFO;
              else:
                  if (Lang::deleteLanguage($row->abbr)):
                      Db::run()->delete(Lang::lTable, array("id" => Filter::$id));
                      Core::buildLangList();
					  Url::doSystemPageSlugs();
                      $json['title'] = Lang::$word->SUCCESS;
                      $json['type'] = "success";
                      $json['message'] = str_replace("[NAME]", $title, Lang::$word->LG_DEL_OK);

                  endif;
              endif;
          endif;
          print json_encode($json);
          break;
          
      /* == Delete Trash == */
      case "trashAll":
		  Db::run()->truncate(Core::txTable);
		  Message::msgReply(true, 'success', Lang::$word->TRS_TRS_OK);
          break;
  endswitch;

  
  /* == Trash Actions == */
  switch ($trash):
      /* == Trash User == */
      case "trashUser":
          if ($row = Db::run()->first(Users::mTable, "*", array("id =" =>Filter::$id, "AND type <>" => "owner"))):
              $data = array(
                  'type' => "user",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              Db::run()->delete(Users::mTable, array("id" => $row->id));
          endif;

		  $message = str_replace("[NAME]", $title, Lang::$word->M_TRASH_OK);
          Message::msgReply(Db::run()->affected(), 'success', $message);
		  Logger::writeLog($message);
          break;

      /* == Trash Page == */
      case "trashPage":
          if ($row = Db::run()->first(Content::pTable, "*", array("id" => Filter::$id))):
              $data = array(
                  'type' => "page",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              $res = Db::run()->delete(Content::pTable, array("id" => $row->id));
              if ($result = Db::run()->select(Content::lTable, "*", array("page_id" => $row->id))->results()):
                  foreach ($result as $item):
                      $dataArray[] = array(
                          'parent' => "page",
                          'type' => "layout",
                          'parent_id' => $row->id,
                          'dataset' => json_encode($item),
                          );
                  endforeach;
                  Db::run()->insertBatch(Core::txTable, $dataArray);
                  Db::run()->delete(Content::lTable, array("page_id" => $row->id));
              endif;
          endif;
		  $message = str_replace("[NAME]", $title, Lang::$word->PAG_TRASH_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;

      /* == Trash Menu == */
      case "trashMenu":
          if ($row = Db::run()->first(Content::mTable, "*", array("id" => Filter::$id))):
              $data = array(
                  'type' => "menu",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              $res = Db::run()->delete(Content::mTable, array("id" => $row->id));
              if ($result = Db::run()->select(Content::mTable, "*", array("parent_id" => $row->id))->results()):
                  foreach ($result as $item):
                      $dataArray[] = array(
                          'parent' => "menu",
                          'type' => "submenu",
                          'parent_id' => $row->id,
                          'dataset' => json_encode($item),
                          );
                  endforeach;
                  Db::run()->insertBatch(Core::txTable, $dataArray);
                  Db::run()->delete(Content::mTable, array("parent_id" => $row->id));
              endif;
              $json['menu'] = App::Content()->getMenuDropList(App::Content()->menuTree(), 0, 0, "&#166;&nbsp;&nbsp;&nbsp;&nbsp;");
          endif;

          $json['type'] = "success";
          $json['title'] = Lang::$word->SUCCESS;
          $json['message'] = str_replace("[NAME]", $title, Lang::$word->MEN_TRASH_OK);
		  print json_encode($json);
		  Logger::writeLog($json['message']);
          break;
		  
      /* == Trash Membership == */
      case "trashMembership":
          if ($row = Db::run()->first(Membership::mTable, "*", array("id =" =>Filter::$id))):
              $data = array(
                  'type' => "membership",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              Db::run()->delete(Membership::mTable, array("id" => $row->id));
          endif;

		  $message = str_replace("[NAME]", $title, Lang::$word->MEM_TRASH_OK);
          Message::msgReply(Db::run()->affected(), 'success', $message);
		  Logger::writeLog($message);
          break;
		  
      /* == Trash Coupon == */
      case "trashCoupon":
          if ($row = Db::run()->first(Content::dcTable, "*", array("id =" =>Filter::$id))):
              $data = array(
                  'type' => "coupon",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              Db::run()->delete(Content::dcTable, array("id" => $row->id));
          endif;

		  $message = str_replace("[NAME]", $title, Lang::$word->DC_TRASH_OK);
          Message::msgReply(Db::run()->affected(), 'success', $message);
		  Logger::writeLog($message);
          break;

      /* == Trash Plugin == */
      case "trashPlugin":
		  if ($row = Db::run()->first(Plugins::mTable, "*", array("id" => Filter::$id))):
              $data = array(
                  'type' => "plugin",
                  'parent_id' => Filter::$id,
                  'dataset' => json_encode($row));
              Db::run()->insert(Core::txTable, $data);
              $res = Db::run()->delete(Plugins::mTable, array("id" => $row->id));
			  Db::run()->delete(Content::lTable, array("plug_id" => $row->id));
		  endif;
		  $message = str_replace("[NAME]", $title, Lang::$word->PLG_TRASH_OK);
          Message::msgReply(true, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;

  /* == Restore Actions == */
  switch ($restore):
      /* == Restore Database == */
      case "restoreBackup":
		  dbTools::doRestore($title);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process User == */
      case "processUser":
          App::Users()->processUser();
      break;
      /* == Update Account == */
      case "updateAccount":
          App::Admin()->updateAccount();
      break;
      /* == Update Password == */
      case "updatePassword":
          App::Admin()->updateAdminPassword();
      break;
      /* == Process Membership == */
      case "processMembership":
          App::Membership()->processMembership();
      break;
      /* == Process Template == */
      case "processTemplate":
          App::Content()->processTemplate();
      break;
      /* == Process Page == */
      case "processPage":
          App::Content()->processPage();
      break;
      /* == Process Builder Page == */
      case "processBuilder":
          App::Content()->processBuilder();
      break;
      /* == Process Menu == */
      case "processMenu":
          App::Content()->processMenu();
      break;
      /* == Process Gateway == */
      case "processGateway":
          App::Admin()->processGateway();
      break;
      /* == Process Field == */
      case "processField":
          App::Content()->processField();
      break;
      /* == Process Mailer == */
      case "processMailer":
          App::Admin()->processMailer();
      break;
      /* == Process Country == */
      case "processCountry":
          App::Content()->processCountry();
      break;
      /* == Process Coupon == */
      case "processCoupon":
          App::Content()->processCoupon();
      break;
      /* == Process Plugin == */
      case "processPlugin":
          App::Plugins()->processPlugin();
      break;
      /* == Process Module == */
      case "processModule":
          App::Modules()->processModule();
      break;
      /* == Process Language == */
      case "processLanguage":
          App::Lang()->processLanguage();
      break;
      /* == Delete Inactive users == */
      case "processMInactive":
          Stats::deleteInactive(intval($_POST['days']));
      break;
      /* == Delete Banned Users == */
      case "processMBanned":
          Stats::deleteBanned();
      break;
      /* == Delete Cart == */
      case "processMCart":
          Stats::emptyCart();
      break;
      /* == Generate SiteMap == */
      case "processMap":
          Content::writeSiteMap();
      break;
      /* == Process Slugs == */
      case "processSlugs":
          App::Core()->processSlugs();
      break;
      /* == Install Plugin/Module == */
      case "processMInstall":
          App::Core()->install();
      break;
      /* == Process Configuration == */
      case "processConfig":
          App::Core()->processConfig();
      break;
  endswitch;
