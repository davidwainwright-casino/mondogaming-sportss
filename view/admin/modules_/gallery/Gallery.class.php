<?php
  /**
   * Gallery Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  class Gallery
  {

      const mTable = "mod_gallery";
      const dTable = "mod_gallery_data";
      const GALDATA = 'gallery/data/';


      /**
       * Gallery::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllGalleries();
          $tpl->title = Lang::$word->_MOD_GA_TITLE;
          $tpl->template = 'admin/modules_/gallery/view/index.tpl.php';
      }

      /**
       * Gallery::Edit()
       * 
       * @param mixed $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_GA_TITLE1;
          $tpl->crumbs = ['admin', 'modules', 'gallery', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [gallery.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules_/gallery/view/index.tpl.php';
          }
      }

      /**
       * Gallery::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_GA_NEW;
		  $tpl->langlist = App::Core()->langlist;
          $tpl->template = 'admin/modules_/gallery/view/index.tpl.php';
      }

      /**
       * Gallery::Photos()
       * 
       * @param int $id
       * @return
       */
      public function Photos($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_GA_SUB4;
          $tpl->crumbs = ['admin', 'modules', 'gallery', 'photos'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [gallery.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->photos = Db::run()->select(self::dTable, null, array("parent_id" => $id), 'ORDER BY sorting')->results();
              $tpl->template = 'admin/modules_/gallery/view/index.tpl.php';
          }
      }
	  
      /**
       * Gallery::processGallery()
       * 
       * @return
       */
      public function processGallery()
      {

          $rules = array(
              'thumb_w' => array('required|numeric|min_len,2|max_len,3', Lang::$word->_MOD_GA_THUMBW),
              'thumb_h' => array('required|numeric|min_len,2|max_len,3', Lang::$word->_MOD_GA_THUMBH),
              'cols' => array('required|numeric|min_len,3|max_len,3', Lang::$word->_MOD_GA_COLS),
              'watermark' => array('required|numeric', Lang::$word->_MOD_GA_WMARK),
              'likes' => array('required|numeric', Lang::$word->_MOD_GA_LIKE),
			  'resize' => array('required|string', Lang::$word->_MOD_GA_RESIZE_THE),
              );

          foreach (App::Core()->langlist as $lang) {
              $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['slug_' . $lang->abbr] = 'string|trim';
			  $filters['description_' . $lang->abbr] = 'string';
          }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
                  $slug[$i] = empty($safe->{'slug_' . $lang->abbr}) 
				  ? Url::doSeo($safe->{'title_' . $lang->abbr}) 
				  : Url::doSeo($safe->{'slug_' . $lang->abbr});
                  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                  $datam['slug_' . $lang->abbr] = $slug[$i];
                  $datam['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
				  
				  //module
                  $datamd['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
                  $datamd['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
			  }

              $datax = array(
                  'thumb_w' => $safe->thumb_w,
                  'thumb_h' => $safe->thumb_h,
                  'cols' => $safe->cols,
                  'watermark' => $safe->watermark,
                  'likes' => $safe->likes,
				  'resize' => $safe->resize,
                  );

              if (!Filter::$id) {
                  $datax['dir'] = Utility::randomString(12);
				  File::makeDirectory(FMODPATH . self::GALDATA . $datax['dir'] . '/thumbs/');
              }
              $data = array_merge($datam, $datax);
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

              if (Filter::$id) {
                  $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_GA_UPDATE_OK);
                  Message::msgReply(Db::run()->affected(), 'success', $message);
				  Db::run()->update(Modules::mTable, $datamd, array("parent_id" => Filter::$id, "modalias" => "gallery"));
                  Logger::writeLog($message);
              } else {
                  if ($last_id) {
					  $datap = array(
						  'modalias' => "gallery",
						  'is_builder' => 1,
						  'parent_id' => $last_id,
						  'icon' => "gallery/thumb.svg",
						  'active' => 1,
						  );
					  Db::run()->insert(Modules::mTable, array_merge($datamd, $datap));
					  
                      $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->_MOD_GA_ADDED_OK);
                      $json['type'] = "success";
                      $json['title'] = Lang::$word->SUCCESS;
                      $json['message'] = $message;
                      $json['redirect'] = Url::url("/admin/modules/gallery", "photos/" . $last_id);
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
       * Gallery::FrontHome()
       * 
       * @return
       */
      public static function FrontHome()
      {
		  
		  return array("rows" => App::Gallery()->getAllGalleries());
      }
	  
      /**
       * Gallery::FrontIndex()
       * 
       * @return
       */
      public function FrontIndex()
      {

		  $core = App::Core();
          $tpl = App::View(FMODPATH . 'modules/');
          $tpl->dir = "front/themes/" . $core->theme . "/";
          $tpl->title = str_replace("[COMPANY]", $core->company, Lang::$word->META_T31);
		  $tpl->keywords = null;
		  $tpl->description = null;
		  
		  $tpl->data = Db::run()->first(Modules::mTable, array("title" . lang::$lang, "info" . lang::$lang, "keywords" . lang::$lang, "description" . lang::$lang), array("modalias" => "gallery"));
		  $tpl->core = $core;
		  $tpl->rows = $this->getAllGalleries();
		  if($tpl->data) {
			  $tpl->title = Url::formatMeta($tpl->data->{'title' . Lang::$lang});
			  $tpl->keywords = $tpl->data->{'keywords' . Lang::$lang};
			  $tpl->description = $tpl->data->{'description' . Lang::$lang};
		  }
		  $tpl->crumbs = [array(0 =>Lang::$word->HOME, 1 => ''), $tpl->data->{'title' . Lang::$lang}];
		  $tpl->menu = App::Content()->menuTree(true);
		  $tpl->plugins = App::Plugins()->getModulelugins("gallery");
		  $tpl->layout = Plugins::moduleLayout($tpl->plugins);
		  $tpl->template = 'front/themes/' . $core->theme . '/mod_index.tpl.php';
      }

      /**
       * Gallery::Render()
       * 
	   * @param int $slug
       * @return
       */
      public function Render($slug)
      {
		  $core = App::Core();
          $tpl = App::View(FMODPATH . 'modules/');
          $tpl->dir = "front/themes/" . $core->theme . "/";
          $tpl->title = str_replace("[COMPANY]", $core->company, Lang::$word->META_T31);
		  $tpl->keywords = null;
		  $tpl->description = null;
		  $tpl->menu = App::Content()->menuTree(true);
		  
          if (!$row = Db::run()->first(self::mTable, null, array("slug" . Lang::$lang => $slug))) {
			  $tpl->data = null;
			  $tpl->title = Lang::$word->META_ERROR; 
              $tpl->template = 'front/themes/' . $core->theme . '/404.tpl.php';
              DEBUG ? Debug::AddMessage("errors", '<i>ERROR</i>', "Invalid page detected [Gallery.class.php, ln.:" . __line__ . "] slug [" . $slug ."]", "session") : Lang::$word->META_ERROR;
          } else {
			  $tpl->data = Db::run()->first(Modules::mTable, array("title" . lang::$lang, "info" . lang::$lang, "keywords" . lang::$lang, "description" . lang::$lang), array("modalias" => "gallery"));
			  $tpl->title = $tpl->title = Url::formatMeta($row->{'title' . Lang::$lang}, $core->modname['gallery']);
			  $tpl->keywords = $tpl->data->{'keywords' . Lang::$lang};
			  $tpl->description = $tpl->data->{'description' . Lang::$lang};
			  $tpl->row = $row;
			  $tpl->photos = Db::run()->select(self::dTable, null, array("parent_id" => $row->id), 'ORDER BY sorting')->results();
			  $tpl->core = $core;
			  
			  $tpl->crumbs = [array(0 =>Lang::$word->HOME, 1 => ''), array(0 => $tpl->data->{'title' . Lang::$lang}, 1 => $core->modname['gallery']), $row->{'title' . Lang::$lang}];
			  $tpl->plugins = App::Plugins()->getModulelugins("gallery");
			  $tpl->layout = Plugins::moduleLayout($tpl->plugins);
			  $tpl->template = 'front/themes/' . $core->theme . '/mod_index.tpl.php';
          }
      }
	  
      /**
       * Gallery::processPhoto()
       * 
       * @return
       */
      public function processPhoto()
      {

          foreach (App::Core()->langlist as $lang) {
              $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr .'"></span>');
			  $filters['description_' . $lang->abbr] = 'string';
          }

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $data['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $data['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
              }

              Db::run()->update(self::dTable, $data, array("id" => Filter::$id));
			  $html = '<div class="meta"> <span class="yoyo bold large white text">' . $data['title' . Lang::$lang] . '</span><p>' . $data['description' . Lang::$lang] . '</p></div>';
              Message::msgModalReply(Db::run()->affected(), 'success', Message::formatSuccessMessage($data['title' . Lang::$lang], Lang::$word->_MOD_GA_PHOTO_OK), $html);
          } else {
              Message::msgSingleStatus();
          }
      }
	  
      /**
       * Gallery::resizeImages()
       * 
       * @return
       */
      public function resizeImages()
      {
          $rules = array(
              'resize' => array('required|string', Lang::$word->_MOD_GA_RESIZE_THE),
              'thumb_w' => array('required|numeric|min_len,2|max_len,3', Lang::$word->_MOD_GA_THUMBW),
              'thumb_h' => array('required|numeric|min_len,2|max_len,3', Lang::$word->_MOD_GA_THUMBH),
              'dir' => array('required|string', Lang::$word->_MOD_GA_DIR),
              );

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);

          if (empty(Message::$msgs)) {
              $images = File::findFiles(FMODPATH . Gallery::GALDATA . $safe->dir . '/', array('fileTypes' => array('jpg', 'png'), 'level'=> 0, 'returnType' => 'fileOnly'));
              if ($images) {
                  switch ($safe->resize) {
                      case "thumbnail":
                          foreach ($images as $row) {
							  $img = new Image(FMODPATH . self::GALDATA . $safe->dir . '/' . $row);
							  $img->thumbnail($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . self::GALDATA . $safe->dir . '/thumbs/' . $row);
                          }
                          break;
                      case "resize":
                          foreach ($images as $row) {
                              $img = new Image(FMODPATH . self::GALDATA . $safe->dir . '/' . $row);
                              $img->resize($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . self::GALDATA . $safe->dir . '/thumbs/' . $row);
                          }
                          break;
                      case "bestFit":
                          foreach ($images as $row) {
                              $img = new Image(FMODPATH . self::GALDATA . $safe->dir . '/' . $row);
                              $img->bestFit($safe->thumb_w, $safe->thumb_h)->save(FMODPATH . self::GALDATA . $safe->dir . '/thumbs/' . $row);
                          }
                          break;
                      case "fitToHeight":
                          foreach ($images as $row) {
                              $img = new Image(FMODPATH . self::GALDATA . $safe->dir . '/' . $row);
                              $img->fitToHeight($safe->thumb_h)->save(FMODPATH . self::GALDATA . $safe->dir . '/thumbs/' . $row);
                          }
                          break;
                      case "fitToWidth":
                          foreach ($images as $row) {
                              $img = new Image(FMODPATH . self::GALDATA . $safe->dir . '/' . $row);
                              $img->fitToWidth($safe->thumb_w)->save(FMODPATH . self::GALDATA . $safe->dir . '/thumbs/' . $row);
                          }
                          break;
                  }
                  $json['type'] = "success";
                  $json['title'] = Lang::$word->SUCCESS;
                  $json['message'] = str_replace("[NUMBER]", '<b>' . count($images) . '</b>', Lang::$word->_MOD_GA_RESIZE_OK);
              } else {
                  $json['type'] = "error";
                  $json['title'] = Lang::$word->ERROR;
                  $json['message'] = Lang::$word->FU_ERROR16;
              }
              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }


      /**
       * Gallery::getGalleryList()
       * 
       * @return
       */
      public static function getGalleryList()
      {

          $row = Db::run()->select(self::mTable, array("id", "title" . Lang::$lang), 'ORDER BY sorting')->results();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Gallery::getAllGalleries()
       * 
       * @return
       */
      public function getAllGalleries()
      {
          $sql = "
		  SELECT 
			m.*,
			COUNT(d.parent_id) AS pics,
			SUM(IFNULL(d.likes, 0)) AS likes
		  FROM
			`" . self::mTable . "` AS m 
			LEFT JOIN `" . self::dTable . "` AS d 
			  ON m.id = d.parent_id 
		  GROUP BY m.id 
		  ORDER BY m.sorting;";
          $row = Db::run()->pdoQuery($sql)->results();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Gallery::getGallery()
       * 
	   * @param int $id
       * @return
       */
      public static function getGallery($id)
      {
		  $row = Db::run()->first(self::mTable, array("id", "title" . Lang::$lang . " as title", "description" . Lang::$lang . " as description", "cols", "dir", "watermark", "likes"), array("id" => $id));

          return ($row) ? $row : 0;
      }
	  
      /**
       * Gallery::renderSingle()
       * 
	   * @param int $parent_id
       * @return
       */
      public static function renderSingle($parent_id)
      {
		  $row = Db::run()->select(self::dTable, null, array("parent_id" => $parent_id), 'ORDER BY sorting')->results();

          return ($row) ? $row : 0;
      }
  }