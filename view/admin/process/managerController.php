<?php
  /**
   * Manager Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */

  define("_YOYO", true);
  require_once ("../../../init.php");

  if (!App::Auth()->is_Admin())
      exit;

  $action = (isset($_REQUEST['action'])) ? $_REQUEST['action'] : null;

  switch ($action):
      /* == Get Files == */
      case "getFiles":
          $include = null;
          if ($type = Validator::notEmptyGet('exts')):
              switch ($type) {
                  case "doc":
                      $include = array("include" => array(
                              "txt",
                              "doc",
                              "docx",
                              "pdf",
                              "xls",
                              "xlsx",
                              "css",
                              "nfo"));
                      break;

                  case "pic":
                      $include = array("include" => array(
                              "jpg",
                              "jpeg",
                              "bmp",
                              "png"));
                      break;

                  case "vid":
                      $include = array("include" => array(
                              "mp4",
                              "avi",
                              "sfw",
                              "webm",
                              "ogv",
                              "mov"));
                      break;

                  case "aud":
                      $include = array("include" => array("mp3", "wav"));
                      break;

                  default:
                      $include = null;
                      break;
              }

          endif;

          $result = File::scanDirectory(File::validateDirectory(UPLOADS, Validator::get('dir')), $include, Validator::get('sorting'));
          print json_encode($result);
          break;

      /* == Remote Images == */
      case "getImages":
          $result = File::scanDirectory(UPLOADS . '/images', array("include" => array("jpg","jpeg","bmp","png","svg")), "name");
		  $list = array();
		  foreach($result['files'] as $row) :
		      $clean = preg_replace('/\\.[^.\\s]{3,4}$/', '', $row['name']);
			  $item = array(
				  'url' => UPLOADURL . '/' . $row['url'], 
				  'thumb' => UPLOADURL . '/thumbs/' . $row['name'], 
				  'id' => strtolower($clean),
				  'title' => $clean,
			  );
			  $list[] = $item;
		  endforeach;
		  print json_encode($list);
		  
      /* == New Folder == */
      case "newFolder":
          if (isset($_POST['name'])):
              if(File::makeDirectory(UPLOADS . '/' . Validator::sanitize($_POST['dir'] . '/' . $_POST['name'], "file"))) :
			     $json['type'] = "success";
				 else:
				 $json['error'] = "error";
			  endif;
			  print json_encode($json);
          endif;
		  
      /* == Unzip File == */
      case "unzip":
          if (isset($_POST['item'])):
		      $dir = pathinfo(UPLOADS . '/' . $_POST['item']);
              if(File::unzip(UPLOADS . '/' . $_POST['item'], $dir['dirname'])) :
			     $json['type'] = "success";
				 else:
				 $json['error'] = "error";
			  endif;
			  print json_encode($json);
          endif;
		  
      /* == Delete Files Folders == */
      case "delete":
          if (isset($_POST['items'])):
              foreach ($_POST['items'] as $item):
                  File::deleteMulti(UPLOADS . '/' . $item);
              endforeach;
			  $json['type'] = "success";
			  print json_encode($json);
          endif;

      /* == Upload == */
      case "upload":
          if (!empty($_FILES['file']['name'])):
		      $dir = File::validateDirectory(UPLOADS, Validator::post('dir')) . '/';
		      $upl = Upload::instance(App::Core()->file_size, App::Core()->file_ext);
			  $upl->process("file", $dir, false, $_FILES['file']['name'], false);
			  if (empty(Message::$msgs)):
				  $img = new Image($dir . $upl->fileInfo['fname']);
				  if ($img->originalInfo['width']):
					  try {
						  $img = new Image($dir. $upl->fileInfo['fname']);
						  $img->fitToWidth(200)->save(UPLOADS . '/thumbs/' . $upl->fileInfo['fname']);
					  }
					  catch (exception $e) {
						  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
					  }
					  $json['filename'] = UPLOADURL . '/' . Validator::post('dir') . '/' . $upl->fileInfo['fname'];
				  else:
					  $json['filename'] = ADMINVIEW . "/images/mime/" . $upl->fileInfo['ext'] . ".png";
				  endif;
			  $json['type'] = "success";
			  else:
				  $json['type'] = "error";
				  $json['filename'] = '';
				  $json['message'] = Message::$msgs['name'];
			  endif;
			  print json_encode($json);
          endif;
          break;
		  
      /* == Editor Upload == */
      case "eupload":
		  if (!empty($_FILES['file']['name'])):
			  $dir = UPLOADS . '/images/';
			  $num_files = count($_FILES['file']['tmp_name']);
			  $jsons = [];
			  $exts = ['image/png', 'image/jpg', 'image/gif', 'image/jpeg', 'image/pjpeg'];
		
			  foreach ($_FILES['file']['name'] as $x => $name):
				  $ext = substr(strrchr($_FILES['file']["name"][$x], '.'), 1);
				  $image = $_FILES['file']["name"][$x];
				  if ($_FILES["file"]["tmp_name"][$x] > App::Core()->file_size):
					  $json['error'] = true;
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR10 . ' ' . File::getSize($maxSize);
					  print json_encode($json);
					  exit;
				  endif;
		
				  $ext = strtolower($_FILES['file']['type'][$x]);
				  if (!in_array($ext, $exts)):
					  $json['error'] = true;
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR8 . "jpg, png, jpeg"; //invalid extension
					  print json_encode($json);
					  exit;;
				  endif;

				  if (file_exists($dir . $image)):
					  $json['error'] = true;
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR6; //file exists
					  print json_encode($json);
					  exit;;
				  endif;
				  
				  if (getimagesize($_FILES['file']["tmp_name"][$x]) == false):
					  $json['error'] = true;
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR7; //invalid image
					  print json_encode($json);
					  exit;;
				  endif;
		
				  if (!move_uploaded_file($_FILES['file']['tmp_name'][$x], $dir . $image)):
					  $json['error'] = true;
					  $json['type'] = "error";
					  $json['title'] = Lang::$word->ERROR;
					  $json['message'] = Message::$msgs['name'] = Lang::$word->FU_ERROR13; //cant move  image
					  print json_encode($json);
					  exit;;
				  endif;
		
				  if (empty(Message::$msgs)):
					  try {
						  $img = new Image($dir . $image);
						  $img->fitToWidth(200)->save(UPLOADS . '/thumbs/' . $image);
						  
						  $jsons['file-'.$x] = array(
						      'url' => UPLOADURL . '/images/' . $image, 'id' => $x
						  );
					  }
					  catch (exception $e) {
						  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
					  }
				  endif;
			  endforeach;
			  print json_encode($jsons);
		  endif;
          break;
  endswitch;