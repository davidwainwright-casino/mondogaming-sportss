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
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'newsletter/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::post('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
  endswitch;
  
  /* == Actions == */
  switch ($action):
  endswitch;
  
   /* == Export Emails == */
  if (isset($_GET['exportEmails'])):
      header("Pragma: no-cache");
	  header('Content-Type: text/csv; charset=utf-8');
	  header('Content-Disposition: attachment; filename=Users.csv');
	  
	  $data = fopen('php://output', 'w');
	  fputcsv($data, array('Email', 'Created'));
	  
	  $result = Newsletter::exportEmails();
	  if($result):
		  foreach ($result as $row) :
			  fputcsv($data, $row);
		  endforeach;
		  fclose($data);
	  endif;
  endif;