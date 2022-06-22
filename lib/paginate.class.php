<?php
  /**
   * Class Pagination
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  class Paginator
  {
      public $items_per_page;
      public $items_total;
      public $num_pages = 1;
      public $limit;
      public $current_page;
      public $default_ipp;
      private $mid_range;
      private $low;
      private $high;
      private $retdata;
      private $querystring;
      private static $instance;
      
      
      /**
       * Paginator::__construct()
       * 
       * @return
       */
      private function __construct()
      {
          $this->current_page = 1;
          $this->mid_range = 7;
          $this->items_per_page = (isset($_GET['ipp']) and is_numeric($_GET['ipp'])) ? Validator::sanitize($_GET['ipp'], "int") : $this->default_ipp;
      }

      /**
       * Paginator::instance()
       * 
       * @return
       */
	  public static function instance(){
		  if (!self::$instance){ 
			  self::$instance = new Paginator(); 
		  } 
	  
		  return self::$instance;  
	  }

      /**
       * Paginator::buildUrl()
       * 
       * @return
       */
      public static function buildUrl($value)
      {
          $parts = parse_url($_SERVER['REQUEST_URI']);
          if (isset($parts['query'])) {
              parse_str($parts['query'], $qs);
          } else {
              $qs = array();
          }
          $qs['pg'] = $value;
          return "?" . $parts['query'] = http_build_query($qs);
      }
	   
      /**
       * Paginator::paginate()
       * 
       * @return
       */
      public function paginate()
      {
          $this->items_per_page = (isset($_GET['ipp']) and !empty($_GET['ipp'])) ? intval($_GET['ipp']) : $this->default_ipp;
          $this->num_pages = ceil($this->items_total / $this->items_per_page);

          $this->current_page = intval(Validator::get('pg'));
          if ($this->current_page < 1 or !is_numeric($this->current_page))
              $this->current_page = 1;
          if ($this->current_page > $this->num_pages)
              $this->current_page = $this->num_pages;
          $prev_page = $this->current_page - 1;
          $next_page = $this->current_page + 1;

          if (isset($_POST)) {
              foreach ($_POST as $key => $val) {
                  if ($key != "pg" && $key != "ipp")
                      $this->querystring .= "&amp;$key=" . Validator::sanitize($val);
              }
          }

          if ($this->num_pages > 1) {
              if ($this->current_page != 1 && $this->items_total >= $this->default_ipp) {
                  $this->retdata = "<a class=\"item\" href=\"" . self::buildUrl($prev_page) . "\"><i class=\"icon long arrow left\"></i></a>";
              } else {
                  $this->retdata = "<a class=\"disabled item\"><i class=\"icon long arrow left\"></i></a>";
              }

              $this->start_range = $this->current_page - floor($this->mid_range / 2);
              $this->end_range = $this->current_page + floor($this->mid_range / 2);

              if ($this->start_range <= 0) {
                  $this->end_range += abs($this->start_range) + 1;
                  $this->start_range = 1;
              }
              if ($this->end_range > $this->num_pages) {
                  $this->start_range -= $this->end_range - $this->num_pages;
                  $this->end_range = $this->num_pages;
              }
              $this->range = range($this->start_range, $this->end_range);

              for ($i = 1; $i <= $this->num_pages; $i++) {
                  if ($this->range[0] > 2 && $i == $this->range[0])
                      $this->retdata .= "<a class=\"disabled item\"> ... </a>";

                  if ($i == 1 or $i == $this->num_pages or in_array($i, $this->range)) {
                      if ($i == $this->current_page) {
                          $this->retdata .= "<a title=\"" . Lang::$word->GOTO . $i . Lang::$word->OF . $this->num_pages . "\" class=\"active item\">$i</a>";
                      } else {
                          $this->retdata .= "<a class=\"item\" title=\"Go To $i of $this->num_pages\" href=\"" . self::buildUrl($i) . "\">$i</a>";
                      }
                  }

                  if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->range[$this->mid_range - 1])
                      $this->retdata .= "<a class=\"disabled item\"> ... </a>";
              }

              if ($this->current_page != $this->num_pages && $this->items_total >= $this->default_ipp) {
                  $this->retdata .= "<a class=\"item\" href=\"" . self::buildUrl($next_page) . "\"><i class=\"icon long arrow right\"></i></a>";
              } else {
                  $this->retdata .= "<a class=\"disabled item\"><i class=\"icon long arrow right\"></i></a>";
              }

          } else {
              for ($i = 1; $i <= $this->num_pages; $i++) {
                  if ($i == $this->current_page) {
                      $this->retdata .= "<a class=\"active item\">$i</a>";
                  } else {
                      $this->retdata .= "<a class=\"item\" href=\"" . self::buildUrl($i) . "\">$i</a>";
                  }
              }
          }
          $this->low = ($this->current_page - 1) * $this->items_per_page;
          $this->high = $this->current_page * $this->items_per_page - 1;
          $this->limit = ($this->items_total == 0) ? '' : " LIMIT $this->low,$this->items_per_page";
      }
      
      /**
       * Paginator::items_per_page()
       * 
       * @return
       */
      public function items_per_page()
      {
          $items = '';
          $ipp_array = array(10, 25, 50, 75, 100);

		  $html =  "<div class=\"paginate yoyo dropdown\">";
		  $html .= "<div class=\"yoyo mini caps bold text\">" . Lang::$word->IPP . "</div>";  
		  $html .= "<i class=\"icon dropdown\"></i>";
		  $html .= "<div class=\"small fluid menu\">";
		  if($this->items_total > $this->items_per_page) {
			  foreach ($ipp_array as $ipp_opt) {
				  $active = $ipp_opt == $this->items_per_page ? " active" : null;
				  $html .= "<div class=\"item$active\" data-url=\"" . SITEURL . '/' . App::get('Core')->_urlParts . "\" data-pg=\"1\" data-ipp=\"$ipp_opt\" data-value=\"$ipp_opt\">$ipp_opt</div>";
			  }
		  }
		  $html .= "</div>";
		  $html .= "</div>";
          return $html;
      }
      
      /**
       * Paginator::jump_menu()
       * 
       * @return
       */
      public function jump_menu()
      {
		  
		  
		  $html =  "<div class=\"paginate yoyo dropdown\">";
		  $html .= "<div class=\"yoyo mini caps bold text\">" . Lang::$word->GOTO . "</div>";  
		  $html .= "<i class=\"icon dropdown\"></i>";
		  $html .= "<div class=\"small fluid menu\">";
		  if($this->num_pages >= 1) {
			  for ($i = 1; $i <= $this->num_pages; $i++) {
				  $active = $i == $this->current_page ? " active" : null;
				  $html .= "<div class=\"item\" data-url=\"" . SITEURL . '/' . App::get('Core')->_urlParts . "\" data-pg=\"idx\" data-ipp=\"$this->items_per_page\" data-value=\"$i\">$i</div>";
			  }
		  }
		  $html .= "</div>";
		  $html .= "</div>";
          return $html;
      }
      
      /**
       * Paginator::display_pages()
       * 
       * @return
       */
      public function display_pages($class='')
      {
          return ($this->items_total > $this->items_per_page) ? '<div class="yoyo ' . $class . ' pagination">' . $this->retdata . '</div>' : "";
      }
  }