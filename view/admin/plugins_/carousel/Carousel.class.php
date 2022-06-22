<?php
  /**
   * Carousel Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Carousel
  {

    const mTable = "plug_carousel";

      /**
       * Carousel::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }


      /**
       * Carousel::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllPlayers();
          $tpl->title = Lang::$word->_PLG_CRL_TITLE;
          $tpl->template = 'admin/plugins_/carousel/view/index.tpl.php';
      }


      /**
       * Carousel::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_CRL_TITLE2;
          $tpl->crumbs = ['admin', 'plugins', 'carousel', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Carousel.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->settings = Utility::jSonToArray($row->settings);
			  $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/plugins_/carousel/view/index.tpl.php';
          }
      }


      /**
       * Carousel::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_CRL_SUB2;
		  $tpl->langlist = App::Core()->langlist;
          $tpl->template = 'admin/plugins_/carousel/view/index.tpl.php';
      }


      /**
       * Carousel::processPlayer()
       * 
       * @return
       */
      public function processPlayer()
      {

          $rules = array(
              'dots' => array('required|numeric', Lang::$word->_PLG_CRL_SUB11),
			  'nav' => array('required|numeric', Lang::$word->_PLG_CRL_SUB12),
              'autoplay' => array('required|numeric|min_len,1|max_len,4', Lang::$word->_PLG_CRL_SUB7),
              'margin' => array('required|numeric|min_len,1|max_len,3', Lang::$word->_PLG_CRL_SUB8),
              'loop' => array('required|numeric', Lang::$word->_PLG_CRL_SUB13),
			  'large' => array('required|numeric', Lang::$word->_PLG_CRL_SUB14),
			  'medium' => array('required|numeric', Lang::$word->_PLG_CRL_SUB14),
			  'small' => array('required|numeric', Lang::$word->_PLG_CRL_SUB14),
              );
			  
		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['body_' . $lang->abbr] = 'advanced_tags';
		  }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
          if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $datam['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
			  }

              $datax = array(
                  'dots' => $safe->dots,
				  'nav' => $safe->nav,
				  'autoplay' => $safe->autoplay,
                  'margin' => $safe->margin,
                  //'loop' => $safe->loop,
				  'settings' => json_encode(array(
				      'dots' => ($safe->dots) ? true : false,
					  'nav' => ($safe->nav) ? true : false,
					  'autoplay' => ($safe->autoplay) ? true : false,
					  'margin' => (int)$safe->margin,
					  'loop' => ($safe->loop) ? true : false,
					  'responsive' => array(
						  0 => array("items" => (int)$safe->small),
						  769 => array("items" => (int)$safe->medium),
						  1024 => array("items" => (int)$safe->large))
					  ))
                  );
				  
			  $data = array_merge($datam, $datax);
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_PLG_CRL_UPDATE_OK) : 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_PLG_CRL_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
			  
			  if(!Filter::$id) {
				  // Insert new mulit plugin
				  $plugin_id = "carousel/" . Utility::randomString();
				  File::makeDirectory(FPLUGPATH . $plugin_id);
				  File::copyFile(FPLUGPATH . 'carousel/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
				  
				  $pid = Db::run()->first(Plugins::mTable, array("id"), array("plugalias" => "carousel"));
				  foreach (App::Core()->langlist as $i => $lang) {
					  $datapm['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  }
				  $datap = array(
					  'parent_id' => $pid->id,
					  'plugin_id' => $last_id,
					  'groups' => 'carousel',
					  'icon' => 'carousel/thumb.png',
					  'plugalias' => $plugin_id,
					  'cplugin' => 1,
					  'active' => 1,
					  );
				  Db::run()->insert(Plugins::mTable, array_merge($datapm, $datap));
		      }
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Carousel::getAllPlayers()
       * 
       * @return
       */
      public function getAllPlayers()
      {

          $row = Db::run()->select(self::mTable)->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Carousel::render()
       * 
	   * @param int $id
       * @return
       */
      public function render($id)
      {

          $row = Db::run()->first(self::mTable, null, array("id" => $id));
          return ($row) ? $row : 0;
      }

  }