<?php
  /**
   * Init
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  $BASEPATH = str_replace("init.php", "", realpath(__FILE__));
  define("BASEPATH", $BASEPATH);
  
  $configFile = BASEPATH . "lib/config.ini.php";
  if (file_exists($configFile)) {
      require_once($configFile);
	  if(file_exists(BASEPATH . 'setup/')) {
		  print '<div style="position:absolute;widht:50%;top:50%;left:50%;transform: translate(-50%, -50%);padding:2rem;color:#fff;font-family:arial;background-color: #ef5350;box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.14), 0px 7px 10px -5px rgba(244, 67, 54, 0.4);">Please remove <strong>/setup/</strong> directory first!</div>';
		  exit;
	  }
  } else {
      header("Location: setup/");
	  exit;
  }

  require_once (BASEPATH . "bootstrap.php");
  Bootstrap::init();
  wError::run();
  Filter::run();
  Lang::Run();
  Debug::run();
  Dynamic_Lang::run();

  define("ADMIN", BASEPATH . "admin/");
  define("FRONT", BASEPATH . "front/");
  
  $dir = (App::Core()->site_dir) ? '/' . App::Core()->site_dir : '';
  $url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
  $site_url = Url::protocol() . "://" . $url;
  
  define("SITEURL", $site_url);
  define("UPLOADURL", SITEURL . '/uploads');
  define("UPLOADS", BASEPATH . 'uploads');
  
  define("ADMINURL", SITEURL . '/admin');
  define("ADMINVIEW", SITEURL . '/view/admin');
  define("ADMINBASE", BASEPATH . 'view/admin');
  
  define("FRONTVIEW", SITEURL . '/view/front');
  define("FRONTBASE", BASEPATH . 'view/front');

  define("BUILDERVIEW", ADMINVIEW . '/builder');
  define("BUILDERBASE", ADMINBASE . '/builder');

  define("BUILDERTHEME", BUILDERVIEW . '/themes/');
  
  define("THEMEURL", FRONTVIEW . '/themes/' . App::Core()->theme);
  define("THEMEBASE", FRONTBASE . '/themes/' . App::Core()->theme);
  
  define("AMODPATH", ADMINBASE . "/modules_/");
  define("AMODULEURL", ADMINVIEW . "/modules_/");
  define("APLUGPATH", ADMINBASE . "/plugins_/");
  define("APLUGINURL", ADMINVIEW . "/plugins_/");

  define("FMODPATH", FRONTBASE . "/modules_/");
  define("FMODULEURL", FRONTVIEW . "/modules_/");
  define("FPLUGPATH", FRONTBASE . "/plugins_/");
  define("FPLUGINURL", FRONTVIEW . "/plugins_/");