<?php
  /**
   * Yplayer Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Yplayer
  {

    const mTable = "plug_yplayer";

      /**
       * Yplayer::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }


      /**
       * Yplayer::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllPlayers();
          $tpl->title = Lang::$word->_PLG_YPL_TITLE;
          $tpl->template = 'admin/plugins_/yplayer/view/index.tpl.php';
      }


      /**
       * Yplayer::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_YPL_TITLE2;
          $tpl->crumbs = ['admin', 'plugins', 'yplayer', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Yplayer.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->row = Utility::jSonToArray($row->config);
              $tpl->template = 'admin/plugins_/yplayer/view/index.tpl.php';
          }
      }


      /**
       * Yplayer::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_YPL_SUB2;
          $tpl->template = 'admin/plugins_/yplayer/view/index.tpl.php';
      }


      /**
       * Yplayer::processPlayer()
       * 
       * @return
       */
      public function processPlayer()
      {

          $rules = array(
              'title' => array('required|string|min_len,3|max_len,80', Lang::$word->NAME),
			  'mode' => array('required|string', Lang::$word->_PLG_YPL_SUB20),
              'video_id' => array('required|string', Lang::$word->_PLG_YPL_SUB21),
              'max_results' => array('required|numeric', Lang::$word->_PLG_YPL_SUB8),
              'pagination' => array('required|numeric', Lang::$word->_PLG_YPL_SUB7),
			  'continuous' => array('required|numeric', Lang::$word->_PLG_YPL_SUB9),
			  'show_playlist' => array('required|numeric', Lang::$word->_PLG_YPL_SUB10),
              'playlist_type' => array('required|string', Lang::$word->_PLG_YPL_SUB11),
			  'show_channel_in_playlist' => array('required|numeric', Lang::$word->_PLG_YPL_SUB12),
			  'show_channel_in_title' => array('required|numeric', Lang::$word->_PLG_YPL_SUB13),
			  'autoplay' => array('required|numeric', Lang::$word->_PLG_YPL_SUB16),
			  'force_hd' => array('required|numeric', Lang::$word->_PLG_YPL_SUB18),
			  'share_control' => array('required|numeric', Lang::$word->_PLG_YPL_SUB17),
              );

		  $filters = array(
			  'title' => 'string',
			  'controls_bg' => 'string',
			  'buttons' => 'string',
			  'buttons_hover' => 'string',
			  'buttons_active' => 'string',
			  'time_text' => 'string',
			  'bar_bg' => 'string',
			  'buffer' => 'string',
			  'video_title' => 'string',
			  'video_channel' => 'string',
			  'playlist_overlay' => 'string',
			  'playlist_title' => 'string',
			  'playlist_channel' => 'string',
			  );

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
          if (empty(Message::$msgs)) {
			  $config = array(
				  "playlist" => $safe->mode == "playlist" ? $safe->video_id : false,
				  "channel" => $safe->mode == "channel" ? $safe->video_id : false,
				  "user" => false,
				  "videos" => $safe->mode == "videos" ? $safe->video_id : false,
				  "api_key" => "YTKEY",
				  "max_results" => $safe->max_results,
				  "pagination" => $safe->pagination,
				  "continuous" => $safe->continuous,
				  "show_playlist" => $safe->show_playlist == false ? false : "auto",
				  "playlist_type" => $safe->playlist_type,
				  "continuous" => $safe->continuous,
				  "show_channel_in_playlist" => $safe->show_channel_in_playlist,
				  "show_channel_in_title" => $safe->show_channel_in_title,
				  "now_playing_text" => Lang::$word->_PLG_YPL_SUB14,
				  "load_more_text" => Lang::$word->_PLG_YPL_SUB15,
				  "autoplay" => $safe->autoplay ? true : false,
				  "force_hd" => $safe->force_hd ? true : false,
				  "share_control" => $safe->share_control,
				  "colors" => array(
					  "controls_bg" => $safe->controls_bg ? $safe->controls_bg : "rgba(0,0,0,.75)",
					  "buttons" => $safe->buttons  ? $safe->buttons : "rgba(255,255,255,.5)",
					  "buttons_hover" => $safe->buttons_hover ? $safe->buttons_hover : "rgba(255,255,255,1)",
					  "buttons_active" => $safe->buttons_active  ? $safe->buttons_active : "rgba(255,255,255,1)",
					  "time_text" => $safe->time_text ? $safe->time_text : "#FFFFFF",
					  "bar_bg" => $safe->mode ? $safe->bar_bg : "rgba(255,255,255,.5)",
					  "buffer" => "rgba(255,255,255,.25)",
					  "fill" => $safe->fill ? $safe->fill : "#FFFFFF",
					  "video_title" => $safe->video_title ? $safe->video_title : "#FFFFFF",
					  "video_channel" => $safe->video_channel ? $safe->video_channel : "#DFF76D",
					  "playlist_overlay" => $safe->playlist_overlay ? $safe->playlist_overlay : "rgba(0,0,0,.75)",
					  "playlist_title" => $safe->playlist_title ? $safe->playlist_title : "#FFFFFF",
					  "playlist_channel" => $safe->playlist_channel ? $safe->playlist_channel : "#DFF76D",
					  "scrollbar" => "#FFFFFF",
					  "scrollbar_bg" => "rgba(255,255,255,.25)",
				  ));
              $data = array(
                  'title' => $safe->title,
                  'layout' => $safe->playlist_type,
				  'config' => json_encode($config),
                  );
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_YPL_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['title'], Lang::$word->_PLG_YPL_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
			  
			  if(!Filter::$id) {
				  // Insert new mulit plugin
				  $plugin_id = "yplayer/" . Utility::randomString();
				  File::makeDirectory(FPLUGPATH . $plugin_id);
				  File::copyFile(FPLUGPATH . 'yplayer/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
				  
				  
				  $pid = Db::run()->first(Plugins::mTable, array("id"), array("plugalias" => "yplayer"));
				  foreach (App::Core()->langlist as $i => $lang) {
					  $datam['title_' . $lang->abbr] = $safe->title;
				  }
				  $datax = array(
					  'parent_id' => $pid->id,
					  'plugin_id' => $last_id,
					  'groups' => 'yplayer',
					  'icon' => 'yplayer/thumb.png',
					  'plugalias' => $plugin_id,
					  'cplugin' => 1,
					  'active' => 1,
					  );
				  Db::run()->insert(Plugins::mTable, array_merge($datam, $datax));
		      }
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Yplayer::getAllPlayers()
       * 
       * @return
       */
      public function getAllPlayers()
      {

          $row = Db::run()->select(self::mTable)->results();
          return ($row) ? $row : 0;
      }
	  
      /**
       * Yplayer::render()
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
