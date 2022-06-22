<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Timeline
  {

      const mTable = "mod_timeline";
      const dTable = "mod_timeline_data";
	  const SPATH = "timeline/snippets/";

      /**
       * Timeline::__construct()
       * 
       * @return
       */
      public function __construct(){}

      /**
       * Timeline::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = Db::run()->select(self::mTable, null, null, "ORDER BY created DESC")->results();
          $tpl->title = Lang::$word->_MOD_TML_TITLE;
          $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
      }

      /**
       * Timeline::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_TML_SUB;
          $tpl->crumbs = ['admin', 'modules', 'timeline', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Timeline.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->layoutlist = $this->LayoutMode();
              $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
          }
      }

      /**
       * Timeline::Save()
       * 
       * @return
       */
      public function Save()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_TML_ADD;
		  $tpl->layoutlist = $this->LayoutMode();
		  $tpl->typelist = $this->typeList();
          $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
      }

      /**
       * Timeline::CustomItems()
       * 
	   * @param int $id
       * @return
       */
      public function CustomItems($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_TML_SUB10;
          $tpl->crumbs = ['admin', 'modules', 'timeline', 'items'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id, "type" => "custom"))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Timeline.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
			  $pager = Paginator::instance();
			  $pager->items_total = Db::run()->count(self::dTable);
			  $pager->default_ipp = App::Core()->perpage;
			  $pager->path = Url::url(Router::$path, "?");
			  $pager->paginate();
			  
			  $tpl->pager = $pager;
              $tpl->row = $row;
			  $tpl->data = Db::run()->select(self::dTable, null, array("tid" => $row->id), "ORDER BY created DESC" . $pager->limit)->results();
              $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
          }
      }

      /**
       * Timeline::CustomEdit()
       * 
	   * @param int $tid
	   * @param int $id
       * @return
       */
      public function CustomEdit($tid, $id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_TML_SUB12;
          
          if (!$row = Db::run()->first(self::mTable, array("id", "type", "plugin_id"), array("id" => $tid, "type" => "custom"))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Timeline.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
			  $tpl->crumbs = ['admin', 'modules', 'timeline', array(0 =>'items', 1 => 'items/' . $tid), 'edit'];
              $tpl->row = $row;
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->data = Db::run()->first(self::dTable, null, array("id" => $id));
			  $tpl->imagedata = Utility::jSonToArray($tpl->data->images);
              $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
          }
      }

      /**
       * Timeline::CustomSave()
       * 
	   * @param int $id
       * @return
       */
      public function CustomSave($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_TML_SUB11;

          if (!$row = Db::run()->first(self::mTable, array("id", "type", "plugin_id"), array("id" => $id, "type" => "custom"))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Timeline.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
			  $tpl->crumbs = ['admin', 'modules', 'timeline', array(0 =>'items', 1 => 'items/' . $id), 'new'];
              $tpl->row = $row;
			  $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules_/timeline/view/index.tpl.php';
          }
      }
	  
      /**
       * Portfolio::processTimeline()
       * 
       * @return
       */
      public function processTimeline()
      {

          $rules = array(
              'name' => array('required|string|min_len,3|max_len,80', Lang::$word->NAME),
              'colmode' => array('required|string', Lang::$word->_MOD_TML_LMODE),
              'limiter' => array('required|numeric', Lang::$word->_MOD_TML_SUB3),
              'showmore' => array('required|numeric', Lang::$word->_MOD_TML_SUB5),
              'maxitems' => array('required|numeric', Lang::$word->_MOD_TML_SUB4),
			  'type' => array('required|string', Lang::$word->_MOD_TML_SUB9),
              );

          switch ($_POST['type']) {
              case "rss":
                  $rules['rssurl'] = array('required|valid_url', Lang::$word->_MOD_TML_SUB8);
                  break;

              case "facebook":
                  $rules['fbpage'] = array('required|string', Lang::$word->_MOD_TML_SUB12);
                  $rules['fbtoken'] = array('required|string', Lang::$word->_MOD_TML_SUB7);
				  $rules['fbid'] = array('required|string', Lang::$word->_MOD_TML_SUB6);
                  break;
          }

          (Filter::$id) ? $this->_updateTimeline($rules) : $this->_addTimeline($rules);
      }

      /**
       * Portfolio::_updateTimeline()
       * 
       * @param array $rules
       * @return
       */
      public function _updateTimeline($rules)
      {

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);

          if (empty(Message::$msgs)) {
              $data = array(
                  'name' => $safe->name,
                  'limiter' => $safe->limiter,
                  'showmore' => $safe->showmore,
                  'maxitems' => $safe->maxitems,
                  'colmode' => $safe->colmode,
                  'rssurl' => $safe->type == "rss" ? $safe->rssurl : 'NULL',
                  'fbpage' => $safe->type == "facebook" ? $safe->fbpage : 0,
                  'fbtoken' => $safe->type == "facebook" ? $safe->fbtoken : 'NULL',
                  );

			  foreach (App::Core()->langlist as $i => $lang) {
				  $dataf['title_' . $lang->abbr] = $safe->name;
			  }
			  
              Db::run()->update(self::mTable, $data, array("id" => Filter::$id));

              $message = Message::formatSuccessMessage($data['name'], Lang::$word->_MOD_TML_UPDATE_OK);
              Message::msgReply(Db::run()->affected(), 'success', $message);
			  Db::run()->update(Modules::mTable, $dataf, array("parent_id" => Filter::$id, "modalias" => "timeline"));
              Logger::writeLog($message);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Portfolio::_addTimeline()
       * 
       * @param array $rules
       * @return
       */
      public function _addTimeline($rules)
      {

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);

          if (empty(Message::$msgs)) {
              $data = array(
                  'name' => $safe->name,
				  'plugin_id' => "timeline/" . $safe->type . '_' . Utility::randomString(),
                  'limiter' => $safe->limiter,
                  'showmore' => $safe->showmore,
                  'maxitems' => $safe->maxitems,
                  'colmode' => $safe->colmode,
				  'type' => $safe->type,
                  'rssurl' => $safe->type == "rss" ? $safe->rssurl : 'NULL',
                  'fbpage' => $safe->type == "facebook" ? $safe->fbpage : 0,
                  'fbtoken' => $safe->type == "facebook" ? $safe->fbtoken : 'NULL',
                  );
				  
              $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();
			  
			  foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->name;
			  }
			  
			  $datap = array(
				  'modalias' => $data['plugin_id'],
				  'parent_id' => $last_id,
				  'icon' => "timeline/thumb.svg",
				  'active' => 1,
				  'is_builder' => 1,
				  );
				  
			  // Create a new plugin
			  File::makeDirectory(FMODPATH . $data['plugin_id']);
			  $plugin_file_main = FMODPATH . $data['plugin_id'] . '/index.tpl.php';
			  $plugin_file = FMODPATH . self::SPATH . '_' . $safe->type . '.tpl.php';
			  File::writeToFile($plugin_file_main, File::loadFile($plugin_file));
			  
			  $message = Message::formatSuccessMessage($data['name'], Lang::$word->_MOD_TML_ADDED_OK);
			  Db::run()->insert(Modules::mTable, array_merge($datam, $datap));

			  if ($safe->type == "custom") {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = $message;
				  $json['redirect'] = Url::url("/admin/modules/timeline/items", $last_id);
			  } else {
				  $json['type'] = "success";
				  $json['title'] = Lang::$word->SUCCESS;
				  $json['message'] = $message;
			  }
			  print json_encode($json);
			  Logger::writeLog($message);

          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Timeline::processItem()
       * 
       * @return
       */
      public function processItem()
      {

		  switch($_POST['type']) {
			  case "iframe":
				  $rules['dataurl'] = array('required|valid_url', Lang::$word->_MOD_TML_IURL);
			  break;
			  
			  case "gallery":
				  if (!array_key_exists('images', $_POST)) {
					  Message::$msgs['images'] = LANG::$word->_MOD_TML_SUB15;
				  }
			  break;
		  }
		  
		  $rules = array(
			  'type' => array('required|string', Lang::$word->_MOD_TML_SUB14),
			  'tid' => array('required|numeric', 'Timeline ID'),
			  );
		  $filters['readmore'] = 'string';
		  
		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['body_' . $lang->abbr] = 'advanced_tags';
		  }

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
          if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                  $datam['body_' . $lang->abbr] = Url::in_url($_POST['body_' . $lang->abbr]);
			  }
              $datax = array(
                  'type' => $safe->type,
                  'tid' => $safe->tid,
				  'readmore' => $safe->readmore,
				  'images' => isset($_POST['images']) ? json_encode($_POST['images']) : "NULL",
				  'dataurl' => isset($_POST['dataurl']) ? Validator::sanitize($_POST['dataurl'], "url") : "NULL",
				  'height' => isset($_POST['height']) ? intval($_POST['height']) : 300,
                  );
				  
              $data = array_merge($datam, $datax);
              (Filter::$id) ? Db::run()->update(self::dTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::dTable, $data)->getLastInsertId();
			  
              if (Filter::$id) {
				  $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_TML_ITMUPDATE_OK);
                  Message::msgReply(Db::run()->affected(), 'success', $message);
				  Logger::writeLog($message);
              } else {
                  if ($last_id) {
					  $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_TML_ITMADDED_OK);
                      $json['type'] = "success";
                      $json['title'] = Lang::$word->SUCCESS;
                      $json['message'] = $message;
                      $json['redirect'] = Url::url("/admin/modules/timeline/items", $safe->tid);
					  Logger::writeLog($message);
                  } else {
                      $json['type'] = "alert";
                      $json['title'] = Lang::$word->ALERT;
                      $json['message'] = Lang::$word->NOPROCCESS;
                  }
                  print json_encode($json);
			  }
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Timeline::Render()
       * 
	   * @param array $id
       * @return
       */
      public function Render($id)
      {
          $row = Db::run()->first(self::mTable, null, array("id" => $id));

          return ($row) ? $row : 0; 
      }
	  
      /**
       * Timeline::LayoutMode()
       * 
       * @return
       */
      public function LayoutMode()
      {
          $array = array(
              'dual' => "Dual Column",
              'left' => "Left Column",
              'right' => "Right Column",
              'center' => "Center Column",
              );

          return $array;
      }
	  
      /**
       * Timeline::typeList()
       * 
       * @return
       */
      public function typeList()
      {
          $array = array(
              'event' => "Event Module",
              'rss' => "Rss Feed",
              'facebook' => "Facebook Page",
              'custom' => "Custom Timeline",
              );
			  
          $data =  Db::run()->select(Modules::mTable, array("modalias", "title" . Lang::$lang . " AS title"), array("system" => 1))->results();
		  if($data) {
			  foreach($data as $row)  {
				  if($row->modalias == "blog") {
				    $array['blog'] = $row->title;
				  }
				  if($row->modalias == "portfolio") {
				    $array['portfolio'] = $row->title;
				  }
			  }
		  }
          return $array;
      }
  }