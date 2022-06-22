<?php

  /**
   * Plugins Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Plugins
  {
      const mTable = "plugins";
	  const lTable = "layout";
	  

      /**
       * Plugins::Index()
       * 
       * @return
       */
      public function Index()
      {

          if (Validator::get('letter')) {
              $letter = Validator::sanitize($_GET['letter'], 'default', 2);
              $and = "AND `title" . Lang::$lang . "` REGEXP '^" . $letter . "'";
              $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE parent_id = 0 $and LIMIT 1");
          } else {
              $counter = Db::run()->count(self::mTable, "parent_id = 0");
              $and = null;
          }

          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::Core()->perpage;
          $pager->path = Url::url(Router::$path, "?");
          $pager->paginate();

          $sql = "SELECT * FROM `" . self::mTable . "` WHERE parent_id = 0 $and ORDER BY hasconfig DESC, title" . Lang::$lang . $pager->limit;

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->pager = $pager;
          $tpl->data = Db::run()->pdoQuery($sql)->results();
          $tpl->template = 'admin/plugins.tpl.php';
          $tpl->title = Lang::$word->PLG_TITLE;
      }

      /**
       * Plugins::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->META_T28;
          $tpl->crumbs = ['admin', 'plugins', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [plugins.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/plugins.tpl.php';
          }
      }

      /**
       * Plugins::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->langlist = App::Core()->langlist;
          $tpl->title = Lang::$word->META_T29;
          $tpl->template = 'admin/plugins.tpl.php';
      }
	  
      /**
       * Plugins::processPlugin()
       * 
       * @return
       */
	  public function processPlugin()
	  {

		  $rules = array('show_title' => array('required|numeric', Lang::$word->PLG_SHOWTITLE));
		  $rules = array('active' => array('required|numeric', Lang::$word->PUBLISHED));
		  $filters = array(
			  'jscode' => 'script_tags',
			  'alt_class' => 'string'
		  );
			  
		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['info_' . $lang->abbr] = 'string';
			  $filters['body_' . $lang->abbr] = 'advanced_tags';
		  }
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
				  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $datam['info_' . $lang->abbr] = $safe->{'info_' . $lang->abbr};
				  $datam['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
			  }
			  
			  $datax = array(
				  'show_title' => $safe->show_title,
				  'alt_class' => $safe->alt_class,
				  'jscode' => $safe->jscode ? json_encode($safe->jscode) : "NULL",
				  'active' => $safe->active,
				  );
			  
			  $data = array_merge($datam, $datax);
			  (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : Db::run()->insert(self::mTable, $data); 
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->PLG_UPDATE_OK) : 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->PLG_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Plugins::Layout()
       * 
       * @return
       */
      public function Layout()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->META_T18;
		  $tpl->pagelist = Db::run()->select(Content::pTable, array("id", "title" . Lang::$lang), array("active" => 1), "ORDER BY title" . Lang::$lang)->results();
		  $tpl->modulelist = App::Modules()->getModuleList(false);
		  $tpl->layoutlist = $this->layoutOptions();
          $tpl->template = 'admin/layout.tpl.php';
      }
	 
	  
      /**
       * Plugins::layoutOptions()
       * 
       * @return
       */
	  public function layoutOptions()
	  {
	
		  $mod_id = Validator::get('mod_id');
		  $page_id = Validator::get('page_id');
	
		  $mod = 0;
		  $page = 0;
		  $row = 0;
		  $type = null;
	      if($mod_id and $page_id) {
			  Url::redirect(Url::url("/admin/layout"));
		  }
		  if ($mod_id and Validator::sanitize($mod_id, "int")) {
			  if ($mod = Db::run()->first(Modules::mTable, array(
				  "id",
				  "modalias"), array("id" => $mod_id))) {
				  $type = "mod_id";
				  $where = 'WHERE l.mod_id = ' . $mod->id;
			  }
		  }
	
		  if ($page_id and Validator::sanitize($page_id, "int")) {
			  if ($res = Db::run()->first(Content::pTable, array(
				  "id"), array("id" => $page_id))) {
				  $type = "page_id";
				  $page = $res->id;
				  $where = "WHERE l.page_id = " . $res->id;
			  }
		  }
	    if($mod_id or $page_id) {
			$sql = "
				SELECT 
				  l.id,
				  l.plug_id,
				  l.page_id,
				  l.mod_id,
				  l.place,
				  p.title" . Lang::$lang . " as title
				FROM
				  `" . self::lTable . "` AS l 
				  INNER JOIN " . self::mTable . " AS p 
					ON p.id = l.plug_id 
				$where 
				  AND l.is_content = ? 
				  AND p.multi = ?
				ORDER BY l.sorting ASC,
				  p.title" . Lang::$lang . " ASC;";
	  
			$row = Db::run()->pdoQuery($sql, array(0, 0))->results();
		}
	
		  $data = new stdClass();
		  $data->row = $row;
		  $data->type = $type;
		  $data->mod = $mod;
		  $data->page = $page;
	
		  return $data;
	  }
	  
      /**
       * Plugins::getFreePugins()
       * 
	   * @param int $ids
       * @return
       */
	  public function getFreePugins($ids)
	  {
		  $and = $ids ? "AND id NOT IN (" . $ids . ")" : null;
		  $sql = "
		  SELECT 
			id,
			title" . Lang::$lang . " AS title,
			plugalias,
			groups,
			plugin_id,
			alt_class,
			jscode,
			body" . Lang::$lang . " AS body,
			show_title,
			icon
		  FROM
			`" . self::mTable . "` 
		  WHERE multi = ?
			$and
			AND active = ? 
		  ORDER BY title" . Lang::$lang . " ASC;";
		  $row = Db::run()->pdoQuery($sql, array(0, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Plugins::getAvailPugins()
       * 
	   * @param array $ids
       * @return
       */
	  public function getAvailPugins($ids)
	  {
		  $sql = "
		  SELECT 
			id,
			title" . Lang::$lang . " AS title,
			plugalias,
			plugin_id,
			icon
		  FROM
			`" . self::mTable . "` 
		  WHERE multi = ?
			AND id IN(" . $ids . ")
			AND active = ? 
		  ORDER BY title" . Lang::$lang . " ASC;";
		  $row = Db::run()->pdoQuery($sql, array(0, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }
	  
      /**
       * Plugins::getPluginSpaces()
       * 
	   * @param int $ids
       * @return
       */
	  public function getPluginSpaces($ids)
	  {
		  $sql = "
		  SELECT 
			l.id,
			l.space,
			p.title" . Lang::$lang . " AS title 
		  FROM
			`" . self::mTable . "` AS p
		  INNER JOIN " . self::lTable . " AS l 
			ON p.id = l.plug_id 
		  WHERE p.id IN (" . $ids . ") 
			AND p.multi = ? 
			AND p.active = ? 
		  ORDER BY title" . Lang::$lang . " ASC;";
		  $row = Db::run()->pdoQuery($sql, array(0, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Plugins::getModulelugins()
       * 
	   * @param str $modalias
       * @return
       */
	  public function getModulelugins($modalias)
	  {
		  $sql = "
		  SELECT 
			p.id,
			l.plug_id,
			l.space,
			l.place,
			p.system,
			p.alt_class,
			p.plugalias,
			p.plugin_id,
			p.title" . Lang::$lang . " as title,
			p.body" . Lang::$lang . " as body,
			p.jscode,
			p.show_title,
			p.cplugin
		  FROM
			`" . self::lTable . "` AS l
		  LEFT JOIN " . self::mTable . " AS p 
			ON p.id = l.plug_id 
		  WHERE l.modalias = ?
		  AND p.active = ?
		  ORDER BY l.sorting;";
		  $row = Db::run()->pdoQuery($sql, array($modalias, 1))->results();  
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Plugins::Render()
       * 
	   * @param array $id
       * @return
       */
      public static function Render($id)
      {

		  $sql = "
		  SELECT 
		    jscode,
			show_title,
			alt_class,
			title" . Lang::$lang . " AS title ,
			body" . Lang::$lang . " AS body 
		  FROM
			`" . self::mTable . "`
		  WHERE id = ?
			AND active = ?";
		  $row = Db::run()->pdoQuery($sql, array($id, 1))->result();  
		  
		  return ($row) ? $row : 0;
		  
	  }

      /**
       * Plugins::RenderAll()
       * 
	   * @param array $ids
       * @return
       */
      public static function RenderAll($ids)
      {

		  $sql = "
		  SELECT 
		    id,
			plugin_id,
		    jscode,
			show_title,
			alt_class,
			title" . Lang::$lang . " AS title ,
			body" . Lang::$lang . " AS body 
		  FROM
			`" . self::mTable . "`
		  WHERE id IN (" . $ids . ") 
			AND active = ?";
		  $row = Db::run()->pdoQuery($sql, array(1))->results();  
		  
		  return ($row) ? $row : 0;
		  
	  }
	  
	  
      /**
       * Plugins::moduleLayout()
       * 
	   * @param array $data
       * @return
       */
	  public static function moduleLayout($data)
	  {
		  $layout = new stdClass();
		  //plugin layout
		  $layout->topWidget = Utility::findInArray($data, "place", "top");
		  $layout->bottomWidget = Utility::findInArray($data, "place", "bottom");
		  $layout->leftWidget = Utility::findInArray($data, "place", "left");
		  $layout->rightWidget = Utility::findInArray($data, "place", "right");
		  
		  //plugin counter
		  $layout->topCount = ($layout->topWidget) ? count($layout->topWidget) : 0;
		  $layout->bottomCount = ($layout->bottomWidget) ? count($layout->bottomWidget) : 0;
		  $layout->leftCount = ($layout->leftWidget) ? count($layout->leftWidget) : 0;
		  $layout->rightCount = ($layout->rightWidget) ? count($layout->rightWidget) : 0;  

		  //plugin space counter
		  $layout->tcounter = Utility::countInArray($layout->topWidget, "space", 10);
		  $layout->bcounter = Utility::countInArray($layout->bottomWidget, "space", 10);
		  
		  return $layout;
	  }

      /**
       * Plugins::loadPluginFile()
       * 
	   * @param array $items
       * @return
       */
      public static function loadPluginFile($items)
      {
		  $data = ["plugin_id" => $items[1], "id" => $items[2], "all" => $items[3]];

		  if(File::is_File(FPLUGPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php")) {
			  $contents = Utility::getSnippets(FPLUGPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php", $data);
		  } else {
			  $contents = Utility::getSnippets(FPLUGPATH . $items[0] . "/index.tpl.php", $data);
		  }
		  
		  return $contents;
		  
	  }
	  
	  
      /**
       * Plugins::parsePluginAssets()
       * 
	   * @param str $body
       * @return
       */
      public static function parsePluginAssets($body)
      {
		  $pattern = "#%%([^/|%<>]+)(?=[/|])#";
		  preg_match_all($pattern, $body, $matches);
		  $core = App::Core();
		  $content = '';

		  if ($matches[1]) {
			  $data = array_unique($matches[1]);
			  foreach ($data as $k => $row) {
				  $themecss = File::is_File(FPLUGPATH . $row . "/themes/" . $core->theme . "/assets/" . $row . ".css");
				  $themejs = File::is_File(FPLUGPATH . $row . "/themes/" . $core->theme . "/assets/" . $row . ".js");
				  $basecss = File::is_File(FPLUGPATH . $row . "/assets/" . $row . ".css");
				  $basejs = File::is_File(FPLUGPATH . $row . "/assets/" . $row . ".js");
				  
				  //css
				  if ($themecss) {
					  $content .= '<link id="' . $row . '" href="' . FPLUGINURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
				  } elseif ($basecss) {
					  $content .= '<link id="' . $row . '" href="' . FPLUGINURL . $row . '/assets/' . $row . '.css" rel="stylesheet" type="text/css">' . "\n";
				  }

				  //js
				  if ($themejs) {
					  $content .= '<script id="' . $row . '" type="text/javascript" src="' . FPLUGINURL . $row . '/themes/' . $core->theme . '/assets/' . $row . '.js"></script>' . "\n";
				  } elseif ($basejs) {
					  $content .= '<script id="' . $row . '" type="text/javascript" src="' . FPLUGINURL . $row . '/assets/' . $row . '.js"></script>' . "\n";
				  }
				  
			  }
		  }
		  return $content;
      }
  }