<?php
  /**
   * F.A.Q.
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Faq
  {

      const mTable = "mod_faq";
	  const cTable = "mod_faq_categories";
	  	  

      /**
       * Faq::__construct()
       * 
       * @return
       */
      public function __construct()
      {
	  }

      /**
       * Faq::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
		  
          $sql = "
		  SELECT 
		    m.id,
			m.category_id,
			m.question" . Lang::$lang . " AS title,
			c.name" . Lang::$lang . " AS name
		  FROM
			`" . self::mTable . "` AS m 
			LEFT JOIN `" . self::cTable . "` AS c 
			ON c.id = m.category_id
		  ORDER BY c.sorting, m.sorting"; 
		  
		  $query = Db::run()->pdoQuery($sql)->results();

		  $data = array();
		  if ($query) {
			  foreach ($query as $i => $row) {
                  if (!array_key_exists($row->name, $data)) {
					  $data[$row->name]['name'] = $row->name;
					  $data[$row->name]['category_id'] = $row->category_id;
                  }
				  
				  $data[$row->name]['items'][$i]['question'] = $row->title;
				  $data[$row->name]['items'][$i]['id'] = $row->id;
			  }
		  }
		  
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $data;
          $tpl->title = Lang::$word->_MOD_FAQ_TITLE;
          $tpl->template = 'admin/modules_/faq/view/index.tpl.php';
		  
	  }

      /**
       * Faq::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_FAQ_TITLE1;
          $tpl->crumbs = ['admin', 'modules', 'faq', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Faq.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->langlist = App::Core()->langlist;
			  $tpl->categories = Db::run()->select(self::cTable, null, null, "ORDER BY sorting")->results();
              $tpl->template = 'admin/modules_/faq/view/index.tpl.php';
          }
      }

      /**
       * Faq::Save()
       * 
       * @return
       */
	  public function Save()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->_MOD_FAQ_TITLE2;
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->categories = Db::run()->select(self::cTable, null, null, "ORDER BY sorting")->results();
		  $tpl->template = 'admin/modules_/faq/view/index.tpl.php';
	  }

      /**
       * Faq::processFaq()
       * 
       * @return
       */
      public function processFaq()
      {

		  $rules = array(
			  'category_id' => array('required|numeric', Lang::$word->CATEGORY),
			  );
			  
          foreach (App::Core()->langlist as $lang) {
              $rules['question_' . $lang->abbr] = array('required|string|min_len,3|max_len,100', Lang::$word->_MOD_FAQ_QUESTION . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['question_' . $lang->abbr] = 'string';
			  $filters['answer_' . $lang->abbr] = 'advanced_tags';
          }

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $datam['question_' . $lang->abbr] = $safe->{'question_' . $lang->abbr};
				  $datam['answer_' . $lang->abbr] = Url::in_url($safe->{'answer_' . $lang->abbr});
              }

              $datax = array(
                  'category_id' => $safe->category_id,
                  );
				  
			  (Filter::$id) ? Db::run()->update(self::mTable, array_merge($datam, $datax), array("id" => Filter::$id)) : Db::run()->insert(self::mTable, array_merge($datam, $datax)); 
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['question' . Lang::$lang], Lang::$word->_MOD_FAQ_UPDATED) : 
			  Message::formatSuccessMessage($datam['question' . Lang::$lang], Lang::$word->_MOD_FAQ_ADDED);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }

      /**
       * Faq::CategoryEdit()
       * 
       * @param int $id
       * @return
       */
      public function CategoryEdit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_FAQ_SUB3;
          $tpl->crumbs = ['admin', 'modules', 'faq', 'category'];

          if (!$row = Db::run()->first(self::cTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Faq.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->tree = $this->categoryTree();
			  $tpl->sortlist = $this->getSortCategoryList($tpl->tree);
			  $tpl->langlist = App::Core()->langlist;
              $tpl->template = 'admin/modules_/faq/view/index.tpl.php';
          }
      }
	  
      /**
       * Faq::CategorySave()
       * 
       * @return
       */
      public function CategorySave()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_FAQ_SUB3;
		  $tpl->tree = $this->categoryTree();
		  $tpl->sortlist = $this->getSortCategoryList($tpl->tree);
		  $tpl->langlist = App::Core()->langlist;
          $tpl->template = 'admin/modules_/faq/view/index.tpl.php';
      }

      /**
       * Faq::processCategory()
       * 
       * @return
       */
	  public function processCategory()
	  {
		  
		  foreach (App::Core()->langlist as $lang) {
			  $rules['name_' . $lang->abbr] = array('required|string|min_len,3|max_len,80', Lang::$word->MEN_NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['name_' . $lang->abbr] = 'string';
		  }
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i=> $lang) {
				  $datam['name_' . $lang->abbr] = $safe->{'name_' . $lang->abbr};
			  }
	
			  (Filter::$id) ? Db::run()->update(self::cTable, $datam, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::cTable, $datam)->getLastInsertId();
			  if (Filter::$id) {
				  $message = Message::formatSuccessMessage($datam['name' . Lang::$lang], Lang::$word->_MOD_FAQ_CAT_UPDATE_OK);
				  Message::msgReply(Db::run()->affected(), 'success', $message);
				  Logger::writeLog($message);
			  } else {
				  if ($last_id) {
					  $message = Message::formatSuccessMessage($datam['name' . Lang::$lang], Lang::$word->_MOD_FAQ_CAT_ADDED_OK);
					  $json['type'] = "success";
					  $json['title'] = Lang::$word->SUCCESS;
					  $json['message'] = $message;
					  $json['redirect'] = Url::url("/admin/modules/faq/categories");
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
       * Faq::categoryTree()
       * 
       * @return
       */
      public function categoryTree()
      {

		  $row = Db::run()->select(self::cTable, array("id", "name" . Lang::$lang), null, "ORDER BY sorting")->results();

          return ($row) ? $row : 0; 

      }

      /**
       * Faq::getSortCategoryList()
       * 
       * @param array $array
	   * @param integer $parent_id
       * @return
       */
	  public function getSortCategoryList($array, $parent_id = 0)
	  {
		  
		  $submenu = false;
		  $class = ($parent_id == 0) ? "parent" : "child";
		  $icon =  '<i class="icon negative trash"></i>';
		  $html = '';
	      if($array){
			  foreach ($array as $row) {
				  if ($submenu === false) {
					  $submenu = true;
					  $html .= "<ol class=\"dd-list\">\n";
				  }
				  $html .= '<li class="dd-item dd3-item clearfix" data-id="' . $row->id . '"><div class="dd-handle dd3-handle"></div>' 
				  . '<div class="dd3-content"><span class="actions"><a class="action" data-set=\'{"option":[{"delete": "deleteCategory","title": "' . $row->{'name' . Lang::$lang} . '","id":' . $row->id . '}],"action":"delete","url":"modules_/faq", "parent":"li"}\'>' . $icon . '</a></span>'
				 . ' <a href="' . Url::url("/admin/modules/faq/category", $row->id) . '">' . $row->{'name' . Lang::$lang} . '</a>' 
				  . '</div>';
				  $html .= "</li>\n";
			  }
		  }
	
		  if ($submenu === true) {
			  $html .= "</ol>\n";
		  }
		  
		  return $html;
	  }
      /**
       * Faq::Render()
       * 
       * @return
       */
      public function Render()
      {

          $sql = "
		  SELECT 
			m.question" . Lang::$lang . " AS title,
			m.answer" . Lang::$lang . " AS answer,
			c.name" . Lang::$lang . " AS name
		  FROM
			`" . self::mTable . "` AS m 
			LEFT JOIN `" . self::cTable . "` AS c 
			ON c.id = m.category_id
		  ORDER BY c.sorting, m.sorting"; 
		  
		  $query = Db::run()->pdoQuery($sql)->results();

		  $data = array();
		  if ($query) {
			  foreach ($query as $i => $row) {
                  if (!array_key_exists($row->name, $data)) {
					  $data[$row->name]['name'] = $row->name;
                  }
				  
				  $data[$row->name]['items'][$i]['question'] = $row->title;
				  $data[$row->name]['items'][$i]['answer'] = $row->answer;
			  }
		  }
		  return ($data) ? $data : 0;
	  }
  }