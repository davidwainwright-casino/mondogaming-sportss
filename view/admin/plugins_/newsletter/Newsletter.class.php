<?php
  /**
   * Newsletter Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');


  class Newsletter
  {

      const mTable = 'plug_newsletter';


      /**
       * Newsletter::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }


      /**
       * Newsletter::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = Db::run()->count(self::mTable);
          $tpl->title = Lang::$word->_PLG_NSL_TITLE;
          $tpl->template = 'admin/plugins_/newsletter/view/index.tpl.php';
      }

      /**
       * Newsletter::process()
       * 
       * @return
       */
      public function process()
      {
          $rules = array('email' => array('required|email', Lang::$word->M_EMAIL), );

          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
/*
          if (!empty($safe->email)) {
              if (self::emailExists($safe->email) && $_POST['active'] == 1) {
                  Message::$msgs['email'] = Lang::$word->M_EMAIL_R2;
              }

              if (!self::emailExists($safe->email) && $_POST['active'] == 0) {
                  Message::$msgs['email'] = Lang::$word->M_EMAIL_R4;
              }
          }
*/
          if (empty(Message::$msgs)) {
			  if (self::emailExists($safe->email)) {
                  Db::run()->delete(self::mTable, array("email" => $safe->email));
                  $json['message'] = Lang::$word->_PLG_NSL_UNSUBOK;
			  } else {
                  Db::run()->insert(self::mTable, array("email" => $safe->email));
                  $json['message'] = Lang::$word->_PLG_NSL_SUBOK;
			  }
			  /*
              if (intval($_POST['active']) == 1) {
                  Db::run()->insert(self::mTable, array("email" => $safe->email));
                  $json['message'] = Lang::$word->_PLG_NSL_SUBOK;
              } else {
                  Db::run()->delete(self::mTable, array("email" => $safe->email));
                  $json['message'] = Lang::$word->_PLG_NSL_UNSUBOK;
              }*/
              $json['type'] = 'success';
              $json['title'] = Lang::$word->SUCCESS;
              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Newsletter::emailExists()
       * 
       * @param mixed $email
       * @return
       */
      public static function emailExists($email)
      {
          $row = Db::run()->first(self::mTable, array('email'), array('email' => $email));

          return ($row) ? $row : 0;
      }

      /**
       * Newsletter::exportEmails()
       * 
       * @return
       */
      public static function exportEmails()
      {
          $sql = "
		  SELECT 
			email,
			created
		  FROM
			`" . self::mTable . "`
		  ORDER BY created DESC;";

          $rows = Db::run()->pdoQuery($sql)->results();
          $array = json_decode(json_encode($rows), true);

          return $array ? $array : 0;
      }
  }