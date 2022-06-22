<?php
  /**
   * Date Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');

  class Date
  {

      /**
       * Date::__construct()
       * 
       * @return
       */
      function __construct(){}


      /**
       * Date::compareDates()
       * 
       * @param mixed $date1
       * @param mixed $date2
       * @return
       */
      public static function compareDates($date1, $date2)
      {
          $date1 = new DateTime($date1);
          $date2 = new DateTime($date2);

          return ($date1 > $date2) ? true : false;
      }

      /**
       * Date::NewDate()
       * 
       * @param mixed $date
       * @param mixed $days
       * @return
       */
      public static function NewDate($date, $days)
      {
		  
		  $cDate = new DateTime($date);
		  $now = new DateTime();

          return ($cDate->diff($now)->days < $days) ? true : false;
      }
	  
      /**
       * Date::dateLabels()
       * 
       * @param mixed $date
       * @return
       */
      public static function dateLabels($date)
      {
		  $now = new DateTime();
		  $match_date = DateTime::createFromFormat("Y-m-d", $date);
		  $diff = $now->diff($match_date);
		  $diffDays = (int) $diff->format("%R%a");
		  
		  if($diffDays >= 1) {
			  echo "positive"; //Tomorrow
		  } elseif($diffDays < 0) {
			  return "negative"; //Yesterday
		  } else {
			  return "primary"; //Today
		  }
      }
	  
      /**
       * Date::isWeekend()
       * 
       * @param mixed $format
       * @param mixed $date
       * @return
       */
      public static function isWeekend($date)
      {
         $date = DateTime::createFromFormat('Y-m-d', $date, new DateTimeZone(App::Core()->dtz));
		 return $date->format('N') >= 6;
      }

      /**
       * Date::doDate()
       * 
       * @param mixed $format
       * @param mixed $date
       * @return
       */
      public static function doDate($format, $date)
      {
          $cal = IntlCalendar::fromDateTime($date);
          if ($format == "long_date" or $format == "short_date") {
              return IntlDateFormatter::formatObject($cal, App::Core()->$format, App::Core()->locale);
          } else {
              return IntlDateFormatter::formatObject($cal, $format);
          }
      }

      /**
       * Date::doTime()
       * 
       * @param mixed $time
       * @return
       */
      public static function doTime($time)
      {

          $cal = IntlCalendar::fromDateTime($time);
          return IntlDateFormatter::formatObject($cal, App::Core()->time_format);
      }

      /**
       * Date::doStime()
       * 
       * @param mixed $time
       * @return
       */
      public static function doStime($time)
      {

          $cal = IntlCalendar::fromDateTime($time);
          return IntlDateFormatter::formatObject($cal, 'HH:mm:ss');
      }
	  
      /**
       * Date::getShortDate()
       * 
       * @param bool $selected
       * @return
       */
      public static function getShortDate($selected = false)
      {

          $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
          $arr = array(
              'MM-dd-yyyy' => IntlDateFormatter::formatObject($cal, 'MM-dd-yyyy'),
              'd-MM-YYYY' => IntlDateFormatter::formatObject($cal, 'd-MM-YYYY'),
              'MM-d-yy' => IntlDateFormatter::formatObject($cal, 'MM-d-yy'),
              'd-MM-yy' => IntlDateFormatter::formatObject($cal, 'd-MM-yy'),
              'dd MMM yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMM yyyy'));

          $shortdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $shortdate;
      }

      /**
       * Date::getLongDate()
       * 
       * @param bool $selected
       * @return
       */
      public static function getLongDate($selected = false)
      {

          $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
          $arr = array(
              'MMMM dd, yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy hh:mm a'),
              'dd MMMM yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'dd MMMM yyyy hh:mm a'),
              'MMMM dd, yyyy' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy'),
              'dd MMMM, yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMMM, yyyy'),
              'EEEE dd MMMM yyyy' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy'),
              'EEEE dd MMMM yyyy HH:mm' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy HH:mm'),
              'EE dd, MMM. yyyy' => IntlDateFormatter::formatObject($cal, 'EE dd, MMM. yyyy'));

          $longdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }
	  
      /**
       * Date::getTimeFormat()
       * 
       * @param bool $selected
       * @return
       */
      public static function getTimeFormat($selected = false)
      {
          $cal = IntlCalendar::fromDateTime(date('H:i:s'));
          $arr = array(
              'hh:mm a' => IntlDateFormatter::formatObject($cal, 'hh:mm a'),
              'HH:mm' => IntlDateFormatter::formatObject($cal, 'HH:mm'),
              );

          $longdate = '';
          foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Date::weekList()
       * 
       * @param bool $list
       * @param bool $long
       * @param bool $selected
       * @return
       */
      public static function weekList($list = true, $long = true, $selected = false)
      {
          $fmt = new IntlDateFormatter(App::Core()->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
          $data = array();

          ($long) ? $fmt->setPattern('EEEE') : $fmt->setPattern('EE');

          for ($i = 0; $i <= 6; $i++) {
              $data[] = $fmt->format(mktime(0, 0, 0, 0, $i, 1970));
          }

          $html = '';
          if ($list) {
              foreach ($data as $key => $val) {
                  $html .= "<option value=\"$key\"";
                  $html .= ($key == $selected) ? ' selected="selected"' : '';
                  $html .= ">$val</option>\n";
              }
          } else {
              $html .= '"' . implode('","', $data) . '"';
          }

          unset($val);
          return $html;
      }

      /**
       * Date::getPeriod()
       * 
       * @param bool $value
       * @return
       */
      public static function getPeriod($value)
      {
          switch ($value) {
              case "day":
                  return Lang::$word->_DAYS;
                  break;
              case "week":
                  return Lang::$word->_WEEKS;
                  break;
              case "month":
                  return Lang::$word->_MONTHS;
                  break;
              case "year":
                  return Lang::$word->_YEARS;
                  break;
          }
      }

      /**
       * Date::getMembershipPeriod()
       * 
       * @return
       */
      public static function getMembershipPeriod()
      {
          $arr = array(
              'D' => Lang::$word->_DAYS,
              'W' => Lang::$word->_WEEKS,
              'M' => Lang::$word->_MONTHS,
              'Y' => Lang::$word->_YEARS
			  );

          return $arr;
      }

      /**
       * Date::getPeriodReadable()
       * 
       * @param bool $value
       * @return
       */
      public static function getPeriodReadable($value)
      {
          switch ($value) {
              case "D":
                  return Lang::$word->_DAYS;
                  break;
              case "W":
                  return Lang::$word->_WEEKS;
                  break;
              case "M":
                  return Lang::$word->_MONTHS;
                  break;
              case "Y":
                  return Lang::$word->_YEARS;
                  break;
          }
      }
	  
      /**
       * Date::NumberOfDays()
       * 
       * @param bool $days
	   * eg: +10 day, -1 week
	   * @param bool $format
       * @return
       */
      public static function NumberOfDays($days, $format = false)
      {
		  $date = new DateTime();
		  $date->modify($days);
		  
		  return $date->format($format ? $format : 'Y-m-d H:i:s');
      }

      /**
       * Date::today()
       * 
       * @param str $date
	   * @param bool $format
       * @return
       */
      public static function today($date = '', $format = false)
      {
		  $date = new DateTime($date);
		  
		  return $date->format($format ? $format : 'Y-m-d H:i:s');
      }

      /**
       * Date::timesince()
       * 
       * @param bool $datetime
       * @return
       */
      public static function timesince($datetime)
      {

          $today = time();
          $createdday = strtotime($datetime);
          $datediff = abs($today - $createdday);
          $difftext = "";
          $years = floor($datediff / (365 * 60 * 60 * 24));
          $months = floor(($datediff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
          $days = floor(($datediff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
          $hours = floor($datediff / 3600);
          $minutes = floor($datediff / 60);
          $seconds = floor($datediff);
          //year checker
          if ($difftext == "") {
              if ($years > 1)
                  $difftext = $years . " " . Lang::$word->_YEARS . " " . Lang::$word->AGO;
              elseif ($years == 1)
                  $difftext = $years . " " . Lang::$word->_YEAR . " " . Lang::$word->AGO;
          }
          //month checker
          if ($difftext == "") {
              if ($months > 1)
                  $difftext = $months . " " . Lang::$word->_MONTHS . " " . Lang::$word->AGO;
              elseif ($months == 1)
                  $difftext = $months . " " . Lang::$word->_MONTH . " " . Lang::$word->AGO;
          }
          //month checker
          if ($difftext == "") {
              if ($days > 1)
                  $difftext = $days . " " . Lang::$word->_DAYS . " " . Lang::$word->AGO;
              elseif ($days == 1)
                  $difftext = $days . " " . Lang::$word->_DAY . " " . Lang::$word->AGO;
          }
          //hour checker
          if ($difftext == "") {
              if ($hours > 1)
                  $difftext = $hours . " " . Lang::$word->_HOURS . " " . Lang::$word->AGO;
              elseif ($hours == 1)
                  $difftext = $hours . " " . Lang::$word->_HOUR . " " . Lang::$word->AGO;
          }
          //minutes checker
          if ($difftext == "") {
              if ($minutes > 1)
                  $difftext = $minutes . " " . Lang::$word->_MINUTES . " " . Lang::$word->AGO;
              elseif ($minutes == 1)
                  $difftext = $minutes . " " . Lang::$word->_MINUTE . " " . Lang::$word->AGO;
          }
          //seconds checker
          if ($difftext == "") {
              if ($seconds > 1)
                  $difftext = $seconds . " " . Lang::$word->_SECONDS . " " . Lang::$word->AGO;
              elseif ($seconds < 1)
                  $difftext =  Lang::$word->JUSTNOW;
              elseif ($seconds == 1)
                  $difftext = $seconds . " " . Lang::$word->_SECOND . " " . Lang::$word->AGO;
          }
          return $difftext;

      }

      /**
       * Date::monthList()
       * 
       * @param bool $list
       * @param bool $long
       * @param bool $selected
       * @return
       */
      public static function monthList($list = true, $long = true, $selected = false)
      {
          $selected = is_null(Validator::get('month')) ? strftime('%m') : Validator::get('month');

          $fmt = new IntlDateFormatter(App::Core()->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
          $data = array();

          ($long) ? $fmt->setPattern('MMMM') : $fmt->setPattern('MMM');

          for ($i = 1; $i <= 12; $i++) {
              $data[] = $fmt->format(mktime(0, 0, 0, $i, 1, 1970));
          }

          $html = '';
          if ($list) {
              foreach ($data as $key => $val) {
                  $html .= "<option value=\"$key\"";
                  $html .= ($key == $selected) ? ' selected="selected"' : '';
                  $html .= ">$val</option>\n";
              }
          } else {
              $html .= '"' . implode('","', $data) . '"';
          }
          unset($val);
          return $html;
      }
	  
      /**
       * Date::getTimezones()
       * 
       * @return
       */
      public static function getTimezones()
      {
          $data = '';
          $tzone = DateTimeZone::listIdentifiers();
          foreach ($tzone as $zone) {
              $selected = ($zone == App::Core()->dtz) ? ' selected="selected"' : '';
              $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
          }
          return $data;
      }

      /**
       * Date::localeList()
       * 
       * @param bool $selected
       * @return
       */
      public static function localeList($selected = false)
      {
          $data = array(
              'aa_DJ' => 'Afar (Djibouti)',
              'aa_ER' => 'Afar (Eritrea)',
              'aa_ET' => 'Afar (Ethiopia)',
              'af_ZA' => 'Afrikaans (South Africa)',
              'sq_AL' => 'Albanian (Albania)',
              'sq_MK' => 'Albanian (Macedonia)',
              'am_ET' => 'Amharic (Ethiopia)',
              'ar_DZ' => 'Arabic (Algeria)',
              'ar_BH' => 'Arabic (Bahrain)',
              'ar_EG' => 'Arabic (Egypt)',
              'ar_IN' => 'Arabic (India)',
              'ar_IQ' => 'Arabic (Iraq)',
              'ar_JO' => 'Arabic (Jordan)',
              'ar_KW' => 'Arabic (Kuwait)',
              'ar_LB' => 'Arabic (Lebanon)',
              'ar_LY' => 'Arabic (Libya)',
              'ar_MA' => 'Arabic (Morocco)',
              'ar_OM' => 'Arabic (Oman)',
              'ar_QA' => 'Arabic (Qatar)',
              'ar_SA' => 'Arabic (Saudi Arabia)',
              'ar_SD' => 'Arabic (Sudan)',
              'ar_SY' => 'Arabic (Syria)',
              'ar_TN' => 'Arabic (Tunisia)',
              'ar_AE' => 'Arabic (United Arab Emirates)',
              'ar_YE' => 'Arabic (Yemen)',
              'an_ES' => 'Aragonese (Spain)',
              'hy_AM' => 'Armenian (Armenia)',
              'as_IN' => 'Assamese (India)',
              'ast_ES' => 'Asturian (Spain)',
              'az_AZ' => 'Azerbaijani (Azerbaijan)',
              'az_TR' => 'Azerbaijani (Turkey)',
              'eu_FR' => 'Basque (France)',
              'eu_ES' => 'Basque (Spain)',
              'be_BY' => 'Belarusian (Belarus)',
              'bem_ZM' => 'Bemba (Zambia)',
              'bn_BD' => 'Bengali (Bangladesh)',
              'bn_IN' => 'Bengali (India)',
              'ber_DZ' => 'Berber (Algeria)',
              'ber_MA' => 'Berber (Morocco)',
              'byn_ER' => 'Blin (Eritrea)',
              'bs_BA' => 'Bosnian (Bosnia and Herzegovina)',
              'br_FR' => 'Breton (France)',
              'bg_BG' => 'Bulgarian (Bulgaria)',
              'my_MM' => 'Burmese (Myanmar [Burma])',
              'ca_AD' => 'Catalan (Andorra)',
              'ca_FR' => 'Catalan (France)',
              'ca_IT' => 'Catalan (Italy)',
              'ca_ES' => 'Catalan (Spain)',
              'zh_CN' => 'Chinese (China)',
              'zh_HK' => 'Chinese (Hong Kong SAR China)',
              'zh_SG' => 'Chinese (Singapore)',
              'zh_TW' => 'Chinese (Taiwan)',
              'cv_RU' => 'Chuvash (Russia)',
              'kw_GB' => 'Cornish (United Kingdom)',
              'crh_UA' => 'Crimean Turkish (Ukraine)',
              'hr_HR' => 'Croatian (Croatia)',
              'cs_CZ' => 'Czech (Czech Republic)',
              'da_DK' => 'Danish (Denmark)',
              'dv_MV' => 'Divehi (Maldives)',
              'nl_AW' => 'Dutch (Aruba)',
              'nl_BE' => 'Dutch (Belgium)',
              'nl_NL' => 'Dutch (Netherlands)',
              'dz_BT' => 'Dzongkha (Bhutan)',
              'en_AG' => 'English (Antigua and Barbuda)',
              'en_AU' => 'English (Australia)',
              'en_BW' => 'English (Botswana)',
              'en_CA' => 'English (Canada)',
              'en_DK' => 'English (Denmark)',
              'en_HK' => 'English (Hong Kong SAR China)',
              'en_IN' => 'English (India)',
              'en_IE' => 'English (Ireland)',
              'en_NZ' => 'English (New Zealand)',
              'en_NG' => 'English (Nigeria)',
              'en_PH' => 'English (Philippines)',
              'en_SG' => 'English (Singapore)',
              'en_ZA' => 'English (South Africa)',
              'en_GB' => 'English (United Kingdom)',
              'en_US' => 'English (United States)',
              'en_ZM' => 'English (Zambia)',
              'en_ZW' => 'English (Zimbabwe)',
              'eo' => 'Esperanto',
              'et_EE' => 'Estonian (Estonia)',
              'fo_FO' => 'Faroese (Faroe Islands)',
              'fil_PH' => 'Filipino (Philippines)',
              'fi_FI' => 'Finnish (Finland)',
              'fr_BE' => 'French (Belgium)',
              'fr_CA' => 'French (Canada)',
              'fr_FR' => 'French (France)',
              'fr_LU' => 'French (Luxembourg)',
              'fr_CH' => 'French (Switzerland)',
              'fur_IT' => 'Friulian (Italy)',
              'ff_SN' => 'Fulah (Senegal)',
              'gl_ES' => 'Galician (Spain)',
              'lg_UG' => 'Ganda (Uganda)',
              'gez_ER' => 'Geez (Eritrea)',
              'gez_ET' => 'Geez (Ethiopia)',
              'ka_GE' => 'Georgian (Georgia)',
              'de_AT' => 'German (Austria)',
              'de_BE' => 'German (Belgium)',
              'de_DE' => 'German (Germany)',
              'de_LI' => 'German (Liechtenstein)',
              'de_LU' => 'German (Luxembourg)',
              'de_CH' => 'German (Switzerland)',
              'el_CY' => 'Greek (Cyprus)',
              'el_GR' => 'Greek (Greece)',
              'gu_IN' => 'Gujarati (India)',
              'ht_HT' => 'Haitian (Haiti)',
              'ha_NG' => 'Hausa (Nigeria)',
              'iw_IL' => 'Hebrew (Israel)',
              'he_IL' => 'Hebrew (Israel)',
              'hi_IN' => 'Hindi (India)',
              'hu_HU' => 'Hungarian (Hungary)',
              'is_IS' => 'Icelandic (Iceland)',
              'ig_NG' => 'Igbo (Nigeria)',
              'id_ID' => 'Indonesian (Indonesia)',
              'ia' => 'Interlingua',
              'iu_CA' => 'Inuktitut (Canada)',
              'ik_CA' => 'Inupiaq (Canada)',
              'ga_IE' => 'Irish (Ireland)',
              'it_IT' => 'Italian (Italy)',
              'it_CH' => 'Italian (Switzerland)',
              'ja_JP' => 'Japanese (Japan)',
              'kl_GL' => 'Kalaallisut (Greenland)',
              'kn_IN' => 'Kannada (India)',
              'ks_IN' => 'Kashmiri (India)',
              'csb_PL' => 'Kashubian (Poland)',
              'kk_KZ' => 'Kazakh (Kazakhstan)',
              'km_KH' => 'Khmer (Cambodia)',
              'rw_RW' => 'Kinyarwanda (Rwanda)',
              'ky_KG' => 'Kirghiz (Kyrgyzstan)',
              'kok_IN' => 'Konkani (India)',
              'ko_KR' => 'Korean (South Korea)',
              'ku_TR' => 'Kurdish (Turkey)',
              'lo_LA' => 'Lao (Laos)',
              'lv_LV' => 'Latvian (Latvia)',
              'li_BE' => 'Limburgish (Belgium)',
              'li_NL' => 'Limburgish (Netherlands)',
              'lt_LT' => 'Lithuanian (Lithuania)',
              'nds_DE' => 'Low German (Germany)',
              'nds_NL' => 'Low German (Netherlands)',
              'mk_MK' => 'Macedonian (Macedonia)',
              'mai_IN' => 'Maithili (India)',
              'mg_MG' => 'Malagasy (Madagascar)',
              'ms_MY' => 'Malay (Malaysia)',
              'ml_IN' => 'Malayalam (India)',
              'mt_MT' => 'Maltese (Malta)',
              'gv_GB' => 'Manx (United Kingdom)',
              'mi_NZ' => 'Maori (New Zealand)',
              'mr_IN' => 'Marathi (India)',
              'mn_MN' => 'Mongolian (Mongolia)',
              'ne_NP' => 'Nepali (Nepal)',
              'se_NO' => 'Northern Sami (Norway)',
              'nso_ZA' => 'Northern Sotho (South Africa)',
              'nb_NO' => 'Norwegian Bokmål (Norway)',
              'nn_NO' => 'Norwegian Nynorsk (Norway)',
              'oc_FR' => 'Occitan (France)',
              'or_IN' => 'Oriya (India)',
              'om_ET' => 'Oromo (Ethiopia)',
              'om_KE' => 'Oromo (Kenya)',
              'os_RU' => 'Ossetic (Russia)',
              'pap_AN' => 'Papiamento (Netherlands Antilles)',
              'ps_AF' => 'Pashto (Afghanistan)',
              'fa_IR' => 'Persian (Iran)',
              'pl_PL' => 'Polish (Poland)',
              'pt_BR' => 'Portuguese (Brazil)',
              'pt_PT' => 'Portuguese (Portugal)',
              'pa_IN' => 'Punjabi (India)',
              'pa_PK' => 'Punjabi (Pakistan)',
              'ro_RO' => 'Romanian (Romania)',
              'ru_RU' => 'Russian (Russia)',
              'ru_UA' => 'Russian (Ukraine)',
              'sa_IN' => 'Sanskrit (India)',
              'sc_IT' => 'Sardinian (Italy)',
              'gd_GB' => 'Scottish Gaelic (United Kingdom)',
              'sr_ME' => 'Serbian (Montenegro)',
              'sr_RS' => 'Serbian (Cyrillic )',
              'sr_LAT' => 'Serbian (Latin)',
              'sid_ET' => 'Sidamo (Ethiopia)',
              'sd_IN' => 'Sindhi (India)',
              'si_LK' => 'Sinhala (Sri Lanka)',
              'sk_SK' => 'Slovak (Slovakia)',
              'sl_SI' => 'Slovenian (Slovenia)',
              'so_DJ' => 'Somali (Djibouti)',
              'so_ET' => 'Somali (Ethiopia)',
              'so_KE' => 'Somali (Kenya)',
              'so_SO' => 'Somali (Somalia)',
              'nr_ZA' => 'South Ndebele (South Africa)',
              'st_ZA' => 'Southern Sotho (South Africa)',
              'es_AR' => 'Spanish (Argentina)',
              'es_BO' => 'Spanish (Bolivia)',
              'es_CL' => 'Spanish (Chile)',
              'es_CO' => 'Spanish (Colombia)',
              'es_CR' => 'Spanish (Costa Rica)',
              'es_DO' => 'Spanish (Dominican Republic)',
              'es_EC' => 'Spanish (Ecuador)',
              'es_SV' => 'Spanish (El Salvador)',
              'es_GT' => 'Spanish (Guatemala)',
              'es_HN' => 'Spanish (Honduras)',
              'es_MX' => 'Spanish (Mexico)',
              'es_NI' => 'Spanish (Nicaragua)',
              'es_PA' => 'Spanish (Panama)',
              'es_PY' => 'Spanish (Paraguay)',
              'es_PE' => 'Spanish (Peru)',
              'es_ES' => 'Spanish (Spain)',
              'es_US' => 'Spanish (United States)',
              'es_UY' => 'Spanish (Uruguay)',
              'es_VE' => 'Spanish (Venezuela)',
              'sw_KE' => 'Swahili (Kenya)',
              'sw_TZ' => 'Swahili (Tanzania)',
              'ss_ZA' => 'Swati (South Africa)',
              'sv_FI' => 'Swedish (Finland)',
              'sv_SE' => 'Swedish (Sweden)',
              'tl_PH' => 'Tagalog (Philippines)',
              'tg_TJ' => 'Tajik (Tajikistan)',
              'ta_IN' => 'Tamil (India)',
              'tt_RU' => 'Tatar (Russia)',
              'te_IN' => 'Telugu (India)',
              'th_TH' => 'Thai (Thailand)',
              'bo_CN' => 'Tibetan (China)',
              'bo_IN' => 'Tibetan (India)',
              'tig_ER' => 'Tigre (Eritrea)',
              'ti_ER' => 'Tigrinya (Eritrea)',
              'ti_ET' => 'Tigrinya (Ethiopia)',
              'ts_ZA' => 'Tsonga (South Africa)',
              'tn_ZA' => 'Tswana (South Africa)',
              'tr_CY' => 'Turkish (Cyprus)',
              'tr_TR' => 'Turkish (Turkey)',
              'tk_TM' => 'Turkmen (Turkmenistan)',
              'ug_CN' => 'Uighur (China)',
              'uk_UA' => 'Ukrainian (Ukraine)',
              'hsb_DE' => 'Upper Sorbian (Germany)',
              'ur_PK' => 'Urdu (Pakistan)',
              'uz_UZ' => 'Uzbek (Uzbekistan)',
              've_ZA' => 'Venda (South Africa)',
              'vi_VN' => 'Vietnamese (Vietnam)',
              'wa_BE' => 'Walloon (Belgium)',
              'cy_GB' => 'Welsh (United Kingdom)',
              'fy_DE' => 'Western Frisian (Germany)',
              'fy_NL' => 'Western Frisian (Netherlands)',
              'wo_SN' => 'Wolof (Senegal)',
              'xh_ZA' => 'Xhosa (South Africa)',
              'yi_US' => 'Yiddish (United States)',
              'yo_NG' => 'Yoruba (Nigeria)',
              'zu_ZA' => 'Zulu (South Africa)');

          $html = '';
          foreach ($data as $key => $val) {
              if ($key == $selected) {
                  $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $html;
      }
  }