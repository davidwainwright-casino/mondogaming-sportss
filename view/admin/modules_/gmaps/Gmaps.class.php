<?php
  /**
   * Gmaps Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Gmaps
  {
      const mTable = "mod_gmaps";
	  const pTable = "mod_gmaps_pins";
	  	  

      /**
       * Gmaps::__construct()
       * 
       * @return
       */
      public function __construct()
      {

	  }

      /**
       * Gmaps::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = Db::run()->select(self::mTable, null, null, "ORDER BY name")->results();
          $tpl->title = Lang::$word->_MOD_GM_TITLE;
          $tpl->template = 'admin/modules_/gmaps/view/index.tpl.php';
      }

      /**
       * Gmaps::Edit()
       * 
       * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_MOD_GM_TITLE1;
          $tpl->crumbs = ['admin', 'modules', 'gmaps', 'edit'];

          if (!$row = Db::run()->first(self::mTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [Gmaps.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
			  $tpl->mtype = $this->mapType();
			  $tpl->styles = File::findFiles(AMODPATH . 'gmaps/view/images/styles/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
			  $tpl->pins = File::findFiles(FMODPATH . 'gmaps/view/images/pins/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
              $tpl->template = 'admin/modules_/gmaps/view/index.tpl.php';
          }
      }

      /**
       * Gmaps::Save()
       * 
       * @return
       */
	  public function Save()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->_MOD_GM_TITLE2;
		  $tpl->mtype = $this->mapType();
		  $tpl->styles = File::findFiles(AMODPATH . 'gmaps/view/images/styles/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
		  $tpl->pins = File::findFiles(FMODPATH . 'gmaps/view/images/pins/', array('fileTypes' => array('png'), 'returnType' => 'fileOnly'));
		  $tpl->template = 'admin/modules_/gmaps/view/index.tpl.php';
	  }
	  
      /**
       * Gmaps::processMap()
       * 
       * @return
       */
      public function processMap()
      {
          $rules = array(
              'name' => array('required|string|min_len,3|max_len,80', Lang::$word->NAME),
              'lat' => array('required|float', Lang::$word->_MOD_GM_LAT),
              'lng' => array('required|float', Lang::$word->_MOD_GM_LNG),
              'body' => array('required|string', Lang::$word->M_ADDRESS),
              'zoom' => array('required|numeric|min_numeric,1|max_numeric,20', Lang::$word->_MOD_GM_SUB1),
			  'minmaxzoom' => array('required|string', Lang::$word->_MOD_GM_SUB1_1),
			  'layout' => array('required|string', Lang::$word->_MOD_GM_SUB4),
			  'type' => array('required|string', Lang::$word->_MOD_GM_SUB),
			  'type_control' => array('required|numeric', Lang::$word->_MOD_GM_SUB2),
			  'streetview' => array('required|numeric', Lang::$word->_MOD_GM_SUB3),
			  'pin' => array('required|string', Lang::$word->_MOD_GM_SUB6),
              );
			  
          $filters = array(
			  'name' => 'string',
			  'body' => 'string'
		  );
		  
          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              foreach (App::Core()->langlist as $i => $lang) {
                  $datam['title_' . $lang->abbr] = $safe->name;
              }
			  
              $data = array(
                  'name' => $safe->name,
                  'lat' => $safe->lat,
				  'lng' => $safe->lng,
				  'body' => $safe->body,
				  'zoom' => $safe->zoom,
				  'minmaxzoom' => $safe->minmaxzoom,
				  'layout' => $safe->layout,
				  'type' => $safe->type,
				  'type_control' => $safe->type_control,
				  'streetview' => $safe->streetview,
				  'style' => File::loadFile(AMODPATH . 'gmaps/snippets/' . $safe->layout . '.json'),
				  'pin' => $safe->pin,
                  );
				  
			   if(!Filter::$id) {
				   $data['plugin_id'] = "gmaps/" . Utility::randomString();
			   }
			   
              (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId(); 
			  
			  // Create a new plugin
			  if(!Filter::$id) {
				  File::makeDirectory(FPLUGPATH . $data['plugin_id']);
	
				  $plugin_file_main = FPLUGPATH . $data['plugin_id'] . '/index.tpl.php';
				  $plugin_file = FPLUGPATH . 'gmaps/master.php';
				  File::writeToFile($plugin_file_main, str_replace('##GMAPID##', $last_id, File::loadFile($plugin_file)));
	
				  $dataxq = array(
					  'system' => 0,
					  'cplugin' => 0,
					  'plugin_id' => $last_id,
					  'icon' => 'gmaps/thumb.png',
					  'plugalias' => $data['plugin_id'],
					  'groups' => "gmaps",
					  'active' => 1,
					  );
	
				  Db::run()->insert(Plugins::mTable, array_merge($datam, $dataxq));
			  }
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($data['name'], Lang::$word->_MOD_GM_UPDATE_OK) : 
			  Message::formatSuccessMessage($data['name'], Lang::$word->_MOD_GM_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Gmaps::render()
       * 
	   * @param int $id
       * @return
       */
      public function render($id)
      {
          $row = Db::run()->first(self::mTable, null, array("id" => $id));
          return ($row) ? $row : 0;
      }
	  
      /**
       * Gmaps::mapType()
       * 
       * @return
       */
      public function mapType()
      {
          $array = array(
			  'roadmap' => 'Road Map',
			  'satellite' => 'Satelite Map',
			  'hybrid' => 'Hybrid Map',
			  'terrain' => 'Terrain Map',
		  );
		  
		  return $array;
      }
  }