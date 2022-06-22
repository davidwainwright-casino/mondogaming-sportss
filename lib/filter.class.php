<?php
  /**
   * Filter Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  class Filter
  {
	  public static $id = null;
	  public static $get = array();
	  public static $post = array();
	  public static $cookie = array();
	  public static $files = array();
	  public static $server = array();
	  
	  
	  /**
	   * Filter::__construct()
	   * 
	   * @return
	   */
	  public function __construct(){}

	  /**
	   * Filter::run()
	   * 
	   * @return
	   */
	  public static function run()
	  {
		  $_GET = self::clean($_GET);
		  $_POST = self::clean($_POST);
		  $_COOKIE = self::clean($_COOKIE);
		  $_FILES  = self::clean($_FILES);
		  $_SERVER = self::clean($_SERVER);
		  
		  self::$get = $_GET;
		  self::$post = $_POST;
		  self::$cookie = $_COOKIE;
		  self::$files  = $_FILES;
		  self::$server = $_SERVER;
		  
		  self::$id = self::getId();
	  }
	  
	  
	  /**
	   * Filter::getId()
	   * 
	   * @return
	   */
	  private static function getId()
	  {
		  if (isset($_REQUEST['id'])) {
			  self::$id = intval($_REQUEST['id']);
			  return self::$id;
		  }
	  }

	  public static function isMobile() {
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));
      }
	  
	  /**
	   * Filter::clean()
	   * 
	   * @param mixed $data
	   * @param bool $utf8_encode
	   * @return
	   */
	  public static function clean($data, $utf8_encode = true)
	  {
		  
		  if (is_array($data)) {
			  return array_map(array(
				  'Filter',
				  'clean'
			  ), $data);
		  }
		  
		  // Fix &entity
		  $data = str_replace(array(
			  '&amp;',
			  '&lt;',
			  '&gt;'
		  ), array(
			  '&amp;amp;',
			  '&amp;lt;',
			  '&amp;gt;'
		  ), $data);

		  //$data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
		  $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		  $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		  $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
		  
		  // Remove any attribute starting with "on" or xmlns
		  //$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
		  $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on)[^>]*+>#iu', '$1>', $data);
		  
		  // Remove javascript: and vbscript: protocols
		  $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
		  
		  // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
		  
		  // Remove namespaced elements (we do not need them)
		  $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
		  
		  do {
			  // Remove really unwanted tags
			  $old_data = $data;
			  //$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed(?:set)?|i(?:layer)|l(?:ayer|ink)|meta|object|title|xml)[^>]*+>#i', '', $data);
			  $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed(?:set)?|i(?:layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		  } while ($old_data !== $data);
		  
		  // we are done...
		  return $data;

	  }
  }