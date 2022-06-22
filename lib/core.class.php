<?php
  /**
   * Core Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');

  class Core
  {

      const sTable = "settings";
	  const txTable = "trash";
	  const gTable = "gateways";
	  
      public static $language;

	  public $_url;
      public $_urlParts;

      /**
       * Core::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          $this->getSettings();
          ($this->dtz) ? ini_set('date.timezone', $this->dtz) : date_default_timezone_set('UTC');
		  Locale::setDefault($this->locale);
      }

      /**
       * Core::getSettings()
       * 
       * @return
       */
      private function getSettings()
      {
          $row = Db::run()->select(self::sTable, null, array('id' => 1))->result();

          $this->site_name = $row->site_name;
		  $this->company = $row->company;
          $this->site_dir = $row->site_dir;
          $this->site_email = $row->site_email;
          $this->logo = $row->logo;
		  $this->plogo = $row->plogo;
          $this->short_date = $row->short_date;
          $this->long_date = $row->long_date;
          $this->time_format = $row->time_format;
          $this->dtz = $row->dtz;
          $this->locale = $row->locale;
          $this->lang = $row->lang;
          $this->weekstart = $row->weekstart;
          $this->theme = $row->theme;
          $this->perpage = $row->perpage;
		  
		  $this->showlang = $row->showlang;
		  $this->showlogin = $row->showlogin;
		  $this->showcrumbs = $row->showcrumbs;
		  $this->showsearch = $row->showsearch;
		  
		  $this->ploader = $row->ploader;

          $this->offline = $row->offline;
          $this->offline_msg = $row->offline_msg;
          $this->offline_d = $row->offline_d;
          $this->offline_t = $row->offline_t;
		  $this->offline_info = $row->offline_info;
          $this->eucookie = $row->eucookie;
          $this->backup = $row->backup;
          $this->currency = $row->currency;
          $this->file_ext = $row->file_ext;
          $this->file_size = $row->file_size;
		  
		  $this->avatar_w = $row->avatar_w;
		  $this->avatar_h = $row->avatar_h;
		  $this->thumb_h = $row->thumb_h;
		  $this->thumb_w = $row->thumb_w;
		  $this->img_w = $row->img_w;
		  $this->img_h = $row->img_h;
		  
		  $this->enable_tax = $row->enable_tax;
		  
		  $this->reg_verify = $row->reg_verify;
		  $this->auto_verify = $row->auto_verify;
		  $this->notify_admin = $row->notify_admin;

          $this->mailer = $row->mailer;
          $this->smtp_host = $row->smtp_host;
          $this->smtp_user = $row->smtp_user;
          $this->smtp_pass = $row->smtp_pass;
          $this->smtp_port = $row->smtp_port;
          $this->sendmail = $row->sendmail;
          $this->is_ssl = $row->is_ssl;
		  
		  $this->slugs = json_decode($row->url_slugs);
		  $this->system_slugs = json_decode($row->system_slugs);
		  
		  $this->moddir = (array)$this->slugs->moddir;
		  $this->modname = (array)$this->slugs->module;
		  $this->pageslug = $this->slugs->pagedata->page;
		  
		  $this->langlist = json_decode($row->lang_list);
		  $this->social = json_decode($row->social_media);
		  
		  $this->ytapi = $row->ytapi;
		  $this->mapapi = $row->mapapi;
		  $this->analytics = $row->analytics;

		  $this->flood = $row->flood;
		  $this->attempt = $row->attempt;
		  $this->logging = $row->logging;
		  
		  $this->inv_info = $row->inv_info;
		  $this->inv_note = $row->inv_note;
		  
          $this->yoyov = $row->yoyov;
          $this->yoyon = $row->yoyon;
		  $this->noticef = $row->noticef;

      }

      /**
       * Core::processConfig()
       * 
       * @return
       */
      public function processConfig()
      {

		  $rules = array(
			  'site_name' => array('required|string|min_len,2|max_len,80', Lang::$word->CG_SITENAME),
			  'company' => array('required|string|min_len,2|max_len,80', Lang::$word->CG_COMPANY),
			  'site_email' => array('required|email', Lang::$word->CG_WEBEMAIL),
			  'theme' => array('required|string', Lang::$word->CG_THEME),
			  'perpage' => array('required|numeric', Lang::$word->CG_PERPAGE),
			  'thumb_w' => array('required|numeric', Lang::$word->CG_TH_WH),
			  'thumb_h' => array('required|numeric', Lang::$word->CG_TH_WH),
			  'img_w' => array('required|numeric', Lang::$word->CG_IM_WH),
			  'img_h' => array('required|numeric', Lang::$word->CG_IM_WH),
			  'avatar_w' => array('required|numeric', Lang::$word->CG_AV_WH),
			  'avatar_h' => array('required|numeric', Lang::$word->CG_AV_WH),
			  'long_date' => array('required|string', Lang::$word->CG_LONGDATE),
			  'short_date' => array('required|string', Lang::$word->CG_SHORTDATE),
			  'time_format' => array('required|string', Lang::$word->CG_TIMEFORMAT),
			  'dtz' => array('required|string', Lang::$word->CG_DTZ),
			  'locale' => array('required|string', Lang::$word->CG_LOCALES),
			  'weekstart' => array('required|numeric', Lang::$word->CG_WEEKSTART),
			  'lang' => array('required|string|min_len,2|max_len,2', Lang::$word->CG_LANG),
			  'ploader' => array('required|numeric', Lang::$word->CG_PLOADER),
			  'eucookie' => array('required|numeric', Lang::$word->CG_EUCOOKIE),
			  'offline' => array('required|numeric', Lang::$word->CG_OFFLINE_M),
			  //'offline_d_submit' => array('required|string', Lang::$word->CG_OFFLINE_DT),
			  //'offline_t_submit' => array('required|string', Lang::$word->CG_OFFLINE_DT),
			  'showlang' => array('required|numeric', Lang::$word->CG_LANG_SHOW),
			  'showlogin' => array('required|numeric', Lang::$word->CG_LOGIN_SHOW),
			  'showsearch' => array('required|numeric', Lang::$word->CG_SEARCH_SHOW),
			  'showcrumbs' => array('required|numeric', Lang::$word->CG_CRUMBS_SHOW),
			  'currency' => array('required|string|min_len,3|max_len,6', Lang::$word->CG_CURRENCY),
			  'enable_tax' => array('required|numeric', Lang::$word->CG_ETAX),
			  'file_size' => array('required|numeric|min_len,1|max_len,3', Lang::$word->CG_FILESIZE),
			  'file_ext' => array('required|string', Lang::$word->CG_FILETYPE),
			  'reg_verify' => array('required|numeric', Lang::$word->CG_REGVERIFY),
			  'auto_verify' => array('required|numeric', Lang::$word->CG_AUTOVERIFY),
			  'notify_admin' => array('required|numeric', Lang::$word->CG_NOTIFY_ADMIN),
			  'flood' => array('required|numeric|min_len,1|max_len,3', Lang::$word->CG_LOGIN_TIME),
			  'attempt' => array('required|numeric|min_len,1|max_len,1', Lang::$word->CG_LOGIN_ATTEMPT),
			  'logging' => array('required|numeric', Lang::$word->CG_LOG_ON),
			  'mailer' => array('required|string|min_len,3|max_len,5', Lang::$word->CG_MAILER),
			  'is_ssl' => array('required|numeric', Lang::$word->CG_SMTP_SSL),
			  'ytapi' => array('required|string', Lang::$word->CG_YTKEY),
			  'mapapi' => array('required|string', Lang::$word->CG_GMAPKEY),
			  );
	
		  $filters = array(
		      'site_dir' => 'string',
			  'twitter' => 'string',
			  'facebook' => 'string',
			  'offline_d_submit' => 'string',
			  'offline_t_submit' => 'string',
			  'inv_info' => 'basic_tags',
			  'inv_note' => 'basic_tags',
			  'offline_info' => 'basic_tags',
			  'offline_msg' => 'basic_tags',
			  'analytics' => 'basic_tags',
			  );

  		switch ($_POST['mailer']) {
  			case "SMTP":
  				$rules['smtp_host'] = ['required|string', Lang::$word->CG_SMTP_HOST];
				$rules['smtp_user'] = ['required|string', Lang::$word->CG_SMTP_USER];
				$rules['smtp_pass'] = ['required|string', Lang::$word->CG_SMTP_PASS];
				$rules['smtp_port'] = ['required|numeric', Lang::$word->CG_SMTP_PORT];
  				break;

  			case "SMAIL":
  				$rules['sendmail'] = ['required|string', Lang::$word->CG_SMAILPATH];
  				break;
  		}
		
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

		  if (!empty($_FILES['logo']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(3145728, "png,jpg,svg");
			  $upl->process("logo", UPLOADS . "/", false, false, false);
		  }

		  if (!empty($_FILES['plogo']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(3145728, "png,jpg");
			  $upl->process("plogo", UPLOADS . "/", false, false, false);
		  }
		  
		  if (empty(Message::$msgs)) {
			  $smedia['facebook'] = $safe->facebook;
			  $smedia['twitter'] = $safe->twitter;
			  
			  $data = array(
				  'site_name' => $safe->site_name,
				  'company' => $safe->company,
				  'site_email' => $safe->site_email,
				  'site_dir' => $safe->site_dir,
				  'theme' => $safe->theme,
				  'perpage' => $safe->perpage,
				  'thumb_w' => $safe->thumb_w,
				  'thumb_h' => $safe->thumb_h,
				  'img_w' => $safe->img_w,
				  'img_h' => $safe->img_h,
				  'avatar_w' => $safe->avatar_w,
				  'avatar_h' => $safe->avatar_h,
				  'long_date' => $safe->long_date,
				  'short_date' => $safe->short_date,
				  'time_format' => $safe->time_format,
				  'weekstart' => $safe->weekstart,
				  'lang' => $safe->lang,
				  'dtz' => $safe->dtz,
				  'locale' => $safe->locale,
				  'ploader' => $safe->ploader,
				  'eucookie' => $safe->eucookie,
				  'offline' => $safe->offline,
				  'offline_msg' => $safe->offline_msg,
				  'offline_d' => Db::toDate($safe->offline_d_submit),
				  'offline_t' => $safe->offline_t_submit,
				  'offline_info' => $safe->offline_info,
				  'showlang' => $safe->showlang,
				  'showlogin' => $safe->showlogin,
				  'showsearch' => $safe->showsearch,
				  'showcrumbs' => $safe->showcrumbs,
				  'currency' => $safe->currency,
				  'enable_tax' => $safe->enable_tax,
				  'file_size' => ($safe->file_size * pow(1024,2)),
				  'file_ext' => $safe->file_ext,
				  'reg_verify' => $safe->reg_verify,
				  'auto_verify' => $safe->auto_verify,
				  'notify_admin' => $safe->notify_admin,
				  'flood' => ($safe->flood * 60),
				  'attempt' => $safe->attempt,
				  'logging' => $safe->logging,
				  'analytics' => $safe->analytics,
				  'mailer' => $safe->mailer,
				  'sendmail' => $safe->sendmail,
				  'smtp_host' => $safe->smtp_host,
				  'smtp_user' => $safe->smtp_user,
				  'smtp_pass' => $safe->smtp_pass,
				  'smtp_port' => $safe->smtp_port,
				  'is_ssl' => $safe->is_ssl,
				  'inv_info' => $safe->inv_info,
				  'inv_note' => $safe->inv_note,
				  'social_media' => json_encode($smedia),
				  'ytapi' => $safe->ytapi,
				  'mapapi' => $safe->mapapi,
				  );

			  if (!empty($_FILES['logo']['name'])) {
				  $data['logo'] = $upl->fileInfo['fname'];
			  }
			  
			  if (!empty($_FILES['plogo']['name'])) {
				  $data['plogo'] = $upl->fileInfo['fname'];
			  }
			  
			  if (Validator::post('dellogo')) {
				  $data['logo'] = "NULL";
			  }
			  if (Validator::post('dellogop')) {
				  $data['plogo'] = "NULL";
			  }
			  
			  Db::run()->update(self::sTable, $data, array('id' => 1));
			  Message::msgReply(Db::run()->affected(), 'success', Lang::$word->CG_UPDATED);
		  } else {
			  Message::msgSingleStatus();
		  }
      }

      /**
       * Core::processSlugs()
       *
       * @return
       */
      public static function processSlugs()
      {
		  $rules = array(
			  'digishop' => array('required|alpha|min_len,3|max_len,60', "digishop"),
			  'blog' => array('required|string|alpha|min_len,3|max_len,60', "blog"),
			  'portfolio' => array('required|alpha|min_len,3|max_len,60', "portfolio"),
			  'gallery' => array('required|alpha|min_len,3|max_len,60', "gallery"),
			  'page' => array('required|alpha|min_len,3|max_len,60', "page"),
			  );

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  
		  if (empty(Message::$msgs)) {
			  $array = array (
				'moddir' => 
				  array (
					$safe->digishop => 'digishop',
					$safe->blog => 'blog',
					$safe->portfolio => 'portfolio',
					$safe->gallery => 'gallery',
				  ),
				'pagedata' => 
					array (
					  'page' => $safe->page,
					),
				'module' => 
					array (
					  'digishop' => $safe->digishop,
					  'digishop-cat' => 'category',
					  'digishop-checkout' => 'checkout',
					  'blog' => $safe->blog,
					  'blog-cat' => 'category',
					  'blog-search' => 'search',
					  'blog-archive' => 'archive',
					  'blog-author' => 'author',
					  'blog-tag' => 'tag',
					  'portfolio' => $safe->portfolio,
					  'portfolio-cat' => 'category',
					  'gallery' => $safe->gallery,
					  'gallery-album' => 'album',
					),
			  );
				
			  $data['url_slugs'] = json_encode($array);

			  Db::run()->update(self::sTable, $data, array("id" => 1)); 
			  Message::msgReply(Db::run()->affected(), 'success', Lang::$word->UTL_SLUGS_OK);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
	  
      /**
       * Core::buildLangList()
       *
       * @return
       */
      public static function buildLangList()
      {
          $result = Db::run()->select(Lang::lTable)->results('json');
		  Db::run()->update(Core::sTable, array("lang_list" => $result), array("id" => 1));
      }
	  
      /**
       * Core::restoreFromTrash()
       *
       * @return
       */
      public static function restoreFromTrash($array, $table)
      {
          if ($array) {
              $mapped = array_map(function($k) {
				  return "`".$k."` = ?";
				  },array_keys((array)$array
				  ));
              $stmt = Db::run()->prepare("INSERT INTO `" . $table . "` SET ".implode(", ",$mapped));
              $stmt->execute(array_values((array)$array));
			  
              $json['type'] = "success";
              print json_encode($json);
          }
      }

      /**
       * Core::install()
       *
       * @return
       */
      public function install()
      {
		  File::makeDirectory(BASEPATH . 'temp_update/');
		  File::deleteRecrusive(BASEPATH . 'temp_update/');

		  if (empty($_FILES['installer']['name'])) {
			  Message::$msgs['installer'] = Lang::$word->UTL_INSTALL_E;
		  } else {
			  if($_FILES['installer']['size'] > ini_get('post_max_size')) {
				  Message::$msgs['installer'] = Lang::$word->FU_ERROR10 . ' ' . ini_get('post_max_size');
			  } else {
				  $file = File::upload("installer", 52428800, "zip");
			  }
		  }
		  
		  if (empty(Message::$msgs)) {
			  File::process($file, BASEPATH . 'temp_update/', false, "temp_install.zip");
			  $path = BASEPATH . 'temp_update/temp_install.zip';
			  
			  $zip = new ZipArchive;
			  $res = $zip->open($path);
			  
			  if ($res === true) {
				  $zip->extractTo(BASEPATH . 'temp_update/');
				  $zip->close();
				  
				  $json_file = File::loadFile(BASEPATH . 'temp_update/package.json');
				  
				  if($json_file) {
					  $package = json_decode($json_file);
					  
					  // check if already exists
					  if(Db::run()->exist($package->sql)) {
						  $json['type'] = "error";
						  $json['title'] = Lang::$word->ERROR;
						  $json['message'] = $package->name . " already exists. Install can not continue.";
					  // validate version
					  } elseif(Validator::compareNumbers($package->ver, App::Core()->yoyov, "<")) {
						  $json['type'] = "error";
						  $json['title'] = Lang::$word->ERROR;
						  $json['message'] = "This package is not compatible with CMS PRO v." . App::Core()->yoyov;
					  // all checks out god to go
					  } else {
						  if(File::exists(BASEPATH . 'temp_update/install.sql')) {
							  $sqldata = File::parseSql(BASEPATH . 'temp_update/install.sql');
							  foreach($sqldata as $sql) {
								  Db::run()->pdoQuery($sql);
							  }
							  
							  File::deleteFile(BASEPATH . 'temp_update/install.sql');
						  } 
						  
						  File::deleteFile(BASEPATH . 'temp_update/temp_install.zip');
						  
						  $json['type'] = "success";
						  $json['title'] = Lang::$word->SUCCESS;
						  $json['message'] = $package->name . " v." . $package->ver . " is successfuly installed.";

						  File::copyDirectory(BASEPATH . 'temp_update', BASEPATH);
						  File::deleteRecrusive(BASEPATH . 'temp_update/');
						  File::deleteFile(BASEPATH . 'install.php');

						  //install languages
						  if($package->lang) {
							  if ($langdata = Db::run()->select(Lang::lTable, array("abbr"), array("abbr <>" => "en" ))->results()) {
								  foreach ($langdata as $lang) {
									  $flag_id = $lang->abbr;
									  include_once(BASEPATH . $package->lang);
								  }
							  }
						  }
						  
						  File::deleteFile(BASEPATH . 'package.json');
					  }
				  } else {
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = "Can not read package";
				  }
			  } else {
				  $json['type'] = "error";
				  $json['title'] = Lang::$word->ERROR;
				  $json['message'] = "Can not open zip archive";
			  }
			  print json_encode($json);
		  } else {
			  Message::msgSingleStatus();
		  }
      }
  }