<?php
  /**
   * Adblock Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Adblock
  {

      const mTable = "mod_adblock";
      const ADATA = 'adblock/';
      const MAXSIZE = 204800;


      /**
       * Adblock::__construct()
       * 
       * @return
       */
      public function __construct()
      {
      }


      /**
       * Adblock::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_AB_TITLE;
          $tpl->data = Db::run()->select(self::mTable, null, null, "ORDER BY title" . Lang::$lang)->results();
          $tpl->template = 'admin/modules_/adblock/view/index.tpl.php';
      }

      /**
       * AdBlock::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_AB_EDIT;
          $tpl->crumbs = ['admin', 'modules', 'adblock', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [AdBlock.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules_/adblock/view/index.tpl.php';
          }
      }

      /**
       * AdBlock::Save()
       * 
       * @return
       */
      public function Save()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_AB_ADD;
          $tpl->langlist = App::Core()->langlist;
          $tpl->template = 'admin/modules_/adblock/view/index.tpl.php';
      }

      /**
       * AdBlock::processCampaign()
       * 
       * @return
       */
      public function processCampaign()
      {
          $rules = array(
              'start_date' => array('required|date', Lang::$word->_MOD_AB_SUB10),
              'end_date' => array('required|date', Lang::$word->_MOD_AB_SUB11),
              'max_views' => array('required|numeric', Lang::$word->_MOD_AB_SUB1),
              'max_clicks' => array('required|numeric', Lang::$word->_MOD_AB_SUB2),
              'min_ctr' => array('required|numeric|min_numeric,0|max_numeric,1', Lang::$word->_MOD_AB_SUB3),
              );
          $filters['banner_html'] = 'string';
          foreach (App::Core()->langlist as $lang) {
              $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
          }

          switch ($_POST['banner_type']) {
              case "yes":
                  $rules['image_link'] = array('required|valid_url', Lang::$word->_MOD_AB_SUB5);
                  $rules['image_alt'] = array('required|string', Lang::$word->_MOD_AB_SUB6);
                  break;

              case "no":
                  $filters['html'] = 'advanced_tags';
                  break;
          }

          (Filter::$id) ? $this->_updateCampaign($rules, $filters) : $this->_addCampaign($rules, $filters);

      }

      /**
       * AdBlock::_updateCampaign()
       * 
       * @param array $rules
       * @param array $filters
       * @return
       */
      public function _updateCampaign($rules, $filters)
      {

          $rules['plugin_id'] = array('required|string', "Invalid Plugin ID");

          if (!empty($_FILES['image']['name'])) {
              $banner = File::upload("image", self::MAXSIZE, "png,jpg,jpeg,gif");
          }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
          $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
              }

              $datax = array(
                  'start_date' => Db::toDate($safe->start_date, false),
                  'end_date' => Db::toDate($safe->end_date, false),
                  'total_views_allowed' => $safe->max_views,
                  'total_clicks_allowed' => $safe->max_clicks,
                  'minimum_ctr' => $safe->min_ctr);

              switch ($_POST['banner_type']) {
                  case "yes":
                      $datax['image_link'] = $safe->image_link;
                      $datax['image_alt'] = $safe->image_alt;
                      break;

                  case "no":
                      $datax['html'] = $safe->html;
                      break;
              }

              //process banner
              if (!empty($_FILES['image']['name'])) {
                  $row = Db::run()->first(self::mTable, array("image"), array('id' => Filter::$id));

                  $bpath = FPLUGPATH . $safe->plugin_id . '/';
                  $result = File::process($banner, $bpath, "BANNER_");
                  File::deleteFile($bpath . $row->image);
                  $datax['image'] = $result['fname'];
              }

              Db::run()->update(self::mTable, array_merge($datam, $datax), array("id" => Filter::$id));

              $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_AB_UPDATE_OK);
              Message::msgReply(Db::run()->affected(), 'success', $message);
              Logger::writeLog($message);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * AdBlock::_addCampaign()
       * 
       * @param array $rules
       * @param array $filters
       * @return
       */
      public function _addCampaign($rules, $filters)
      {

          if (!empty($_FILES['image']['name'])) {
              $banner = File::upload("image", self::MAXSIZE, "png,jpg,jpeg,gif");
          }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
          $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
              }

              $datax = array(
                  'plugin_id' => "adblock/" . Utility::randomString(),
                  'start_date' => Db::toDate($safe->start_date, false),
                  'end_date' => Db::toDate($safe->end_date, false),
                  'total_views_allowed' => $safe->max_views,
                  'total_clicks_allowed' => $safe->max_clicks,
                  'minimum_ctr' => $safe->min_ctr);

              switch ($_POST['banner_type']) {
                  case "yes":
                      $datax['image_link'] = $safe->image_link;
                      $datax['image_alt'] = $safe->image_alt;
                      break;

                  case "no":
                      $datax['html'] = $safe->html;
                      break;
              }

              //process banner
              if (!empty($_FILES['image']['name'])) {
                  $bpath = FPLUGPATH . $datax['plugin_id'] . '/';
                  $result = File::process($banner, $bpath, "BANNER_");
                  $datax['image'] = $result['fname'];
              }
              $last_id = Db::run()->insert(self::mTable, array_merge($datam, $datax))->getLastInsertId();

              // Create a new plugin
              File::makeDirectory(FPLUGPATH . $datax['plugin_id']);

              $plugin_file_main = FPLUGPATH . $datax['plugin_id'] . '/index.tpl.php';
              $plugin_file = FPLUGPATH . 'adblock/master.tpl.php';
              File::writeToFile($plugin_file_main, File::loadFile($plugin_file));

              $dataxq = array(
                  'system' => 0,
                  'cplugin' => 1,
                  'plugin_id' => $last_id,
				  'icon' => 'rss/thumb.png',
                  'plugalias' => $datax['plugin_id'],
                  'active' => 1,
                  );

              Db::run()->insert(Plugins::mTable, array_merge($datam, $dataxq));
              $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_AB_ADDED_OK);

              if ($last_id) {
                  $json['type'] = "success";
                  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = $message;
                  $json['redirect'] = Url::url("/admin/modules/adblock");
              } else {
                  $json['type'] = "alert";
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
       * AdBlock::Render($id)
       *
       * @param int $id
       * @return
       */
      public static function Render($id)
      {
		  $sql = "
		  SELECT 
			*,
			title" . Lang::$lang . " AS title
		  FROM
			`" . self::mTable . "` 
		  WHERE id = ?;";
		  $row = Db::Run()->pdoQuery($sql, array($id))->result();
		  
		  return ($row) ? $row : 0;
      }

      /**
       * AdBlock::udateView($id)
       *
       * @param int $id
       * @return
       */
      public static function udateView($id)
      {
			Db::run()->pdoQuery("
				UPDATE `" . self::mTable . "` 
				SET total_views = total_views + 1
				WHERE id = " . $id . "
			");
      }
	  
      /**
       * AdBlock::isOnline($row)
       *
       * @param int $row
       * @return
       */
      public static function isOnline($row)
      {
          $now = strtotime(Date::today());

          //time-period checking
          if (strtotime($row->start_date) > $now)
              return false;
          if ($row->end_date > 0 && strtotime($row->end_date) <= $now)
              return false;

          $total_views_allowed = $row->total_views_allowed;
          $total_views = $row->total_views;
          $total_clicks_allowed = $row->total_clicks_allowed;
          $total_clicks = $row->total_clicks;
          $min_ctr = $row->minimum_ctr;
          $ctr = ($total_views) ? round($total_clicks / $total_views) : 0;


          //conditions checking
          if ($total_views_allowed > 0 && $total_views > 0 && $total_views_allowed <= $total_views)
              return false;
          if ($total_clicks_allowed > 0 && $total_clicks > 0 && $total_clicks_allowed <= $total_clicks)
              return false;
          if ($min_ctr > 0 && $total_views > 0 && $ctr < $min_ctr)
              return false;

          return true;
      }

      /**
       * AdBlock::isOnlineStr($row)
       *
       * @param int $row
       * @return
       */
      public static function isOnlineStr($row)
      {
          return (self::isOnline($row)) ? Lang::$word->_MOD_AB_ONLINE : Lang::$word->_MOD_AB_OFFLINE;
      }
  }