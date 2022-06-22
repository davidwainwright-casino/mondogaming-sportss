<?php

  /**
   * Rss Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Rss
  {

      const mTable = "plug_rss";
	  const timetolive = 7200;
	  

      /**
       * Rss::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }


      /**
       * Rss::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllRss();
          $tpl->title = Lang::$word->_PLG_RSS_TITLE;
          $tpl->template = 'admin/plugins_/rss/view/index.tpl.php';
      }


      /**
       * Rss::Edit()
       * 
       * @param mixed $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_RSS_TITLE2;
          $tpl->crumbs = ['admin', 'plugins', 'rss', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [rss.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->template = 'admin/plugins_/rss/view/index.tpl.php';
          }
      }


      /**
       * Rss::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_RSS_TITLE1;
          $tpl->template = 'admin/plugins_/rss/view/index.tpl.php';
      }


      /**
       * Rss::processRss()
       * 
       * @return
       */
      public function processRss()
      {

          $rules = array(
              'title' => array('required|string|min_len,3|max_len,80', Lang::$word->NAME),
              'url' => array('required|valid_url', Lang::$word->_PLG_RSS_URL),
              'items' => array('required|numeric|min_len,1|max_len,2', Lang::$word->_PLG_RSS_ITEMS),
              'show_date' => array('required|numeric', Lang::$word->_PLG_RSS_SHOW_DATE),
              'show_desc' => array('required|numeric', Lang::$word->_PLG_RSS_SHOW_DESC),
              'max_words' => array('required|numeric|min_len,1|max_len,3', Lang::$word->_PLG_RSS_BODYTRIM),
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
                  'url' => $safe->url,
                  'items' => $safe->items,
                  'show_date' => $safe->show_date,
				  'show_desc' => $safe->show_desc,
				  'max_words' => $safe->max_words,
                  );
			   
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_RSS_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_RSS_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
			  
			  if(!Filter::$id) {
				  // Insert new mulit plugin
				  $plugin_id = "rss/" . Utility::randomString();
				  File::makeDirectory(FPLUGPATH . $plugin_id);
				  File::copyFile(FPLUGPATH . 'rss/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
				  
				  $pid = Db::run()->first(Plugins::mTable, array("id"), array("plugalias" => "rss"));
				  foreach (App::Core()->langlist as $i => $lang) {
					  $datam['title_' . $lang->abbr] = $safe->title;
				  }
				  $datax = array(
					  'parent_id' => $pid->id,
					  'plugin_id' => $last_id,
					  'groups' => 'rss',
					  'icon' => 'rss/thumb.png',
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
       * Rss::getAllRss()
       * 
       * @return
       */
      public function getAllRss()
      {

          $row = Db::run()->select(self::mTable)->results();
          return ($row) ? $row : 0;
      }

      /**
       * Rss::getRssById()
       * 
       * @return
       */
      public function getRssById($id)
      {

          $row = Db::run()->first(self::mTable, null, array("id" => $id));
          return ($row) ? $row : 0;
      }
	  
      /**
       * Rss::render()
       * 
       * @param mixed $feed_url
       * @param integer $max_item_cnt
       * @return
       */
      public static function render($feed_url, $max_item_cnt = 10)
      {
          $result = "";
          // get feeds and parse items
          $rss = new DOMDocument();
          $cache_file = FPLUGPATH . 'rss/cache/' . md5($feed_url);
          // load from file or load content
          if (self::timetolive > 0 && is_file($cache_file) && (filemtime($cache_file) + self::timetolive > time())) {
              $rss->load($cache_file);
          } else {
              $rss->load($feed_url);
              if (self::timetolive > 0) {
                  $rss->save($cache_file);
              }
          }
          $feed = array();
          foreach ($rss->getElementsByTagName('item') as $node) {
              $item = array(
                  'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                  'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                  'content' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                  'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
                  'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                  );
              $content = $node->getElementsByTagName('encoded');
              if ($content->length > 0) {
                  $item['content'] = $content->item(0)->nodeValue;
              }
              array_push($feed, $item);
          }
          // real good count
          if ($max_item_cnt > count($feed)) {
              $max_item_cnt = count($feed);
          }
		  
		  return [$feed, $max_item_cnt];
      }

  }
