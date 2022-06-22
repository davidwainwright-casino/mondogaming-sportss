<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'events/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Event == */
      case "deleteEvent":
          $res = Db::run()->delete(Events::mTable, array("id" => Filter::$id));
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_EM_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Event == */
      case "processEvent":
          App::Events()->processEvent();
      break;
      /* == Get Events == */
      case "events":
		  if(empty($_GET['year']) or empty($_GET['year'])):
			  $year = Date::doDate("yyyy", Date::today());
			  $month = Date::doDate("MM", Date::today());
		  else:
			  $year = Validator::sanitize($_GET['year'], "time");
			  $month = Validator::sanitize($_GET['month'], "time"); 
		  endif;
			  $json['events'] = App::Events()->getCalendar($year, $month);
		  print json_encode($json);
      break;
  endswitch;
  
  /* == Live Search == */
  if (isset($_GET['liveSearch'])):
      $string = Validator::sanitize($_GET['value'], 'string', 15);
      switch (Validator::get('type')):
          case "events":
              if (strlen($string) > 3):
                  $sql = "
					SELECT 
					  id,
					  date_start,
					  title" . Lang::$lang . "
					FROM
					  `" . Events::mTable . "`
					WHERE MATCH (title" . Lang::$lang . ") AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY title" . Lang::$lang . " 
					LIMIT 10 ";

                  $html = '';
                  if ($result = Db::run()->pdoQuery($sql)->results()):
                      $html .= '<table class="yoyo basic dashed table">';
                      foreach ($result as $row):
                          $link = Url::url("/admin/modules/events/edit", $row->id);
                          $html .= '<tr>';
                          $html .= '<td>';
                          $html .= '<span class="yoyo basic disabled label">' . $row->id . '</span>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= '<a href="' . $link . '" class="white">' . $row->{'title' . Lang::$lang} . '</a>';
                          $html .= '</td>';
                          $html .= '<td>';
                          $html .= Date::doDate("short_date", $row->date_start);
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