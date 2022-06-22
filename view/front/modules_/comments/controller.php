<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  Bootstrap::Autoloader(array(AMODPATH . 'comments/'));

  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Actions == */
  switch ($action):
      /* == Vote Up/Down == */
      case "vote":
          if(Filter::$id) :
		      $type = Validator::sanitize($_POST['type'], "alpha");
			  $vote = ($type == "down") ? 'vote_down = vote_down - 1' : 'vote_up = vote_up + 1';

			  Db::run()->pdoQuery("
				  UPDATE `" . Comments::mTable . "` 
				  SET $vote
				  WHERE id = '" . Filter::$id . "'
			  ");
			  $json['type'] = $type;
			  $json['status'] = 'success';
			  print json_encode($json);
		  endif;
      break;
      /* == Reply == */
      case "comment":
	  case "reply":
          App::Comments()->processComment();
      break;
      /* == Delete == */
	  case "delete":
		  if(App::Auth()->is_Admin()):
			  Db::run()->delete(Comments::mTable, array("id" => Filter::$id));
			  Db::run()->delete(Comments::mTable, array("comment_id" => Filter::$id));
		  endif;
      break;
  endswitch;