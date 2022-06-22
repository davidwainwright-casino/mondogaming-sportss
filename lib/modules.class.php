<?php
  /**
   * Modules Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Modules
  {
      const mTable = "modules";
	  const mcTable = "mod_comments";


      /**
       * Modules::Index()
       * 
       * @return
       */
      public function Index()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = Db::run()->select(self::mTable, null, array("parent_id" => 0), 'ORDER BY  title' . Lang::$lang)->results();
          $tpl->template = 'admin/modules.tpl.php';
          $tpl->title = Lang::$word->MDL_TITLE;
      }

      /**
       * Modules::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->META_T30;
          $tpl->crumbs = ['admin', 'modules', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id, "parent_id" => 0))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [modules.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules.tpl.php';
          }
      }

      /**
       * Modules::processModule()
       * 
       * @return
       */
	  public function processModule()
	  {

			  
		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['info_' . $lang->abbr] = 'string';
			  $filters['keywords_' . $lang->abbr] = 'string';
			  $filters['description_' . $lang->abbr] = 'string';
		  }
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
				  $data['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $data['info_' . $lang->abbr] = $safe->{'info_' . $lang->abbr};
				  $data['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
				  $data['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
			  }

			  Db::run()->update(self::mTable, $data, array("id" => Filter::$id)); 
			  
			  $message = Message::formatSuccessMessage($data['title' . Lang::$lang], Lang::$word->MDL_UPDATE_OK); 
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Modules::getModuleList()
       * 
       * @return
       */
      public function getModuleList($is_content = true)
      {
		  
		  $type = ($is_content) ? 'content' : 'is_menu';

          $sql = "
			  SELECT id, modalias, title" . Lang::$lang . "  
			  FROM
				`" . self::mTable . "`
			  WHERE active = ?
			  AND $type = ?
			  ORDER BY title" . Lang::$lang . ";";

          $row = Db::run()->pdoQuery($sql, array(1, 1))->results();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Modules::moduleFiledsList()
       * 
       * @return
       */
      public function moduleFiledsList()
      {
		  
          $sql = "
			  SELECT id, modalias, title" . Lang::$lang . " as title 
			  FROM
				`" . self::mTable . "`
			  WHERE hasfields = ?;";

          $row = Db::run()->pdoQuery($sql, array(1))->results();

          return ($row) ? $row : 0;
      }

      /**
       * Modules::getAvailModules()
       * 
	   * @param str $modalias
       * @return
       */
	  public function getAvailModules($modalias)
	  {
		  $sql = "
		  SELECT 
			id,
			title" . Lang::$lang . " AS title,
			modalias,
			parent_id,
			icon
		  FROM
			`" . self::mTable . "` 
		  WHERE is_builder = ?
			AND modalias IN(" . $modalias . ")
			AND active = ? 
		  ORDER BY modalias;";
		  $row = Db::run()->pdoQuery($sql, array(1, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Modules::getAllAvailModules()
       * 
       * @return
       */
	  public function getAllAvailModules()
	  {
		  $sql = "
		  SELECT 
			id,
			title" . Lang::$lang . " AS title,
			modalias,
			parent_id,
			icon
		  FROM
			`" . self::mTable . "` 
		  WHERE is_builder = ?
			AND active = ? 
		  ORDER BY modalias;";
		  $row = Db::run()->pdoQuery($sql, array(1, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }
	  
      /**
       * Modules::getFreeModules()
       * 
	   * @param int $ids
       * @return
       */
	  public function getFreeModules($ids)
	  {
		  $and = $ids ? "AND id NOT IN (" . $ids . ")" : null;
		  $sql = "
		  SELECT 
			id,
			title" . Lang::$lang . " AS title,
			modalias,
			parent_id,
			icon
		  FROM
			`" . self::mTable . "` 
		  WHERE is_builder = ?
			$and
		  ORDER BY modalias;";
		  $row = Db::run()->pdoQuery($sql, array(1))->results();  
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Modules::render()
       * 
	   * @param str $segment
       * @return
       */
      public static function render($segment)
      {
		  
		  if(in_array($segment, App::Core()->modname)) {
			  $mod = App::Core()->moddir[$segment];
		  
				if(File::is_File(FMODPATH . $mod . "/themes/" . App::Core()->theme . "/index.tpl.php")) {
					$content = FMODPATH . $mod . "/themes/" . App::Core()->theme . "/index.tpl.php";
				} else {
					$content = FMODPATH . $mod . "/index.tpl.php";
				}
								
			  return($content);		  
		  }  
	  }
	  
      /**
       * Modules::parseModuleAssets()
       * 
	   * @param str $body
       * @return
       */
      public static function parseModuleAssets($body)
      {
		  $pattern = "#%%([^/|%<>]+)(?=[/|])#";
		  preg_match_all($pattern, $body, $matches);
		  $core = App::Core();
		  $content = '';

		  if ($matches[1]) {
			  $data = array_unique($matches[1]);
			  foreach ($data as $k => $row) {
				  $themecss = File::is_File(FMODPATH . $row . "/themes/" . $core->theme . "/assets/" . $row . ".css");
				  $themejs = File::is_File(FMODPATH . $row . "/themes/" . $core->theme . "/assets/" . $row . ".js");
				  $basecss = File::is_File(FMODPATH . $row . "/assets/" . $row . ".css");
				  $basejs = File::is_File(FMODPATH . $row . "/assets/" . $row . ".js");
				  
				  //css
				  if ($themecss) {
					  $content .= '<link id="' . $row . '" href="' . FMODULEURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
				  } elseif ($basecss) {
					  $content .= '<link id="' . $row . '" href="' . FMODULEURL . $row . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
				  }

				  //js
				  if ($themejs) {
					  $content .= '<script id="' . $row . '" type="text/javascript" src="' . FMODULEURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.js"></script>' . "\n";
				  } elseif ($basejs) {
					  $content .= '<script id="' . $row . '" type="text/javascript" src="' . FMODULEURL . $row . '/assets/' . $row . '.js"></script>' . "\n";
				  }
				  
			  }
		  }
		  return $content;
      }
  }