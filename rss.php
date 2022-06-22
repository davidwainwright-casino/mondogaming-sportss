<?php
  /**
   * Rss
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
 
  define("_YOYO", true);
  require_once ("init.php");

  header("Content-Type: text/xml");
  header('Pragma: no-cache');
  $html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  $html .= "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
  $html .= "<channel>\n";
  $html .= "<title><![CDATA[" . App::Core()->site_name . "]]></title>\n";
  $html .= "<link><![CDATA[" . SITEURL . "]]></link>\n";
  $html .= "<description><![CDATA[Latest 20 Rss Feeds - " . App::Core()->company . "]]></description>\n";
  $html .= "<generator>" . App::Core()->company . "</generator>\n";

  $sql = "
  SELECT 
	body" . Lang::$lang . " AS body,
	title" . Lang::$lang . " AS title,
	slug" . Lang::$lang . " AS slug,
	DATE_FORMAT(created, '%a, %d %b %Y %T GMT') AS created 
  FROM
	" . Content::pTable . " 
  WHERE active = ? 
	AND page_type = ? 
  ORDER BY created DESC 
  LIMIT 20 ;";

  $data = Db::run()->pdoQuery($sql, array(1, "normal"))->results();
  foreach ($data as $row) {
      $title = $row->title;

      $newbody = '';
      $body = $row->body;
      $string = preg_replace('/%%(.*?)%%/', '', $body);
	  $newbody = Validator::sanitize($string, "default", 350);

      $date = $row->created;
      $slug = $row->slug;
      $url = Url::url('/' . App::Core()->pageslug, $slug);

      $html .= "<item>\n";
      $html .= "<title><![CDATA[$title]]></title>\n";
      $html .= "<link><![CDATA[$url]]></link>\n";
      $html .= "<guid isPermaLink=\"true\"><![CDATA[$url]]></guid>\n";
      $html .= "<description><![CDATA[$newbody]]></description>\n";
      $html .= "<pubDate><![CDATA[$date]]></pubDate>\n";
      $html .= "</item>\n";
  }
  unset($row);
  $html .= "</channel>\n";
  $html .= "</rss>";
  echo $html;