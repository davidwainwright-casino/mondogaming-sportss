<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'adblock/'));
  $action = Validator::request('action');

  /* == Actions == */
  switch ($action):
      /* == Capture Click == */
      case "click":
          if(Filter::$id) :
			  Db::run()->pdoQuery("
				  UPDATE `" . AdBlock::mTable . "` 
				  SET total_clicks = total_clicks + 1
				  WHERE id = " . Filter::$id . "
			  ");
		  endif;
      break;
  endswitch;