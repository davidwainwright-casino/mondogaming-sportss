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
  
  Bootstrap::Autoloader(array(APLUGPATH . 'poll/'));
  $action = Validator::request('action');

  /* == Actions == */
  switch ($action):
      /* == Vote == */
      case "vote":
          if(Filter::$id) :
		      if(App::Poll()->updatePollResult(Filter::$id)):
				  $json['type'] = "success";
			  else:
				  $json['type'] = "error";
			  endif;
		  else:
			  $json['type'] = "error";
		  endif;
		  print json_encode($json);
      break;
  endswitch;