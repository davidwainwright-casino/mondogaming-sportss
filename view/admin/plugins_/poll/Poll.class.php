<?php
  /**
   * Poll Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Poll
  {

      const oTable = "plug_poll_options";
      const qTable = "plug_poll_questions";
      const vTable = "plug_poll_votes";


      /**
       * Poll::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }

      /**
       * Poll::AdminIndex()
       * 
       * @return
       */
      public function AdminIndex()
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->data = $this->getAllPolls();
          $tpl->title = Lang::$word->_PLG_PL_TITLE3;
          $tpl->template = 'admin/plugins_/poll/view/index.tpl.php';
      }

      /**
       * Poll::Edit()
       * 
	   * @param int $id
       * @return
       */
      public function Edit($id)
      {
          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_PL_TITLE1;
          $tpl->crumbs = ['admin', 'plugins', 'poll', 'edit'];

          if (!$row = Db::run()->first(self::qTable, null, array("id" => $id))) {
              $tpl->template = 'admin/error.tpl.php';
              $tpl->error = DEBUG ? "Invalid ID ($id) detected [poll.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
          } else {
              $tpl->data = $row;
              $tpl->options = $this->getPollOptions($id);
              $tpl->template = 'admin/plugins_/poll/view/index.tpl.php';
          }
      }

      /**
       * Poll::Save()
       * 
       * @return
       */
      public function Save()
      {

          $tpl = App::View(BASEPATH . 'view/');
          $tpl->dir = "admin/";
          $tpl->title = Lang::$word->_PLG_PL_TITLE2;
          $tpl->template = 'admin/plugins_/poll/view/index.tpl.php';
      }

      /**
       * Poll::processPoll()
       * 
       * @return
       */
      public function processPoll()
      {
          $rules = array('question' => array('required|string|min_len,3|max_len,80', Lang::$word->_PLG_PL_QUESTION));
          (Filter::$id) ? $this->_updatePoll($rules) : $this->_addPoll($rules);
      }

      /**
       * Poll::_addPoll()
       * 
	   * @param array $rules
       * @return
       */
      public function _addPoll($rules)
      {
          $filters['question'] = 'string';
		  
          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (!array_key_exists('value', $_POST)) {
              Message::$msgs['value'] = LANG::$word->_PLG_PL_OPTIONS;
          }

          if (array_key_exists('value', $_POST)) {
              if (!array_filter($_POST['value'])) {
                  Message::$msgs['value'] = LANG::$word->_PLG_PL_OPTIONS;
              }
          }

          if (empty(Message::$msgs)) {
              $data = array('question' => $safe->question);
              $last_id = Db::run()->insert(self::qTable, $data)->getLastInsertId();

              // Insert new mulit plugin
			  $plugin_id = "poll/" . Utility::randomString();
			  File::makeDirectory(FPLUGPATH . $plugin_id);
			  File::copyFile(FPLUGPATH . 'poll/master.php', FPLUGPATH . $plugin_id . '/index.tpl.php');
			  
			  $pid = Db::run()->first(Plugins::mTable, array("id"), array("plugalias" => "poll"));
			  foreach (App::Core()->langlist as $i => $lang) {
				  $datam['title_' . $lang->abbr] = $safe->question;
			  }
              $datax = array(
				  'parent_id' => $pid->id,
				  'plugin_id' => $last_id,
				  'icon' => 'poll/thumb.png',
				  'plugalias' => $plugin_id,
                  'groups' => 'poll',
                  'active' => 1,
                  );
              Db::run()->insert(Plugins::mTable, array_merge($datam, $datax));

              if (array_key_exists('value', $_POST)) {
                  $values = array_filter($_POST['value']);
                  foreach ($_POST['value'] as $key => $val) {
                      $key++;
                      $dataArray[] = array(
                          'question_id' => $last_id,
                          'value' => validator::sanitize($val),
                          'position' => $key++,
                          );
                  }
                  Db::run()->insertBatch(self::oTable, $dataArray);
              }

              $message = Message::formatSuccessMessage($data['question'], Lang::$word->_PLG_PL_ADDED);

              $json['type'] = "success";
              $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = $message;
              $json['redirect'] = Url::url("/admin/plugins", "poll");
              Logger::writeLog($message);

              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Poll::_updatePoll()
       * 
	   * @param array $rules
       * @return
       */
      public function _updatePoll($rules)
      {

          $filters['question'] = 'string';
		  
          $validate = Validator::instance();
          $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);

          if (empty(Message::$msgs)) {
              $data = array('question' => $safe->question);

              Db::run()->update(self::qTable, $data, array("id" => Filter::$id));

              if (array_key_exists('value', $_POST)) {
                  $values = array_filter($_POST['value']);
                  $counter = count($_POST['newvalue']);
                  foreach ($_POST['value'] as $key => $val) {
                      $dataArray[] = array(
                          'question_id' => Filter::$id,
                          'value' => validator::sanitize($val),
                          'position' => $counter++,
                          );
                  }
                  Db::run()->insertBatch(self::oTable, $dataArray);
              }

              $message = Message::formatSuccessMessage($data['question'], Lang::$word->_PLG_PL_UPDATED);

              $json['type'] = "success";
              $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = $message;
              $json['redirect'] = Url::url("/admin/plugins", "poll");
              Logger::writeLog($message);

              print json_encode($json);
          } else {
              Message::msgSingleStatus();
          }
      }

      /**
       * Poll::getPolls()
       * 
       * @return
       */
      public function getAllPolls()
      {
          $sql = "
		  SELECT 
			q.question,
			q.id as id,
			o.value,
			IFNULL(COUNT(v.option_id), 0) AS total
		  FROM
			`" . self::oTable . "` AS o 
			LEFT JOIN `" . self::vTable . "` AS v 
			  ON o.id = v.option_id 
			JOIN `" . self::qTable . "` AS q 
			  ON o.question_id = q.id 
		  GROUP BY o.id 
		  ORDER BY o.position;";

          $query = Db::run()->pdoQuery($sql)->results();

          $data = array();
          if ($query) {
              $pid = null;
              foreach ($query as $i => $row) {
                  if ($pid != $row->id) {
                      $pid = $row->id;
                      $data[$row->id]['name'] = $row->question;
                      $data[$row->id]['id'] = $row->id;
                  }
                  $data[$row->id]['totals'] = isset($data[$row->id]['totals']) ? $data[$row->id]['totals'] += $row->total : $row->total;
                  $data[$row->id]['opts'][$i]['value'] = $row->value;
                  $data[$row->id]['opts'][$i]['total'] = $row->total;
              }
          }

          $data = json_decode(json_encode($data));
          return ($data) ? $data : 0;

      }


      /**
       * Poll::Render()
       * 
	   * @param int $id
       * @return
       */
      public function Render($id)
      {
          $sql = "
		  SELECT 
			q.question,
			q.id as id,
			o.value,
			o.id As oid,
			IFNULL(COUNT(v.option_id), 0) AS total
		  FROM
			`" . self::oTable . "` AS o 
			LEFT JOIN `" . self::vTable . "` AS v 
			  ON o.id = v.option_id 
			JOIN `" . self::qTable . "` AS q 
			  ON o.question_id = q.id 
			WHERE o.question_id = ?
		  GROUP BY o.id 
		  ORDER BY o.position;";

          $query = Db::run()->pdoQuery($sql, array($id))->results();

          $data = array();
          if ($query) {
              $pid = null;
              foreach ($query as $i => $row) {
                  if ($pid != $row->id) {
                      $pid = $row->id;
                      $data[$row->id]['name'] = $row->question;
                      $data[$row->id]['id'] = $row->id;
                  }
                  $data[$row->id]['totals'] = isset($data[$row->id]['totals']) ? $data[$row->id]['totals'] += $row->total : $row->total;
                  $data[$row->id]['opts'][$i]['value'] = $row->value;
                  $data[$row->id]['opts'][$i]['total'] = $row->total;
				  $data[$row->id]['opts'][$i]['oid'] = $row->oid;
              }
          }

          $data = json_decode(json_encode($data));
          return ($data) ? $data : 0;

      }

	  /**
	   * Poll::updatePollResult()
	   * 
	   * @param int $id
	   * @return
	   */	  
	  public function updatePollResult($id)
	  {
		  
		  if ($row = Db::run()->first(self::oTable, array("id", "question_id"), array("id" => $id))) {
			  $data['option_id'] = $row->id;
			  $data['ip'] = Validator::sanitize($_SERVER['REMOTE_ADDR']);
	
			  Db::run()->insert(self::vTable, $data);
			  if (Db::run()->affected()) {
				  App::Session()->set("CMSPRO_voted", $row->question_id, true);
				  return true;
			  }
			  return false;
		  }
		  return false;
	  }
	  
      /**
       * Poll::getPollOptions()
       * 
	   * @param int $id
       * @return
       */
      public function getPollOptions($id)
      {

          $row = Db::run()->select(self::oTable, null, array("question_id" => $id), "ORDER BY position")->results();
          return ($row) ? $row : 0;
      }
  }