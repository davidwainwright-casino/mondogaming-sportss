<?php
  /**
   * Meta Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  class parseMeta
  {

      public static $contents = '';
      const encoding = 'UTF-8';

      // minimum word length for inclusion into the single word metakeys
      const min_word_length = 4;
      const min_word_occur = 2;

      // minimum word length for inclusion into the 2-word phrase metakeys
      const min_2words_length = 4;
      const min_2words_phrase_length = 10;
      const min_2words_phrase_occur = 3;

      // minimum phrase length for inclusion into the 2-word phrase metakeys
      const min_3words_length = 4;
      const min_3words_phrase_length = 12;
      const min_3words_phrase_occur = 4;
	  
	  private static $instance; 


      /**
       * parseMeta::__construct()
       * 
       * @param mixed $text
       * @return
       */
      private function __construct($text)
      {
          mb_internal_encoding(self::encoding);
          self::$contents = self::process_text(Validator::truncate($text, 150));
      }


      /**
       * parseMeta::instance()
       * 
       * @return
       */
	  public static function instance($text){
		  if (!self::$instance){ 
			  self::$instance = new parseMeta(Validator::truncate($text, 150)); 
		  } 
	  
		  return self::$instance;  
	  }

      /**
       * parseMeta::metaText()
       * 
       * @param mixed $text
       * @return
       */
      public static function metaText($text)
      {
          return Validator::sanitize($text, "string", 150);
      }


      /**
       * parseMeta::get_keywords()
       * 
       * @return
       */
      public static function get_keywords()
      {

          if (self::$contents === false)
              return '';

          $onew_arr = self::parse_words();

          $twow_arr = self::parse_2words();

          $thrw_arr = self::parse_3words();

          // remove 2-word phrases if same single words exist
          if ($onew_arr !== false && $twow_arr !== false) {
              $cnt = count($onew_arr);
              for ($i = 0; $i < $cnt - 1; $i++) {
                  foreach ($twow_arr as $key => $phrase) {
                      if ($onew_arr[$i] . ' ' . $onew_arr[$i + 1] === $phrase)
                          unset($twow_arr[$key]);
                  }
              }
          }

          // remove 3-word phrases if same single words exist
          if ($onew_arr !== false && $thrw_arr !== false) {
              $cnt = count($onew_arr);
              for ($i = 0; $i < $cnt - 2; $i++) {
                  foreach ($thrw_arr as $key => $phrase) {
                      if ($onew_arr[$i] . ' ' . $onew_arr[$i + 1] . ' ' . $onew_arr[$i + 2] === $phrase)
                          unset($thrw_arr[$key]);
                  }
              }
          }

          // remove duplicate ENGLISH plural words
          if (Lang::$lang == 'en') {
              if ($onew_arr !== false) {
                  $cnt = count($onew_arr);
                  for ($i = 0; $i < $cnt - 1; $i++) {
                      for ($j = $i + 1; $j < $cnt; $j++) {
                          if (array_key_exists($i, $onew_arr) && array_key_exists($j, $onew_arr)) {
                              if ($onew_arr[$i] . 's' == $onew_arr[$j])
                                  unset($onew_arr[$j]);
                              if (array_key_exists($j, $onew_arr)) {
                                  if ($onew_arr[$i] == $onew_arr[$j] . 's')
                                      unset($onew_arr[$i]);
                              }
                          }
                      }
                  }
              }
          }

          // ready for output - implode arrays
          if ($onew_arr !== false)
              $onew_kw = implode(',', $onew_arr) . ',';
          else
              $onew_kw = '';

          if ($twow_arr !== false)
              $twow_kw = implode(',', $twow_arr) . ',';
          else
              $twow_kw = '';

          if ($thrw_arr !== false)
              $thrw_kw = implode(',', $thrw_arr) . ',';
          else
              $thrw_kw = '';

          $keywords = $onew_kw . $twow_kw . $thrw_kw;
          return rtrim($keywords, ',');
      }

      //------------------------------------------------------------------

      /**
       * parseMeta::process_text()
       * 
       * @param mixed $str
       * @return
       */
      public function process_text($str)
      {

          if (preg_match('/^\s*$/', $str))
              return false;

          // strip HTML
          $str = Validator::sanitize($str, "string");

          //convert all characters to lower case
          $str = mb_strtolower($str, self::encoding);

          // some cleanup
          $str = ' ' . $str . ' '; // pad that is necessary
          $str = preg_replace('#\ [a-z]{1,2}\ #i', ' ', $str); // remove 2 letter words and numbers
          $str = preg_replace('#[0-9\,\.:]#', '', $str); // remove numerals, including commas and dots that are part of the numeral
          $str = preg_replace("/([a-z]{2,})('|’)s/", '\\1', $str); // remove only the 's (as in mother's)
          $str = str_replace('-', ' ', $str); // remove hyphens (-)

          // IGNORE WORDS LIST
          // add, remove, edit as needed

          $common = array(
              'able',
              'about',
              'above',
              'act',
              'add',
              'afraid',
              'after',
              'again',
              'against',
              'age',
              'ago',
              'agree',
              'all',
              'almost',
              'alone',
              'along',
              'already',
              'also',
              'although',
              'always',
              'am',
              'amount',
              'an',
              'and',
              'anger',
              'angry',
              'animal',
              'another',
              'answer',
              'any',
              'appear',
              'apple',
              'are',
              'arrive',
              'arm',
              'arms',
              'around',
              'arrive',
              'as',
              'ask',
              'at',
              'attempt',
              'aunt',
              'away',
              'back',
              'bad',
              'bag',
              'bay',
              'be',
              'became',
              'because',
              'become',
              'been',
              'before',
              'began',
              'begin',
              'behind',
              'being',
              'bell',
              'belong',
              'below',
              'beside',
              'best',
              'better',
              'between',
              'beyond',
              'big',
              'body',
              'bone',
              'born',
              'borrow',
              'both',
              'bottom',
              'box',
              'boy',
              'break',
              'bring',
              'brought',
              'bug',
              'built',
              'busy',
              'but',
              'buy',
              'by',
              'call',
              'came',
              'can',
              'cause',
              'choose',
              'close',
              'close',
              'consider',
              'come',
              'consider',
              'considerable',
              'contain',
              'continue',
              'could',
              'cry',
              'cut',
              'dare',
              'dark',
              'deal',
              'dear',
              'decide',
              'deep',
              'did',
              'die',
              'do',
              'does',
              'dog',
              'done',
              'doubt',
              'down',
              'during',
              'each',
              'ear',
              'early',
              'eat',
              'effort',
              'either',
              'else',
              'end',
              'enjoy',
              'enough',
              'enter',
              'etc',
              'even',
              'ever',
              'every',
              'except',
              'expect',
              'explain',
              'fail',
              'fall',
              'far',
              'fat',
              'favor',
              'fear',
              'feel',
              'feet',
              'fell',
              'felt',
              'few',
              'fill',
              'find',
              'fit',
              'fly',
              'follow',
              'for',
              'forever',
              'forget',
              'from',
              'front',
              'full',
              'fully',
              'gave',
              'get',
              'gives',
              'goes',
              'gone',
              'good',
              'got',
              'gray',
              'great',
              'green',
              'grew',
              'grow',
              'guess',
              'had',
              'half',
              'hang',
              'happen',
              'has',
              'hat',
              'have',
              'he',
              'hear',
              'heard',
              'held',
              'hello',
              'help',
              'her',
              'here',
              'hers',
              'high',
              'highest',
              'highly',
              'hill',
              'him',
              'his',
              'hit',
              'hold',
              'hot',
              'how',
              'however',
              'i',
              'if',
              'ill',
              'in',
              'include',
              'including',
              'included',
              'indeed',
              'instead',
              'into',
              'iron',
              'is',
              'it',
              'its',
              'just',
              'keep',
              'kept',
              'knew',
              'know',
              'known',
              'late',
              'least',
              'led',
              'left',
              'lend',
              'less',
              'let',
              'like',
              'likely',
              'lone',
              'long',
              'longer',
              'look',
              'lot',
              'make',
              'many',
              'may',
              'me',
              'mean',
              'met',
              'might',
              'mile',
              'mine',
              'moon',
              'more',
              'most',
              'move',
              'much',
              'must',
              'my',
              'near',
              'nearly',
              'necessary',
              'neither',
              'never',
              'next',
              'no',
              'none',
              'nor',
              'not',
              'note',
              'nothing',
              'now',
              'number',
              'of',
              'off',
              'often',
              'oh',
              'on',
              'once',
              'only',
              'or',
              'other',
              'ought',
              'our',
              'out',
              'please',
              'prepare',
              'probable',
              'pull',
              'pure',
              'push',
              'put',
              'raise',
              'ran',
              'rather',
              'reach',
              'realize',
              'reply',
              'require',
              'rest',
              'run',
              'said',
              'same',
              'sat',
              'saw',
              'say',
              'see',
              'seem',
              'seen',
              'self',
              'sell',
              'sent',
              'separate',
              'set',
              'shall',
              'she',
              'should',
              'side',
              'sign',
              'since',
              'so',
              'sold',
              'some',
              'soon',
              'sorry',
              'stay',
              'step',
              'stick',
              'still',
              'stood',
              'such',
              'sudden',
              'suppose',
              'take',
              'taken',
              'talk',
              'tall',
              'tell',
              'ten',
              'than',
              'thank',
              'that',
              'the',
              'their',
              'them',
              'then',
              'there',
              'therefore',
              'these',
              'they',
              'this',
              'those',
              'though',
              'through',
              'till',
              'to',
              'today',
              'told',
              'tomorrow',
              'too',
              'took',
              'tore',
              'tought',
              'toward',
              'tried',
              'tries',
              'trust',
              'try',
              'turn',
              'two',
              'under',
              'until',
              'up',
              'upon',
              'us',
              'use',
              'usual',
              'various',
              'verb',
              'very',
              'visit',
              'want',
              'was',
              'we',
              'well',
              'went',
              'were',
              'what',
              'when',
              'where',
              'whether',
              'which',
              'while',
              'white',
              'who',
              'whom',
              'whose',
              'why',
              'will',
              'with',
              'within',
              'without',
              'would',
              'yes',
              'yet',
              'you',
              'young',
              'your',
              'yours');

          if (isset($common)) {
              foreach ($common as $word)
                  $str = str_replace(' ' . $word . ' ', ' ', $str);
              unset($common);
          }

          // replace multiple whitespaces
          $str = preg_replace('/\s\s+/', ' ', $str);
          $str = trim($str);

          if (preg_match('/^\s*$/', $str))
              return false;

          // break along paragraphs, punctuations
          $arrA = explode("\n", $str);
          foreach ($arrA as $key => $value) {
              if (strpos($value, '.') !== false)
                  $arrB[$key] = explode('.', $value);
              else
                  $arrB[$key] = $value;
          }
          $arrB = self::array_flatten($arrB);
          unset($arrA);
          foreach ($arrB as $key => $value) {
              if (strpos($value, '!') !== false)
                  $arrC[$key] = explode('!', $value);
              else
                  $arrC[$key] = $value;
          }
          $arrC = self::array_flatten($arrC);
          unset($arrB);
          foreach ($arrC as $key => $value) {
              if (strpos($value, '?') !== false)
                  $arrD[$key] = explode('?', $value);
              else
                  $arrD[$key] = $value;
          }
          $arrD = self::array_flatten($arrD);
          unset($arrC);
          foreach ($arrD as $key => $value) {
              if (strpos($value, ',') !== false)
                  $arrE[$key] = explode(',', $value);
              else
                  $arrE[$key] = $value;
          }
          $arrE = self::array_flatten($arrE);
          unset($arrD);
          foreach ($arrE as $key => $value) {
              if (strpos($value, ';') !== false)
                  $arrF[$key] = explode(';', $value);
              else
                  $arrF[$key] = $value;
          }
          $arrF = self::array_flatten($arrF);
          unset($arrE);
          foreach ($arrF as $key => $value) {
              if (strpos($value, ':') !== false)
                  $arrG[$key] = explode(':', $value);
              else
                  $arrG[$key] = $value;
          }
          $arrG = self::array_flatten($arrG);
          unset($arrF);

          return $arrG;
      }

      /**
       * parseMeta::parse_words()
       * 
       * @return
       */
      public static function parse_words()
      {

          if (self::min_word_length === 0)
              return false; // 0 means disable

          $str = implode(' ', self::$contents);
          $str = self::strip_punctuations($str);

          // create an array out of the site contents
          $s = explode(' ', $str);

          // iterate inside the array
          foreach ($s as $key => $val) {
              if (mb_strlen($val, self::encoding) >= self::min_word_length)
                  $k[] = $val;
          }

          if (!isset($k))
              return false;

          $k = array_count_values($k);

          return self::occure_filter($k, self::min_word_occur);
      }

      /**
       * parseMeta::parse_2words()
       * 
       * @return
       */
      public static function parse_2words()
      {

          if (self::min_2words_length === false)
              return false; // 0 means disable

          foreach (self::$contents as $key => $str) {
              $str = self::strip_punctuations($str);
              $arr[$key] = explode(' ', $str); // 2-dimensional array
          }

          $z = 0; // key of the 2-word array
          $lines = count($arr);
          for ($a = 0; $a < $lines; $a++) {
              $words = count($arr[$a]);
              for ($i = 0; $i < $words - 1; $i++) {
                  if ((mb_strlen($arr[$a][$i], self::encoding) >= self::min_2words_length) && (mb_strlen($arr[$a][$i + 1], self::encoding) >= self::min_2words_length)) {
                      $y[$z] = $arr[$a][$i] . " " . $arr[$a][$i + 1];
                      $z++;
                  }
              }
          }

          if (!isset($y))
              return false;

          $y = array_count_values($y);

          return self::occure_filter($y, self::min_2words_phrase_occur);
      }

      /**
       * parseMeta::parse_3words()
       * 
       * @return
       */
      public static function parse_3words()
      {

          if (self::min_3words_length === false)
              return false; // 0 means disable

          foreach (self::$contents as $key => $str) {
              $str = self::strip_punctuations($str);
              $arr[$key] = explode(' ', $str); // 2-dimensional array
          }

          $z = 0; // key of the 3-word array
          $lines = count($arr);
          for ($a = 0; $a < $lines; $a++) {
              $words = count($arr[$a]);
              for ($i = 0; $i < $words - 2; $i++) {
                  if ((mb_strlen($arr[$a][$i], self::encoding) >= self::min_3words_length) && (mb_strlen($arr[$a][$i + 1], self::encoding) >= self::min_3words_length) && (mb_strlen($arr[$a][$i + 2], self::
                      encoding) >= self::min_3words_length)) {
                      $y[$z] = $arr[$a][$i] . " " . $arr[$a][$i + 1] . " " . $arr[$a][$i + 2];
                      $z++;
                  }
              }
          }

          if (!isset($y))
              return false;

          $y = array_count_values($y);

          return self::occure_filter($y, self::min_3words_phrase_occur);
      }

      /**
       * parseMeta::occure_filter()
       * 
       * @param mixed $array
       * @param mixed $min
       * @return
       */
      public static function occure_filter($array, $min)
      {
          $cnt = 0;
          foreach ($array as $word => $occured) {
              if ($occured >= $min) {
                  $new[$cnt] = $word;
                  $cnt++;
              }
          }
          if (isset($new))
              return $new;
          return false;
      }

      /**
       * parseMeta::array_flatten()
       * 
       * @param mixed $array
       * @param bool $flat
       * @return
       */
      public static function array_flatten($array, $flat = false)
      {
          if (!is_array($array) || empty($array))
              return '';
          if (empty($flat))
              $flat = array();

          foreach ($array as $key => $val) {
              if (is_array($val))
                  $flat = self::array_flatten($val, $flat);
              else
                  $flat[] = $val;
          }

          return $flat;
      }
	  
      /**
       * parseMeta::strip_punctuations()
       * 
       * @param mixed $str
       * @return
       */
      public static function strip_punctuations($str)
      {
          if ($str == '')
              return '';
          $punctuations = array(
              '"',
              "'",
              '’',
              '˝',
              '„',
              '`',
              '.',
              ',',
              ';',
              ':',
              '+',
              '±',
              '-',
              '_',
              '=',
              '(',
              ')',
              '[',
              ']',
              '<',
              '>',
              '{',
              '}',
              '/',
              '\\',
              '|',
              '?',
              '!',
              '@',
              '#',
              '%',
              '^',
              '&',
              '§',
              '$',
              '¢',
              '£',
              '€',
              '¥',
              '₣',
              '฿',
              '*',
              '~',
              '。',
              '，',
              '、',
              '；',
              '：',
              '？',
              '！',
              '…',
              '—',
              '·',
              'ˉ',
              'ˇ',
              '¨',
              '‘',
              '’',
              '“',
              '”',
              '々',
              '～',
              '‖',
              '∶',
              '＂',
              '＇',
              '｀',
              '｜',
              '〃',
              '〔',
              '〕',
              '〈',
              '〉',
              '《',
              '》',
              '「',
              '」',
              '『',
              '』',
              '．',
              '〖',
              '〗',
              '【',
              '】',
              '（',
              '）',
              '［',
              '］',
              '｛',
              '｝',
              '／',
              '“',
              '”');
          $str = str_replace($punctuations, ' ', $str);
          return preg_replace('/\s\s+/', ' ', $str);
      }

      //------------------------------------------------------------------

      /**
       * parseMeta::remove_duplicate_keywords()
       * 
       * @param mixed $str
       * @return
       */
      public static function remove_duplicate_keywords($str)
      {
          if ($str == '')
              return $str;
          $str = trim(mb_strtolower($str));
          $kw_arr = explode(',', $str); // array
          foreach ($kw_arr as $key => $val) {
              $kw_arr[$key] = trim($val);
              if ($kw_arr[$key] == '')
                  unset($kw_arr[$key]);
          }
          $kw_arr = array_unique($kw_arr);
          // remove duplicate ENGLISH plural words
          if (Lang::$lang == 'en') {
              $cnt = count($kw_arr);
              for ($i = 0; $i < $cnt; $i++) {
                  for ($j = $i + 1; $j < $cnt; $j++) {
                      if (array_key_exists($i, $kw_arr) && array_key_exists($j, $kw_arr)) {
                          if ($kw_arr[$i] . 's' == $kw_arr[$j])
                              unset($kw_arr[$j]);
                          elseif ($kw_arr[$i] == $kw_arr[$j] . 's')
                              unset($kw_arr[$i]);
                          //--------------
                          elseif (preg_match('#ss$#', $kw_arr[$j])) {
                              if ($kw_arr[$i] === $kw_arr[$j] . 'es')
                                  unset($kw_arr[$i]); // addresses VS address
                          } elseif (preg_match('#ss$#', $kw_arr[$i])) {
                              if ($kw_arr[$i] . 'es' === $kw_arr[$j])
                                  unset($kw_arr[$j]); // address VS addresses
                          }
                          //---------------
                      }
                      $kw_arr = array_values($kw_arr);
                  }
                  $kw_arr = array_values($kw_arr);
              }
          }
          // job is done!
          return implode(',', $kw_arr);
      }
  }