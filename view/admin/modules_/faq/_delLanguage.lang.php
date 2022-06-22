<?php
  /**
   * Add Language
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  Bootstrap::Autoloader(array(AMODPATH . 'faq/'));

  //mod_faq
  Db::run()->pdoQuery("
  ALTER TABLE `" . Faq::mTable . "` 
	DROP COLUMN question_$abbr,
	DROP COLUMN answer_$abbr;");

  //mod_faq_categories
  Db::run()->pdoQuery("
  ALTER TABLE `" . Faq::cTable . "` 
	DROP COLUMN name_$abbr;");