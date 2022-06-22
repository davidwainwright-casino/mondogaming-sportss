<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once ("../../../../init.php");
  
  Bootstrap::Autoloader(array(AMODPATH . 'timeline/'));
?>
<?php if($id = Validator::get('item_id')):?>
<?php if($conf = App::Timeline()->render(Utility::decode($id))):?>
<?php
  switch ($conf->type) {
      case "blog":
	      Bootstrap::Autoloader(array(AMODPATH . 'blog/'));
          $sql = "
		  SELECT 
			id,
			images,
			thumb,
			YEAR(created) AS year,
			MONTH(created) AS month,
			created as timedate,
			slug" . Lang::$lang . " AS slug,
			title" . Lang::$lang . " AS title,
			body" . Lang::$lang . " AS content
		  FROM
			`" . Blog::mTable . "` 
		  WHERE expire <= NOW()
		  AND active = ?
		  ORDER BY created DESC 
		  LIMIT " . $conf->limiter;
		  
          if ($data = Db::run()->pdoQuery($sql, array(1))->results()) {
              $json_response = array();
              $array = array();
			  $core = App::Core();
			  
              foreach ($data as $row) {
                  $imagedata = Utility::jSonToArray($row->images);

                  $array['year'] = $row->year;
                  $array['month'] = $row->month;
                  $array['timedate'] = $row->timedate;
                  $array['edate'] = Date::doDate("long_date", $row->timedate);
                  $array['title'] = $row->title;
                  $array['content'] = Validator::sanitize($row->content, "default", 250);
                  $array['more'] = Url::url('/' . $core->modname['blog'], $row->slug);
                  $array['display_mode'] = 'blog_post';

                  if ($imagedata) {
                      $images = array();
                      foreach ($imagedata as $img) {
						  $images[] = FMODULEURL . Blog::BLOGDATA . $row->id . '/thumbs/' . $img->name;
                          $array['thumb'] = $images;
                      }
                  } else {
					  $array['thumb'] = $row->thumb ? array(FMODULEURL . Blog::BLOGDATA . $row->id . '/thumbs/' . $row->thumb) : '';
				  }
                  array_push($json_response, $array);
              }

              $json['type'] = 'success';
              $json['entries'] = $json_response;
		  }
          break;

      case "event":
	      Bootstrap::Autoloader(array(AMODPATH . 'events/'));
          $sql = "
		  SELECT 
			YEAR(date_start) AS year,
			MONTH(date_start) AS month,
			CONCAT(date_start,' ',time_start) as timedate,
			title" . Lang::$lang . " AS title,
			body" . Lang::$lang . " AS content,
			venue" . Lang::$lang . " AS venue ,
			contact_person,
			contact_email,
			contact_phone,
			color
		  FROM
			`" . Events::mTable . "` 
		  WHERE active = ? 
		  ORDER BY date_start DESC 
		  LIMIT " . $conf->limiter;
		  
          if ($data = Db::run()->pdoQuery($sql, array(1))->results()) {
              $json_response = array();
              $array = array();
			  
              foreach ($data as $row) {
                  $array['year'] = $row->year;
                  $array['month'] = $row->month;
                  $array['timedate'] = $row->timedate;
                  $array['edate'] = Date::doDate("long_date", $row->timedate);
                  $array['title'] = $row->title;
                  $array['content']['body'] = Url::out_url($row->content);
				  $array['content']['venue'] = $row->venue;
				  $array['content']['person'] = $row->contact_person;
				  $array['content']['email'] = $row->contact_email;
				  $array['content']['phone'] = $row->contact_phone;
				  $array['content']['color'] = $row->color;
				  
				  $array['more'] = null;
                  $array['display_mode'] = 'event';
				  
                  array_push($json_response, $array);
              }

              $json['type'] = 'success';
              $json['entries'] = $json_response;
		  }
          break;

      case "portfolio":
	      Bootstrap::Autoloader(array(AMODPATH . 'portfolio/'));
          $sql = "
		  SELECT 
			id,
			thumb,
			YEAR(created) AS year,
			MONTH(created) AS month,
			created as timedate,
			slug" . Lang::$lang . " AS slug,
			title" . Lang::$lang . " AS title,
			body" . Lang::$lang . " AS content
		  FROM
			`" . Portfolio::mTable . "` 
		  ORDER BY created DESC 
		  LIMIT " . $conf->limiter;
		  
          if ($data = Db::run()->pdoQuery($sql)->results()) {
              $json_response = array();
              $array = array();
			  $core = App::Core();
			  
              foreach ($data as $row) {
                  $array['year'] = $row->year;
                  $array['month'] = $row->month;
                  $array['timedate'] = $row->timedate;
                  $array['edate'] = Date::doDate("short_date", $row->timedate);
                  $array['title'] = $row->title;
                  $array['content'] = Validator::sanitize($row->content, "default", 250);
				  $array['thumb'] = $row->thumb ? array(FMODULEURL . 'portfolio/data/' . $row->id . '/thumbs/' . $row->thumb) : '';
				  
				  $array['more'] = Url::url('/' . $core->modname['portfolio'], $row->slug);
                  $array['display_mode'] = 'blog_post';
				  
                  array_push($json_response, $array);
              }

              $json['type'] = 'success';
              $json['entries'] = $json_response;
		  }
          break;

      case "custom":
          $sql = "
		  SELECT 
			*,
			YEAR(created) AS year,
			MONTH(created) AS month,
			created AS timedate,
			title" . Lang::$lang . " AS title,
			body" . Lang::$lang . " AS content 
		  FROM
			`" . Timeline::dTable . "` 
		  WHERE tid = ? 
		  ORDER BY created DESC 
		  LIMIT " . $conf->limiter;

          if ($data = Db::run()->pdoQuery($sql, array($conf->id))->results()) {
              $json_response = array();
              $array = array();

              foreach ($data as $row) {
                  $imagedata = Utility::jSonToArray($row->images);

                  $array['year'] = $row->year;
                  $array['month'] = $row->month;
                  $array['timedate'] = $row->timedate;
                  $array['edate'] = Date::doDate("long_date", $row->timedate);
                  $array['title'] = $row->title;
                  $array['content'] = Url::out_url($row->content);
                  $array['more'] = $row->readmore;
                  $array['display_mode'] = $row->type;

                  if ($imagedata) {
                      $images = array();
                      foreach ($imagedata as $img) {
                          $images[] = UPLOADURL . '/' . $img;
                          $array['thumb'] = $images;
                      }
                  }

                  if ($row->type == "iframe") {
                      $array['url'] = $row->dataurl;
                      $array['height'] = $row->height;
                  }
                  array_push($json_response, $array);
              }

              $json['type'] = 'success';
			  $json['entries'] = $json_response;
          }

          break;
		  
      case "rss":
          Bootstrap::Autoloader(array(APLUGPATH . 'rss/'));
          $items = App::Rss()->render($conf->rssurl, $conf->limiter);
		  $array = array();
		  $json_response = array();
		  
		  if($items) {
			  for($x = 0; $x < $items[1]; $x++) {
				  $array['title'] = str_replace(' & ', ' &amp; ', $items[0][$x]['title']);
				  $array['content'] = $items[0][$x]['desc'];
				  $date = date('Y-m-d H:i:s', strtotime($items[0][$x]['date']));
				  $array['year'] = Date::doDate("yyyy", $date);
                  $array['month'] = Date::doDate("MM", $date);
                  $array['timedate'] = Date::doDate("yyyy-MM-dd", $date);
                  $array['edate'] = Date::doDate("long_date", $date);

				  $array['more'] = $items[0][$x]['link'];
				  $array['display_mode'] = 'blog_post';
				  
				  array_push($json_response, $array);
			  }
			  
              $json['type'] = 'success';
			  $json['entries'] = $json_response;
		  }
	  
          break;
  }
  $json['maxitems'] = ($conf->maxitems) ? $conf->maxitems : null;
  $json['showmore'] = $conf->showmore;
  $json['colmode'] = $conf->colmode;
  print json_encode($json);
?>
<?php endif;?>
<?php endif;?>