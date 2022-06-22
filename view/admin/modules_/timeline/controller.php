<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'timeline/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Timeline == */
      case "deleteTimeline":
          if($row = Db::run()->first(Timeline::mTable, array("id", "plugin_id"), array("id" => Filter::$id))) :
              $res = Db::run()->delete(Timeline::mTable, array("id" => $row->id));
			  Db::run()->delete(Timeline::dTable, array("tid" => $row->id));
			  Db::run()->delete(Plugins::mTable, array("plugalias" => $row->plugin_id));
			  Db::run()->delete(Modules::mTable, array("parent_id" => Filter::$id, "modalias" => "timeline"));
			  File::deleteDirectory(FPLUGPATH . $row->plugin_id);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_TML_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
      /* == Delete Item == */
      case "deleteItem":
          $res = Db::run()->delete(Timeline::dTable, array("id" => Filter::$id));
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_TML_DELI_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Timeline == */
      case "processTimeline":
          App::Timeline()->processTimeline();
      break;
      /* == Process Item == */
      case "processItem":
          App::Timeline()->processItem();
      break;
  endswitch;
  
  /* == Live Search == */
  if (isset($_GET['liveSearch'])):
      $string = Validator::sanitize($_GET['value'], 'string', 15);
      switch (Validator::get('type')):
          case "timeline":
              if (strlen($string) > 3):
                  $sql = "
					SELECT 
					  id,
					  created,
					  title" . Lang::$lang . "
					FROM
					  `" . Timeline::dTable . "`
					WHERE MATCH (title" . Lang::$lang . ") AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY title" . Lang::$lang . " 
					LIMIT 10 ";

                  $html = '';
                  if ($result = Db::run()->pdoQuery($sql)->results()):
                      $html .= '<table class="yoyo basic dashed table">';
                      foreach ($result as $row):
                          $link = Url::url("/admin/modules/timeline/iedit", $row->id);
                          $html .= '<tr>';
                          $html .= '<td>';
                          $html .= '<span class="yoyo basic disabled label">' . $row->id . '</span>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= '<a href="' . $link . '" class="white">' . $row->{'title' . Lang::$lang} . '</a>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= Date::Dodate("short_date", $row->created);
                          $html .= '</td>';
                          $html .= '</tr>';
                      endforeach;
                      $html .= '</table>';
					  $json['html'] = $html;
					  $json['status'] = 'success';
                  else:
					  $json['status'] = 'error';
                  endif;
				  print json_encode($json);
              endif;
          break;
      endswitch;
  endif;