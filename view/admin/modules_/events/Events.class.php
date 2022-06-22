<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  

  class Events
  {

      const mTable = "mod_events";
	  	  

      /**
       * Events::__construct()
       * 
       * @return
       */
      public function __construct()
      {
	  }

      /**
       * Events::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {

		  $enddate = (Validator::get('enddate') && Validator::get('enddate') <> "") ? Validator::sanitize(Db::toDate(Validator::get('enddate'), false)) : date("Y-m-d");
		  $fromdate = Validator::get('fromdate') ? Validator::sanitize(Db::toDate(Validator::get('fromdate'), false)) : null;
		  
          if (isset($_GET['letter']) and (Validator::get('fromdate') && Validator::get('fromdate') <> "")) {
              $letter = Validator::sanitize($_GET['letter'], 'string', 2);
              $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE `date_start` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `title" . Lang::$lang . "` REGEXP '^" . $letter . "'");
              $where = "WHERE `date_start` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `title" . Lang::$lang . "` REGEXP '^" . $letter . "'";

          } elseif (Validator::get('fromdate') && Validator::get('fromdate') <> "") {
              $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE `date_start` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'");
              $where = "WHERE `date_start` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";

          } elseif (isset($_GET['letter'])) {
              $letter = Validator::sanitize($_GET['letter'], 'string', 2);
              $where = "WHERE `title" . Lang::$lang . "` REGEXP '^" . $letter . "'";
              $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE `title" . Lang::$lang . "` REGEXP '^" . $letter . "' LIMIT 1");
          } else {
			  $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` LIMIT 1");
              $where = null;
          }
		  
          if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
              list($sort, $order) = explode("|", $_GET['order']);
              $sort = Validator::sanitize($sort, "default", 16);
              $order = Validator::sanitize($order, "default", 4);
              if (in_array($sort, array(
                  "title",
                  "venue",
                  "contact",
                  "ending"))) {
                  $ord = ($order == 'DESC') ? " DESC" : " ASC";
                  $sorting = $sort . $ord;
              } else {
                  $sorting = " date_start DESC";
              }
          } else {
              $sorting = " date_start DESC";
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::Core()->perpage;
          $pager->path = Url::url(Router::$path, "?");
          $pager->paginate();
		  
          $sql = "
		  SELECT 
		    id,
		    date_start,
			date_end AS ending,
			time_start,
			time_end,
			contact_person as contact,
			title" . Lang::$lang . " AS title,
			venue" . Lang::$lang . " AS venue
		  FROM
			`" . self::mTable . "`
		  $where 
		  ORDER BY $sorting " . $pager->limit; 
		  
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = Db::run()->pdoQuery($sql)->results();
          $tpl->title = Lang::$word->_MOD_EM_TITLE;
		  $tpl->pager = $pager;
          $tpl->template = 'admin/modules_/events/view/index.tpl.php';
		  
	  }

      /**
       * Events::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_EM_TITLE1;
          $tpl->crumbs = ['admin', 'modules', 'events', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Events.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules_/events/view/index.tpl.php';
          }
      }

      /**
       * Events::Save()
       * 
       * @return
       */
	  public function Save()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->_MOD_EM_SUB;
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->template = 'admin/modules_/events/view/index.tpl.php';
	  }
	  
      /**
       * Events::Calendar()
       * 
       * @return
       */
      public function Calendar()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_EM_TITLE;
          $tpl->template = 'admin/modules_/events/view/index.tpl.php'; 
	  }

      /**
       * Events::processEvent()
       * 
       * @return
       */
      public function processEvent()
      {

          $rules = array(
              'date_start_submit' => array('required|date', Lang::$word->_MOD_EM_DATE_ST),
              'date_end_submit' => array('required|date', Lang::$word->_MOD_EM_DATE_E),
              'time_start' => array('required|time', Lang::$word->_MOD_EM_TIME_ST),
              'time_end' => array('required|time', Lang::$word->_MOD_EM_TIME_ET),
              'active' => array('required|numeric|min_len,1|max_len,1', Lang::$word->PUBLISHED),
              );
          
		  $filters['time_start'] = 'string';
		  $filters['time_end'] = 'string';
		  $filters['contact_person'] = 'string';
		  $filters['contact_email'] = 'string';
		  $filters['contact_phone'] = 'string';
		  $filters['color'] = 'string';
		  
          foreach (App::Core()->langlist as $lang) {
              $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,100', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['venue_' . $lang->abbr] = 'string';
			  $filters['body_' . $lang->abbr] = 'advanced_tags';
          }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $datam['venue_' . $lang->abbr] = $safe->{'venue_' . $lang->abbr};
				  $datam['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
              }
              $datax = array(
                  'date_start' => $safe->date_start_submit,
                  'date_end' => $safe->date_end_submit,
                  'time_start' => $safe->time_start_submit,
                  'time_end' => $safe->time_end_submit,
                  'contact_person' => $safe->contact_person,
				  'contact_email' => $safe->contact_email,
				  'contact_phone' => $safe->contact_phone,
				  'color' => $safe->color,
				  'active' => $safe->active,
                  );

			  $data = array_merge($datam, $datax);
			  (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : Db::run()->insert(self::mTable, $data); 
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_EM_UPDATE_OK) : 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_EM_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Events::Render()
       * 
       * @return
       */
      public function Render()
      {
          $sql = "
		  SELECT 
			color,
			date_start,
			date_end,
			contact_person,
			contact_email,
			contact_phone,
			DATE_FORMAT(`time_start`, '%H:%i') as time_start,
			DATE_FORMAT(`time_end`, '%H:%i') as time_end,
			DATE_FORMAT(`date_start`, '%Y-%m') as month,
			venue" . Lang::$lang . " as venue,
			title" . Lang::$lang . " as title,
			body" . Lang::$lang . " as body
		  FROM
			`" . self::mTable . "`
		  WHERE active = ? 
		  ORDER BY date_start;"; 
		  
          $row = Db::run()->pdoQuery($sql, array(1))->results();  
		  return ($row) ? $row : 0;
	  }

      /**
       * Booking::getCalendar()
       * 
	   * @param int $year
	   * @param int $month
       * @return
       */
      public function getCalendar($year, $month)
      {
          $sql = "
		  SELECT 
			id,
			color,
			date_start,
			date_end,
			DATE_FORMAT(`time_start`, '%H:%i') as time_start,
			DATE_FORMAT(`time_end`, '%H:%i') as time_end,
			venue" . Lang::$lang . " as venue,
			title" . Lang::$lang . " as title
		  FROM
			`" . self::mTable . "`
		  WHERE YEAR(date_start) = ? 
			AND MONTH(date_start) = ? 
			OR MONTH(date_end) = ?
			AND active = ? 
		  ORDER BY time_start ASC;";
		  
		  $row = Db::run()->pdoQuery($sql, array($year, $month, $month, 1))->results();
		  return ($row) ? $row : 0;
	  }
	  
      /**
       * Events::getEvents()
       * 
       * @return
       */
      public function getEvents($year, $month)
      {
		  $ld = date('t', strtotime('now'));
          $sql = "
		  SELECT 
			id,
			color,
			date_start,
			date_end,
			contact_person,
			contact_email,
			contact_phone,
			DATE_FORMAT(`time_start`, '%H:%i') as time_start,
			DATE_FORMAT(`time_end`, '%H:%i') as time_end,
			venue" . Lang::$lang . " as venue,
			title" . Lang::$lang . " as title,
			body" . Lang::$lang . " as body
		  FROM
			`" . self::mTable . "`
		  WHERE date_start <= '$year-$month-01' 
		    AND date_end >= '$year-$month-$ld)'
			AND active = ? 
		  ORDER BY time_start ASC;";
		  
		  $row = Db::run()->pdoQuery($sql, array(1))->results();
		  return ($row) ? $row : 0;
	  }
	  
  }