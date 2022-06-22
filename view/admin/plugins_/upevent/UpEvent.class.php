<?php
  /**
   * UpEvent Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class UpEvent
  {

      const mTable = "mod_events";
	  

      /**
       * UpEvent::__construct()
       * 
       * @return
       */
      public function __construct()
      {
		  $this->Config();
	  }

      /**
       * UpEvent::Config()
       * 
       * @return
       */
      private function Config()
      {

          $row = File::readIni(APLUGPATH . 'upevent/config.ini');
          $this->event_id = $row->upevent->event_id;

          return ($row) ? $this : 0;
      }
	  
      /**
       * UpEvent::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_UE_TITLE1;
		  $tpl->data = $this->Config();
		  $tpl->events = Db::Run()->select(self::mTable, array("id", "title" . Lang::$lang), array("active" => 1))->results();
          $tpl->template = 'admin/plugins_/upevent/view/index.tpl.php';
      }

      /**
       * UpEvent::processConfig()
       * 
       * @return
       */
	  public function processConfig()
	  {
	
          if (!array_key_exists('event_id', $_POST)) {
              Message::$msgs['event_id'] = LANG::$word->_PLG_UE_ERR;
          }
		  
		  if (empty(Message::$msgs)) {
			  $data = array('upevent' => array(
					  'event_id' => Utility::implodeFields($_POST['event_id']),
					  ));
	
			  Message::msgReply(File::writeIni(APLUGPATH . 'upevent/config.ini', $data), 'success', Lang::$word->_PLG_UE_UPDATED);
			  Logger::writeLog(Lang::$word->_PLG_UE_UPDATED);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
	  /**
	   * UpEvent::getEvents()
	   * 
	   * @return
	   */
	  public function getEvents()
	  {
		  
		  $sql = "SELECT id, title" . Lang::$lang . " as title FROM `" . self::mTable . "` WHERE id IN(" . $this->event_id . ");";
		  $row = Db::Run()->pdoQuery($sql)->results();
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * UpEvent::Render()
	   * 
	   * @return
	   */
	  public function Render()
	  {
		  
		  $sql = "
		  SELECT 
			id,
			date_start,
			date_end,
			time_start,
			time_end,
			color,
			venue" . Lang::$lang . " AS venue,
			title" . Lang::$lang . " AS title,
			body" . Lang::$lang . " AS body 
		  FROM
			`" . self::mTable . "` 
		  WHERE id IN (" . $this->event_id . ");";
		  $row = Db::Run()->pdoQuery($sql)->results();
		  
		  return ($row) ? $row : 0;
	  }
  }
