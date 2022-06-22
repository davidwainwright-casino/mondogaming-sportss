<?php
  /**
   * Helper
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../init.php");
	  
  if (!App::Auth()->is_Admin())
      exit;
	  
  /* == Live Search == */
  if (isset($_GET['liveSearch'])):
      $string = Validator::sanitize($_GET['value'], 'string', 15);
      switch (Validator::get('type')):
	      // Users
          case "users":
              if (strlen($string) > 3):
                  $sql = "
					SELECT 
					  id,
					  username,
					  email,
					  CONCAT(fname, ' ', lname) AS name
					FROM
					  `" . Users::mTable . "`
					WHERE MATCH (fname) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					OR MATCH (lname) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					OR MATCH (username) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					OR MATCH (email) AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY fname
					LIMIT 10 ";

                  $html = '';
                  if ($result = Db::run()->pdoQuery($sql)->results()):
                      $html .= '<table class="yoyo basic dashed table">';
                      foreach ($result as $row):
                          $link = Url::url("/admin/users/edit", $row->id);
                          $html .= '<tr>';
                          $html .= '<td>';
                          $html .= '<span class="yoyo basic disabled label">' . $row->id . '</span>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= '<a href="' . $link . '" class="white">' . $row->name . '</a>';
                          $html .= '</td>';
	                      $html .= '<td class="yoyo large text">';
                          $html .= $row->username;
                          $html .= '</td>';
	                      $html .= '<td class="yoyo large text">';
                          $html .= $row->email;
                          $html .= '</td>';
                          $html .= '</tr>';
                      endforeach;
                      $html .= '</table>';
					  $json['html'] = $html;
					  $json['status'] = 'success';
                  else:
					  $json['status'] = 'error';
                  endif;
				  print json_encode($json);
              endif;
          break;
	      // Pages
          case "pages":
              if (strlen($string) > 3):
                  $sql = "
					SELECT 
					  id,
					  title" . Lang::$lang . "
					FROM
					  `" . Content::pTable . "`
					WHERE MATCH (title" . Lang::$lang . ") AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY title" . Lang::$lang . " 
					LIMIT 10 ";

                  $html = '';
                  if ($result = Db::run()->pdoQuery($sql)->results()):
                      $html .= '<table class="yoyo basic dashed table">';
                      foreach ($result as $row):
                          $link = Url::url("/admin/pages/edit", $row->id);
                          $html .= '<tr>';
                          $html .= '<td>';
                          $html .= '<span class="yoyo basic disabled label">' . $row->id . '</span>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= '<a href="' . $link . '" class="white">' . $row->{'title' . Lang::$lang} . '</a>';
                          $html .= '</td>';
                          $html .= '</tr>';
                      endforeach;
                      $html .= '</table>';
					  $json['html'] = $html;
					  $json['status'] = 'success';
                  else:
					  $json['status'] = 'error';
                  endif;
				  print json_encode($json);
              endif;
          break;
	      // Plugins
          case "plugins":
              if (strlen($string) > 3):
                  $sql = "
					SELECT 
					  id,
					  hasconfig,
					  plugalias,
					  title" . Lang::$lang . "
					FROM
					  `" . Plugins::mTable . "`
					WHERE MATCH (title" . Lang::$lang . ") AGAINST ('" . $string . "*' IN BOOLEAN MODE)
					ORDER BY title" . Lang::$lang . " 
					LIMIT 10 ";

                  $html = '';
                  if ($result = Db::run()->pdoQuery($sql)->results()):
                      $html .= '<table class="yoyo basic dashed table">';
                      foreach ($result as $row):
						  $link = Url::url("/admin/plugins/edit", $row->id);
                          $html .= '<tr>';
                          $html .= '<td>';
                          $html .= '<span class="yoyo basic disabled label">' . $row->id . '</span>';
                          $html .= '</td>';
                          $html .= '<td class="yoyo large text">';
                          $html .= '<a href="' . $link . '" class="black">' . $row->{'title' . Lang::$lang} . '</a>';
                          $html .= '</td>';
						  if($row->hasconfig):
							  $html .= '<td class="yoyo large text">';
							  $html .= '<a href="' . Url::url("admin/plugins", $row->plugalias) . '" class="yoyo icon basic primary circular small button"><i class="icon cogs"></i></a>';
							  $html .= '</td>';
						  endif;
                          $html .= '</tr>';
                      endforeach;
                      $html .= '</table>';
					  $json['html'] = $html;
					  $json['status'] = 'success';
                  else:
					  $json['status'] = 'error';
                  endif;
				  print json_encode($json);
              endif;
          break;
      endswitch;

  endif;
  
  /* == Post Actions== */
  if (isset($_POST['processItem'])):
	  switch ($_POST['page']) :
		  /* == Process Notification == */
		  case "resendNotification":
			  App::Users()->resendNotification();
		  break;
		  /* == Update Role Description == */
		  case "editRole":
			  App::Users()->updateRoleDescription();
		  break;  
          /* == Save Page Content == */
          case "savePage":
		      $field = Validator::sanitize($_POST['field'], "db");
			  if ($row = Db::run()->first(Content::pTable, array("id", $field), array("id" => Filter::$id))):
				  $validate = Validator::instance();
				  $safe = $validate->doFilter($_POST, array('data' => 'advanced_tags'));
				  Db::run()->update(Content::pTable, array($field => Url::in_url($_POST['data'])), array("id" => $row->id));
				  $json['status'] = "success";
			  else:
				  $json['status'] = "error";
			  endif;
				  print json_encode($json);
          break;
          /* == Sort Menus == */
          case "sortMenus":
		      $jsonstring = $_POST['sortlist'];
			  $jsonDecoded = json_decode($jsonstring, true, 12);
			  $result = Utility::parseJsonArray($jsonDecoded);
			  $i = 0;
			  foreach ($result as $value):
				  if (is_array($value)):
					  $i++;
					  $data = array('position' => $i, 'parent_id' => $value['parent_id']);
					  Db::run()->update(Content::mTable, $data, array('id' => $value['id']));
				  endif;
			  endforeach; 
          break;
      endswitch;
  endif;
  
  /* == Get Actions== */
  if (isset($_GET['doAction'])):
      switch ($_GET['page']) :
          /* == Resend Notification == */
          case "resendNotification":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/'); 
			  $tpl->template = 'resendNotification.tpl.php'; 
			  $tpl->data = Db::run()->first(Users::mTable, array("id", "email", "CONCAT(fname,' ',lname) as name"), array('id' => Filter::$id));
			  echo $tpl->render(); 
          break;
          /* == Edit Role == */
          case "editRole":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/'); 
			  $tpl->data = Db::run()->first(Users::rTable, null, array('id' => Filter::$id));
			  $tpl->template = 'editRole.tpl.php'; 
			  echo $tpl->render(); 
          break;
          /* == Get Internal Links == */
          case "getlinks":
			  $list = array();
			  $core = App::Core();
			  $data = Db::run()->select(Content::pTable, array("id", "title" . Lang::$lang, "slug" . Lang::$lang), array("active" => 1), "ORDER BY title" . Lang::$lang . " ASC")->results();
			  if ($data):
				  foreach ($data as $row):
					  if(Validator::get('is_builder')) :
						  $item = array(
							  'name' => $row->{'title' . Lang::$lang}, 
							  'href' => Url::url("/" . $core->pageslug, $row->{'slug' . Lang::$lang}), 
							  'id' => $row->id
						  );
					  else:
						  $item = array(
							  'name' => $row->{'title' . Lang::$lang}, 
							  'url' => Url::url("/" . $core->pageslug, $row->{'slug' . Lang::$lang}), 
							  'id' => $row->id
						  );
					  endif;
					 $list[] = $item;
				  endforeach;
			  endif;
			  if(Validator::get('is_builder')) :
				  $json['message'] = $list;
				  print json_encode($json);
			  else:
				  print json_encode($list);
			  endif;
          break;
          /* == Get Content Plugins == */
          case "pluglist":
			  $list = array();
			  if ($data = App::Plugins()->getFreePugins(null)):
				  foreach ($data as $row):
					  $item = array(
						  'title' => $row->title, 
						  'alias' => $row->plugalias, 
						  'icon' => APLUGINURL . $row->icon, 
						  'id' => $row->id
					  );
					  $list[] = $item;
				  endforeach;
				  $json['pluglist'] = $list;
			  endif;
				  print json_encode($json);
          break;
          /* == Get membership List == */
          case "membershiplist":
			  if ($_GET['type'] == "Membership"):
				  $json['status'] = 'success';
				  $json['html'] = Utility::loopOptionsMultiple(App::Membership()->getMembershipList(), "id", "title" . Lang::$lang);
			  else:
				  $json['status'] = 'none';
			  endif;
				  print json_encode($json);
          break;
          /* == Get Module List == */
          case "modulelist":
			  if ($modalias = Db::run()->getValueById(Modules::mTable, "modalias", Filter::$id)):
				  $json['status'] = 'success';
				  $tpl = App::View(AMODPATH . $modalias . '/'); 
				  $tpl->template = 'config.tpl.php'; 
				  $tpl->data = $_GET['module_data'];
				  $json['html'] = File::exists(AMODPATH . $modalias . '/config.tpl.php') ? $tpl->render() : null;
			  else:
				  $json['status'] = 'error';
			  endif;
				  print json_encode($json);
          break;
          /* == Get Content Type == */
          case "contenttype":
		      $type = Validator::sanitize($_GET['type'], "alpha");
			  $html = '';
			  switch ($type):
				  case "page":
					  $data = Db::run()->select(Content::pTable, array("id", "title" . Lang::$lang), array("active" => 1), "ORDER BY title" . Lang::$lang . " ASC")->results();
					  if ($data):
						  foreach ($data as $row):
							  $html .= "<option value=\"" . $row->id . "\">" . $row->{'title' . Lang::$lang} . "</option>\n";
						  endforeach;
						  $json['type'] = 'page';
					  endif;
					  break;
		
				  case "module":
					  $data = Db::run()->select(Modules::mTable, array("id", "title" . Lang::$lang), array("active" => 1, "is_menu" => 1), "ORDER BY title" . Lang::$lang . " ASC")->results();
					  if ($data):
						  foreach ($data as $row):
							  $html .= "<option value=\"" . $row->id . "\">" . $row->{'title' . Lang::$lang}  . "</option>\n";
						  endforeach;
						  $json['type'] = 'module';
					  endif;
					  break;
					  
				  default:
					  $json['type'] = 'web';
					  
			  endswitch;
			  $json['message'] = $html;
			  print json_encode($json);
          break;
          /* == Get Language Section == */
          case "languagesection":
		      if(File::exists(BASEPATH . Lang::langdir . "/" . $_GET['abbr'] . "/lang.xml")):
				  $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . "/" . $_GET['abbr'] . "/lang.xml");
				  $section = $xmlel->xpath('/language/phrase[@section="' . Validator::sanitize($_GET['section']) . '"]');
				  $tpl = App::View(BASEPATH . 'view/admin/snippets/'); 
				  $tpl->xmlel = $xmlel;
				  $tpl->section = $section;
				  $tpl->type = $_GET['type'];
				  $tpl->abbr = $_GET['abbr'];
				  $tpl->template = 'loadLanguageSection.tpl.php'; 
				  $json['html'] = $tpl->render(); 
			  else:
				  $json['type'] = "error";
				  $json['title'] = Lang::$word->ERROR;
				  $json['message'] = Lang::$word->FU_ERROR15;
			  endif;
			  print json_encode($json);
          break;
          /* == Get Language File == */
          case "languagefile":
			  if (File::exists(BASEPATH . Lang::langdir . $_GET['abbr'] . "/" . $_GET['section'])):
				  $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . $_GET['abbr'] . "/" . $_GET['section']);
				  $tpl = App::View(BASEPATH . 'view/admin/snippets/');
				  $tpl->xmlel = $xmlel;
				  $tpl->section = null;
				  $tpl->fpath = $_GET['section'];
				  $tpl->type = $_GET['type'];
				  $tpl->abbr = $_GET['abbr'];
				  $tpl->template = 'loadLanguageSection.tpl.php';
				  $json['html'] = $tpl->render();
				  $json['type'] = "success";
			  else:
				  $json['type'] = "error";
				  $json['title'] = Lang::$word->ERROR;
				  $json['message'] = Lang::$word->FU_ERROR15;
			  endif;
			  print json_encode($json);
          break;
          /* == Get Unused Plugins == */
          case "getFreePlugins":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/');
			  $tpl->template = 'getFreePlugins.tpl.php';
			  $tpl->section = Validator::sanitize($_GET['section']);
			  $tpl->data = App::Plugins()->getFreePugins(Utility::implodeFields(Validator::get('ids')));
			  $json['html'] = $tpl->render();
			  print json_encode($json);
          break;
          /* == Get Plugin Layout == */
          case "getPluginLayout":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/');
			  $tpl->template = 'getPluginLayout.tpl.php';
			  $tpl->section = Validator::sanitize($_GET['section']);
			  $tpl->data = App::Plugins()->getPluginSpaces(Utility::implodeFields($_GET['ids']));
			  $json['html'] = $tpl->render();
			  print json_encode($json);
          break;

          /* == Get Builder User Plugin == */
          case "builderUserPlugin":
		      if($row = Db::run()->first(Plugins::mTable, array("id", "title" . Lang::$lang . " as title", "body" . Lang::$lang . " as body", "show_title", "alt_class"), array("id" => Filter::$id))):
				  //$tpl = App::View(BASEPATH . 'view/admin/snippets/');
				  //$tpl->template = 'getUserPlugins.tpl.php';
				  //$tpl->row = $row;
				  
                  $json['status'] = "success";
				  //$json['html'] = $tpl->render();
				  $json['html'] = $row->body;
			  else:
                  $json['status'] = "error";
				  $json['html'] = '';  
			  endif;
			  print json_encode($json);
		  break;
		  
          /* == Get Builder Plugin == */
          case "builderPlugin":
		      if($row = Db::run()->first(Plugins::mTable, array("id", "title" . Lang::$lang . " as title", "body" . Lang::$lang . " as body",  "plugalias", "groups", "alt_class", "plugin_id", "show_title"), array("id" => Filter::$id))):
			      $data = ["plugin_id" => $row->plugin_id, "id" => $row->id, "all" => array($row)];
				  if(File::is_File(FPLUGPATH . $row->plugalias . "/themes/" . App::Core()->theme . "/index.tpl.php")) :
				     $content = Utility::getSnippets(FPLUGPATH . $row->plugalias . "/themes/" . App::Core()->theme . "/index.tpl.php", $data);
				  else:
				     $content = Utility::getSnippets(FPLUGPATH . $row->plugalias . "/index.tpl.php", $data);
				  endif;
				  
                  $json['status'] = "success";
				  $json['html'] = $content;
			  else:
                  $json['status'] = "error";
				  $json['html'] = '';  
			  endif;
			  print json_encode($json);
          break;
          /* == Load Builder Plugin == */
          case "loadBuilderPlugins":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/');
			  $tpl->template = 'loadBuilderPlugins.tpl.php';
			  $tpl->data = App::Plugins()->getAvailPugins(Utility::implodeFields($_GET['ids']));
			  $json['html'] = $tpl->render();
			  print json_encode($json);
          break;
          /* == Get Builder Modules == */
          case "builderModule":
		      if($row = Db::run()->first(Modules::mTable, array("id as module_id", "title" . Lang::$lang . " as title", "modalias", "parent_id as id"), array("id" => Filter::$id))):
				  if(File::is_File(FMODPATH . $row->modalias . "/themes/" . App::Core()->theme . "/index.tpl.php")) :
				     $content = Utility::getSnippets(FMODPATH . $row->modalias . "/themes/" . App::Core()->theme . "/index.tpl.php", $data = (array)$row);
				  else:
				     $content = Utility::getSnippets(FMODPATH . $row->modalias . "/index.tpl.php", $data = (array)$row);
				  endif;
				  
				  $assets = Modules::parseModuleAssets('%%' . $row->modalias . '|module|0|0"%%');
				  
                  $json['status'] = "success";
				  $json['html'] = $content;
				  $json['assets'] = $assets;
				  $json['assets_id'] = $row->modalias;
			  else:
                  $json['status'] = "error";
				  $json['html'] = '';  
			  endif;
			  print json_encode($json);
          break;
		  
          /* == Load Builder Modules == */
          case "loadBuilderModules":
			  $tpl = App::View(BASEPATH . 'view/admin/snippets/');
			  $tpl->template = 'loadBuilderModules.tpl.php';
			  if(isset($_GET['modalias'])) {
				  $tpl->data = App::Modules()->getAvailModules(Utility::implodeFields($_GET['modalias'], ',', true));
			  } else {
				  $tpl->data = App::Modules()->getAllAvailModules();
			  }

			  if($tpl->data):
			     $json['html'] = $tpl->render();
				 $json['status'] = 'success';
			  else:
				 $json['status'] = 'error';
			  endif;
			  print json_encode($json);
          break;
		  
          /* == Load Editors Page == */
          case "loadPage":
		      $lang = Validator::sanitize($_GET['lang'], "string", 2);
			  if($row = Db::run()->first(Content::pTable, array("body_" . $lang), array("id" => Filter::$id))):
				 print Content::parseContentData($row->{'body_' . $lang}, true);
			  endif; 
          break;
		  
          /* == Get Builder Section == */
          case "bsection":
			  if (File::getFile(BUILDERBASE . '/themes/' . $_GET['file'] . '.tpl.php')):
			      $file = File::loadFile(BUILDERBASE . '/themes/' . $_GET['file'] . '.tpl.php');
				  $file = str_replace("[SITEURL]", SITEURL, $file);
				  $json['html'] = $file;
				  $json['status'] = 'success';
			  else:
				  $json['status'] = 'error';
			  endif;
				  print json_encode($json);
          break;
      endswitch;
  endif;

  /* == Quick Simple Actions == */
  if (Validator::post('simpleAction')):
      switch ($_POST['action']):
		  /* == Database Backup == */
          case "databaseBackup":
              dbTools::doBackup();
          break;
		  /* == Language Color == */
          case "langcolor":
              $color = Validator::sanitize($_POST['color'], "string", 7);
              if (Db::run()->update(Lang::lTable, array('color' => $color), array("id" => Filter::$id))):
                  $data = Lang::getLanguages();
                  Db::run()->update(Core::sTable, array('lang_list' => json_encode($data)), array("id" => 1));
              endif;
          break;
		  /* == Sort Layout == */
          case "sortLayout":
              $type = Validator::sanitize($_POST['type']);
              $place = Validator::sanitize($_POST['position']);
              $is_page = null;
              if ($type == "page_id"):
                  $and = " AND page_id = " . intval($_POST['page']);
                  $is_page = "`type` = 'page',";
              else:
                  $and = " AND mod_id = " . intval($_POST['mod'][0]['id']);
              endif;

			  $i = 0;
			  $query = "UPDATE `" . Plugins::lTable . "` SET `place` = '" . $place . "', $is_page `sorting` = CASE ";
			  $idlist = '';
			  foreach ($_POST['items'] as $item):
				  $i++;
				  $query .= " WHEN plug_id = " . $item . " THEN " . $i . " ";
				  $idlist .= $item . ',';
			  endforeach;
			  $idlist = substr($idlist, 0, -1);
			  $query .= "
				  END
				  WHERE plug_id IN (" . $idlist . ")";
			  $query .= $and;
			  Db::run()->pdoQuery($query);
          break;
		  /* == Insert Layout == */
		  case "insertLayout":
			  $type = Validator::sanitize($_POST['type']);
			  $place = Validator::sanitize($_POST['position']);

			  if ($type == "page_id"):
				  foreach ($_POST['items'] as $item):
					  $dataArray[] = array(
						  'plug_id' => $item,
						  'place' => $place,
						  'page_id' => intval($_POST['page']),
						  'type' => "page_id");
				  endforeach;
			  else:
				  foreach ($_POST['items'] as $item):
					  $dataArray[] = array(
						  'plug_id' => $item,
						  'place' => $place,
						  'mod_id' => intval($_POST['mod'][0]['id']),
						  'modalias' => Validator::sanitize($_POST['mod'][0]['modalias']),
						  'type' => "mod_id");
				  endforeach;
			  endif;
			  Db::run()->insertBatch(Plugins::lTable, $dataArray);
		  break;
		  /* == Update Layout == */
		  case "updateLayout":
			  $type = Validator::sanitize($_POST['type']);
			  $is_page = null;
			  if ($type == "page_id"):
				  $and = " AND page_id = " . intval($_POST['page']);
				  $is_page = "`type` = 'page',";
			  else:
				  $and = " AND mod_id = " . intval($_POST['mod'][0]['id']);
			  endif;

			  $query = "UPDATE `" . Plugins::lTable . "` SET $is_page `space` = CASE ";
			  $idlist = '';
			  foreach ($_POST['items'] as $item):
				  $id = Validator::sanitize($item['name'], "int");
				  $space = Validator::sanitize($item['value'], "int");
				  $query .= " WHEN id = " . $id . " THEN " . $space . " ";
				  $idlist .= $id . ',';
			  endforeach;
			  $idlist = substr($idlist, 0, -1);
			  $query .= "
					  END
					  WHERE id IN (" . $idlist . ")";
			  $query .= $and;
			  Db::run()->pdoQuery($query);
		  break;
		  /* == Delete LayoutPlugin == */
		  case "deleteLayout":
			  $type = Validator::sanitize($_POST['type']);
			  if ($type == "page_id"):
				  $array = array("plug_id" => Filter::$id, "page_id" => intval($_POST['page']));
			  else:
				  $array = array("plug_id" => Filter::$id, "mod_id" => intval($_POST['mod'][0]['id']));
			  endif;

			  if (Db::run()->delete(Plugins::lTable, $array)):
				  $json['type'] = "success";
			  else:
				  $json['type'] = "error";
			  endif;
			  $json['title'] = Lang::$word->SUCCESS;
			  print json_encode($json);
          break;
		  
		  /* == Restore User == */
		  case "restoreUser":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Users::mTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete User == */
		  case "deleteUser":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
		  
		  /* == Restore Coupon == */
		  case "restoreCoupon":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Content::dcTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete Coupon == */
		  case "deleteCoupon":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
		  
		  /* == Restore Membership == */
		  case "restoreMembership":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Membership::mTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete Membership == */
		  case "deleteMembership":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
		  
		  /* == Restore Menu == */
		  case "restoreMenu":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Content::mTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete Menu == */
		  case "deleteMenu":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
		  
		  /* == Restore Page == */
		  case "restorePage":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Content::pTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete Page == */
		  case "deletePage":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
		  
		  /* == Restore Plugin == */
		  case "restorePlugin":
			  if($result = Db::run()->first(Core::txTable, array('dataset'), array("id" => filter::$id))):
				  $array = Utility::jSonToArray($result->dataset);
				  Core::restoreFromTrash($array, Plugins::mTable);
				  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  endif;
		  break;
		  /* == Delete Plugin == */
		  case "deletePlugin":
			  Db::run()->delete(Core::txTable, array("id" => filter::$id));
			  $json['type'] = "success";
			  print json_encode($json);
		  break; 
      endswitch;
  endif;

  /* == Quick Edit == */
  if (isset($_POST['quickedit'])):
      $title = Validator::cleanOut($_POST['title']);
      $title = Validator::sanitize($title);
      switch ($_POST['type']) :
          /* == Update Language Phrase == */
          case "phrase":
			  if (file_exists(BASEPATH . Lang::langdir . "/" . $_POST['path'])):
				  $xmlel = simplexml_load_file(BASEPATH . Lang::langdir . "/" . $_POST['path']);
				  $node = $xmlel->xpath("/language/phrase[@data = '" . $_POST['key'] . "']");
				  $node[0][0] = $title;
				  $xmlel->asXML(BASEPATH . Lang::langdir . "/" . $_POST['path']);
			  endif;
              break;  
      endswitch;
	  $json['title'] = $title;
	  print json_encode($json);
  endif;
  
  /* == Quick Status == */
  if (Validator::post('quickStatus')):
      switch ($_POST['status']) :
          /* == Roles == */
          case "role":
		      if(Auth::checkAcl("owner")):
			      Db::run()->update(Users::rpTable, array("active" => intval($_POST['active'])), array("id" => Filter::$id));
			  endif;
          break;
          /* == Coupons == */
          case "coupon":
		      Db::run()->update(Content::dcTable, array("active" => intval($_POST['active'])), array("id" => Filter::$id));
          break;
          /* == Gateway == */
          case "gateway":
		      Db::run()->update(Core::gTable, array("active" => intval($_POST['active'])), array("id" => Filter::$id));
          break;
      endswitch;
  endif;

   /* == Sort Custom Fields == */
  if (Validator::post('sortFields')):
      $i = 0;
      $query = "UPDATE `" . Content::cfTable . "` SET `sorting` = CASE ";
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
  endif;
  
   /* == Export Users == */
  if (isset($_GET['exportUsers'])):
      header("Pragma: no-cache");
	  header('Content-Type: text/csv; charset=utf-8');
	  header('Content-Disposition: attachment; filename=UserList.csv');
	  
	  $data = fopen('php://output', 'w');
	  fputcsv($data, array('Name', 'Membership', 'Expire', 'Email', 'Newsletter', 'Created'));
	  
	  $result = Stats::exportUsers();
	  if($result):
		  foreach ($result as $row) :
			  fputcsv($data, $row);
		  endforeach;
		  fclose($data);
	  endif;
  endif;

   /* == Export User Payments == */
  if (isset($_GET['exportUserPayments'])):
      header("Pragma: no-cache");
	  header('Content-Type: text/csv; charset=utf-8');
	  header('Content-Disposition: attachment; filename=UserPayments.csv');
	  
	  $data = fopen('php://output', 'w');
	  fputcsv($data, array('TXN ID', 'Name', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
	  
	  $result = Stats::exportUserPayments(Filter::$id);
	  if($result):
		  foreach ($result as $row) :
			  fputcsv($data, $row);
		  endforeach;
		  fclose($data);
	  endif;
  endif;

   /* == Export Membership Payments == */
  if (isset($_GET['exportMembershipPayments'])):
      header("Pragma: no-cache");
	  header('Content-Type: text/csv; charset=utf-8');
	  header('Content-Disposition: attachment; filename=MembershipPayments.csv');
	  
	  $data = fopen('php://output', 'w');
	  fputcsv($data, array('TXN ID', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
	  
	  $result = Stats::exportMembershipPayments(Filter::$id);
	  if($result):
		  foreach ($result as $row) :
			  fputcsv($data, $row);
		  endforeach;
		  fclose($data);
	  endif;
  endif;
  
   /* == User Payments Chart == */
  if (isset($_GET['getUserPaymentsChart'])):
      $data = Stats::getUserPaymentsChart(Filter::$id);
	  print json_encode($data);
  endif;

   /* == Membership Payments Chart == */
  if (isset($_GET['getMembershipPaymentsChart'])):
      $data = Stats::getMembershipPaymentsChart(Filter::$id);
	  print json_encode($data);
  endif;

   /* == Site Sales Chart == */
  if (isset($_GET['getSalesChart'])):
      $data = Stats::getAllSalesStats();
	  print json_encode($data);
  endif;

   /* == Export All Payments == */
  if (isset($_GET['exportAllTransactions'])):
      header("Pragma: no-cache");
	  header('Content-Type: text/csv; charset=utf-8');
	  header('Content-Disposition: attachment; filename=AllPayments.csv');
	  
	  $data = fopen('php://output', 'w');
	  fputcsv($data, array('TXN ID', 'Item', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
	  
	  $result = Stats::exportAllTransactions();
	  if($result):
		  foreach ($result as $row) :
			  fputcsv($data, $row);
		  endforeach;
	  endif;
  endif;

   /* == Index Payments Chart == */
  if (isset($_GET['getIndexStats'])):
      $data = Stats::indexSalesStats();
	  print json_encode($data);
	  
  endif;

  /* == Main Stats == */
  if (isset($_GET['getMainStats'])):
      $data = Stats::getMainStats();
	  print json_encode($data);
  endif;
  
  /* == Main Stats == */
  if (isset($_GET['getSubStats'])):
      $data = Stats::getSubStats();
	  print json_encode($data);
  endif;
  
  
  /* == Clear Session Temp Queries == */
  if (isset($_GET['ClearSessionQueries'])):
      App::Session()->remove('debug-queries');
	  App::Session()->remove('debug-warnings');
	  App::Session()->remove('debug-errors');
	  print 1;
  endif;