<?php
  /**
   * Twitts Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  require_once ("oAuth.php");

  class Twitts
  {

      const RequestTimelineMentions = '1.1/statuses/mentions_timeline';
      const RequestTimelineUser = '1.1/statuses/user_timeline';
      const RequestTimelineHome = '1.1/statuses/home_timeline';
      const RequestRetweetsOfMe = '1.1/statuses/retweets_of_me';
      const RequestRetweets = '1.1/statuses/retweets';
      const RequestTweet = '1.1/statuses/show';
      const RequestSearch = '1.1/search/tweets';
      const RequestFavorites = '1.1/favorites/list';

      const CountPerObject = 0;
      const CountTotal = 1;

      private $classOptions = array();
      private $options = array();
      private $requestedData = null;
      private $loaded = false;
      private $defaultCount = 5;

      private $entityMap = array(
          'urls' => array(
              'option_flag' => 'detect_links',
              'format' => 'format_link',
              'prefix' => 'link'),
          'media' => array(
              'option_flag' => 'detect_links',
              'format' => 'format_media',
              'prefix' => 'media'),
          'user_mentions' => array(
              'option_flag' => 'detect_mentions',
              'format' => 'format_mention',
              'prefix' => 'mention'),
          'hashtags' => array(
              'option_flag' => 'detect_hashtags',
              'format' => 'format_hashtag',
              'prefix' => 'hashtag',
              ));

      /**
       * Twitts::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          $this->Config();
          $this->options = array(
              'detect_links' => true,
              'detect_mentions' => true,
              'detect_hashtags' => true,
              'use_ssl' => true,
              'cache_life' => 3600,
              'request_type' => self::RequestTimelineUser,
              'count_mode' => self::CountTotal,
              'cache_dir' => FPLUGPATH . 'twitts/cache/',
              'format' => '{tweet:text}',
              'format_retweet' => '<li class="retweet">{tweet:text}</li>',
              'format_link' => '<a href="{link:expanded_url}" class="link-tweet">{link:url}</a>',
              'format_media' => '<a href="{media:expanded_url}" class="link-media">{media:url}</a>',
              'format_mention' => '<a href="http://twitter.com/{mention:screen_name}" title="View {mention:name}\'s Profile on Twitter" class="link-mention">@{mention:screen_name}</a>',
              'format_hashtag' => '<a href="http://twitter.com/#!/search?q={hashtag:text}" title="Search &#35;{hashtag:text} on Twitter" class="link-hashtag">#{hashtag:text}</a>',
              'relative_time_keywords' => array(
                  'second',
                  'minute',
                  'hour',
                  'day',
                  'week',
                  'month',
                  'year',
                  'decade'),
              'relative_time_plural_keywords' => array(
                  Lang::$word->_SECONDS,
                  Lang::$word->_MINUTES,
                  Lang::$word->_HOURS,
                  Lang::$word->_DAYS,
                  Lang::$word->_WEEKS,
                  Lang::$word->_MONTHS,
                  Lang::$word->_YEARS,
                  'decades'),
              'relative_time_prefix' => '',
              'relative_time_suffix' => Lang::$word->AGO,
              );

          $credentials = array(
              'screen_name' => $this->username,
              'consumer_key' => $this->consumer_key,
              'consumer_secret' => $this->consumer_secret,
              'user_token' => $this->access_token,
              'user_secret' => $this->access_secret,
              );

          $this->classOptions = array_keys($this->options);
          $this->options = array_merge($this->options, $credentials);

          $required = array(
              'consumer_key',
              'consumer_secret',
              'user_token',
              'user_secret',
              );

          foreach ($required as $i => $opt) {
              if (self::option($opt) != null) {
                  unset($required[$i]);
              }
          }

          if (count($required) > 0) {
              Debug::AddMessage("errors", '<i>Exception</i>', 'missing option required for request ' . implode(" and ", $required), "session");
          }

          self::setOption('user_agent', Url::doSeo(App::Core()->company));
          File::makeDirectory(self::option('cache_dir'));
      }


      /**
       * Twitts::Config()
       * 
       * @return
       */
      private function Config()
      {

          $row = File::readIni(APLUGPATH . 'twitts/config.ini');
          $this->username = Utility::decode($row->twitts->username);
          $this->consumer_key = Utility::decode($row->twitts->consumer_key);
          $this->consumer_secret = Utility::decode($row->twitts->consumer_secret);
          $this->access_token = Utility::decode($row->twitts->access_token);
          $this->access_secret = Utility::decode($row->twitts->access_secret);
          $this->counter = $row->twitts->counter;
          $this->speed = $row->twitts->speed;
          $this->show_image = $row->twitts->show_image;
          $this->timeout = $row->twitts->timeout;

          return ($row) ? $row : 0;

      }

      /**
       * Twitts::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_TW_TITLE;
          $tpl->template = 'admin/plugins_/twitts/view/index.tpl.php';
      }

      /**
       * Twitts::processConfig()
       * 
       * @return
       */
	  public function processConfig()
	  {
	
		  $rules = array(
			  'consumer_key' => array('required|string', Lang::$word->_PLG_TW_KEY),
			  'consumer_secret' => array('required|string', Lang::$word->_PLG_TW_SECRET),
			  'access_token' => array('required|string', Lang::$word->_PLG_TW_TOKEN),
			  'access_secret' => array('required|string', Lang::$word->_PLG_TW_TSECRET),
			  'username' => array('required|string', Lang::$word->_PLG_TW_USER),
			  'show_image' => array('required|numeric', Lang::$word->_PLG_TW_SHOW_IMG),
			  'counter' => array('required|numeric|min_len,1|max_len,2', Lang::$word->_PLG_TW_COUNT),
			  'speed' => array('required|numeric|min_len,3|max_len,4', Lang::$word->_PLG_TW_SHOW_IMG),
			  'timeout' => array('required|numeric|min_len,4|max_len,5', Lang::$word->_PLG_TW_SHOW_IMG),
			  );
	
		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
	
		  if (empty(Message::$msgs)) {
			  $data = array('twitts' => array(
					  'username' => Utility::encode($safe->username),
					  'consumer_key' => Utility::encode($safe->consumer_key),
					  'consumer_secret' => Utility::encode($safe->consumer_secret),
					  'access_token' => Utility::encode($safe->access_token),
					  'access_secret' => Utility::encode($safe->access_secret),
					  'counter' => $safe->counter,
					  'speed' => $safe->speed,
					  'show_image' => $safe->show_image,
					  'timeout' => $safe->timeout,
					  ));
	
			  Message::msgReply(File::writeIni(APLUGPATH . 'twitts/config.ini', $data), 'success', Lang::$word->_PLG_TW_UPDATED);
			  Logger::writeLog(Lang::$word->_PLG_TW_UPDATED);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Twitts::option()
       * 
       * @param mixed $name
       * @return
       */
      public function option($name)
      {
          return $this->options[$name] ? : null;
      }

      /**
       * Twitts::setOption()
       * 
       * @param mixed $name
       * @param mixed $value
       * @return
       */
      public function setOption($name, $value)
      {
          $this->options[$name] = $value;
          $this->loaded = false;
      }

      /**
       * Twitts::GetRequestData()
       * 
       * @return
       */
      public function GetRequestData()
      {
          if (!self::isLoaded())
              self::loadRequest();

          return $this->requestedData;
      }

      /**
       * Twitts::PrintFeed()
       * 
       * @param mixed $callback
       * @return
       */
      public function PrintFeed($callback = null)
      {
          if (!self::isLoaded())
              self::loadRequest();

          $iterator = self::getRequestDataIterator();

          if (!isset($iterator) || !is_array($iterator))
              Debug::AddMessage("errors", '<i>Exception</i>', 'unsupported request type for <code>->PrintFeed()</code> (<em>' . self::option('request_type') . '</em>)', "session");

          $callable = is_callable($callback);

          $total = array();
          $max = $this->counter;

          foreach ($iterator as $i => $tweet) {
              if (empty($tweet))
                  continue;

              if (self::option('count_mode') == self::CountPerObject) {
                  $user = $tweet[$tweet['is_retweet'] ? 'retweeter' : 'user']['screen_name'];

                  if (!isset($total[$user])) {
                      $total[$user] = 1;
                  } else {
                      $total[$user]++;
                  }
                  if ($total[$user] > $max) {
                      continue;
                  }
              } else {
                  if ($i + 1 > $max) {
                      break;
                  }
              }

              if ($callable) {
                  call_user_func($callback, $tweet);
              } else {
                  echo self::formatString(self::option($tweet['is_retweet'] ? 'format_retweet' : 'format'), $tweet, 'tweet');
              }
          }

      }

      /**
       * Twitts::renderTweets()
       * 
       * @param mixed $iterator
       * @return
       */
      private function renderTweets(&$iterator)
      {
          foreach ($iterator as $i => &$tweet) {
              if (empty($tweet))
                  continue;

              if (isset($tweet['retweeted_status'])) {
                  $user = $tweet['user'];
                  $tweet = $tweet['retweeted_status'];
                  $tweet['retweeter'] = $user;
                  $tweet['is_retweet'] = true;
              } else {
                  $tweet['is_retweet'] = false;
              }

              $tweet['original_text'] = $tweet['text'];
              $tweet['relative_time'] = self::relativeTime(strtotime($tweet['created_at']));
              $tweet['twitter_link'] = 'https://twitter.com/' . $tweet['user']['id_str'] . '/status/' . $tweet['id_str'];
              $tweet['tweet_index'] = $i;

              $entities = $tweet['entities'];

              $formatQueue = array();

              foreach ($entities as $type => $group) {
                  if (!isset($this->entityMap[$type]))
                      continue;

                  $map = $this->entityMap[$type];

                  if (self::option($map['option_flag'])) {
                      foreach ($group as $entity) {
                          $entity['format'] = self::formatString(self::option($map['format']), $entity, $map['prefix']);

                          $formatQueue[] = $entity;
                      }
                  }
              }

              usort($formatQueue, function ($a, $b)
              {
                  return $b['indices'][0] - $a['indices'][0]; }
              );

              foreach ($formatQueue as $entity) {
                  list($start, $end) = $entity['indices'];

                  $tweet['text'] = self::mbSubstringReplace($tweet['text'], $entity['format'], $start, $end - $start);
              }
          }

          self::sortTweets();
      }

      /**
       * Twitts::formatString()
       * 
       * @param mixed $input
       * @param mixed $obj
       * @param mixed $prefix
       * @return
       */
      public function formatString($input, $obj, $prefix)
      {
          return preg_replace_callback("/\{$prefix:([a-z-:0-9_]+)}/i", function ($match)use ($obj)
          {
              $dimensions = explode(':', $match[1]); 
			  if (!isset($obj[$dimensions[0]]))return $match[0]; $replacement = $obj[$dimensions[0]]; 
			  for ($i = 1; $i < count($dimensions); $i++) {
                  if (!isset($replacement[$dimensions[$i]])) {
				  return $match[0]; 
				  } else 
                  $replacement = $replacement[$dimensions[$i]]; 
				  }

          return is_array($replacement) ? $match[0] : $replacement; 
		  }
      , $input);
      }

	  /**
	   * Twitts::relativeTime()
	   * 
	   * @param mixed $timestamp
	   * @return
	   */
	  private static function mbSubstringReplace($string, $replacement, $start, $length = null)
	  {
		  if ($length == null)
			  return mb_substr($string, 0, $start) . $replacement;
	
		  return mb_substr($string, 0, $start) . $replacement . mb_substr($string, $start + $length);
	  }
	
	  /**
	   * Twitts::relativeTime()
	   * 
	   * @param mixed $timestamp
	   * @return
	   */
	  private function relativeTime($timestamp)
	  {
		  $difference = time() - $timestamp;
	
		  $periods = array(self::option('relative_time_keywords'), self::option('relative_time_plural_keywords'));
	
		  $lengths = array(
			  60,
			  60,
			  24,
			  7,
			  4.35,
			  12,
			  10);
	
		  for ($i = 0; $i < 7 && $difference >= $lengths[$i]; $i++)
			  $difference /= $lengths[$i];
	
		  $difference = round($difference);
	
		  return implode(' ', array(
			  self::option('relative_time_prefix'),
			  $difference,
			  $periods[($difference != 1) ? 1 : 0][$i],
			  self::option('relative_time_suffix')));
	  }
	
	  /**
	   * Twitts::loadRequest()
	   * 
	   * @return
	   */
	  private function loadRequest()
	  {
		  $auth = new tmhOAuth($this->options);
	
		  if (self::cacheFileExists())
			  self::cacheLoad($auth);
		  else
			  self::apiLoad($auth);
	
		  self::renderTweets(self::getRequestDataIterator());
	
		  $this->loaded = true;
	  }
	
	  /**
	   * Twitts::getRequestDataIterator()
	   * 
	   * @return
	   */
	  private function &getRequestDataIterator()
	  {
		  switch (self::option('request_type')) {
			  case self::RequestSearch:
				  return $this->requestedData['statuses'];
				  break;
			  default:
				  return $this->requestedData;
		  }
	  }
	
	  /**
	   * Twitts::sortTweets()
	   * 
	   * @return
	   */
	  private function sortTweets()
	  {
		  usort(self::getRequestDataIterator(), function ($a, $b)
		  {
			  if (empty($a) || empty($b))return 0; return strtotime($b['created_at']) - strtotime($a['created_at']); }
		  );
	  }
	
	  /**
	   * Twitts::hasMultiIdentifiers()
	   * 
	   * @return
	   */
	  private function hasMultiIdentifiers()
	  {
		  $type = self::option('request_type');
	
		  $supportedTypes = array(
			  self::RequestTimelineUser,
			  self::RequestRetweets,
			  self::RequestTweet,
			  self::RequestFavorites,
			  self::RequestSearch);
	
		  $supportsType = in_array($type, $supportedTypes);
	
		  $identifier = self::cacheGetRequestIdentifier();
		  $identifierValue = self::option($identifier);
	
		  $is_array = is_array($identifierValue);
	
		  if (!$supportsType && $is_array)
			  Debug::AddMessage("errors", '<i>Exception</i>', 'unsupported option for multiple values (<em>' . $identifier . '</em>) for request type <em>' . $type . '</em>', "session")
				  ;
	
		  return $is_array;
	  }
	
	  /**
	   * Twitts::isLoaded()
	   * 
	   * @return
	   */
	  private function isLoaded()
	  {
		  return $this->loaded;
	  }
	
	  /**
	   * Twitts::apiLoad()
	   * 
	   * @param mixed $auth
	   * @return
	   */
	  private function apiLoad($auth)
	  {
		  $identifier = self::cacheGetRequestIdentifier();
		  $params = self::apiGetParams();
		  $cache = array();
	
		  $this->requestedData = array();
	
		  if ($identifier == null) {
			  $request = self::apiMakeRequest($auth, $params);
	
			  $this->requestedData = $request;
	
			  $cache = self::cacheWrapData($request);
		  } else {
			  if (self::hasMultiIdentifiers()) {
				  $identifiers = $params[$identifier];
	
				  foreach ($identifiers as $id) {
					  $params[$identifier] = $id;
	
					  $request = self::apiMakeRequest($auth, $params);
	
					  $this->requestedData = array_merge_recursive($this->requestedData, $request);
	
					  $cache[strtolower($id)] = self::cacheWrapData($request);
				  }
			  } else {
				  $request = self::apiMakeRequest($auth, $params);
	
				  $this->requestedData = $request;
	
				  $cache[strtolower($params[$identifier])] = self::cacheWrapData($request);
			  }
		  }
	
		  self::cacheSave($cache);
	  }
	
	  /**
	   * Twitts::apiMakeRequest()
	   * 
	   * @param mixed $auth
	   * @param mixed $params
	   * @return
	   */
	  private function apiMakeRequest($auth, $params)
	  {
		  $auth->request('GET', $auth->url(self::option('request_type')), $params);
	
		  $data = json_decode($auth->response['response'], true);
	
		  if (isset($data['errors'])) {
			  $errors = '';
	
			  foreach ($data['errors'] as $error)
				  $errors .= ' ' . $error['message'] . '; code ' . $error['code'] . '.';
	
			  Debug::AddMessage("errors", '<i>Exception</i>', 'the following errors were reported by Twitter - ' . $errors, "session");
		  }
	
		  if (self::option('request_type') != self::RequestSearch && self::isArrayAssociative($data))
			  $data = array($data);
	
		  return $data;
	  }
	
	
	  /**
	   * Twitts::apiGetParams()
	   * 
	   * @return
	   */
	  private function apiGetParams()
	  {
		  return array_diff_key($this->options, array_flip($this->classOptions));
	  }
	
	  /**
	   * Twitts::cacheLoad()
	   * 
	   * @param mixed $auth
	   * @return
	   */
	  private function cacheLoad($auth)
	  {
		  $cache = self::cacheMakeRequest();
		  $params = self::apiGetParams();
		  $identifier = self::cacheGetRequestIdentifier();
	
		  if ($identifier == null) {
			  if (self::cacheIsExpired($cache, null)) {
				  $request = self::apiMakeRequest($auth, $params);
	
				  $this->requestedData = $request;
	
				  $cache = self::cacheWrapData($request);
	
				  self::cacheSave($cache);
			  } else {
				  $this->requestedData = $cache['data'];
			  }
		  } else {
			  if (self::hasMultiIdentifiers()) {
				  list($identifiers, $changed) = array($params[$identifier], 0);
	
				  $this->requestedData = array();
	
				  foreach ($identifiers as $id) {
					  $params[$identifier] = $id;
	
					  if (self::cacheIsExpired($cache, $id)) {
						  $changed++;
	
						  $request = self::apiMakeRequest($auth, $params);
	
						  $this->requestedData = array_merge_recursive($this->requestedData, $request);
	
						  $cache[strtolower($id)] = self::cacheWrapData($request);
					  } else {
						  $this->requestedData = array_merge_recursive($this->requestedData, $cache[strtolower($id)]['data']);
					  }
				  }
	
				  if ($changed > 0)
					  self::cacheSave($cache);
			  } else {
				  if (self::cacheIsExpired($cache, $params[$identifier])) {
					  $request = self::apiMakeRequest($auth, $params);
	
					  $this->requestedData = $request;
	
					  $cache[strtolower($params[$identifier])] = self::cacheWrapData($request);
	
					  self::cacheSave($cache);
				  } else {
					  $this->requestedData = $cache[strtolower($params[$identifier])]['data'];
				  }
			  }
		  }
	  }
	
	  /**
	   * Twitts::cacheMakeRequest()
	   * 
	   * @return
	   */
	  private function cacheMakeRequest()
	  {
		  return json_decode(file_get_contents(self::cacheGetFileName()), true);
	  }
	
	  /**
	   * Twitts::cacheSave()
	   * 
	   * @param mixed $data
	   * @return
	   */
	  private function cacheSave($data)
	  {
		  file_put_contents(self::cacheGetFileName(), json_encode($data));
	  }
	
	  /**
	   * Twitts::cacheWrapData()
	   * 
	   * @param mixed $data
	   * @return
	   */
	  private function cacheWrapData($data)
	  {
		  return array('time' => time(), 'data' => $data);
	  }
	
	  /**
	   * Twitts::cacheGetRequestIdentifier()
	   * 
	   * @return
	   */
	  private function cacheGetRequestIdentifier()
	  {
		  switch (self::option('request_type')) {
			  case self::RequestTimelineUser:
			  case self::RequestFavorites:
				  $queue = array('screen_name', 'user_id');
	
				  foreach ($queue as $i) {
					  if (self::option($i) != null)
						  return $i;
				  }
	
				  Debug::AddMessage("errors", '<i>Exception</i>', 'missing option required for request ' . implode(' or ', $queue), "session");
				  break;
			  case self::RequestRetweets:
			  case self::RequestTweet:
				  if (self::option('id') == null)
					  Debug::AddMessage("errors", '<i>Exception</i>', 'missing option required for request id', "session");
	
				  return 'id';
			  case self::RequestSearch:
				  if (self::option('q') == null)
					  Debug::AddMessage("errors", '<i>Exception</i>', 'missing option required for request q', "session");
	
				  return 'q';
				  break;
			  default:
				  return null;
		  }
	  }
	
	  /**
	   * Twitts::cacheFileExists()
	   * 
	   * @return
	   */
	  function cacheFileExists()
	  {
		  return file_exists(self::cacheGetFileName());
	  }
	
	  /**
	   * Twitts::cacheIsExpired()
	   * 
	   * @param mixed $data
	   * @param mixed $identifier
	   * @return
	   */
	  private function cacheIsExpired($data, $identifier)
	  {
		  $obj = $identifier == null ? $data : @$data[strtolower($identifier)];
	
		  return !isset($obj) || (time() - $obj['time'] >= self::option('cache_life'));
	  }
	
	  /**
	   * Twitts::cacheGetFileName()
	   * 
	   * @return
	   */
	  private function cacheGetFileName()
	  {
		  return self::option('cache_dir') . preg_replace('/(\/|\\\)/', '-', self::option('screen_name')) . '.cache';
	  }
	
	  /**
	   * Twitts::isArrayAssociative()
	   * 
	   * @param mixed $array
	   * @return
	   */
	  private function isArrayAssociative($array)
	  {
		  return array_keys($array) !== range(0, count($array) - 1);
	  }
  }