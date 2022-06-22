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
  
  Bootstrap::Autoloader(array(AMODPATH . 'gallery/'));

  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Actions == */
  switch ($action):
      /* == Like Image == */
      case "like":
          if(Filter::$id) :
			  Db::run()->pdoQuery("
				  UPDATE `" . Gallery::dTable . "` 
				  SET likes = likes + 1
				  WHERE id = '" . Filter::$id . "'
			  ");
		  endif;
      break;
      /* == Watermark Image == */
      case "watermark":
	      if($dir = Validator::get('dir') and $thumb = Validator::get('thumb')):
		     if(File::exists($old = FMODPATH . Gallery::GALDATA . $dir. '/' . $thumb)):
			      $new = FMODPATH . Gallery::GALDATA . $dir. '/w_' . $thumb;
				  try {
					  $img = new Image($old);
					  $img->overlay(UPLOADS . '/watermark.png', 'bottom right', .35, -30, -30)->save($new);
					  $image = imagecreatefromjpeg($new);

					  header("Content-type: image/jpeg");
					  header('Content-Disposition: inline; filename=' . $new);
					  imagejpeg($image);
					  imagedestroy($image);
					  exit;
				  }
				  catch (exception $e) {
					  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
				  }
			 endif;
		  endif;
      break;
  endswitch;