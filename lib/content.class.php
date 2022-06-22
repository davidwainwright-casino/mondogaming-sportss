<?php
  /**
   * Content Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
	  

  class Content
  {

      const mTable = "menus";
      const pTable = "pages";
      const lTable = "layout";
	  
	  const cTable = "countries";
	  const dcTable = "coupons";
	  const eTable = "email_templates";
	  const cfTable = "custom_fields";
	  const cfdTable = "custom_fields_data";

	  public static $pagedata = array();
	  public static $segments = array();
	  

      /**
       * Content::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }

      /**
       * Content::Pages()
       * 
       * @return
       */
      public function Pages()
      {

          $pager = Paginator::instance();
          $pager->items_total = Db::run()->count(self::pTable);
          $pager->default_ipp = App::Core()->perpage;
          $pager->path = Url::url(Router::$path, "?");
          $pager->paginate();

          $where = (App::Auth()->usertype <> "owner") ? array("is_admin" => 1) : null;
          $row = Db::run()->select(self::pTable, "*", $where, "ORDER BY title" . Lang::$lang . $pager->limit)->results();
		  
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->crumbs = ['admin', Lang::$word->ADM_PAGES];
		  $tpl->data = $row;
		  $tpl->pager = $pager;
		  $tpl->title = Lang::$word->META_T8; 
		  $tpl->template = 'admin/pages.tpl.php';

      }

      /**
       * Content::PageEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function PageEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T9;
		  $tpl->crumbs = ['admin', 'pages', 'edit'];
	
		  if (!$row = Db::run()->first(self::pTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->membership_list = App::Membership()->getMembershipList();
			  $tpl->access_list = Membership::getAccessList();
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->template = 'admin/pages.tpl.php';
		  }
	  }

      /**
       * Content::PageSave()
       * 
       * @return
       */
	  public function PageSave()
	  {
		  
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->module_list = App::Modules()->getModuleList();
		  $tpl->membership_list = App::Membership()->getMembershipList();
		  $tpl->access_list = Membership::getAccessList();
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->title = Lang::$word->PAG_SUB4;
		  $tpl->template = 'admin/pages.tpl.php';
	  }
	  
      /**
       * Content::processPage()
       * 
       * @return
       */
	  public function processPage()
	  {

		  $rules = array(
			  'is_admin' => array('required|numeric', Lang::$word->PAG_PGADM),
			  'active' => array('required|numeric', Lang::$word->PUBLISHED),
			  'is_comments' => array('required|numeric', Lang::$word->PAG_MDLCOMMENT),
			  'show_header' => array('required|numeric', Lang::$word->PAG_NOHEAD),
			  );

		  $filters = array(
			  'jscode' => 'advanced_tags',
			  'access' => 'string',
			  );

          if ($_POST['access'] == "Membership" && empty($_POST['membership_id'])) {
			  $rules['access'] = array('required|numeric|min_len,1|max_len,2', Lang::$word->PAG_MEMLVL);
          }

		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->PAG_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['slug_' . $lang->abbr] = 'string|trim';
			  $filters['caption_' . $lang->abbr] = 'string|trim';
			  $filters['custom_bg_' . $lang->abbr] = 'string';
			  $filters['keywords_' . $lang->abbr] = 'string|trim';
			  $filters['description_' . $lang->abbr] = 'string|trim';
			  //$filters['body_' . $lang->abbr] = 'advanced_tags|trim';
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
                  $datam['caption_' . $lang->abbr] = $safe->{'caption_' . $lang->abbr};
                  $datam['custom_bg_' . $lang->abbr] = $safe->{'custom_bg_' . $lang->abbr};
                  $datam['keywords_' . $lang->abbr] = $safe->{'keywords_' . $lang->abbr};
                  $datam['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
                  //$datam['body_' . $lang->abbr] = Url::in_url($safe->{'body_' . $lang->abbr});
			  }

              $datax = array(
                  'is_comments' => $safe->is_comments,
                  'membership_id' => empty($_POST['membership_id']) ? 0 : Utility::implodeFields($_POST['membership_id']),
                  'active' => $safe->active,
				  'is_admin' => $safe->is_admin,
                  'jscode' => json_encode($safe->jscode),
				  'show_header' => $safe->show_header,
                  //'page_type' => "normal",
				  'access' => $safe->access,
                  );

              if (Filter::$id) {
				  $sdata = array();
                  foreach (App::Core()->langlist as $i => $lang) {
                      $sdata['page_slug_' . $lang->abbr] = $datam['slug_' . $lang->abbr];
                  }
				  Db::run()->update(self::mTable, $sdata, array("page_id" => Filter::$id));
              } else {
                  $datax['created_by'] = App::Auth()->uid;
                  $datax['created_by_name'] = App::Auth()->name;
              }
			   
              $data = array_merge($datam, $datax);
			  
              (Filter::$id) ? Db::run()->update(self::pTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::pTable, $data)->getLastInsertId();
              //Process system page slugs
              Url::doSystemPageSlugs();

              if (Filter::$id) {
				  $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->PAG_UPDATE_OK);
                  Message::msgReply(true, 'success', $message);
				  Logger::writeLog($message);
              } else {
                  if ($last_id) {
					  $message = Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->PAG_ADDED_OK);
                      $json['type'] = "success";
                      $json['title'] = Lang::$word->SUCCESS;
                      $json['message'] = $message;
                      $json['redirect'] = ADMINURL . '/builder/' . Core::$language . '/' . $last_id;
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
       * Content::processBuilder()
       * 
       * @return
       */
	  public function processBuilder()
	  {

		  $rules = array(
			  'id' => array('required|numeric', "Invalid ID detected"),
			  'lang' => array('required|string|min_len,2|max_len,3', "Invalid Language detected"),
			  );

		  $filters['content'] = 'trim|advanced_tags'; 
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  if($_POST['lang'] == "all") {
				  foreach (App::Core()->langlist as $i => $lang) {
					  $data['body_' . $lang->abbr] = Url::in_url($safe->content);
				  }
			  } else {
				  $data['body_' . $safe->lang] = Url::in_url($safe->content);
			  }
			  
			  Db::run()->update(self::pTable, $data, array("id" => Filter::$id));
			  $message = Message::formatSuccessMessage($_POST['pagename'], Lang::$word->PAG_UPDATE_OK);
			  Message::msgReply(Db::run()->affected(), 'success', $message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	    
      /**
       * Content::getPageList()
       * 
       * @return
       */
      public function getPageList()
      {

          $where = (App::Auth()->usertype <> "owner") ? array("is_admin" => 1) : null;
          $row = Db::run()->select(self::pTable, array("id", "title" . Lang::$lang) , $where, "ORDER BY title" . Lang::$lang)->results();

          return ($row) ? $row : 0;
      }
	  
      /**
       * Content::Menus()
       * 
       * @return
       */
      public function Menus()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->contenttype = $this->getContentType();
		  $tpl->tree = $this->menuTree();
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->droplist = self::getMenuDropList($tpl->tree, 0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;");
		  $tpl->sortlist = $this->getSortMenuList($tpl->tree);
		  $tpl->template = 'admin/menus.tpl.php';
		  $tpl->title = Lang::$word->META_T12; 

      }

      /**
       * Content::MenuEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function MenuEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T13;
		  $tpl->crumbs = ['admin', 'menus', 'edit'];
	
		  if (!$row = Db::run()->first(self::mTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->contenttype = $this->getContentType();
			  $tpl->tree = $this->menuTree();
		      $tpl->droplist = self::getMenuDropList($tpl->tree, 0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row->parent_id);
		      $tpl->sortlist = $this->getSortMenuList($tpl->tree);
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->pagelist = $this->getPageList();
			  $tpl->modulelist = App::Modules()->getModuleList(false);
			  $tpl->template = 'admin/menus.tpl.php';
		  }
	  }

      /**
       * Content::processMenu()
       * 
       * @return
       */
	  public function processMenu()
	  {

		  $rules = array(
			  'content_type' => array('required|string|min_len,3|max_len,8', Lang::$word->MEN_CTYPE),
			  'home_page' => array('required|numeric', Lang::$word->PAG_PGHOME),
			  'active' => array('required|numeric', Lang::$word->PUBLISHED),
			  'parent_id' => array('required|numeric', Lang::$word->MEN_PARENT),
			  );

		  $filters = array(
			  'icon' => 'string',
			  );

		  if ($_POST['content_type'] == "page" and empty($_POST['page_id'])) {
			  Message::$msgs['page_id'] = Lang::$word->MEN_SUB2;
		  }
		  if ($_POST['content_type'] == "module" and empty($_POST['mod_id'])) {
			  Message::$msgs['mod_id'] = Lang::$word->MEN_SUB2;
		  }

		  foreach (App::Core()->langlist as $lang) {
			  $rules['name_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->MEN_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['name_' . $lang->abbr] = 'string';
			  $filters['caption_' . $lang->abbr] = 'string';
		  }
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  if ($_POST['content_type'] == "page") {
				  $slug = Db::run()->select(self::pTable, Utility::getLangSlugs("slug_"), array("id" => intval($_POST['page_id'])))->results();
			  } elseif ($_POST['content_type'] == "module") {
				  $slug = Db::run()->getValueById(Modules::mTable, "modalias", intval($_POST['mod_id']));
			  } else {
				  $slug = "NULL";
			  }
			  foreach (App::Core()->langlist as $lang) {
				  $datam['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
				  $datam['caption_' . $lang->abbr] = $safe->{'caption_' . $lang->abbr};
				  if (isset($_POST['page_id'])) {
					  $datam['page_slug_' . $lang->abbr] = $slug[0]->{'slug_' . $lang->abbr};
				  }
			  }

			  $datax = array(
				  'mod_id' => (isset($_POST['mod_id'])) ? intval($_POST['mod_id']) : 0,
				  'mod_slug' => isset($_POST['mod_id']) ? $slug : "NULL",
				  'page_id' => (isset($_POST['page_id'])) ? intval($_POST['page_id']) : 0,
				  'content_type' => $safe->content_type,
				  'parent_id' => $safe->parent_id,
				  'icon' => $safe->icon,
				  'link' => (isset($_POST['web'])) ? Validator::sanitize($_POST['web']) : "NULL",
				  'target' => (isset($_POST['target'])) ? Validator::sanitize($_POST['target'], "db") : "NULL",
				  'home_page' => $safe->home_page,
				  'active' => $safe->active,
				  );

			  if ($datax['home_page'] == 1) {
				  Db::run()->pdoQuery("UPDATE `" . self::mTable . "` SET `home_page`= DEFAULT(home_page);");
			  }
	/*
			  if (isset($_POST['mod_id']) and $_POST['home_page'] == 1) {
				  $pdata['module_id'] = intval($_POST['mod_id']);
				  $pdata['module_name'] = $slug;
				  Db::run()->update(self::pTable, $pdata, array("page_type" => "home"));
			  }*/
			  
			  $data = array_merge($datam, $datax);
	
			  (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();
			  if (Filter::$id) {
				  $message = Message::formatSuccessMessage($datam['name' . Lang::$lang], Lang::$word->MEN_UPDATE_OK);
				  Message::msgReply(Db::run()->affected(), 'success', $message);
				  Logger::writeLog($message);
			  } else {
				  if ($last_id) {
					  $message = Message::formatSuccessMessage($datam['name' . Lang::$lang], Lang::$word->MEN_ADDED_OK);
					  $json['type'] = "success";
					  $json['title'] = Lang::$word->SUCCESS;
					  $json['message'] = $message;
					  $json['redirect'] = Url::url("/admin/menus");
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
       * Content::menuTree()
       * 
       * @return
       */
	  public function menuTree($active = false)
	  {
		  $is_active = ($active) ? array("active" => 1) : null;
	
		  $data = Db::run()->select(self::mTable, null, $is_active,'ORDER BY parent_id, position')->results();
	
		  $menu = array();
		  $result = array();
	      if($data) {
			  foreach ($data as $row) {
				  $menu['id'] = $row->id;
				  $menu['name'] = $row->{'name' . Lang::$lang};
				  $menu['parent_id'] = $row->parent_id;
				  $menu['caption'] = $row->{'caption'.Lang::$lang};
				  $menu['page_id'] = $row->page_id;
				  $menu['mod_id'] = $row->mod_id;
				  $menu['content_type'] = $row->content_type;
				  $menu['link'] = $row->link;
				  $menu['home_page'] = $row->home_page;
				  $menu['active'] = $row->active;
				  $menu['target'] = $row->target;
				  $menu['icon'] = $row->icon;
				  $menu['pslug'] = $row->{'page_slug' . Lang::$lang};
				  $menu['mslug'] = $row->mod_slug;
				  
				  $result[$row->id] = $menu;
			  }
		  }
		  return $result;
	  }

      /**
       * Content::getMenuDropList()
       * 
	   * @param mixed $array
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $tro
	   * @param bool $selected
       * @return
       */
	  public static function getMenuDropList($array, $parent_id, $level = 0, $spacer, $selected = false)
	  {
		  $html = '';
		  if ($array) {
			  foreach ($array as $key => $row) {
				  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "";
	
				  if ($parent_id == $row['parent_id']) {
					  $html .= "<option value=\"" . $row['id'] . "\"" . $sel . ">";
	
					  for ($i = 0; $i < $level; $i++)
						  $html .= $spacer;
	
					  $html .= $row['name'] . "</option>\n";
					  $level++;
					  $html .= self::getMenuDropList($array, $key, $level, $spacer, $selected);
					  $level--;
				  }
			  }
			  unset($row);
		  }
		  return $html;
	  }

      /**
       * Content::getSortMenuList()
       * 
       * @param integer $parent_id
       * @return
       */
	  public function getSortMenuList($array, $parent_id = 0)
	  {
		  
		  $submenu = false;
		  $class = ($parent_id == 0) ? "parent" : "child";
		  $icon =  '<i class="icon negative trash"></i>';
		  $html = '';
	
		  foreach ($array as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($submenu === false) {
					  $submenu = true;
					  $html .= "<ol class=\"dd-list\">\n";
				  }
				  $html .= '<li class="dd-item dd3-item clearfix" data-id="' . $row['id'] . '"><div class="dd-handle dd3-handle"></div>' 
				  . '<div class="dd3-content"><span class="actions"><a class="delMenu" data-set=\'{"option":[{"trash": "trashMenu","title": "' . Validator::sanitize($row['name'], "chars") . '","id":' . $row['id'] . '}],"action":"trash","parent":"li"}\'>' . $icon . '</a></span>'
				  . ' <a href="' . Url::url("/admin/menus/edit", $row['id']) . '">' . $row['name'] . '</a>' 
				  . '</div>';
				  $html .= $this->getSortMenuList($array, $key);
				  $html .= "</li>\n";
			  }
		  }
		  unset($row);
	
		  if ($submenu === true) {
			  $html .= "</ol>\n";
		  }
		  
		  return $html;
	  }

      /**
       * Content::renderMenu()
       * 
	   * @param array $array
	   * @param integer $parent_id
	   * @param str $menuid
	   * @param str $class
       * @return
       */
      public function renderMenu($array, $parent_id = 0, $menuid = 'main-menu', $class = 'top-menu')
      {
		  
		  if(is_array($array) && count($array) > 0) {
				  
			  $submenu = false;
			  $attr = (!$parent_id) ? ' class="' . $class . '" id="' . $menuid . '"' : ' class="menu-submenu"';
			  $attr2 = (!$parent_id) ? ' class="nav-item"' : ' class="nav-submenu-item"';
			  $core = App::Core();
			  $pageslug = $core->pageslug;
			  $modslug = $core->slugs->module;
			  $segments = self::$segments;

			  foreach ($array as $key => $row) {
				  if ($row['parent_id'] == $parent_id) {
					  
					  if ($submenu === false) {
						  $submenu = true;	
						  print "<ul" . $attr . ">\n";
					  }
					  
					  $url = Url::url("/" . $pageslug, $row['pslug']);
					  $homeactive = (isset(self::$pagedata->page_type) and self::$pagedata->page_type == "home") ? "active" : "normal";

					  $name = ($parent_id == 0)  ? '<strong>' . $row['name'] . '</strong>' : $row['name'];
					  $home = ($row['home_page']) ? " homepage" : "";
					  $icon = ($row['icon']) ? '<i class="' . $row['icon'] . '"></i>' : "";
					  $caption = ($row['caption']) ? '<small>' . $row['caption'] . '</small>' : null;
					  
					  switch ($row['content_type']) {
						  case 'module':
						      //$mactive = (isset($segments[0]) and $segments[0] == $modslug->{$row['mslug']}) ? "active" : "normal";
							  $mactive = (in_array($modslug->{$row['mslug']}, $segments) ?  "active" : "normal");
							  $murl = $row['home_page'] ? SITEURL . '/' : Url::url('/' . $modslug->{$row['mslug']});
							  if($row['home_page']) {
								  $link = '<a href="' . SITEURL . '/" class="' . $homeactive . $home . '">' . $icon . $name . $caption . '</a>';
							  } else {
								  $link = '<a data-mslug="' . $modslug->{$row['mslug']} . '" href="' . $murl . '" class="' . $mactive . '">' . $icon . $name . $caption . '</a>';
							  }
							  break;
							  
						  case 'page':
						      $active = ((in_array($row['pslug'], $segments) and $row['mod_id'] == 0) ? "active" : "normal");
							  if ($row['home_page']) {
								  $link = '<a href="' . SITEURL . '/" class="' . $homeactive . $home . '">' . $icon . $name . $caption . '</a>';
							  } else {
								  $link = '<a href="' . $url . '" class="' . $active . $home . '">' . $icon . $name. $caption . '</a>';
							  }
							  break;
							  
						  case 'web':
							  $wlink = ($row['link'] == "#") ? '#' : $row['link'];
							  $wtarget = ($row['link'] == "#") ? null : ' target="' . $row['target'] . '"';
							  $link = '<a href="' . Url::out_url($wlink) . '"' . $wtarget . '>' . $icon . $name . $caption . '</a>';
							  break;
					  }
					  
					  print '<li class="nav-item men'.$row['id'].'" data-id="' . $row['id'] . '">';
					  print $link;
					  $this->renderMenu($array, $key);
					  print "</li>\n";
				  }
			  }
			  unset($row);
			  
			  if ($submenu === true)
				  print "</ul>\n";
		  }	
		  
	  }
	  
	  
      /**
       * Content::Fields()
       * 
       * @return
       */
      public function Fields()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->crumbs = ['admin', Lang::$word->META_T15];
		  $tpl->template = 'admin/fields.tpl.php';
		  $tpl->data = Db::run()->select(self::cfTable, "*", null, "ORDER BY sorting")->results(); 
		  $tpl->title = Lang::$word->META_T15; 

      }

      /**
       * Content::FieldEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function FieldEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T16;
		  $tpl->crumbs = ['admin', 'fields', 'edit'];
	
		  if (!$row = Db::run()->first(self::cfTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->modlist = App::Modules()->moduleFiledsList();
			  $tpl->template = 'admin/fields.tpl.php';
		  }
	  }

      /**
       * Content::FieldSave()
       * 
       * @return
       */
	  public function FieldSave()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T17;
		  $tpl->modlist = App::Modules()->moduleFiledsList();
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->template = 'admin/fields.tpl.php';
	  }

      /**
       * Content::processField()
       * 
       * @return
       */
	  public function processField()
	  {
	
		  $rules = array('required' => array('required|numeric', Lang::$word->NAME));
		  $rules = array('active' => array('required|numeric', Lang::$word->PUBLISHED));
		  $rules = array('section' => array('required|string', Lang::$word->SECTION));
	
		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'string';
			  $filters['tooltip_' . $lang->abbr] = 'string';
			  $filters['section'] = 'string';
		  }
	
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
	
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
				  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $datam['tooltip_' . $lang->abbr] = $safe->{'tooltip_' . $lang->abbr};
			  }
	
			  $datax = array(
				  'section' => $safe->section,
				  'required' => $safe->required,
				  'active' => $safe->active,
				  );
	
			  if (!Filter::$id) {
				  $datam['name'] = Utility::randomString(6);
			  }
	
			  $data = array_merge($datam, $datax);
			  (Filter::$id) ? Db::run()->update(self::cfTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::cfTable, $data)->getLastInsertId();
	
			  if (!Filter::$id) {
				  switch ($safe->section) {
					  case "digishop":
						  if (Db::run()->pdoQuery("SHOW TABLES LIKE 'mod_digishop'")->result()) {
							  Bootstrap::Autoloader(array(AMODPATH . 'digishop/'));
							  $digishop = Db::run()->select(Digishop::mTable)->results();
							  foreach ($digishop as $row) {
								  $dataDigishop[] = array(
									  'digishop_id' => $row->id,
									  'field_id' => $last_id,
									  'section' => "digishop",
									  'field_name' => $datam['name'],
									  );
							  }
							  Db::run()->insertBatch(self::cfdTable, $dataDigishop);
						  }
						  break;

					  case "shop":
						  if (Db::run()->pdoQuery("SHOW TABLES LIKE 'mod_shop'")->result()) {
							  Bootstrap::Autoloader(array(AMODPATH . 'shop/'));
							  $portfolio = Db::run()->select(Shop::mTable)->results();
							  foreach ($portfolio as $row) {
								  $dataPortfolio[] = array(
									  'shop_id' => $row->id,
									  'field_id' => $last_id,
									  'section' => "shop",
									  'field_name' => $datam['name'],
									  );
							  }
							  Db::run()->insertBatch(self::cfdTable, $dataPortfolio);
						  }
						  break;
						  
					  case "portfolio":
						  if (Db::run()->pdoQuery("SHOW TABLES LIKE 'mod_portfolio'")->result()) {
							  Bootstrap::Autoloader(array(AMODPATH . 'portfolio/'));
							  $portfolio = Db::run()->select(Portfolio::mTable)->results();
							  foreach ($portfolio as $row) {
								  $dataPortfolio[] = array(
									  'portfolio_id' => $row->id,
									  'field_id' => $last_id,
									  'section' => "portfolio",
									  'field_name' => $datam['name'],
									  );
							  }
							  Db::run()->insertBatch(self::cfdTable, $dataPortfolio);
						  }
						  break;
						  
					  case "profile":
						  $users = Db::run()->select(Users::mTable)->results();
						  foreach ($users as $row) {
							  $dataUsers[] = array(
								  'user_id' => $row->id,
								  'field_id' => $last_id,
								  'section' => $safe->section,
								  'field_name' => $datam['name'],
								  );
						  }
						  Db::run()->insertBatch(self::cfdTable, $dataUsers);
						  break;
				  }
			  }
	
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->CF_UPDATE_OK) : 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->CF_ADDED_OK);
	
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Content::dataFields()
       * 
       * @return
       */
      public static function dataFields()
      {
		  $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
		  if ($fl_array) {
			  $result = array();
			  foreach ($fl_array as $val) {
				array_push($result, Validator::sanitize($val));
			  }
			  return implode("::", array_filter($result));
		  }
		  return false;
      }
	  
      /**
       * Content::Countries()
       * 
       * @return
       */
      public function Countries()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->template = 'admin/countries.tpl.php';
		  $tpl->data = Db::run()->select(self::cTable, null, null, "ORDER BY sorting DESC")->results(); 
		  $tpl->title = Lang::$word->CNT_TITLE; 

      }

      /**
       * Content::CountryEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function CountryEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->CNT_EDIT;
		  $tpl->crumbs = ['admin', 'countries', 'edit'];
	
		  if (!$row = Db::run()->first(self::cTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->template = 'admin/countries.tpl.php';
		  }
	  }

      /**
       * Content::processCountry()
       * 
       * @return
       */
	  public function processCountry()
	  {
	
		  $rules = array(
			  'name' => array('required|string|min_len,3|max_len,60', Lang::$word->NAME),
			  'abbr' => array('required|string|min_len,2|max_len,2', Lang::$word->CNT_ABBR),
			  'active' => array('required|numeric', Lang::$word->CNT_ABBR),
			  'home' => array('required|numeric', Lang::$word->CNT_ABBR),
			  'sorting' => array('required|numeric', Lang::$word->CNT_ABBR),
			  'vat' => array('required|numeric|min_numeric,0|max_numeric,50', Lang::$word->TRX_TAX),
			  'id' => array('required|numeric', "ID"),
			  );

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'name' => $safe->name,
				  'abbr' => $safe->abbr,
				  'sorting' => $safe->sorting,
				  'home' => $safe->home,
				  'active' => $safe->active,
				  'vat' => $safe->vat,
				  );

			  if ($data['home'] == 1) {
				  Db::run()->pdoQuery("UPDATE `" . self::cTable . "` SET `home`= DEFAULT(home);");
			  }	
			  
			  Db::run()->update(self::cTable, $data, array("id" => Filter::$id)); 
			  Message::msgReply(Db::run()->affected(), 'success', Message::formatSuccessMessage($data['name'], Lang::$word->CNT_UPDATED));
			  Logger::writeLog(Message::formatSuccessMessage($data['name'], Lang::$word->CNT_UPDATED));
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Content::getCountryList()
       * 
       * @return
       */
      public function getCountryList()
      {

		  $row = Db::run()->select(self::cTable, null, null, "ORDER BY sorting DESC")->results();

          return ($row) ? $row : 0; 

      }

      /**
       * Content::Templates()
       * 
       * @return
       */
      public function Templates()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->crumbs = ['admin', 'email templates'];
		  $tpl->template = 'admin/templates.tpl.php';
		  $tpl->data = Db::run()->select(self::eTable, null, null, "ORDER BY name" . Lang::$lang . " DESC")->results(); 
		  $tpl->title = Lang::$word->META_T10; 

      }

      /**
       * Content::TemplateEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function TemplateEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T11;
		  $tpl->crumbs = ['admin', 'templates', 'edit'];
	
		  if (!$row = Db::run()->first(self::eTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->template = 'admin/templates.tpl.php';
		  }
	  }

      /**
       * Content::processTemplate()
       * 
       * @return
       */
	  public function processTemplate()
	  {

		  foreach (App::Core()->langlist as $lang) {
			  $rules['name_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $rules['subject_' . $lang->abbr] = array('required|string|min_len,3|max_len,100', Lang::$word->ET_SUBJECT . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['name_' . $lang->abbr] = 'string';
			  $filters['subject_' . $lang->abbr] = 'string';
			  $filters['body_' . $lang->abbr] = 'advanced_tags';
			  $filters['help_' . $lang->abbr] = 'string';
		  }
		  $rules = array('id' => array('required|numeric', "ID"));

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
				  $data['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
				  $data['subject_' . $lang->abbr] = $safe->{'subject_' . $lang->abbr};
				  $data['help_' . $lang->abbr] = $safe->{'help_' . $lang->abbr};
				  $data['body_' . $lang->abbr] = str_replace(SITEURL, "[SITEURL]", $safe->{'body_' . $lang->abbr});
			  }
	
			  Db::run()->update(self::eTable, $data, array("id" => Filter::$id)); 
			  $message = Message::formatSuccessMessage($data['name' . Lang::$lang], Lang::$word->ET_UPDATED);
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
	  /**
	   * Content::verifyCustomFields()
	   * 
	   * @param mixed $section
	   * @return
	   */
	  public static function verifyCustomFields($section)
	  {
	
		  if ($data = Db::run()->select(self::cfTable, null, array("section" => $section, "active" => 1, "required" => 1))->results()) {
			  foreach ($data as $row) {
				  Validator::checkPost('custom_' . $row->name, Lang::$word->FIELD_R0 . ' "' . $row->{'title' . Lang::$lang} . '" ' . Lang::$word->FIELD_R100);
			  }
		  }
	  } 
	  
	  /**
	   * Content::rendertCustomFields()
	   * 
	   * @param mixed $id
	   * @param mixed $section
	   * @return
	   */
	  public static function rendertCustomFields($id = '', $section)
	  {

		  if ($id) {
			  switch($section) {
				  case "digishop":
					 $where = 'WHERE cd.digishop_id = ?';
				  break;

				  case "shop":
					 $where = 'WHERE cd.shop_id = ?';
				  break;
				  
				  case "portfolio":
					 $where = 'WHERE cd.portfolio_id = ?';
				  break;
				  
				  case "profile":
					 $where = 'WHERE cd.user_id = ? ';
				  break;
				  
			  }
		  
			  $sql = "
			  SELECT 
				cf.*,
				cd.field_value 
			  FROM
				`" . self::cfTable . "` AS cf 
				LEFT JOIN `" . self::cfdTable . "` AS cd 
				  ON cd.field_id = cf.id 
			  $where 
			  AND cf.section = ? 
			  ORDER BY cf.sorting;";
			  $data = Db::run()->pdoQuery($sql, array($id, $section))->results();
		  } else {
			  $data = Db::run()->select(self::cfTable, null, array("section" => $section), "ORDER BY sorting")->results();
		  }
	
		  $html = '';
		  if ($data) {
			  foreach ($data as $i => $row) {
				  $tootltip = $row->{'tooltip' . Lang::$lang} ? ' <i data-content="' . $row->{'tooltip' . Lang::$lang} . '" class="icon question sign"></i>' : '';
				  $required = $row->required ? ' <i class="icon asterisk"></i>' : '';
				  $html .= '<div class="yoyo fields align-middle">';
				  $html .= '<div class="field four wide labeled">';
				  $html .= '<label class="content-right mobile-content-left">' . $row->{'title' . Lang::$lang} . $required . $tootltip . '</label>';
				  $html .= '</div>';
				  $html .= '<div class="six wide field">';
				  $html .= '<input name="custom_' . $row->name . '" type="text" placeholder="' . $row->{'title' . Lang::$lang} . '" value="' . ($id ? $row->field_value : '') . '">';
				  $html .= '</div>';
				  $html .= '</div>';
			  }
		  }
	
		  return $html;
	  }

	  /**
	   * Content::rendertCustomFieldsFront()
	   * 
	   * @param mixed $id
	   * @param mixed $section
	   * @return
	   */
	  public static function rendertCustomFieldsFront($id = '', $section)
	  {
		  
		  $html = '';

		  if ($id) {
			  switch($section) {
				  case "digishop":
					 $where = 'WHERE cd.digishop_id = ?';
				  break;

				  case "shop":
					 $where = 'WHERE cd.shop_id = ?';
				  break;
				  
				  case "portfolio":
					 $where = 'WHERE cd.portfolio_id = ?';
				  break;
				  
				  case "profile":
					 $where = 'WHERE cd.user_id = ? ';
				  break;
				  
			  }
		  
			  $sql = "
			  SELECT 
				cf.*,
				cd.field_value 
			  FROM
				`" . self::cfTable . "` AS cf 
				LEFT JOIN `" . self::cfdTable . "` AS cd 
				  ON cd.field_id = cf.id 
			  $where 
			  AND cf.section = ? 
			  AND cf.active = ? 
			  ORDER BY cf.sorting;";
			  $result = Db::run()->pdoQuery($sql, array($id, $section, 1))->results();
		  } else {
			  $result = Db::run()->select(self::cfTable, null, array("section" => $section, "active" => 1), "ORDER BY sorting")->results();
		  }
		  
		  $html .= Utility::getSnippets(THEMEBASE . "/snippets/customFields.tpl.php", $data = ["data" => $result, "id" => $id, "section" => $section]);
		  
		  return $html;

	  }
	  
      /**
       * Content::Coupons()
       * 
       * @return
       */
      public function Coupons()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->template = 'admin/coupons.tpl.php';
		  $tpl->data = Db::run()->select(self::dcTable, null, array("ctype" => "membership"))->results(); 
		  $tpl->title = Lang::$word->META_T25; 

      }

      /**
       * Content::CouponEdit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function CouponEdit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T26;
		  $tpl->crumbs = ['admin', 'coupons', 'edit'];
	
		  if (!$row = Db::run()->first(self::dcTable, null, array("id" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->mlist  = App::Membership()->getMembershipList();
			  $tpl->template = 'admin/coupons.tpl.php';
		  }
	  }

      /**
       * Content::CouponSave()
       * 
       * @return
       */
	  public function CouponSave()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T27;
		  $tpl->mlist  = App::Membership()->getMembershipList();
		  $tpl->template = 'admin/coupons.tpl.php';
	  }

      /**
       * Content::processCoupon()
       * 
       * @return
       */
	  public function processCoupon()
	  {
	
		  $rules = array(
			  'title' => array('required|string|min_len,3|max_len,60', Lang::$word->NAME),
			  'code' => array('required|string', Lang::$word->DC_CODE),
			  'discount' => array('required|numeric|min_numeric,1|max_numeric,99', Lang::$word->DC_DISC),
			  'type' => array('required|string', Lang::$word->DC_TYPE),
			  'active' => array('required|numeric', Lang::$word->PUBLISHED),
			  );

          $filters = array(
			  'title' => 'string',
			  'code' => 'string',
			  'discount' => 'numbers'
		  );
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  
		  if (empty(Message::$msgs)) {
			  $data = array(
				  'title' => $safe->title,
				  'code' => $safe->code,
				  'discount' => $safe->discount,
				  'type' => $safe->type,
				  'ctype' => "membership",
				  'membership_id' => Validator::post('membership_id') ? Utility::implodeFields($_POST['membership_id']) : 0,
				  'active' => $safe->active,
				  );
				  
			  (Filter::$id) ? Db::run()->update(self::dcTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::dcTable, $data)->getLastInsertId(); 
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['title'], Lang::$word->DC_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['title'], Lang::$word->DC_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Content::validatePage()
       * 
       * @return
       */ 	  
      public static function validatePage()
	  {
		  switch (self::$pagedata->access) {
			  case "Registered":
				  if (!App::Auth()->logged_in) {
					  echo Utility::getSnippets(THEMEBASE . "/snippets/registerError.tpl.php");
					  return false;
				  } else {
					  return true;
				  }
			  
			  break;

			  case "Membership":
			      $m_arr = explode(",", self::$pagedata->membership_id);
				  if (App::Auth()->logged_in and in_array(App::Auth()->membership_id, $m_arr)) {
					  return true;
				  } else {
					  $rows = Db::run()->pdoQuery("SELECT title" . Lang::$lang . " as title FROM `" . Membership::mTable . "` WHERE id IN(" . self::$pagedata->membership_id . ")")->results();
					  echo Utility::getSnippets(THEMEBASE . "/snippets/membershipError.tpl.php", $data = $rows);
					  return false;
				  }
			  break;

			  default:
				  return true;
			  break;
		  }
      } 

      /**
       * Content::writeSiteMap()
       * 
       * @return
       */
	  public static function writeSiteMap()
	  {
		  
		  $filename = BASEPATH . 'sitemap.xml';
		  $file = SITEURL . '/sitemap.xml';
		  if (is_writable($filename)) {
			  File::writeToFile($filename, self::makeSiteMap());
			  Message::msgReply($file, 'success', Message::formatSuccessMessage($file, Lang::$word->UTL_MAP_OK));
		  } else {
			  Message::msgReply($file, 'error', Message::formatErrorMessage($file, Lang::$word->UTL_MAP_ERROR));
		  }
	  }

      /**
       * Content::makeSiteMap()
       * 
       * @return
       */ 	  
      public static function makeSiteMap()
	  {
		  
		  $html = "";
		  $html .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		  $html .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\r\n";
		  $html .= "<url>\r\n";
		  $html .= "<loc>" . SITEURL . "/</loc>\r\n";
		  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
		  $html .= "</url>\r\n";
          
		  $core = App::Core();
		  
		  //pages
          $pages = "SELECT slug" . Lang::$lang . " as slug FROM `" . Content::pTable . "` WHERE active = ? AND page_type = 'normal' ORDER BY title" . Lang::$lang;
		  $query = Db::run()->pdoQuery($pages, array(1));

		  foreach ($query->results() as $row) {
			  $html .= "<url>\r\n";
			  $html .= "<loc>" . Url::url('/' . $core->pageslug, $row->slug) . "</loc>\r\n";
			  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $html .= "<changefreq>weekly</changefreq>\r\n";
			  $html .= "</url>\r\n";
		  }
          unset($row, $query);
		  
		  //blog
		  if(Db::run()->exist('mod_blog')) {
			  $blog = "SELECT slug" . Lang::$lang . " as slug FROM `mod_blog` WHERE active = ? ORDER BY created DESC;";
			  $query = Db::run()->pdoQuery($blog, array(1));
			  
			  foreach ($query->results() as $row) {
				  $html .= "<url>\r\n";
				  $html .= "<loc>" . Url::url('/' . $core->modname['blog'], $row->slug) . "</loc>\r\n";
				  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				  $html .= "<changefreq>weekly</changefreq>\r\n";
				  $html .= "</url>\r\n";
			  }
			  unset($row, $query);
		  }

		  //digishop
		  if(Db::run()->exist('mod_digishop')) {
			  $digishop = "SELECT slug" . Lang::$lang . " as slug FROM `mod_digishop` WHERE active = ? ORDER BY created DESC;";
			  $query = Db::run()->pdoQuery($digishop, array(1));
			  
			  foreach ($query->results() as $row) {
				  $html .= "<url>\r\n";
				  $html .= "<loc>" . Url::url('/' . $core->modname['digishop'], $row->slug) . "</loc>\r\n";
				  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				  $html .= "<changefreq>weekly</changefreq>\r\n";
				  $html .= "</url>\r\n";
			  }
			  unset($row, $query);
		  }

		  //shop
		  if(Db::run()->exist('mod_shop')) {
			  $digishop = "SELECT slug" . Lang::$lang . " as slug FROM `mod_shop` WHERE active = ? ORDER BY created DESC;";
			  $query = Db::run()->pdoQuery($digishop, array(1));
			  
			  foreach ($query->results() as $row) {
				  $html .= "<url>\r\n";
				  $html .= "<loc>" . Url::url('/' . $core->modname['shop'], $row->slug) . "</loc>\r\n";
				  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				  $html .= "<changefreq>weekly</changefreq>\r\n";
				  $html .= "</url>\r\n";
			  }
			  unset($row, $query);
		  }
		  
		  //portfolio
		  if(Db::run()->exist('mod_portfolio')) {
			  $portfolio = "SELECT slug" . Lang::$lang . " as slug FROM `mod_portfolio` ORDER BY created DESC;";
			  $query = Db::run()->pdoQuery($portfolio);
			  
			  foreach ($query->results() as $row) {
				  $html .= "<url>\r\n";
				  $html .= "<loc>" . Url::url('/' . $core->modname['portfolio'], $row->slug) . "</loc>\r\n";
				  $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				  $html .= "<changefreq>weekly</changefreq>\r\n";
				  $html .= "</url>\r\n";
			  }
			  unset($row, $query);
		  }
		  
		  $html .= "</urlset>";
		  
		  return $html;
      } 
	  
      /**
       * Content::pageBg()
       * 
       * @return
       */ 	  
      public static function pageBg()
	  {
          if(self::$pagedata->{'custom_bg' . Lang::$lang}) {
			  return ' style="background-image: url(' . UPLOADURL . '/' . self::$pagedata->{'custom_bg' . Lang::$lang} . '); background-repeat: no-repeat; background-position: top center; background-size: cover;"';
		  }
      } 

      /**
       * Content::pageHeading()
       * 
       * @return
       */ 	  
      public static function pageHeading()
	  {
		  if(File::is_File(UPLOADS . "/images/" . self::$pagedata->{'slug' . Lang::$lang} . '.jpg')) {
			  $bg = ' style="background-image: url(' . UPLOADURL . '/images/' . self::$pagedata->{'slug' . Lang::$lang} . '.jpg);"';
		  } elseif(File::is_File(UPLOADS . '/images/default-heading.jpg')) {
			  $bg = ' style="background-image: url(' . UPLOADURL . '/images/default-heading.jpg);"';
		  } else {
			  $bg = '';
		  }
		  
		  return $bg;
      } 
				   
      /**
       * Content::parseContentData()
       * 
	   * @param str $body
       * @return
       */
      public static function parseContentData($body, $is_builder = false)
      {

		  $pattern = "/%%(.*?)%%/";
		  preg_match_all($pattern, $body, $matches);
		  $contents = array();

		  if ($matches[1]) {
			  $ids = array();
			  foreach ($matches[1] as $val) {
				  $v = explode("|", $val);
				  $ids[] = $v[3];
			  }
			  $all = Plugins::RenderAll(Utility::implodeFields($ids));
			  foreach ($matches[1] as $k => $row) {
				  $items = explode("|", $row);
				  if($items[1] == "uplugin") {
					  $ubody = array_column($all, 'body', 'id');
					  $html = '<div data-mode="readonly" data-wuplugin-id="' . $items[3] . '">' . $ubody[$items[3]] . '</div>';
				  } elseif($items[1] == "plugin") {
					  if(File::is_File(FPLUGPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php")) {
						  $contents = Utility::getSnippets(FPLUGPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php", $data = ["plugin_id" => $items[2], "id" => $items[3], "all" => $all]);
					  } else {
						  $contents = Utility::getSnippets(FPLUGPATH . $items[0] . "/index.tpl.php", $data = ["plugin_id" => $items[2], "id" => $items[3], "all" => $all]);
					  }
					  if($is_builder) {
						   $html = '<div data-mode="readonly" data-wplugin-id="' . $items[3] . '" data-wplugin-plugin_id="' . $items[2] . '" data-wplugin-alias="' . $items[0] . '">' . $contents . '</div>';
					  } else {
						  $html = $contents;
					  }
				  } else {
					  $group = explode("/", $items[0]);
					  if(File::is_File(FMODPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php")) {
						  $contents = Utility::getSnippets(FMODPATH . $items[0] . "/themes/" . App::Core()->theme . "/index.tpl.php", $data = ["module_id" => $items[2], "alias" => $items[0], "id" => $items[3]]);
					  } else {
						  $contents = Utility::getSnippets(FMODPATH . $items[0] . "/index.tpl.php", $data = ["module_id" => $items[2], "alias" => $items[0], "id" => $items[3]]);
					  } 
					  if($is_builder) {
						   $html = '<div data-mode="readonly" data-wmodule-group="' . $group[0] . '" data-wmodule-module_id="' . $items[2] . '" data-wmodule-id="' . $items[3] . '" data-wmodule-alias="' . $items[0] . '">' . $contents . '</div>';
					  } else {
						  $html = $contents;
					  }
				  }

				  $body = str_replace($matches[0][$k], $html, $body);
			  }
		  }
		  return Url::out_url($body);

      }
	  
      /**
       * Content::getContentType()
       * 
	   * @param bool $selected
       * @return
       */ 	  
      public function getContentType()
	  {
		  $modlist = App::Modules()->getModuleList();
          if($modlist) {
			  $array = array(
					'page' => Lang::$word->CON_PAGE,
					'module' => Lang::$word->MODULE,
					'web' => Lang::$word->EXT_LINK
			  );
		  } else {
			  $array = array(
					'page' => Lang::$word->CON_PAGE,
					'web' => Lang::$word->EXT_LINK
			  );  
		  }
		  
          return $array;
      } 
  }
