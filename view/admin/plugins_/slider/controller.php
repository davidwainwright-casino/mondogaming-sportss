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
	  
  Bootstrap::Autoloader(array(APLUGPATH . 'slider/'));

  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::request('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;

  /* == Delete == */
  switch ($delete):
      /* == Delete Slider == */
      case "deleteSlider":
		  $res = Db::run()->delete(Slider::mTable, array("id" => Filter::$id));
		  Db::run()->delete(Slider::dTable, array("parent_id" => Filter::$id));
		  if($row = Db::run()->first(Plugins::mTable, array("id", "plugalias"), array("plugin_id" => Filter::$id, "groups" => "slider"))) :
		      Db::run()->delete(Content::lTable, array("plug_id" => $row->id));
			  Db::run()->delete(Plugins::mTable, array("id" => $row->id));
			  
			  File::deleteDirectory(FPLUGPATH . $row->plugalias);
		  endif;
		  
		  $message = str_replace("[NAME]", $title, Lang::$word->_PLG_SL_DEL_OK);
          Message::msgReply($res, 'success', $message);
		  Logger::writeLog($message);
          break;
  endswitch;

  /* == Actions == */
  switch ($action):
      /* == Update Slide Data == */
      case "updateSlideData":
          $html = '';
          if ($_POST['oid']):
              $_SESSION['slider']['items'][$_POST['oid']] = array('html' => Validator::cleanOut($_POST['html']));
          endif;

          if (isset($_SESSION['slider']['items'][Filter::$id])):
              $html .= $_SESSION['slider']['items'][Filter::$id]['html'];
              $json['type'] = "success";

          endif;

          $json['html'] = $html;
          print json_encode($json);

          break;
		  
      /* == Save Single Slide == */
      case "saveSlideData":
          if (Filter::$id):
		      $data = array(
				 'image' => $_POST['image'] ? Validator::sanitize(str_replace(UPLOADURL . '/', "", $_POST['image'])) : 'NULL',
				 'color' => $_POST['color'] ? Validator::sanitize($_POST['color']) : 'NULL',
				 //'mode' => Validator::sanitize($_POST['mode']),
				 'attrib' => Validator::sanitize($_POST['attr']),
				 'html' => Url::in_url(Validator::cleanOut($_POST['html'])),
				 'html_raw' => Url::in_url(Validator::cleanOut($_POST['html_raw'])),
			  );
		      Db::run()->update(Slider::dTable, $data, array("id" => Filter::$id));
			  $json['type'] = "success";
          endif;

          $json['title'] = Lang::$word->SUCCESS;
		  $json['message'] = Message::formatSuccessMessage($_POST['slidename'], Lang::$word->_PLG_SL_UPDATED);
          print json_encode($json);

          break;
		  
      /* == Delete Slide == */
      case "deleteSlide":
          if(Db::run()->delete(Slider::dTable, array('id' => Filter::$id))):
              $json['type'] = "success";
          endif;
          print json_encode($json);
          break;

      /* == Edit Slide == */
      case "editSlide":
          if($row = Db::run()->first(Slider::dTable, null, array('id' => Filter::$id))):
		      $data = Db::run()->first(Slider::mTable, array("height"), array('id' => $row->parent_id));
			  $json = array (
				'html' => Url::out_url($row->html_raw),
				'image' => $row->image,
				'color' => $row->color,
				'type' => "success",
				'height' => $data->height == 100 ? '100vh' : $data->height . '0px',
			  );
          else:
			  $json['type'] = "error";
          endif;
          print json_encode($json);
          break;

      /* == Update Slide == */
      case "updateSlide":
		  $data = array(
			 'image' => isset($_POST['image']) ? Validator::sanitize($_POST['image']) : 'NULL',
			 'color' => isset($_POST['color']) ? Validator::sanitize($_POST['color']) : 'NULL',
			 'mode' => Validator::sanitize($_POST['mode']),
		  );
		  Db::run()->update(Slider::dTable, $data, array("id" => intval($_POST['id'])));
		  $json['type'] = "success";
          print json_encode($json);
          break;
		  
      /* == Duplicate Slide == */
      case "duplicateSlide":
          if($row = Db::run()->first(Slider::dTable, null, array('id' => Filter::$id))):
		      $data = array(
			     'parent_id' => $row->parent_id,
				 'title' => $row->title,
				 'image' => $row->image ? $row->image : 'NULL',
				 'color' => $row->color ? $row->color : 'NULL',
				 'mode' => $row->mode,
				 'html_raw' => str_replace("item_" . Filter::$id, "item_" . intval(Filter::$id + 1), $row->html_raw),
				 'html' => $row->html,
			  );
		      $last_id = Db::run()->insert(Slider::dTable, $data)->getLastInsertId();
			  $data['id'] = $last_id;

			  $tpl = App::View(APLUGPATH . 'slider/snippets/');
			  $tpl->data = (object) $data; 
			  $tpl->template = 'loadThumb.tpl.php'; 
			  $json = array (
			    'id' => $last_id,
				'html' => Url::out_url($row->html_raw),
				'thumb' => $tpl->render(),
				'color' => $row->color,
				'mode' => $row->mode,
				'image' => $row->image,
				'type' => "success",
			  );
          else:
			  $json['type'] = "error";
          endif;
          print json_encode($json);
          break;

      /* == New Slide == */
      case "newSlide":
		  $data = array(
			 'parent_id' => Filter::$id,
			 'title' => "New Slide",
			 'color' => "#ffffff",
			 'image' => Validator::sanitize($_POST['image']),
			 'mode' => 'bg',
		  );
		  $last_id = Db::run()->insert(Slider::dTable, $data)->getLastInsertId();
		  $data['id'] = $last_id;
		  
		  $sdata['html_raw'] = '
		  <div class="uitem" id="item_' . $last_id . ' data-type="bg">
			<div class="uimage" style="background-size: cover; background-position: center center; background-repeat: no-repeat; background-image: url([SITEURL]/uploads/' . $data['image'] . '); min-height:400px;">
			  <div class="ucontent" style="min-height: 400px;">
				<div class="row">
				  <div class="columns">
					<div class="ws-layer" data-delay="50" data-duration="600" data-animation="popInLeft">
					  <div data-text="true" style="font-size: 40px;"><span style="color: #ffffff;">WELCOME TO CMS PRO</span></div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>';
		  Db::run()->update(Slider::dTable, $sdata, array("id" => $last_id));
		  

		  $tpl = App::View(APLUGPATH . 'slider/snippets/'); 
		  $tpl->template = 'loadThumb.tpl.php'; 
		  $tpl->data = (object) $data;
		  
		  $json = array (
			'id' => $last_id,
			'mode' => $data['mode'],
			'image' => $data['image'],
			'thumb' => $tpl->render(),
			'type' => "success",
		  );

		print json_encode($json);
		break;
		  
      /* == Reorder Slides == */
      case "slideOrder":
		  $i = 0;
		  $query = "UPDATE `" . Slider::dTable . "` SET `sorting` = CASE ";
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
		  
      /* == Slide Property == */
      case "propSlide":
          if($row = Db::run()->first(Slider::dTable, null, array('id' => Filter::$id))):
			  $tpl = App::View(APLUGPATH . 'slider/snippets/');
			  $tpl->data = $row; 
			  $tpl->template = 'loadThumb.tpl.php'; 
			  $json = array (
			    'id' => $row->id,
				'html' => $row->html_raw,
				'thumb' => $tpl->render(),
				'color' => $row->color,
				'mode' => $row->mode,
				'image' => $row->image,
				'baseimage' => basename($row->image),
				'type' => "success",
			  );
          else:
			  $json['type'] = "error";
          endif;
          print json_encode($json);
          break;

      /* == Save Configuration == */
      case "saveConfig":
		  App::Slider()->saveConfig();
          break;
		  
  endswitch;
  
  /* == Quick Edit== */
  if (isset($_POST['quickedit'])):
      $title = Validator::cleanOut($_POST['title']);
      $title = Validator::sanitize($title);
      if (empty($title)):
          print '--- EMPTY STRING ---';
          exit;
      endif;
      switch ($_POST['type']) {
          /* == Update Slide Title  == */
          case "sltitle":
              Db::run()->update(Slider::dTable, array("title" => $title), array('id' => Filter::$id));
              break;
      }

	  $json['title'] = Validator::truncate($title, 20);
	  print json_encode($json);
  endif;
?>