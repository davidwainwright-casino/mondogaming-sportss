<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  define("_YOYO", true);
  require_once("../../../../init.php");
  
  if (!App::Auth()->is_Admin())
      exit;
	  
  Bootstrap::Autoloader(array(AMODPATH . 'gallery/'));

  $delete = Validator::post('delete');
  $action = Validator::request('action');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Gallery == */
      case "deleteGallery":
          if ($row = Db::run()->first(Gallery::mTable, array("dir"), array("id" =>Filter::$id))):
			  $res = Db::run()->delete(Gallery::mTable, array("id" => Filter::$id));
			  Db::run()->delete(Gallery::dTable, array("parent_id" => Filter::$id));
			  Db::run()->delete(Modules::mcTable, array("parent_id" => Filter::$id, "section" => "gallery"));
			  Db::run()->delete(Modules::mTable, array("parent_id" => Filter::$id, "modalias" => "gallery"));
			  File::deleteRecrusive(FMODPATH . Gallery::GALDATA . $row->dir, true);
          endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_GA_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
      /* == Delete Photo == */
      case "deletePhoto":
          if ($row = Db::run()->first(Gallery::dTable, array("thumb"), array("id" =>Filter::$id))):
			  $res = Db::run()->delete(Gallery::dTable, array("id" => Filter::$id));
			  File::deleteFile(FMODPATH . Gallery::GALDATA . $_POST['dir'] . '/' . $row->thumb);
			  File::deleteFile(FMODPATH . Gallery::GALDATA . $_POST['dir'] . '/w_' . $row->thumb);
          endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_MOD_GA_PHOTO_DEL_OK);
          Message::msgReply($res, 'success', $message);
          break;
  endswitch;
  
  /* == Actions == */
  switch ($action):
      /* == Process Gallery == */
      case "processGallery":
          App::Gallery()->processGallery();
      break;
      /* == Resize Images == */
      case "resizeImages":
          App::Gallery()->resizeImages();
      break;
      /* == Upload == */
      case "upload":
          if (!empty($_FILES['file']['name'])):
		      $dir = File::validateDirectory(FMODPATH . Gallery::GALDATA, Validator::post('dir')) . '/';
		      $upl = Upload::instance(App::Core()->file_size, "png,jpg,jpeg");
			  $upl->process("file", $dir, false, $_FILES['file']['name'], false);
			  if (empty(Message::$msgs)):
			      $row = Db::run()->first(Gallery::mTable, array("id", "thumb_w", "thumb_h", "resize", "dir"), array("dir" => Validator::sanitize($_POST['dir'])));
				  try {
					  $img = new Image($dir. $upl->fileInfo['fname']);
					  if($row->resize == "fitToHeight") {
						  $img->fitToHeight($row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
					  } else {
						  $img->{$row->resize}($row->thumb_w, $row->thumb_h)->save($dir . '/thumbs/' . $upl->fileInfo['fname']);
					  }
					  $data = array_merge(Utility::insertLangSlugs("title", $upl->fileInfo['xame']), array("parent_id" => $row->id, "thumb" => $upl->fileInfo['fname']));
					  Db::run()->insert(Gallery::dTable, $data);
				  }
				  catch (exception $e) {
					  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
				  }
				  $json['filename'] = FMODULEURL . Gallery::GALDATA . $row->dir. '/thumbs/' . $upl->fileInfo['fname'];
			  $json['type'] = "success";
			  else:
				  $json['type'] = "error";
				  $json['filename'] = '';
				  $json['message'] = Message::$msgs['name'];
			  endif;
			  print json_encode($json);
          endif;
          break;
      /* == Load Photos == */
      case "loadPhotos":
          if ($row = Db::run()->select(Gallery::dTable, null, array("parent_id" => Filter::$id))->results()):
			  $tpl = App::View(AMODPATH . 'gallery/snippets/'); 
			  $tpl->photos = $row;
			  $tpl->data = Db::run()->first(Gallery::mTable, array("dir", "poster"), array("id" => Filter::$id));
			  $tpl->template = 'loadPhotos.tpl.php'; 
			  $json['type'] = "success";
			  $json['html'] = $tpl->render();
		  else:
		      $json['type'] = "error";
		  endif;
		  print json_encode($json);
      break;
      /* == Set Poster == */
      case "setPoster":
          if(Db::run()->update(Gallery::mTable, array("poster" => Validator::sanitize($_POST['thumb'])), array("id" => Filter::$id))):
			  $json['type'] = "success";
		  else:
			  $json['type'] = "error";
		  endif;
		  print json_encode($json);
      break;
	  
      /* == Sort Items == */
      case "sortItems":
	      $table = ($_POST['type'] == "sortAlbums" ) ? Gallery::mTable : Gallery::dTable;
		  $i = 0;
		  $query = "UPDATE `" . $table . "` SET `sorting` = CASE ";
		  $idlist = '';
		  foreach ($_POST['sorting'] as $item):
			  $i++;
			  $query .= " WHEN id = " . $item . " THEN " . $i . " ";
			  $idlist .= $item . ',';
		  endforeach;
		  $idlist = substr($idlist, 0, -1);
		  $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
		  Db::run()->pdoQuery($query);
      break;
  endswitch;
  
  /* == Post Actions== */
  if (isset($_POST['processItem'])):
	  switch ($_POST['page']) :
		  /* == Edit Photo == */
		  case "editPhoto":
			  App::Gallery()->processPhoto();
          break;
      endswitch;
  endif;
  
  /* == Get Actions== */
  if (isset($_GET['processItem'])):
	  switch ($_GET['page']) :
		  /* == Edit Photo == */
		  case "editPhoto":
			  $tpl = App::View(AMODPATH . 'gallery/snippets/'); 
			  $tpl->data = Db::run()->first(Gallery::dTable, null, array('id' => Filter::$id));
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->core = App::Core();
			  $tpl->template = 'editPhoto.tpl.php'; 
			  echo $tpl->render(); 
          break;
      endswitch;
  endif;