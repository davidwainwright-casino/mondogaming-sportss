<?php
  /**
   * Language
   * 
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');


  final class Lang
  {
      const langdir = "lang/";
      public static $language;
      public static $word = array();
      public static $lang;

      const lTable = "language";

      /**
       * Lang::__construct()
       * 
       * @return
       */
      public function __construct()
      {
      }


      /**
       * Lang::Run()
       * 
       * @return
       */
      public static function Run()
      {
          self::get();
      }

      /**
       * Lang::Index()
       * 
       * @return
       */
      public function Index()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->META_T21;
          $tpl->data = Db::run()->select(self::lTable, null, null, "ORDER BY home DESC")->results();
          $tpl->template = 'admin/languages.tpl.php';
      }

      /**
       * Lang::Translate()
       * 
       * @param mixed $id
       * @return
       */
      public function Translate($id)
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->LG_TITLE1;
          $tpl->crumbs = ['admin', 'languages', Lang::$word->LG_TITLE1];

          if (!$row = Db::run()->first(self::lTable, null, array('id' => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [language.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->row = $row;
              $tpl->data = simplexml_load_file(BASEPATH . Lang::langdir . $row->abbr . "/lang.xml");
              $tpl->sections = Lang::getSections();
              $tpl->pluglang = glob("" . BASEPATH . Lang::langdir . $row->abbr . "/plugins/" . "*.lang.plugin.xml");
              $tpl->modlang = glob("" . BASEPATH . Lang::langdir . $row->abbr . "/modules/" . "*.lang.module.xml");
              $tpl->template = 'admin/languages.tpl.php';
          }

      }

      /**
       * Lang::Edit()
       * 
       * @param mixed $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->LG_TITLE1;
          $tpl->crumbs = ['admin', 'languages', 'edit'];

          if (!$row = Db::run()->first(self::lTable, null, array("id =" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [lang.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->template = 'admin/languages.tpl.php';
          }
      }


      /**
       * Lang::Save()
       * 
       * @return
       */
      public function Save()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->LG_SUB5;
          $tpl->template = 'admin/languages.tpl.php';
      }

      /**
       * Lang::processLanguage()
       * 
       * @return
       */
      public function processLanguage()
      {
          $rules = array(
              'name' => array('required|string', Lang::$word->NAME),
              'abbr' => array('required|string|min_len,2|max_len,2', Lang::$word->LG_ABBR),
              );

          $filters = array(
              'author' => 'string',
              'color' => 'string',
              );

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
          $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              $data = array(
                  'name' => $safe->name,
                  'abbr' => strtolower($safe->abbr),
                  'color' => $safe->color,
                  'author' => $safe->author,
                  );

              (Filter::$id) ? Db::run()->update(self::lTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::lTable, $data)->getLastInsertId();

              $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['name'], Lang::$word->LG_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['name'], Lang::$word->LG_ADDED_OK);

              Message::msgReply(Db::run()->affected(), 'success', $message);
              Logger::writeLog($message);
			  

              //run language files
              if (!Filter::$id) {
				  Core::buildLangList();
				  
                  $flag_id = $data['abbr'];

                  // custom fields
                  $sql = "
				  ALTER TABLE `" . Content::cfTable . "` 
					ADD COLUMN title_$flag_id VARCHAR (60) NOT NULL AFTER title_en,
					ADD COLUMN tooltip_$flag_id VARCHAR (100) DEFAULT NULL AFTER tooltip_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Content::cfTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::cfTable . "` SET `tooltip_" . $flag_id . "`=`tooltip_en`");

                  // email templates
                  $sql = "
				  ALTER TABLE `" . Content::eTable . "` 
					ADD COLUMN name_$flag_id VARCHAR (100) NOT NULL AFTER name_en,
					ADD COLUMN subject_$flag_id VARCHAR (150) NOT NULL AFTER subject_en,
					ADD COLUMN help_$flag_id tinytext AFTER help_en,
					ADD COLUMN body_$flag_id text NOT NULL AFTER body_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Content::eTable . "` SET `name_" . $flag_id . "`=`name_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::eTable . "` SET `subject_" . $flag_id . "`=`subject_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::eTable . "` SET `help_" . $flag_id . "`=`help_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::eTable . "` SET `body_" . $flag_id . "`=`body_en`");

                  // layout
                  $sql = "
				  ALTER TABLE `" . Content::lTable . "` 
					ADD COLUMN page_slug_$flag_id VARCHAR (150) DEFAULT NULL AFTER page_slug_en;";
                  Db::run()->pdoQuery($sql);
				  
				  Db::run()->pdoQuery("UPDATE `" . Content::lTable . "` SET `page_slug_" . $flag_id . "`=`page_slug_en`");

                  // memberships
                  $sql = "
				  ALTER TABLE `" . Membership::mTable . "` 
					ADD COLUMN title_$flag_id VARCHAR (80) NOT NULL AFTER title_en,
					ADD COLUMN description_$flag_id VARCHAR (150) DEFAULT NULL AFTER description_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Membership::mTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Membership::mTable . "` SET `description_" . $flag_id . "`=`description_en`");
				  
                  // menus
                  $sql = "
				  ALTER TABLE `" . Content::mTable . "` 
					ADD COLUMN page_slug_$flag_id VARCHAR (100) DEFAULT NULL AFTER page_slug_en,
					ADD COLUMN name_$flag_id VARCHAR (100) NOT NULL AFTER name_en,
					ADD COLUMN caption_$flag_id VARCHAR (100) DEFAULT NULL AFTER caption_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Content::mTable . "` SET `page_slug_" . $flag_id . "`=`page_slug_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::mTable . "` SET `name_" . $flag_id . "`=`name_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::mTable . "` SET `caption_" . $flag_id . "`=`caption_en`");
				  
                  // mod_adblock
                  $sql = "
				  ALTER TABLE `mod_adblock` 
					ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en;";
                  Db::run()->pdoQuery($sql);
				  
				  Db::run()->pdoQuery("UPDATE `mod_adblock` SET `title_" . $flag_id . "`=`title_en`");

                  // mod_events
                  $sql = "
				  ALTER TABLE `mod_events` 
					ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
					ADD COLUMN venue_$flag_id VARCHAR (100) DEFAULT NULL AFTER venue_en,
					ADD COLUMN body_$flag_id TEXT AFTER body_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `mod_events` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `mod_events` SET `venue_" . $flag_id . "`=`venue_en`");
				  Db::run()->pdoQuery("UPDATE `mod_events` SET `body_" . $flag_id . "`=`body_en`");
				  
                  // mod_gallery
                  $sql = "
				  ALTER TABLE `mod_gallery` 
					ADD COLUMN title_$flag_id VARCHAR (60) NOT NULL AFTER title_en,
					ADD COLUMN slug_$flag_id VARCHAR (100) NOT NULL AFTER slug_en,
					ADD COLUMN description_$flag_id VARCHAR (100) DEFAULT NULL AFTER description_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `mod_gallery` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `mod_gallery` SET `slug_" . $flag_id . "`=`slug_en`");
				  Db::run()->pdoQuery("UPDATE `mod_gallery` SET `description_" . $flag_id . "`=`description_en`");

                  // mod_gallery_data
                  $sql = "
				  ALTER TABLE `mod_gallery_data` 
					ADD COLUMN title_$flag_id VARCHAR (80) NOT NULL AFTER title_en,
					ADD COLUMN description_$flag_id VARCHAR (200) DEFAULT NULL AFTER description_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `mod_gallery_data` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `mod_gallery_data` SET `description_" . $flag_id . "`=`description_en`");
				  
                  // mod_timeline_data
                  $sql = "
				  ALTER TABLE `mod_timeline_data` 
					ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
					ADD COLUMN body_$flag_id TEXT AFTER body_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `mod_timeline_data` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `mod_timeline_data` SET `body_" . $flag_id . "`=`body_en`");
				  
                  // modules
                  $sql = "
				  ALTER TABLE `" . Modules::mTable . "` 
					ADD COLUMN title_$flag_id VARCHAR (120) NOT NULL AFTER title_en,
					ADD COLUMN info_$flag_id VARCHAR (200) DEFAULT NULL AFTER info_en,
					ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
					ADD COLUMN description_$flag_id TEXT AFTER description_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Modules::mTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Modules::mTable . "` SET `info_" . $flag_id . "`=`info_en`");
				  Db::run()->pdoQuery("UPDATE `" . Modules::mTable . "` SET `keywords_" . $flag_id . "`=`keywords_en`");
				  Db::run()->pdoQuery("UPDATE `" . Modules::mTable . "` SET `description_" . $flag_id . "`=`description_en`");
				  
                  // pages
                  $sql = "
				  ALTER TABLE `" . Content::pTable . "` 
					ADD COLUMN title_$flag_id VARCHAR (200) NOT NULL AFTER title_en,
					ADD COLUMN slug_$flag_id VARCHAR (200) DEFAULT NULL AFTER slug_en,
					ADD COLUMN caption_$flag_id VARCHAR(150) DEFAULT NULL AFTER caption_en,
					ADD COLUMN custom_bg_$flag_id VARCHAR(100) DEFAULT NULL AFTER custom_bg_en,
					ADD COLUMN body_$flag_id TEXT AFTER body_en,
					ADD COLUMN keywords_$flag_id VARCHAR(200) DEFAULT NULL AFTER keywords_en,
					ADD COLUMN description_$flag_id TEXT AFTER description_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `slug_" . $flag_id . "`=`slug_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `caption_" . $flag_id . "`=`caption_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `custom_bg_" . $flag_id . "`=`custom_bg_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `body_" . $flag_id . "`=`body_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `keywords_" . $flag_id . "`=`keywords_en`");
				  Db::run()->pdoQuery("UPDATE `" . Content::pTable . "` SET `description_" . $flag_id . "`=`description_en`");
				  
                  // plug_carousel
                  $sql = "
				  ALTER TABLE `plug_carousel` 
					ADD COLUMN title_$flag_id VARCHAR (100) NOT NULL AFTER title_en,
					ADD COLUMN body_$flag_id TEXT AFTER body_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `plug_carousel` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `plug_carousel` SET `body_" . $flag_id . "`=`body_en`");
				  
                  // plugins
                  $sql = "
				  ALTER TABLE `" . Plugins::mTable . "` 
					ADD COLUMN title_$flag_id VARCHAR (120) NOT NULL AFTER title_en,
					ADD COLUMN body_$flag_id TEXT AFTER body_en,
					ADD COLUMN info_$flag_id VARCHAR (150) DEFAULT NULL AFTER info_en;";
                  Db::run()->pdoQuery($sql);

				  Db::run()->pdoQuery("UPDATE `" . Plugins::mTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Plugins::mTable . "` SET `title_" . $flag_id . "`=`title_en`");
				  Db::run()->pdoQuery("UPDATE `" . Plugins::mTable . "` SET `info_" . $flag_id . "`=`info_en`");
				  
                  //modules
                  if ($modules = File::scanFiles(AMODPATH, "*_addLanguage.lang.php")) {
                      foreach ($modules as $mdata) {
                          include_once ($mdata);
                      }
                  }
              }
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Lang::deleteLanguage()
       * 
       * @param mixed $abbr
       * @return
       */
      public static function deleteLanguage($abbr)
      {

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Content::cfTable . "` 
			DROP COLUMN title_$abbr,
			DROP COLUMN tooltip_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Content::eTable . "` 
			DROP COLUMN name_$abbr,
			DROP COLUMN subject_$abbr,
			DROP COLUMN body_$abbr,
			DROP COLUMN help_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Content::lTable . "` 
			DROP COLUMN page_slug_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Membership::mTable . "` 
			DROP COLUMN title_$abbr,
			DROP COLUMN description_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Content::mTable . "` 
			DROP COLUMN page_slug_$abbr,
			DROP COLUMN name_$abbr,
			DROP COLUMN caption_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `mod_adblock` 
			DROP COLUMN title_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `mod_events` 
			DROP COLUMN title_$abbr,
			DROP COLUMN venue_$abbr,
			DROP COLUMN body_$abbr;");
			
          Db::run()->pdoQuery("
		  ALTER TABLE `mod_gallery` 
			DROP COLUMN title_$abbr,
			DROP COLUMN slug_$abbr,
			DROP COLUMN description_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `mod_gallery_data` 
			DROP COLUMN title_$abbr,
			DROP COLUMN description_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `mod_timeline_data` 
			DROP COLUMN title_$abbr,
			DROP COLUMN body_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Modules::mTable . "`
			DROP COLUMN title_$abbr,
			DROP COLUMN info_$abbr,
			DROP COLUMN keywords_$abbr,
			DROP COLUMN description_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Content::pTable . "` 
			DROP COLUMN title_$abbr,
			DROP COLUMN slug_$abbr,
			DROP COLUMN caption_$abbr,
			DROP COLUMN custom_bg_$abbr,
			DROP COLUMN body_$abbr,
			DROP COLUMN keywords_$abbr,
			DROP COLUMN description_$abbr;");

          Db::run()->pdoQuery("
		  ALTER TABLE `plug_carousel`
			DROP COLUMN title_$abbr,
			DROP COLUMN body_$abbr;"
			);

          Db::run()->pdoQuery("
		  ALTER TABLE `" . Plugins::mTable . "`
			DROP COLUMN title_$abbr,
			DROP COLUMN body_$abbr,
			DROP COLUMN info_$abbr;");

          //modules
          if ($modules = File::scanFiles(AMODPATH, "*_delLanguage.lang.php")) {
              foreach ($modules as $mdata) {
                  include_once ($mdata);
              }
          }
		  return true;
      }

      /**
       * Lang::get()
       * 
       * @return
       */
      private static function get()
      {
          $core = App::Core();
          if (isset($_COOKIE['LANG_CMSPRO'])) {
              Core::$language = $_COOKIE['LANG_CMSPRO'];

              $sel_lang = Validator::sanitize($_COOKIE['LANG_CMSPRO'], "alpha", 2);
              $vlang = self::fetchLanguage($sel_lang);
              if (in_array($sel_lang, $vlang)) {
                  Core::$language = $sel_lang;
              } else {
                  Core::$language = $core->lang;
              }
              if (file_exists(BASEPATH . self::langdir . Core::$language . "/lang.xml")) {
                  self::$word = self::set(BASEPATH . self::langdir . Core::$language . "/lang.xml", Core::$language);
              } else {
                  self::$word = self::set(BASEPATH . self::langdir . $core->lang . "/lang.xml", $core->lang);
              }
          } else {
              Core::$language = $core->lang;
              self::$word = self::set(BASEPATH . self::langdir . $core->lang . "/lang.xml", $core->lang);

          }
          self::$lang = "_" . Core::$language;
          return self::$word;
      }

      /**
       * Lang::set()
       * 
       * @param mixed $lang
       * @param mixed $abbr
       * @return
       */
      public static function set($lang, $abbr)
      {
          $xmlel = simplexml_load_file($lang);
          $countplugs = glob("" . BASEPATH . self::langdir . "$abbr/plugins/" . "*.lang.plugin.xml");
          $totalplugs = count($countplugs);
          $countmods = glob("" . BASEPATH . self::langdir . "$abbr/modules/" . "*.lang.module.xml");
          $totalmods = count($countmods);
          $data = new stdClass();
          foreach ($xmlel as $pkey) {
              $key = (string )$pkey['data'];
              $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
          }
          if ($totalplugs) {
              foreach ($countplugs as $val) {
                  $pxml = simplexml_load_file($val);
                  foreach ($pxml as $pkey) {
                      $key = (string )$pkey['data'];
                      $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
                  }
              }
          }

          if ($totalmods) {
              foreach ($countmods as $val1) {
                  $pxml = simplexml_load_file($val1);
                  foreach ($pxml as $pkey) {
                      $key = (string )$pkey['data'];
                      $data->$key = (string )str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
                  }
              }
          }

          return $data;
      }

      /**
       * Lang::getSections()
       * 
       * @return
       */
      public static function getSections()
      {
          $xmlel = simplexml_load_file(BASEPATH . self::langdir . Core::$language . "/lang.xml");
          $query = '/language/phrase[not(@section = preceding-sibling::phrase/@section)]/@section';

          $sections = array();
          foreach ($xmlel->xpath($query) as $text) {
              $sections[] = (string )$text;
          }
          asort($sections);
          return $sections;
      }

      /**
       * Lang::fetchLanguage()
       * 
       * @return
       */
      public static function fetchLanguage()
      {
          $lang_array = '';
          $directory = BASEPATH . self::langdir;
          if (!is_dir($directory)) {
              return false;
          } else {
              $lang_array = glob($directory . "*", GLOB_ONLYDIR);
              $lang_array = str_replace($directory, "", $lang_array);

          }

          return $lang_array;
      }
	  
      /**
       * Lang::getLanguages()
       * 
       * @return
       */
      public static function getLanguages()
      {

          $row = Db::run()->select(self::lTable, null, null, "ORDER BY home DESC")->results();

          return ($row) ? $row : 0;
      }
  }