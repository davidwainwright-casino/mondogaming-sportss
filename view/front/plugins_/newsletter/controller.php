<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
  require_once ("../../../../init.php");

  Bootstrap::Autoloader(array(APLUGPATH . 'newsletter/'));

  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Actions == */
  switch ($action):
      /* == Process == */
      case "processNewsletter":
          App::Newsletter()->process();
      break;
  endswitch;