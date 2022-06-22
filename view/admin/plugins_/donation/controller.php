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
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'donation/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::request('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Donation == */
      case "deleteDonation":
		  $res = Db::run()->delete(Donate::mTable, array("id" => Filter::$id));
		  Db::run()->delete(Donate::dTable, array("parent_id" => Filter::$id));
		  if($row = Db::run()->first(Plugins::mTable, array("id", "plugalias"), array("plugin_id" => Filter::$id, "groups" => "donation"))) :
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
      /* == Process Donation == */
      case "processDonate":
          App::Donate()->processDonate();
      break;
      /* == Export Donation == */
      case "exportDonations":
		  if($row = Db::run()->first(Donate::mTable, array('id', 'title'), array("id" => Filter::$id))):
			  header("Pragma: no-cache");
			  header('Content-Type: text/csv; charset=utf-8');
			  header('Content-Disposition: attachment; filename=OrderHistory_' . Url::doSeo($row->title) . '.csv');
			  
			  $data = fopen('php://output', 'w');
			  fputcsv($data, array(Lang::$word->NAME, Lang::$word->M_EMAIL1, Lang::$word->TRX_AMOUNT, Lang::$word->TRX_PP, Lang::$word->DATE));
		
			  $array = Donate::exportDonations($row->id);
			  $result = json_decode(json_encode($array), true);
			  
			  if($result):
				  foreach ($result as $row) :
					  fputcsv($data, $row);
				  endforeach;
			  endif;
		  endif;
      break;
  endswitch;