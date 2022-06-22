<?php
 /**
  * Upload Class
  *
  * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

 class Upload
 {
 	private $maxSize;
 	private $allowedExt;
 	public $fileInfo = array();
 	private static $instance;

 	/**
 	 * Upload::__construct()
 	 * 
 	 * @param mixed $maxSize
 	 * @param mixed $allowedExt
 	 * @return
 	 */
 	private function __construct($maxSize, $allowedExt)
 	{
 		$this->maxSize = $maxSize;
 		$this->allowedExt = $allowedExt;
 	}

 	/**
 	 * Upload::instance()
 	 * 
 	 * @return
 	 */
 	public static function instance($maxSize = null, $allowedExt = null)
 	{
 		if (!self::$instance) {
 			self::$instance = new Upload($maxSize, $allowedExt);
 		}

 		return self::$instance;
 	}

 	/**
 	 * Upload::check()
 	 * 
 	 * @param mixed $uploadName
 	 * @return
 	 */
 	public function check($uploadName)
 	{
 		if (isset($_FILES[$uploadName])) {
 			$this->fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]["name"], '.'), 1);
 			$this->fileInfo['name'] = basename($_FILES[$uploadName]["name"]);
			$this->fileInfo['xame'] = substr($_FILES[$uploadName]["name"], 0, strrpos($_FILES[$uploadName]["name"], "."));
 			$this->fileInfo['size'] = $_FILES[$uploadName]["size"];
 			$this->fileInfo['temp'] = $_FILES[$uploadName]["tmp_name"];

 			if ($this->fileInfo['size'] > $this->maxSize) {
 				Message::$msgs['name'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($this->maxSize);
 				Debug::AddMessage("errors", '<i>Error</i>', 'Uploaded file is larger than allowed.', "session");
 				return false;
 			}

 			if (strlen($this->allowedExt) == 0) {
 				Message::$msgs['name'] = Lang::$word->FU_ERROR9; //no extension specified
 				Debug::AddMessage("errors", '<i>Error</i>', 'Invalid file extension specified.', "session");
 				return false;
 			}

 			$exts = explode(',', $this->allowedExt);
 			if (!in_array(strtolower($this->fileInfo['ext']), $exts)) {
 				Message::$msgs['name'] = Lang::$word->FU_ERROR8 . $this->allowedExt; //no extension specified
 				Debug::AddMessage("errors", '<i>Error</i>', 'Invalid file extension specified.', "session");
 				return false;
 			}

 			if (in_array(strtolower($this->fileInfo['ext']), array(
 				"jpg",
 				"png",
 				"bmp",
 				"gif",
 				"jpeg"))) {
 				if (getimagesize($this->fileInfo['temp']) == false) {
 					Message::$msgs['name'] = Lang::$word->FU_ERROR7; //invalid image
 					Debug::AddMessage("errors", '<i>Error</i>', 'Invalid image detected.', "session");
 					return false;
 				}
 			}

 			return true;
 		}
 		Message::$msgs['name'] = Lang::$word->FU_ERROR11; //Either form not submitted or file/s not found
 		Debug::AddMessage("errors", '<i>Error</i>', 'Temp file could not be found.', "session");
 		return false;

 	}

 	/**
 	 * Upload::quickRandom()
 	 * 
 	 * @param int $length
 	 * @return
 	 */
	public static function quickRandom($length = 24)
	{
		$bytes = openssl_random_pseudo_bytes($length * 2);
		return substr(str_replace(array('/', '+', '='), '', base64_encode($bytes)), 0, $length);
	}

 	/**
 	 * Upload::process()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $dir
 	 * @param bool $fname
 	 * @param mixed $prefix
 	 * @param bool $replace
 	 * @return
 	 */
 	public function process($name, $dir, $prefix = 'SOURCE_', $fname = false, $replace = true)
 	{
 		if (!is_dir($dir)) {
 			Message::$msgs['dir'] = Lang::$word->FU_ERROR12; //Directory doesn't exist!
 		}
 		if ($this->check($name)) {
 			if ($fname == false) {
 				$this->fileInfo['fname'] = $prefix . self::quickRandom() . '.' . $this->fileInfo['ext'];
 			} else {
 				$this->fileInfo['fname'] = $this->fileInfo['name'];
 			}
 			if ($replace) {
 				while (file_exists($dir . $this->fileInfo['fname'])) {
 					$this->fileInfo['fname'] = $prefix . Utility::randName(4) . '.' . $this->fileInfo['ext'];
 				}
 			}
 			if (!move_uploaded_file($this->fileInfo['temp'], $dir . $this->fileInfo['fname'])) {
 				Message::$msgs['name'] = Lang::$word->FU_ERROR13; //File not moved
 				Debug::AddMessage("errors", '<i>Error</i>', 'File could not be moved from temp directory', "session");
 			}
 		}
 	}
 }