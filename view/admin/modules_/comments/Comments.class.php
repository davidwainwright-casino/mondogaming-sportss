<?php
  /**
   * Comments Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Comments
  {

      const mTable = "mod_comments";


      /**
       * Comments::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          $this->Config();
      }

      /**
       * Comments::Config()
       * 
       * @return
       */
      private function Config()
      {

          $row = File::readIni(AMODPATH . 'comments/config.ini');
          $this->auto_approve = $row->comments->auto_approve;
          $this->rating = $row->comments->rating;
		  $this->timesince = $row->comments->timesince;
          $this->blacklist_words = $row->comments->blacklist_words;
          $this->char_limit = $row->comments->char_limit;
          $this->dateformat = $row->comments->dateformat;
          $this->notify_new = $row->comments->notify_new;
          $this->perpage = $row->comments->perpage;
          $this->public_access = $row->comments->public_access;
          $this->show_captcha = $row->comments->show_captcha;
          $this->sorting = $row->comments->sorting;
          $this->username_req = $row->comments->username_req;

          return ($row) ? $this : 0;
      }

      /**
       * Comments::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {

          $counter = Db::run()->count(false, false, "SELECT COUNT(*) FROM `" . self::mTable . "` WHERE active = 0 LIMIT 1");
          $pager = Paginator::instance();
          $pager->items_total = $counter;
          $pager->default_ipp = App::Core()->perpage;
          $pager->path = Url::url(Router::$path, "?");
          $pager->paginate();

          $sql = "		
		  SELECT 
			c.id,
			c.user_id,
			c.comment_id,
			c.parent_id,
			c.section,
			c.body,
			c.created,
			c.username as uname,
			u.username,
			CONCAT(u.fname, ' ', u.lname) AS name
		  FROM
			`" . self::mTable . "` AS c 
			LEFT JOIN `" . Users::mTable . "` AS u 
			  ON u.id = c.user_id 
		  WHERE c.active = ?
		  ORDER BY c.created DESC " . $pager->limit . ";";
				
          $rows = Db::run()->pdoQuery($sql, array(0))->results();
		  
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $rows;
          $tpl->title = Lang::$word->_MOD_CM_TITLE2;
		  $tpl->pager = $pager;
          $tpl->template = 'admin/modules_/comments/view/index.tpl.php';
      }
	  
      /**
       * Comments::Settings()
       * 
       * @return
       */
      public function Settings()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_CM_TITLE1;
          $tpl->data = $this->Config();
          $tpl->template = 'admin/modules_/comments/view/index.tpl.php';
      }

      /**
       * Comments::processConfig()
       * 
       * @return
       */
      public function processConfig()
      {

          $rules = array(
              'auto_approve' => array('required|numeric', Lang::$word->_MOD_CM_AA),
              'rating' => array('required|numeric', Lang::$word->_MOD_CM_RATING),
              'char_limit' => array('required|numeric', Lang::$word->_MOD_CM_CHAR),
              'notify_new' => array('required|numeric', Lang::$word->_MOD_CM_NOTIFY),
              'perpage' => array('required|numeric', Lang::$word->_MOD_CM_PERPAGE),
              'public_access' => array('required|numeric', Lang::$word->_MOD_CM_REG_ONLY),
              'show_captcha' => array('required|numeric', Lang::$word->_MOD_CM_CAPTCHA),
              'sorting' => array('required|string|min_len,3|max_len,4', Lang::$word->_MOD_CM_SORTING),
              'dateformat' => array('required|string', Lang::$word->_MOD_CM_DATE),
              'username_req' => array('required|numeric', Lang::$word->_MOD_CM_UNAME_R),
              );
          $filters['blacklist_words'] = 'string';

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
          $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              $data = array('comments' => array(
                      'auto_approve' => $safe->auto_approve,
                      'rating' => $safe->rating,
                      'char_limit' => $safe->char_limit,
                      'notify_new' => $safe->notify_new,
                      'perpage' => $safe->perpage,
                      'public_access' => $safe->public_access,
                      'show_captcha' => $safe->show_captcha,
                      'sorting' => $safe->sorting,
                      'dateformat' => $safe->dateformat,
					  'timesince' => (empty($_POST['timesince']) ? 0 : 1),
                      'username_req' => $safe->username_req,
                      'blacklist_words' => $safe->blacklist_words,
                      ));

              Message::msgReply(File::writeIni(AMODPATH . 'comments/config.ini', $data), 'success', Lang::$word->_MOD_CM_CUPDATED);
              Logger::writeLog(Lang::$word->_MOD_CM_CUPDATED);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Comments::commentTree()
       * 
       * @param str $section
       * @param int $id
       * @return
       */
      public function commentTree($section, $id)
      {

          $counter = "
		  SELECT 
			COUNT(*) 
		  FROM
			`" . self::mTable . "` 
		  WHERE section = '" . $section . "' 
			AND comment_id = 0 
			AND parent_id = $id 
			AND active = 1 
		  LIMIT 1;";

          $pager = Paginator::instance();
          $pager->items_total = Db::run()->count(false, false, $counter);
          $pager->default_ipp = $this->perpage;
          $pager->path = Url::url(Router::$path, "?");
          $pager->paginate();

          if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
              list($sort, $order) = explode("|", $_GET['order']);
              $sort = Validator::sanitize($sort, "default", 16);
              $order = Validator::sanitize($order, "default", 4);
              if (in_array($sort, array(
                  "vote_up",
                  "vote_down",
                  "created"))) {
                  $ord = ($order == 'DESC') ? " DESC" : " ASC";
                  $sorting = $sort . $ord;
              } else {
                  $sorting = " created " . $this->sorting;
              }
          } else {
              $sorting = " created " . $this->sorting;
          }

          $sql = "		
		  SELECT 
			c.id,
			c.user_id,
			c.comment_id,
			c.parent_id,
			c.section,
			c.vote_down,
			c.vote_up,
			c.body,
			c.created,
			c.username as uname,
			u.username,
			CONCAT(u.fname, ' ', u.lname) AS name,
			u.avatar 
		  FROM
			`" . self::mTable . "` AS c 
			INNER JOIN 
			  (SELECT 
				id 
			  FROM
				`" . self::mTable . "` 
			  WHERE section = ? 
				AND parent_id = ? 
				AND comment_id = 0
				AND active = 1 
			  ORDER BY $sorting 
			  " . $pager->limit . ") AS ch 
			  ON ch.id IN (c.id, c.comment_id) 
			LEFT JOIN `" . Users::mTable . "` AS u 
			  ON u.id = c.user_id 
				WHERE section = ? 
				AND parent_id = ? 
				AND c.active = 1
		  ORDER BY $sorting;";

          $data = Db::run()->pdoQuery($sql, array(
              $section,
              $id,
              $section,
              $id))->results();

          $comments = array();
          $result = array();

          foreach ($data as $row) {
              $comments['id'] = $row->id;
              $comments['user_id'] = $row->user_id;
              $comments['comment_id'] = $row->comment_id;
              $comments['parent_id'] = $row->parent_id;
              $comments['vote_up'] = $row->vote_up;
              $comments['vote_down'] = $row->vote_down;
              $comments['section'] = $row->section;
              $comments['body'] = $row->body;
              $comments['created'] = $row->created;
              $comments['name'] = $row->name;
              $comments['username'] = $row->username;
              $comments['uname'] = $row->uname;
              $comments['avatar'] = $row->avatar;
              $result[$row->id] = $comments;
          }
          return $result;
      }

      /**
       * Comments::getCommentList()
       * 
       * @param array $array
       * @param integer $comment_id
       * @param str $class
       * @return
       */
      public function getCommentList($array, $comment_id = 0, $class = 'threaded')
      {

          $submenu = false;
          $class = ($comment_id == 0) ? "yoyo comments $class" : "comments";
          $delete = (App::Auth()->is_Admin()) ? '<a class="delete"><i class="icon trash"></i></a>' : null;
          $html = '';

          foreach ($array as $key => $row) {
              if ($row['comment_id'] == $comment_id) {
                  if ($submenu === false) {
                      $submenu = true;
                      $html .= "<div class=\"$class\">\n";
                  }
                  if ($row['uname']) {
                      $user = '<span class="author">' . $row['uname'] . '</span>';
                      $avatar = '<div class="avatar"><img src="' . UPLOADURL . '/avatars/blank.svg" alt=""></div>';
                  } else {
                      $profile = Url::url('/' . App::Core()->system_slugs->profile[0]->{'slug' . Lang::$lang}, $row['username']);
                      $user = '<a href="' . $profile . '" class="author">' . $row['name'] . '</a>';
                      $avatar = '<a href="' . $profile . '" class="avatar"><img src="' . UPLOADURL . '/avatars/' . ($row['avatar'] ? $row['avatar'] : "blank.svg") . '" alt=""></a>';
                  }

                  $html .= '<div class="comment" data-id="' . $row['id'] . '" id="comment_' . $row['id'] . '">';
                  $html .= $avatar;
                  $html .= '<div class="content">';
                  $html .= $user;
                  $html .= '<div class="metadata">';
                  $html .= '<span class="date">' . ($this->timesince) ? Date::timesince($row['created']) : Date::doDate($this->dateformat, $row['created']) . '</span>';
                  $html .= $delete;
                  $html .= '</div>';
                  $html .= '<div class="text">' . $row['body'] . '</div>';
                  $html .= '<div class="yoyo horizontal divided list actions">';
                  if ($this->rating) {
                      $html .= '<a data-up="' . $row['vote_up'] . '" data-id="' . $row['id'] . '" 
					  class="item up"><span class="yoyo positive text">' . $row['vote_up'] . '</span> <i class="icon chevron up"></i></a>';
                      $html .= '<a data-down="' . $row['vote_down'] . '" data-id="' . $row['id'] . '" 
					  class="item down"><span class="yoyo negative text">' . $row['vote_down'] . '</span> <i class="icon chevron down"></i></a>';
                  }
                  if ($comment_id == 0) {
                      $html .= '<a data-id="' . $row['id'] . '" class="item replay">' . Lang::$word->_MOD_CM_REPLAY . '</a>';
                  }
                  $html .= '</div>';
                  $html .= '</div>';
                  $html .= $this->getCommentList($array, $key);
                  $html .= "</div>\n";
              }
          }
          unset($row);

          if ($submenu === true) {
              $html .= "</div>\n";
          }

          return $html;
      }

      /**
       * Comments::Render()
       * 
       * @param str $section
       * @param int $id
       * @return
       */
      public static function Render($section, $id)
      {
          return App::Comments()->getCommentList(App::Comments()->commentTree($section, $id));
      }

      /**
       * Comments::singleComment()
       * 
       * @param int $id
       * @return
       */
      public static function singleComment($id)
      {
          $sql = "		
		  SELECT 
			c.id,
			c.user_id,
			c.comment_id,
			c.parent_id,
			c.section,
			c.vote_down,
			c.vote_up,
			c.body,
			c.created,
			c.username AS uname,
			u.username,
			CONCAT(u.fname, ' ', u.lname) AS name,
			u.avatar 
		  FROM
			`" . self::mTable . "` AS c 
			LEFT JOIN `" . Users::mTable . "` AS u 
			  ON u.id = c.user_id 
		  WHERE c.id = ?;";
				
          $row = Db::run()->pdoQuery($sql, array($id))->result();
		  
          return ($row) ? $row : 0;
      }
	  
      /**
       * Comments::processComment()
       * 
       * @return
       */
	  public function processComment()
	  {

		  $rules = array(
			  'id' => array('required|numeric', "Invalid ID detected"),
			  'username' => array('required|string', Lang::$word->NAME),
			  'message' => array('required|string', Lang::$word->MESSAGE),
			  'parent_id' => array('required|numeric', "Invalid ID detected"),
			  'url' => array('required|string', "Invalid URI detected"),
			  );
          
		  $filters = array(
			  'message' => 'trim|string',
			  'type' => 'trim|string',
			  'url' => 'trim|string',
			  'username' => 'trim|string',
		  );
		  
		  if($this->show_captcha) {
			  if (App::Session()->get('wcaptcha') != $_POST['captcha'])
				  Message::$msgs['captcha'] = Lang::$word->CAPTCHA; 
		  }
		  
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  $core = App::Core();
		  
		  if (empty(Message::$msgs)) {
              $data = array(
                  'comment_id' => $safe->id,
				  'user_id' => (App::Auth()->logged_in) ? App::Auth()->uid : 0,
                  'parent_id' => $safe->parent_id,
				  'username' => (App::Auth()->logged_in) ? "NULL" : $safe->username,
                  'section' => ($core->pageslug == $safe->section) ? "page" : $core->modname[$safe->section],
                  'body' => Validator::censored($safe->message, $this->blacklist_words),
                  'active' => ($this->auto_approve) ? 1 : 0,
				  );
			  
			  $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId();

			  // Set shop product raings
              if($safe->id == 0 and $data['section'] == "shop") {
				  $db = Db::run()->prepare("
					  UPDATE `mod_shop` 
					  SET `ratings` = `ratings` + 1, `likes` = `likes` + " . intval($_POST['star']) . "
					  WHERE `id` = :id;
				  ");
				  $db->execute(array(
					  'id' => $safe->parent_id
				  ));
			  }
			  
			  if($this->auto_approve) {
				  $message = Lang::$word->_MOD_CM_MSGOK1;
				  $tpl = App::View(FMODPATH . 'comments/snippets/'); 
				  $tpl->template = 'loadComment.tpl.php'; 
				  $tpl->data = $this->singleComment($last_id);
				  $tpl->conf = $this;
				  $json['html'] = $tpl->render(); 
			  } else {
				  $message = Lang::$word->_MOD_CM_MSGOK2;
			  }

			  $json['type'] = 'success';
			  $json['title'] = Lang::$word->SUCCESS;
			  $json['message'] = $message;
			  print json_encode($json);
			  
			  if($this->notify_new) {
				  $user = (App::Auth()->logged_in) ? App::Auth()->name : $safe->username;
				  $mailer = Mailer::sendMail();
				  $tpl = Db::run()->first(Content::eTable, array("body" . Lang::$lang . " as body", "subject" . Lang::$lang . " as subject"), array('typeid' => 'newComment'));
				  $body = str_replace(array(
					  '[LOGO]',
					  '[DATE]',
					  '[COMPANY]',
					  '[NAME]',
					  '[MESSAGE]',
					  '[PAGEURL]',
					  '[IP]',
					  '[FB]',
					  '[TW]',
					  '[SITEURL]'), array(
					  Utility::getLogo(),
					  date('Y'),
					  $core->company,
					  (App::Auth()->logged_in) ? App::Auth()->name : $safe->username,
					  $data['body'],
					  SITEURL . $safe->url,
					  Url::getIP(),
					  $core->social->facebook,
					  $core->social->twitter,
					  SITEURL), $tpl->body);
		
				  $msg = Swift_Message::newInstance()
						->setSubject($tpl->subject)
						->setTo(array($core->site_email => $core->company))
						->setFrom(array($core->site_email => $user))
						->setBody($body, 'text/html'
						);
				  $mailer->send($msg);
			  }
			  
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
  }