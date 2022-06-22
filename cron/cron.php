<?php
  /**
   * Cron
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2016
   * @version $Id: cron.php, v1.00 2016-02-05 10:12:05 gewa Exp $
   */
  define("_YOYO", true);
  require_once("../init.php");
  
  Cron::Run(1);