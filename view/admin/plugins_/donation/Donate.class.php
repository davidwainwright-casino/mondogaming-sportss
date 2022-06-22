<?php
  /**
   * Donate Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Donate
  {

      const mTable = "plug_donation";
	  const dTable = "plug_donation_data";
	  


      /**
       * Donate::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }


      /**
       * Donate::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllDonations();
          $tpl->title = Lang::$word->_PLG_DP_TITLE;
          $tpl->template = 'admin/plugins_/donation/view/index.tpl.php';
      }


      /**
       * Donate::Edit()
       * 
       * @param mixed $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_DP_SUB4;
          $tpl->crumbs = ['admin', 'plugins', 'donation', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [donate.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->gateways = Db::run()->select(Core::gTable)->results();
			  $tpl->pagelist = App::Content()->getPageList();
              $tpl->template = 'admin/plugins_/donation/view/index.tpl.php';
          }
      }


      /**
       * Donate::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
		  $tpl->gateways = Db::run()->select(Core::gTable)->results();
		  $tpl->pagelist = App::Content()->getPageList();
          $tpl->title = Lang::$word->_PLG_DP_TITLE1;
          $tpl->template = 'admin/plugins_/donation/view/index.tpl.php';
      }

      /**
       * Donate::processDonate()
       * 
       * @return
       */
      public function processDonate()
      {

          $rules = array(
              'title' => array('required|string|min_len,3|max_len,80', Lang::$word->_PLG_DP_SUB1),
              'target_amount' => array('required|numeric', Lang::$word->_PLG_DP_TARGET),
              'redirect_page' => array('required|numeric', Lang::$word->_PLG_DP_SUB3),
              );

          $filters = array(
			  'title' => 'string',
		  );
		    
          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
          if (empty(Message::$msgs)) {
              $data = array(
                  'title' => $safe->title,
                  'target_amount' => $safe->target_amount,
                  'redirect_page' => $safe->redirect_page,
                  );
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_DP_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_DP_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
			  
			  if(!Filter::$id) {
				  // Insert new mulit plugin
				  $plugin_id = "donation/" . Utility::randomString();
				  File::makeDirectory(FPLUGPATH . $plugin_id);
				  File::copyFile(FPLUGPATH . 'donation/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
				  
				  $pid = Db::run()->first(Plugins::mTable, array("id"), array("plugalias" => "donation"));
				  foreach (App::Core()->langlist as $i => $lang) {
					  $datam['title_' . $lang->abbr] = $safe->title;
				  }
				  $datax = array(
					  'parent_id' => $pid->id,
					  'plugin_id' => $last_id,
					  'groups' => 'donation',
					  'icon' => 'donation/thumb.png',
					  'plugalias' => $plugin_id,
					  'active' => 1,
					  );
				  Db::run()->insert(Plugins::mTable, array_merge($datam, $datax));
		      }
          } else {
              Message::msgSingleStatus();
          }
      }
	  
      /**
       * Donate::getAllDonations()
       * 
       * @return
       */
      public function getAllDonations()
      {

          $sql = "
		  SELECT 
			m.id,
			m.title,
			m.target_amount,
			SUM(d.amount) AS total 
		  FROM
			`" . self::mTable . "` AS m 
			LEFT JOIN `" . self::dTable . "` AS d 
			  ON d.parent_id = m.id 
		  GROUP BY m.id;";
		  
          $row = Db::run()->pdoQuery($sql)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Donate::Render()
       * 
	   * @param int $id
       * @return
       */
      public function Render($id)
      {

          $sql = "
		  SELECT 
			m.id,
			m.title,
			m.target_amount,
			m.redirect_page,
			m.pp_email,
			(SELECT COALESCE(SUM(amount), 0) FROM `" . self::dTable . "` WHERE parent_id = m.id) as total
		  FROM
			`" . self::mTable . "` AS m 
		  WHERE m.id = ?;";

          $row = Db::run()->pdoQuery($sql, array($id))->result();
		  
		  if($row) {
			  $page = Db::run()->first(Content::pTable, array("slug" . Lang::$lang . " as slug"), array("id" => $row->redirect_page));
			  $row->page = $page->slug;
			  return $row;
		  } else {
			  return  0;
			  
		  }
      }
	  
      /**
       * Donate::exportDonations()
       * 
	   * @param int $id
       * @return
       */
      public static function exportDonations($id)
      {

          $sql = "
		  SELECT 
			d.name,
			d.email,
			d.amount,
			d.pp,
			d.created 
		  FROM
			`" . self::dTable . "` AS d 
		  WHERE parent_id = ?
		  ORDER BY d.created;";
		  
          $row = Db::run()->pdoQuery($sql, array($id))->results();
          return ($row) ? $row : 0;
      }

  }
