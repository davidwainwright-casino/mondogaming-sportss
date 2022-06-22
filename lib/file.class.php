<?php

  /**
   * File Class
   *
  * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
  

  class File
  {

      /**
       * File::Index()
       * 
       * @return
       */
      public function Index()
      {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->crumbs = ['admin', Lang::$word->META_T20];
		  $tpl->template = 'admin/manager.tpl.php';
		  $tpl->title = Lang::$word->META_T20; 

      }
	  
      /**
       * File::getExtension()
       * 
       * @param mixed $path
       * @return
       */
      public static function getExtension($path)
      {
          return strtolower(pathinfo($path, PATHINFO_EXTENSION));
      }

      /**
       * File::deleteRecrusive()
       * 
       * Usage File::deleteRecrusive("test/dir");
       * @param string $dir
       * @param string $removeParent - remove parent directory
       * @return
       */
      public static function deleteRecrusive($dir = '', $removeParent = false)
      {
          if (is_dir($dir)) {
              $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
              $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
              foreach ($ri as $file) {
                  $file->isDir() ? rmdir($file) : unlink($file);
              }
              $removeParent ? self::deleteDirectory($dir) : null;
              return true;
          } else {
              return true;
          }
      }

      /**
       * File::deleteMulti()
       * 
       * @param string $dir
       * @return
       */
      public static function deleteMulti($dir)
      {
          if (is_dir($dir)) {
			  self::deleteRecrusive($dir, true);
		  } else {
			  self::deleteFile($dir);
		  }
      }
	  
      /**
       * File::deleteDirectory()
       * 
       * @param string $dir
       * @return
       */
      public static function deleteDirectory($dir = '')
      {
          self::emptyDirectory($dir);
          return rmdir($dir);
      }

      /**
       * File::makeDirectory()
       * 
       * /my/path/to/dir/
       * @param string $dir
       * @return
       */
      public static function makeDirectory($dir = '')
      {
          if (!file_exists($dir)) {
              if (false === mkdir($dir, 0755, true)) {
                  self::_errorHanler('directory-error', 'Directory not writable {dir}.', array('{dir}' => $dir));
              }
			  return true;
          }
      }

      /**
       * File::renameDirectory()
       * 
       * /my/path/to/dir
       * @param string $old
       * @param string $new
       * @return
       */
      public static function renameDirectory($old = '', $new = '')
      {
          if (file_exists($old)) {
              if (false === rename($old, $new)) {
                  self::_errorHanler('directory-error', 'Can\'t rename {dir}.', array('{dir}' => $new));
              }
          }
      }

      /**
       * File::emptyDirectory()
       * 
       * @param string $dir
       * @return
       */
      public static function emptyDirectory($dir = '')
      {
          foreach (glob($dir . '/*') as $file) {
              if (is_dir($file)) {
                  self::emptyDirectory($file);
              } else {
                  unlink($file);
              }
          }
          return true;
      }

      /**
       * File::copyDirectory()
       * 
       * Copies content of source directory into destination directory
       * Warning: if the destination file already exists, it will be overwritten
       * @param string $source
       * @param string $dest
       * @param bool $permissions
       * @return
       */
      public static function copyDirectory($source, $dest, $permissions = 0755)
      {
		  if (is_link($source)) {
		  	return symlink(readlink($source), $dest);
		  }
		  
		  if (is_file($source)) {
			  return copy($source, $dest);
		  }
		  
		  if (!is_dir($dest)) {
			  mkdir($dest, $permissions, true);
		  }
		  
		  $dir = dir($source);
		  while (false !== $entry = $dir->read()) {
			  // Skip pointers
			  if ($entry == '.' || $entry == '..') {
				  continue;
			  }
	  
			  // Deep copy directories
			  self::copyDirectory("$source/$entry", "$dest/$entry", $permissions);
		  }
		  
		  $dir->close();
		  return true;
      }
	  
      /**
       * File::isDirectoryEmpty()
       * 
       * @param string $dir
       * @return
       */
      public static function isDirectoryEmpty($dir = '')
      {
          if ($dir == '' || !is_readable($dir))
              return false;
          $hd = opendir($dir);
          while (false !== ($entry = readdir($hd))) {
              if ($entry !== '.' && $entry !== '..') {
                  return false;
              }
          }
          closedir($hd);
          return true;
      }

      /**
       * File::getDirectoryFilesNumber()
       * 
       * @param string $dir
       * @return
       */
      public static function getDirectoryFilesNumber($dir = '')
      {
          return count(glob($dir . '*'));
      }

      /**
       * File::removeDirectoryOldestFile()
       * 
       * @param string $dir
       * @return
       */
      public static function removeDirectoryOldestFile($dir = '')
      {
          $oldestFileTime = date('Y-m-d H:i:s');
          $oldestFileName = '';
          if ($hdir = opendir($dir)) {
              while (false !== ($obj = readdir($hdir))) {
                  if ($obj == '.' || $obj == '..' || $obj == '.htaccess')
                      continue;
                  $fileTime = date('Y-m-d H:i:s', filectime($dir . $obj));
                  if ($fileTime < $oldestFileTime) {
                      $oldestFileTime = $fileTime;
                      $oldestFileName = $obj;
                  }
              }
          }
          if (!empty($oldestFileName)) {
              self::deleteFile($dir . $oldestFileName);
          }
      }

      /**
       * File::findSubDirectories()
       * 
       * @param string $dir
       * @param bool $fullPath
       * @return
       */
      public static function findSubDirectories($dir = '.', $fullPath = false)
      {
          $subDirectories = array();
          $folder = dir($dir);
          while ($entry = $folder->read()) {
              if ($entry != '.' && $entry != '..' && is_dir($dir . $entry)) {
                  $subDirectories[] = ($fullPath ? $dir : '') . $entry;
              }
          }
          $folder->close();
          return $subDirectories;
      }


      /**
       * File::scanDirectory()
       * 
       * @param string $dir
       * @param bool $options
       * @param bool $sorting
       * @return
       */
      public static function scanDirectory($directory, $options = array(), $sorting)
      {

          if (substr($directory, -1) == '/') {
              $directory = substr($directory, 0, -1);
          }
          $base = UPLOADS;
		  
          if (!file_exists($directory) || !is_dir($directory)) {
              self::_errorHanler('directory-error', 'Invalid directory selected {dir}.', array('{dir}' => $directory));
              return false;

          } elseif (is_readable($directory)) {
              $dirs = array();
              $files = array();

              $exclude = array(
                  "htaccess",
                  "git",
                  "php");

              $dirfiles = new DirectoryIterator($directory);
              foreach ($dirfiles as $file) {
                  $path = $directory . '/' . $file->getBasename();
                  $real_path = isset($options['showpath']) ? $path : str_replace(UPLOADS, "", $path);
                  if ($file->isDot() or in_array($file, array("thumbs", "backups")))
                      continue;

                  if ($file->isDir()) {
                      $dirs[] = array(
                          'path' => $real_path,
                          'url' => self::_fixPath(str_replace(UPLOADS, "", $file->getBasename()) . "/"),
                          'name' => str_replace("_", " ", $file->getBasename()),
                          'kind' => 'directory',
                          'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS)));
                  }

                  if ($file->isFile()) {
                      if (isset($options['include'])) {
                          $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                      } else {
                          $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                      }

                      if ($file->getBasename() != "." && $file->getBasename() != ".." && $filter) {
						  $url = self::_fixPath(str_replace($base, "", $file->getPathname()));
                          $files[] = array(
                              'path' => $real_path,
							  'url' => ltrim($url, '/'),
                              'name' => $file->getBasename(),
                              'extension' => $file->getExtension(),
							  'dir' => pathinfo($real_path, PATHINFO_DIRNAME),
                              'mime' => self::getMimeType($file->getPathname()),
                              'is_image' => in_array($file->getExtension(), array(
                                  "jpg",
                                  "jpeg",
								  "svg",
                                  "png",
                                  "gif",
                                  "bmp")) ? true : false,
                              'ftime' => Date::doDate("short_date", date('d-m-Y', $file->getMTime())),
                              'size' => File::getSize($file->getSize()),
                              'kind' => 'file');
                      }
                  }
              }

              $data['directory'] = $dirs;
			  $data['dirsize'] = count($dirs);
			  $data['filesize'] = count($files);

              switch ($sorting) {
                  case "date":
                      $data['files'] = Utility::sortArray($files, 'ftime');
                      break;

                  case "size":
                      $data['files'] = Utility::sortArray($files, 'size');
                      break;

                  case "name":
                      $data['files'] = Utility::sortArray($files, 'name');
                      break;

                  case "type":
                      $data['files'] = Utility::sortArray($files, 'extension');
                      break;

                  default:
                      $data['files'] = $files;
                      break;
              }


              return $data;
          } else {
              self::_errorHanler('directory-error', 'Directory not readable {dir}.', array('{dir}' => $directory));
              return false;
          }
      }

      /**
       * File::scanDirectoryRecursively()
       * 
       * @param string $dir
       * @param bool $options
       * @return
       */
      public static function scanDirectoryRecursively($directory, $options = array())
      {
          if (substr($directory, -1) == '/') {
              $directory = substr($directory, 0, -1);
          }

          if (!file_exists($directory) || !is_dir($directory)) {
              self::_errorHanler('directory-error', 'Invalid directory selected {dir}.', array('{dir}' => $directory));
              return false;

          } elseif (is_readable($directory)) {
              $iterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
              $all_files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::SELF_FIRST);

              $dirs = array();
              $files = array();
              $exclude = array(
                  "htaccess",
                  "git",
                  "php");

              foreach ($all_files as $file) {
                  $path = $directory . '/' . $file->getBasename();
                  $real_path = isset($options['showpath']) ? $path : str_replace(UPLOADS, "", $path);

                  if ($file->isDir()) {
                      $dirs[] = array(
                          'path' => $real_path,
                          'url' => str_replace(BASEPATH, "", $file->getPathname()) . "/",
                          'name' => str_replace("_", " ", $file->getBasename()),
                          'kind' => 'directory',
                          'total' => iterator_count(new FilesystemIterator($file->getPathname(), FilesystemIterator::SKIP_DOTS)));
                  }

                  if ($file->isFile()) {
                      if (isset($options['include'])) {
                          $filter = in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $options['include']);
                      } else {
                          $filter = !in_array(pathinfo($file->getBasename(), PATHINFO_EXTENSION), $exclude);
                      }

                      if ($file->getBasename() != "." && $file->getBasename() != ".." && $filter) {
                          $files[] = array(
                              'path' => $path,
                              'url' => self::_fixPath(str_replace(BASEPATH, "", $file->getPathname())),
                              'name' => $file->getBasename(),
                              'extension' => $file->getExtension(),
                              //'mime' => self::getMimeType($file->getBasename()),
                              'is_image' => in_array($file->getExtension(), array(
                                  "jpg",
                                  "jpeg",
                                  "png",
                                  "gif",
                                  "bmp")) ? true : false,
                              'ftime' => Date::doDate("short_date", date('d-m-Y', $file->getMTime())),
                              'size' => File::getSize($file->getSize()),
                              'kind' => 'file');
                      }

                  }
              }

              $data['directory'] = $dirs;
              $data['files'] = $files;
              return $data;
          } else {
              self::_errorHanler('directory-error', 'Directory not readable {dir}.', array('{dir}' => $directory));
              return false;
          }
      }

      /**
       * File::is_File()
       * 
       * @param string $file
       * @return
       */
	  public static function is_File($file = '')
	  {
		  if (file_exists($file)) {
			  return true;
		  } else {
			  return false;
		  }
	
	  }
	  
      /**
       * File::getFile()
       * 
       * @param string $file
       * @return
       */
	  public static function getFile($file = '')
	  {
		  if (file_exists($file)) {
			  return $file;
		  } else {
			  self::_errorHanler('file-loading-error', 'An error occurred while fetching file {file}.', array('{file}' => $file));
		  }
	
	  }
	  
      /**
       * File::loadFile()
       * 
       * @param string $file
       * @return
       */
      public static function loadFile($file = '')
      {
          $content = file_get_contents($file);
          self::_errorHanler('file-loading-error', 'An error occurred while loading file {file}.', array('{file}' => $file));
          return $content;
      }

      /**
       * File::writeToFile()
       * 
       * @param string $file
       * @param string $content
       * @return
       */
      public static function writeToFile($file = '', $content = '')
      {
          file_put_contents($file, $content);
          self::_errorHanler('file-writing-error', 'An error occurred while writing to file {file}.', array('{file}' => $file));
          return true;
      }

      /**
       * File::copyFile()
       * 
       * @param string $src (absolute path BASEPATH . $src)
       * @param string $dest (absolute path BASEPATH . $dest)
       * @return
       */
      public static function copyFile($src = '', $dest = '')
      {
          $result = copy($src, $dest);
          self::_errorHanler('file-coping-error', 'An error occurred while copying the file {source} to {destination}.', array('{source}' => $src, '{destination}' => $dest));
          return $result;
      }

      /**
       * File::findFiles()
       * 
       * Returns the files found under the given directory and subdirectories
       * Usage:
       * findFiles(
       *    $dir,
       *    array(
       *       'fileTypes'=>array('php', 'zip'),
       *   	 'exclude'=>array('html', 'htaccess', 'path/to/'),
       *   	 'level'=>-1,
       *       'returnType'=>'fileOnly'
       *  ))
       * fileTypes: array, list of file name suffix (without dot). 
       * exclude: array, list of directory and file exclusions. Each exclusion can be either a name or a path.
       * level: integer, recursion depth, (-1 - unlimited depth, 0 - current directory only, N - recursion depth)
       * returnType : 'fileOnly' or 'fullPath'
       * @param mixed $dir
       * @param mixed $options
       * @return
       */
      public static function findFiles($dir, $options = array())
      {
          $fileTypes = isset($options['fileTypes']) ? $options['fileTypes'] : array();
          $exclude = isset($options['exclude']) ? $options['exclude'] : array();
          $level = isset($options['level']) ? $options['level'] : -1;
          $returnType = isset($options['returnType']) ? $options['returnType'] : 'fileOnly';
          $filesList = self::_findFilesRecursive($dir, '', $fileTypes, $exclude, $level, $returnType);
          sort($filesList);
          return $filesList;
      }

      /**
       * File::scanFiles()
       * 
       * @param string $file
	   * @param string $extension (*php)
       * @return
       */
      public static function scanFiles($dir, $extension)
      {
		  $dirs = glob($dir.'*', GLOB_ONLYDIR);
		  $files = array();
		  foreach( $dirs as $d ) {
			  $file = glob($d .'/'. $extension);
			  if(count($file)) {
				  $files = array_merge($files, $file);
			  }
		  }
		  return $files;
      }
	  
      /**
       * File::deleteFile()
       * 
       * @param string $file
       * @return
       */
      public static function deleteFile($file = '')
      {
          $result = false;
          if (is_file($file)) {
              $result = unlink($file);
          }
          self::_errorHanler('file-deleting-error', 'An error occurred while deleting the file {file}.', array('{file}' => $file));
          return $result;
      }

      /**
       * File::getThemes()
       * 
       * @param mixed $dir
       * @return
       */
      public static function getThemes($dir)
      {
          $directories = glob($dir . '/*', GLOB_ONLYDIR);
		  $themes = [];
          if ($directories) {
              foreach ($directories as $row) {
                  $themes[] = $dir = basename($row);
				  //$selected = ($selected == $dir) ? " selected=\"selected\"" : "";
				  //print "<option value=\"{$dir}\"{$selected}>{$dir}</option>\n";
			  }
          }
		  return $themes;
      }

      /**
       * File::getMailerTemplates()
       * 
       * @return
       */
      public static function getMailerTemplates()
      {
          $path = BASEPATH . "themes/mailer/";
          $files = glob($path . "*.{tpl.php}", GLOB_BRACE);

          return $files;
      }

      /**
       * File::getFileSize()
       * 
       * @param mixed $file
       * @param string $units
       * @param bool $print
       * @return
       */
      public static function getFileSize($file, $units = 'kb', $print = false)
      {
          if (!$file || !is_file($file))
              return 0;
          $showunit = $print ? $units : null;
          $filesSize = filesize($file);
          switch (strtolower($units)) {
              case 'g':
              case 'gb':
                  $result = number_format($filesSize / (1024 * 1024 * 1024), 2, '.', ',') . $showunit;
                  ;
                  break;
              case 'm':
              case 'mb':
                  $result = number_format($filesSize / (1024 * 1024), 2, '.', ',') . $showunit;
                  ;
                  break;
              case 'k':
              case 'kb':
                  $result = number_format($filesSize / 1024, 2, '.', ',') . $showunit;
                  break;
              case 'b':
              default:
                  $result = number_format($filesSize, 2, '.', ',') . $showunit;
                  ;
                  break;
          }
          return $result;
      }

      /**
       * File::getSize()
       * 
       * @param mixed $size
       * @param str $precision
       * @return
       */
      public static function getSize($size, $precision = 2)
      {
		  $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		  $step = 1024;
		  $i = 0;
		  while (($size / $step) > 0.9) {
			  $size = $size / $step;
			  $i++;
		  }
		  return round($size, $precision).$units[$i];
      }

      /**
       * File::directorySize()
       * 
       * @param str $dir
	   * @param bool $format
       * @return
       */
	  public static function directorySize($dir, $format = false){
		  $btotal = 0;
		  $dir = realpath($dir);
		  if($dir!==false){
			  foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)) as $obj){
				  $btotal += $obj->getSize();
			  }
		  }
		  return $format ? self::getSize($btotal) : $btotal;
	  }

      /**
       * File::unzip()
       * 
       * @param str $archive
       * @param str $dir
       * @param bool $delete
       * @return
       */
      public static function unzip($archive, $dir)
      {

          // Check if webserver supports unzipping.
          if (!class_exists('ZipArchive')) {
              self::_errorHanler('zip-error', 'Your PHP version does not support unzip functionality.', array('{zip}' => 'ZipArchive'));
              return false;
          }

          if (substr($dir, -1) == '/') {
              $dir = substr($dir, 0, -1);
          }

          if (!file_exists($archive) || !is_dir($dir)) {
              self::_errorHanler('directory-error', 'Invalid directory or file selected {dir}.', array('{dir}' => $dir));
              return false;

          } elseif (is_writeable($dir . '/')) {
              $zip = new ZipArchive;
              if ($zip->open($archive) === true) {
                  $zip->extractTo($dir);
                  $zip->close();
              } else {
                  self::_errorHanler('zip-error', 'Cannot read .zip archive.', array('{zip}' => $archive));
              }

              return true;
          } else {
              self::_errorHanler('directory-error', 'Directory not writeable {dir}.', array('{dir}' => $dir));
              return false;
          }
      }

 	/**
 	 * File::upload()
 	 * 
 	 * @param mixed $uploadName
	 * @param mixed $maxSize
	 * @param mixed $allowedExt
 	 * @return
 	 */
	  public static function upload($uploadName, $maxSize = null, $allowedExt = null)
	  {
	
		  if (isset($_FILES[$uploadName])) {
			  $fileInfo['ext'] = substr(strrchr($_FILES[$uploadName]["name"], '.'), 1);
			  $fileInfo['name'] = basename($_FILES[$uploadName]["name"]);
			  $fileInfo['xame'] = substr($_FILES[$uploadName]["name"], 0, strrpos($_FILES[$uploadName]["name"], "."));
			  $fileInfo['size'] = $_FILES[$uploadName]["size"];
			  $fileInfo['temp'] = $_FILES[$uploadName]["tmp_name"];
	
			  if ($fileInfo['size'] > $maxSize) {
				  Message::$msgs['name'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
				  return false;
	
			  }
			  if (strlen($allowedExt) == 0) {
				  Message::$msgs['name'] = Lang::$word->FU_ERROR9; //no extension specified
				  return false;
	
			  }
			  $exts = explode(',', $allowedExt);
			  if (!in_array(strtolower($fileInfo['ext']), $exts)) {
				  Message::$msgs['name'] = Lang::$word->FU_ERROR8 . $allowedExt; //no extension specified
				  return false;
	
			  }
			  if (in_array(strtolower($fileInfo['ext']), array(
				  "jpg",
				  "png",
				  "bmp",
				  "gif",
				  "jpeg"))) {
				  if (getimagesize($fileInfo['temp']) == false) {
					  Message::$msgs['name'] = Lang::$word->FU_ERROR7; //invalid image
					  return false;
	
				  }
			  }
			  return $fileInfo;
		  } else {
			  Message::$msgs['name'] = Lang::$word->FU_ERROR11; //Either form not submitted or file/s not found
			  return false;
		  }
	  }

 	/**
 	 * File::process()
 	 * 
 	 * @param mixed $name
 	 * @param mixed $dir
 	 * @param bool $fname
 	 * @param mixed $prefix
 	 * @param bool $replace
 	 * @return
 	 */
	  public static function process($result, $dir, $prefix = 'SOURCE_', $fname = false, $replace = true)
	  {
		  if (!is_dir($dir)) {
			  Message::$msgs['dir'] = Lang::$word->FU_ERROR12; //Directory doesn't exist!
		  }
	
		  if ($fname == false) {
			  $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
		  } else {
			  $fileInfo['fname'] = $fname;
		  }
		  if ($replace) {
			  while (file_exists($dir . $fileInfo['fname'])) {
				  $fileInfo['fname'] = $prefix . Utility::randomString(12) . '.' . $result['ext'];
			  }
		  }
		  if (move_uploaded_file($result['temp'], $dir . $fileInfo['fname'])) {
			  return array_merge($result, $fileInfo);
		  } else {
			  Debug::AddMessage("errors", '<i>Error</i>', 'File could not be moved from temp directory', "session");
		  }
	
	  }
	  
	  
      /**
       * File::createShortenName()
       * 
       * @param mixed $file
       * @param integer $lengthFirst
       * @param integer $lengthLast
       * @return
       */
      public static function createShortenName($file, $lengthFirst = 10, $lengthLast = 10)
      {
          return preg_replace("/(?<=.{{$lengthFirst}})(.+)(?=.{{$lengthLast}})/", "...", $file);
      }


      /**
       * File::getMimeType()
       * 
       * @param mixed $file
       * @return
       */
      public static function getMimeType($file)
      {

          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          $mtype = finfo_file($finfo, $file);
          finfo_close($finfo);

          return $mtype;
      }

      /**
       * File::readIni()
       * 
       * @param mixed $file
       * @return
       */
	  public static function readIni($file = null)
	  {
		  if (empty($file)) {
			  self::_errorHanler('directory-error', 'File does not exists.', array('{file}' => $file));
			  return false;
		  }
		  $result = parse_ini_file(realpath($file), true);
		  $result = json_encode($result);
		  return json_decode($result);
	  }
	
	  /**
	   * File::writeIni()
	   * 
	   * @param mixed $file
	   * @param mixed $data
	   * @param bool $sections
	   * @return
	   */
	  public static function writeIni($file = null, $data = array(), $sections = true)
	  {
	
		  $content = null;
	
		  if ($sections) {
			  foreach ($data as $section => $rows) {
				  $content .= '[' . $section . ']' . PHP_EOL;
				  foreach ($rows as $key => $val) {
					  if (is_array($val)) {
						  foreach ($val as $v) {
							  $content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
						  }
					  } elseif (empty($val)) {
						  $content .= $key . ' = ' . PHP_EOL;
					  } else {
						  $content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
					  }
				  }
				  $content .= PHP_EOL;
			  }
		  } else {
			  foreach ($data as $key => $val) {
				  if (is_array($val)) {
					  foreach ($val as $v) {
						  $content .= $key . '[] = ' . (is_numeric($v) ? $v : '"' . $v . '"') . PHP_EOL;
					  }
				  } elseif (empty($val)) {
					  $content .= $key . ' = ' . PHP_EOL;
				  } else {
					  $content .= $key . ' = ' . (is_numeric($val) ? $val : '"' . $val . '"') . PHP_EOL;
				  }
			  }
		  }

		  return self::writeToFile($file, trim($content));
	  }

      /**
       * File::download()
       * 
       * @param mixed $fileLocation
	   * @param mixed $fileName
	   * @param int $maxSpeed
       * @return
       */
	  public static function download($fileLocation, $fileName, $maxSpeed = 1024)
	  {
		  if (connection_status() != 0)
			  return (false);
		  $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));
	
		  /* List of File Types */
		  $fileTypes['swf'] = 'application/x-shockwave-flash';
		  $fileTypes['pdf'] = 'application/pdf';
		  $fileTypes['txt'] = 'text/plain';
		  $fileTypes['exe'] = 'application/octet-stream';
		  $fileTypes['zip'] = 'application/zip';
		  $fileTypes['doc'] = 'application/msword';
		  $fileTypes['xls'] = 'application/vnd.ms-excel';
		  $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
		  $fileTypes['gif'] = 'image/gif';
		  $fileTypes['png'] = 'image/png';
		  $fileTypes['jpeg'] = 'image/jpg';
		  $fileTypes['jpg'] = 'image/jpg';
		  $fileTypes['rar'] = 'application/rar';
	
		  $fileTypes['ra'] = 'audio/x-pn-realaudio';
		  $fileTypes['ram'] = 'audio/x-pn-realaudio';
		  $fileTypes['ogg'] = 'audio/x-pn-realaudio';
	
		  $fileTypes['wav'] = 'video/x-msvideo';
		  $fileTypes['wmv'] = 'video/x-msvideo';
		  $fileTypes['avi'] = 'video/x-msvideo';
		  $fileTypes['asf'] = 'video/x-msvideo';
		  $fileTypes['divx'] = 'video/x-msvideo';
	
		  $fileTypes['mp3'] = 'audio/mpeg';
		  $fileTypes['mp4'] = 'audio/mpeg';
		  $fileTypes['mpeg'] = 'video/mpeg';
		  $fileTypes['mpg'] = 'video/mpeg';
		  $fileTypes['mpe'] = 'video/mpeg';
		  $fileTypes['mov'] = 'video/quicktime';
		  $fileTypes['swf'] = 'video/quicktime';
		  $fileTypes['3gp'] = 'video/quicktime';
		  $fileTypes['m4a'] = 'video/quicktime';
		  $fileTypes['aac'] = 'video/quicktime';
		  $fileTypes['m3u'] = 'video/quicktime';
	
		  $contentType = $fileTypes[$extension];
	
	
		  header("Cache-Control: public");
		  header("Content-Transfer-Encoding: binary\n");
		  header('Content-Type: $contentType');
	
		  $contentDisposition = 'attachment';
	
		  if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			  $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
			  header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
		  } else {
			  header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
		  }
	
		  header("Accept-Ranges: bytes");
		  $range = 0;
		  $size = filesize($fileLocation);
	
		  if (isset($_SERVER['HTTP_RANGE'])) {
			  list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
			  str_replace($range, "-", $range);
			  $size2 = $size - 1;
			  $new_length = $size - $range;
			  header("HTTP/1.1 206 Partial Content");
			  header("Content-Length: $new_length");
			  header("Content-Range: bytes $range$size2/$size");
		  } else {
			  $size2 = $size - 1;
			  header("Content-Range: bytes 0-$size2/$size");
			  header("Content-Length: " . $size);
		  }
	
		  if ($size == 0) {
			  die('Zero byte file! Aborting download');
		  }
	
		  $fp = fopen("$fileLocation", "rb");
	
		  fseek($fp, $range);
	
		  while (!feof($fp) and (connection_status() == 0)) {
			  set_time_limit(0);
			  print (fread($fp, 1024 * $maxSpeed));
			  flush();
			  @ob_flush();
			  sleep(1);
		  }
		  fclose($fp);
		  exit;
	
		  return ((connection_status() == 0) and !connection_aborted());
	  } 

      /**
       * File::parseSQL()
       * 
       * @param mixed $content
       * @return
       */
	  public static function parseSQL($content)
	  {
	
		  $sqlList = array();
		  $lines = explode("\n", file_get_contents($content));
		  $query = "";
	
		  foreach ($lines as $sql_line) {
			  $sql_line = trim($sql_line);
			  if ($sql_line === "") {
				  continue;
			  } else {
				  if (strpos($sql_line, "--") === 0) {
					  continue;
				  } else {
					  if (strpos($sql_line, "#") === 0) {
						  continue;
					  }
				  }
				  $query .= $sql_line;
				  if (preg_match("/(.*);/", $sql_line)) {
					  $query = trim($query);
					  $query = substr($query, 0, strlen($query) - 1);
					  $sqlList[] = $query . ';';
					  $query = "";
				  }
			  }
	
		  }
		  return $sqlList;
	  }
  
      /**
       * File::exists()
       * 
       * @param mixed $file
       * @return
       */
      public static function exists($file)
      {

          return file_exists($file) ? true : false;
      }

      /**
       * File::_fixPath()
       * 
       * @param mixed $path
       * @return
       */
      public static function _fixPath($path)
      {
          $path = str_replace('\\', '/', $path);
          $path = preg_replace("#/+#", "/", $path);

          return $path;
      }

      /**
       * File::_findFilesRecursive()
       * 
       * @param mixed $dir
       * @param mixed $base
       * @param mixed $fileTypes
       * @param mixed $exclude
       * @param mixed $level
       * @param string $returnType
       * @return
       */
      protected static function _findFilesRecursive($dir, $base, $fileTypes, $exclude, $level, $returnType = 'fileOnly')
      {
          $list = array();
          if ($hdir = opendir($dir)) {
              while (($file = readdir($hdir)) !== false) {
                  if ($file === '.' || $file === '..')
                      continue;
                  $path = $dir . '/' . $file;
                  $isFile = is_file($path);
                  if (self::_validatePath($base, $file, $isFile, $fileTypes, $exclude)) {
                      if ($isFile) {
                          $list[] = ($returnType == 'fileOnly') ? $file : $path;
                      } else
                          if ($level) {
                              $list = array_merge($list, self::_findFilesRecursive($path, $base . '/' . $file, $fileTypes, $exclude, $level - 1, $returnType));
                          }
                  }
              }
          }
          closedir($hdir);
          return $list;
      }

      /**
       * File::validateDirectory()
       * 
       * @param mixed $basepath
       * @param mixed $userpath
       * @return
       */
      public static function validateDirectory($basepath, $userpath)
      {

          $realBase = realpath($basepath);
          $userpath = $basepath . $userpath;
          $realUserPath = realpath($userpath);

          return ($realUserPath === false || strpos($realUserPath, $realBase) !== 0) ? $basepath : $userpath;
      }

      /**
       * File::_validatePath()
       * 
       * @param mixed $base
       * @param mixed $file
       * @param mixed $isFile
       * @param mixed $fileTypes
       * @param mixed $exclude
       * @return
       */
      protected static function _validatePath($base, $file, $isFile, $fileTypes, $exclude)
      {
          foreach ($exclude as $e) {
              if ($file === $e || strpos($base . '/' . $file, $e) === 0)
                  return false;
          }
          if (!$isFile || empty($fileTypes))
              return true;
          if (($type = pathinfo($file, PATHINFO_EXTENSION)) !== '') {
              return in_array($type, $fileTypes);
          } else {
              return false;
          }
      }

      /**
       * File::_errorHanler()
       * 
       * @param string $msgType
       * @param string $msg
       * @return
       */
      private static function _errorHanler($msgType = '', $msg = '')
      {
          if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
              $err = error_get_last();
              if (isset($err['message']) && $err['message'] != '') {
                  $lastError = $err['message'] . ' | file: ' . $err['file'] . ' | line: ' . $err['line'];
                  $errorMsg = ($lastError) ? $lastError : $msg;
                  Debug::addMessage('errors', $msgType, $errorMsg, 'session');
                  @trigger_error('');
              }
          }
      }

  }