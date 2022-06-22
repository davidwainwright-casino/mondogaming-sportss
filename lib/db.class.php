<?php
  /**
   * Db Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');


  class Db extends PDO
  {

      private $_oSTH = null;
      public $sSql = '';
      public $sTable = array();
      public $aWhere = array();
      public $aColumn = array();
      public $sOther = array();
      public $aResults = array();
      public $aResult = array();
      public $iLastId = 0;
      public $iAllLastId = array();
      public $affected = 0;
      public $aData = null;
      public $batch = false;
      private $server_info = null;
      public $db_info = array();
      private $_dbName;
      private $aValidOperation = array(
          'SELECT',
		  'CREATE',
          'INSERT',
          'UPDATE',
		  'ALTER',
          'DELETE'
		  );
      protected static $oPDO = null;
      private static $_error;
      private static $_errorMessage;
      public static $count = 0;
      public static $mode = PDO::FETCH_OBJ;


      /**
       * Db::__construct()
       * 
       * @param mixed $params
       * @return
       */
      public function __construct($params = array())
      {
          if (!empty($params)) {


          } else {
              try {
                  if (DB_DATABASE != '') {
                      parent::__construct(DB_DRIVER . ':host=' . DB_SERVER . ';dbname=' . DB_DATABASE, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                      $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                      $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                      $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, self::$mode);
                      $this->db_info = array(
                          "AUTOCOMMIT",
                          "ERRMODE",
                          "CASE",
                          "CLIENT_VERSION",
                          "CONNECTION_STATUS",
                          "ORACLE_NULLS",
                          "PERSISTENT",
                          "PREFETCH",
                          "SERVER_INFO",
                          "SERVER_VERSION",
                          "TIMEOUT");
                  } else {
                      throw new Exception('Missing database configuration file');
                  }
              }
              catch (PDOException $e) {
                  header('HTTP/1.1 503 Service Temporarily Unavailable');
                  header('Status: 503 Service Temporarily Unavailable');
                  $output = self::_fatalErrorPageContent();
                  if (DEBUG) {
                      $output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties</p>', $output);
                      $output = str_ireplace('{CODE}', '<b>Description:</b> ' . $e->getMessage() . '<br>
                        <b>File:</b> ' . str_replace(BASEPATH, "", $e->getFile()) . '<br>
                        <b>Line:</b> ' . $e->getLine(), $output);
                  } else {
                      $output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties. Please check back again later</p>', $output);
                      $output = str_ireplace('{CODE}', 'For more information turn on debug mode in your application', $output);
                  }
                  echo $output;
                  exit(1);
              }

              $this->_dbName = DB_DATABASE;
              $this->server_info = parent::getAttribute(PDO::ATTR_SERVER_INFO);
          }

      }

      /**
       * Db::__destruct()
       * 
       * @return
       */
      public function __destruct()
      {
          self::$oPDO = null;
      }

      /**
       * Db::getPDO()
       * 
       * @param mixed $params
       * @return
       */
      public static function run($params = array())
      {
          if (self::$oPDO == null)
              self::$oPDO = new self($params);
          return self::$oPDO;
      }

      /**
       * Db::start()
       * 
       * @return
       */
      public function start()
      {
          $this->beginTransaction();
      }

      /**
       * Db::end()
       * 
       * @return
       */
      public function end()
      {
          $this->commit();
      }

      /**
       * Db::back()
       * 
       * @return
       */
      public function back()
      {
          $this->rollback();
      }

      /**
       * Db::result()
       * 
       * @param integer $iRow
       * @return
       */
      public function result($iRow = 0)
      {
          return isset($this->aResults[$iRow]) ? $this->aResults[$iRow] : false;
      }

      /**
       * Db::affected()
       * 
       * @return
       */
      public function affected()
      {
          return is_numeric($this->affected) ? $this->affected : false;
      }

      /**
       * Db::getLastInsertId()
       * 
       * @return
       */
      public function getLastInsertId()
      {
          return $this->iLastId;
      }

      /**
       * Db::getAllLastInsertId()
       * 
       * @return
       */
      public function getAllLastInsertId()
      {
          return $this->iAllLastId;
      }

      /**
       * Db::getVersion()
       * 
       * @return
       */
      public function getVersion()
      {
          $version = $this->getAttribute(PDO::ATTR_SERVER_VERSION);
          return preg_replace('/[^0-9,.]/', '', $version);
      }

      /**
       * Db::pdoQuery()
       * 
       * @param string $sSql
       * @param mixed $aBindWhereParam
       * @return
       */
      public function pdoQuery($sSql = '', $aBindWhereParam = array())
      {
          $error = false;

          $sSql = trim($sSql);
          $operation = explode(' ', $sSql);
          $operation[0] = strtoupper($operation[0]);
          if (!in_array($operation[0], $this->aValidOperation)) {
              $this->_errorLog('invalid operation called in query. use only ' . implode(', ', $this->aValidOperation), $this->sSql);
          }
          if (!empty($sSql) && count($aBindWhereParam) <= 0) {
              $this->sSql = $sSql;
              $this->_oSTH = $this->prepare($this->sSql);
              try {
                  if ($this->_oSTH->execute()) {
                      $this->affected = $this->_oSTH->rowCount();
                      $this->aResults = $this->_oSTH->fetchAll();
                      $this->_oSTH->closeCursor();
                  } else {
                      $error = true;
                      $this->_errorLog('pdoQuery [pdo.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo() . ' => ' . $this->sSql);
                  }
              }
              catch (PDOException $e) {
                  $error = true;
                  $this->_errorLog('pdoQuery [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage() . ' => ' . $this->sSql);
              }
          } elseif (!empty($sSql) && count($aBindWhereParam) > 0) {
              $this->sSql = $sSql;
              $this->aData = $aBindWhereParam;
              $this->_oSTH = $this->prepare($this->sSql);
              $this->_bindPdoParam($aBindWhereParam);
              try {
                  if ($this->_oSTH->execute()) {
                      switch ($operation[0]):
                          case 'SELECT':
                              $this->affected = $this->_oSTH->rowCount();
                              $this->aResults = $this->_oSTH->fetchAll();
                              break;
                          case 'INSERT':
                              $this->iLastId = $this->lastInsertId();
                              break;
                          case 'UPDATE':
                              $this->affected = $this->_oSTH->rowCount();
                              break;
                          case 'DELETE':
                              $this->affected = $this->_oSTH->rowCount();
                              break;
                          case 'DROP':
						  case 'CREATE':
                              $this->affected = $this->_oSTH->rowCount();
                              break;
                      endswitch;
                      $this->_oSTH->closeCursor();
                  } else {
                      self::error($this->_oSTH->errorInfo());
                  }
              }
              catch (PDOException $e) {
                  $error = true;
                  $this->_errorLog($operation[0] . ' [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage() . ' => ' . $this->interpolateQuery());
              }
          } else {
              $this->_errorLog('Query is empty.', $this->sSql);
          }
          Debug::AddMessage("queries", ++self::$count . '. ' . $operation[0] . ' | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::select()
       * 
       * @param string $sTable
       * @param mixed $aColumn
       * @param mixed $aWhere
       * @param string $sOther
       * @return
       */
      public function select($sTable = '', $aColumn = array(), $aWhere = array(), $sOther = '')
      {
          $error = false;

          if (!is_array($aColumn)) {
              $aColumn = array();
          }
          $sField = count($aColumn) > 0 ? implode(', ', $aColumn) : '*';
          if (null !== $aWhere && count($aWhere) > 0 && is_array($aWhere)) {
              $this->aData = $aWhere;
              if (strstr(key($aWhere), ' ')) {
                  $tmp = $this->customWhere($this->aData);
                  $sWhere = $tmp['where'];
              } else {
                  foreach ($aWhere as $k => $v) {
                      $tmp[] = "$k = :s_$k";
                  }
                  $sWhere = implode(' AND ', $tmp);
              }
              unset($tmp);
              $this->sSql = "SELECT $sField FROM `$sTable` WHERE $sWhere $sOther;";
          } else {
              $this->sSql = "SELECT $sField FROM `$sTable` $sOther;";
          }
          $this->_oSTH = $this->prepare($this->sSql);
          if (null !== $aWhere && count($aWhere) > 0 && is_array($aWhere)) {
              $this->_bindPdoNameSpace($aWhere);
          }
          try {
              if ($this->_oSTH->execute()) {
                  $this->affected = $this->_oSTH->rowCount();
                  $this->aResults = $this->_oSTH->fetchAll();
                  $this->_oSTH->closeCursor();
              } else {
                  $this->_errorLog('select [db.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
              }
          }
          catch (PDOException $e) {
              $this->_errorLog('select [db.class.php, ln.:' . __line__ . ']', $e->getMessage() . ' => ' . $this->sSql);
              $error = true;
          }

          Debug::AddMessage("queries", ++self::$count . '. select | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::first()
       * 
       * @param mixed $sTable
       * @param mixed $aColumn
       * @param mixed $aWhere
       * @return
       */
      public function first($sTable = '', $aColumn = array(), $aWhere = array())
      {
          return $this->select($sTable, $aColumn, $aWhere, 'LIMIT 1')->result();

      }

      /**
       * Db::getValueById()
       * 
       * @param mixed $sTable
       * @param mixed $aColumn
       * @param mixed $id
       * @return
       */
      public function getValueById($sTable = '', $aColumn, $id = '')
      {
          if($row = $this->select($sTable, array($aColumn), array('id' => $id), 'LIMIT 1')->result()) {
			  return $row->$aColumn;
		  } else {
			  return false;
		  }
		  

      }
	  
      /**
       * Db::insert()
       * 
       * @param mixed $sTable
       * @param mixed $aData
       * @return
       */
      public function insert($sTable, $aData = array())
      {
          $error = false;
          if (!empty($sTable)) {
              if (count($aData) > 0 && is_array($aData)) {
                  foreach ($aData as $f => $v) {
                      $tmp[] = ":s_$f";
                  }
                  $sNameSpaceParam = implode(',', $tmp);
                  unset($tmp);
                  $sFields = implode(',', array_keys($aData));
                  $this->sSql = "INSERT INTO `$sTable` ($sFields) VALUES ($sNameSpaceParam);";
                  $this->_oSTH = $this->prepare($this->sSql);
                  $this->aData = $aData;
                  $this->_bindPdoNameSpace($aData);
                  try {
                      if ($this->_oSTH->execute()) {
                          $this->iLastId = $this->lastInsertId();
						  $this->affected = $this->_oSTH->rowCount();
                          $this->_oSTH->closeCursor();
                      } else {
                          $error = true;
                          $this->_errorLog('insert [db.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                      }
                  }
                  catch (PDOException $e) {
                      $error = true;
                      $this->_errorLog('insert [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
                  }
              } else {
                  $error = true;
                  $this->_errorLog('Data not in valid format., ln.:' . __line__ . ']', $aData);
              }
          } else {
              $error = true;
              $this->_errorLog('Table name not found., ln.:' . __line__ . ']', $sTable);
          }

          Debug::AddMessage("queries", ++self::$count . '. insert | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::insertBatch()
       * 
       * @param mixed $sTable
       * @param mixed $aData
       * @return
       */
      public function insertBatch($sTable, $aData = array())
      {
          $error = false;
		  $this->start();
          if (!empty($sTable)) {
              if (count($aData) > 0 && is_array($aData)) {
                  foreach ($aData[0] as $f => $v) {
                      $tmp[] = ":s_$f";
                  }
                  $sNameSpaceParam = implode(',', $tmp);
                  unset($tmp);
                  $sFields = implode(', ', array_keys($aData[0]));
				  
				  $this->sSql = "INSERT INTO `$sTable` ($sFields) VALUES ";
				  foreach ($aData as $key => $value) {
					  $this->sSql .= '(' . "'" . implode("', '", array_values($value)) . "'" . '), ';
				  }
				  $this->sSql = rtrim($this->sSql, ', ');
				  $this->_oSTH = $this->prepare($this->sSql);
				  
                  try {
                      if ($this->_oSTH->execute()) {
						  $this->iAllLastId[] = $this->lastInsertId();
						  $this->affected = $this->_oSTH->rowCount();
                      } else {
                          $error = true;
                          $this->_errorLog('insertBatch [db.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                      }
                  }
                  catch (PDOException $e) {
                      $error = true;
                      $this->_errorLog('insertBatch [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
                  }
				  $this->end();
				  $this->_oSTH->closeCursor();
              } else {
                  $error = true;
                  $this->_errorLog('Data not in valid format., ln.:' . __line__ . ']', $aData);
              }
          } else {
              $error = true;
              $this->_errorLog('Table name not found., ln.:' . __line__ . ']', $sTable);
          }

          Debug::AddMessage("queries", ++self::$count . '. insertBatch | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::update()
       * 
       * @param string $sTable
       * @param mixed $aData
       * @param mixed $aWhere
       * @param string $sOther
       * @return
       */
      public function update($sTable = '', $aData = array(), $aWhere = array(), $sOther = '')
      {
          $error = false;
          if (!empty($sTable)) {
              if (count($aData) > 0 && count($aWhere) > 0) {
                  foreach ($aData as $k => $v) {
                      $tmp[] = "$k = :s_$k";
                  }
                  $sFields = implode(', ', $tmp);
                  unset($tmp);
                  foreach ($aWhere as $k => $v) {
                      $tmp[] = "$k = :s_$k";
                  }
                  $this->aData = $aData;
                  $this->aWhere = $aWhere;
                  $sWhere = implode(' AND ', $tmp);
                  unset($tmp);
                  $this->sSql = "UPDATE `$sTable` SET $sFields WHERE $sWhere $sOther;";
                  $this->_oSTH = $this->prepare($this->sSql);
                  $this->_bindPdoNameSpace($aData);
                  $this->_bindPdoNameSpace($aWhere);
                  try {
                      if ($this->_oSTH->execute()) {
                          $this->affected = $this->_oSTH->rowCount();
                          $this->_oSTH->closeCursor();
                      } else {
                          $error = true;
                          $this->_errorLog('update [db.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                      }
                  }
                  catch (PDOException $e) {
                      $error = true;
                      $this->_errorLog('update [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
                  }
              } else {
                  $error = true;
                  $this->_errorLog('Data not in valid format., ln.:' . __line__ . ']', $aData);
              }
          } else {
              $error = true;
              $this->_errorLog('Table name not found., ln.:' . __line__ . ']', $sTable);
          }
          Debug::AddMessage("queries", ++self::$count . '. update | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::delete()
       * 
       * @param mixed $sTable
       * @param mixed $aWhere
       * @param string $sOther
       * @return
       */
      public function delete($sTable, $aWhere = array(), $sOther = '')
      {
          $error = false;

          if (!empty($sTable)) {

              if (count($aWhere) > 0 && is_array($aWhere)) {
                  $this->aData = $aWhere;
                  if (strstr(key($aWhere), ' ')) {
                      $tmp = $this->customWhere($this->aData);
                      $sWhere = $tmp['where'];
                  } else {
                      foreach ($aWhere as $k => $v) {
                          $tmp[] = "$k = :s_$k";
                      }
                      $sWhere = implode(' AND ', $tmp);
                  }
                  unset($tmp);
                  $this->sSql = "DELETE FROM `$sTable` WHERE $sWhere $sOther;";
              } else {
                  $this->sSql = "DELETE FROM `$sTable` $sOther;";
              }

              $this->_oSTH = $this->prepare($this->sSql);
              if (count($aWhere) > 0 && is_array($aWhere)) {
                  $this->_bindPdoNameSpace($aWhere);
              }

              try {
                  if ($this->_oSTH->execute()) {
                      $this->affected = $this->_oSTH->rowCount();
                      $this->_oSTH->closeCursor();
                  } else {
                      $error = true;
                      $this->_errorLog('delete [db.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                  }
              }
              catch (PDOException $e) {
                  $error = true;
                  $this->_errorLog('delete [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
              }
          } else {
              $error = true;
              $this->_errorLog('Table name not found., ln.:' . __line__ . ']', $sTable);
          }

          Debug::AddMessage("queries", ++self::$count . '. delete | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }

      /**
       * Db::results()
       * 
       * @param string $type
       * @return
       */
      public function results($type = 'array')
      {
          switch ($type) {
              case 'array':
                  return $this->aResults;
                  break;
              case 'json':
                  return json_encode($this->aResults);
                  break;
          }
      }

      /**
       * Db::count()
       * 
       * @param string $sTable
       * @param string $sWhere
       * @param string $sFull
       * @return
       */
      public function count($sTable = '', $sWhere = '', $sFull = '')
      {
          $error = false;
          if ($sWhere) {
              $this->sSql = "SELECT COUNT(*) AS NUMROWS FROM `$sTable` WHERE $sWhere;";
          } elseif ($sFull) {
              $this->sSql = $sFull;
          } else {
              $this->sSql = "SELECT COUNT(*) AS NUMROWS FROM `$sTable`;";
          }

          $this->_oSTH = $this->prepare($this->sSql);
          try {
              $this->_oSTH->execute();
              if ($sFull) {
                  $result = $this->_oSTH->fetchColumn();
              } else {
                  $this->aResults = $this->_oSTH->fetch();
                  $result = $this->aResults->NUMROWS;
              }
              $this->_oSTH->closeCursor();
          }
          catch (PDOException $e) {
              $error = true;
              $this->_errorLog('count [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
          }

          Debug::AddMessage("queries", ++self::$count . '. count | <i>total: ' . (($result) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $result;
      }

      /**
       * Db::batchUpdate()
       * 
       * @param string $sTable
       * @param string $sColumn
       * @param string $sValue
	   * @param array $sArray
       * @return
       */
      public function batchUpdate($sTable, $sColumn, $sValue, $sArray)
      {

		  $error = false;
		  
		  $this->sSql = "UPDATE `" . $sTable . "` SET `$sColumn` = :$sValue WHERE id = :id";
		  $this->_oSTH = $this->prepare($this->sSql);
		  $this->_oSTH->bindParam(":$sValue", $column_value);
		  $this->_oSTH->bindparam(":id", $id);
		  $i = 0;
		  try {
			  foreach($sArray as $k => $val) {
				  $i++;
				  $id = $val->id;
				  $column_value = $val->$sValue;
				  $this->_oSTH->execute();
			  }
			  $this->affected = $this->_oSTH->rowCount();
			  $this->_oSTH->closeCursor();

		  }
		  catch (PDOException $e) {
			  $error = true;
			  $this->_errorLog('update [db.class.php, ln.:' . __line__ . ']', $e->getMessage());
		  }
		  
          Debug::AddMessage("queries", ++self::$count . '. batchUpdate | <i>total: ' . (($i) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return $this;
      }
	  
      /**
       * Db::truncate()
       * 
       * @param string $sTable
       * @return
       */
      public function truncate($sTable = '')
      {
          $error = false;

          if (!empty($sTable)) {
              $this->sSql = "TRUNCATE TABLE `$sTable`;";
              $this->_oSTH = $this->prepare($this->sSql);
              try {
                  if ($this->_oSTH->execute()) {
                      $this->_oSTH->closeCursor();
                  } else {
                      $error = true;
                      $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                  }
              }
              catch (PDOException $e) {
                  $error = true;
                  $this->_errorLog('truncate [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
              }
          } else {
              $error = true;
              $this->_errorLog('truncate name not found., ln.:' . __line__ . ']', $sTable);
          }

          Debug::AddMessage("queries", ++self::$count . '. truncate | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return true;
      }

      /**
       * Db::drop()
       * 
       * @param string $sTable
       * @return
       */
      public function drop($sTable = '')
      {
          $error = false;

          if (!empty($sTable)) {
              $this->sSql = "DROP TABLE `$sTable`;";
              $this->_oSTH = $this->prepare($this->sSql);

              try {
                  if ($this->_oSTH->execute()) {
                      $this->_oSTH->closeCursor();
                  } else {
                      $error = true;
                      $this->_errorLog('drop [pdo.class.php, ln.:' . __line__ . ']', $this->_oSTH->errorInfo());
                  }
              }
              catch (PDOException $e) {
                  $error = true;
                  $this->_errorLog('drop [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
              }
          } else {
              $error = true;
              $this->_errorLog('Table name not found., ln.:' . __line__ . ']', $sTable);
          }
          Debug::AddMessage("queries", ++self::$count . '. drop | <i>total: ' . (($this->affected) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
          return true;
      }

      /**
       * Db::describe()
       * 
       * @param string $sTable
       * @return
       */
      public function describe($sTable = '')
      {
          $this->sSql = $sSql = "DESC $sTable;";
          $this->_oSTH = $this->prepare($sSql);
          $this->_oSTH->execute();
          $aColList = $this->_oSTH->fetchAll();
          foreach ($aColList as $key) {
              $aField[] = $key->Field;
              $aType[] = $key->Type;
          }
          return array_combine($aField, $aType);
      }

      /**
       * Db::exist()
       * 
       * @param string $sTable
       * @return
       */
      public function exist($sTable = '')
      {
          if (!empty($sTable)) {
			  $error = false;
              $this->sSql = "SELECT COUNT(*) as NUMROWS FROM information_schema.tables WHERE (TABLE_SCHEMA = '" . DB_DATABASE . "') AND (TABLE_NAME = '$sTable') LIMIT 1;";
			  $this->_oSTH = $this->prepare($this->sSql);
			  try {
				  $this->_oSTH->execute();
				  $result = $this->_oSTH->fetchColumn();
				  $this->_oSTH->closeCursor();
			  }
			  catch (PDOException $e) {
				  $error = true;
				  $this->_errorLog('exist [pdo.class.php, ln.:' . __line__ . ']', $e->getMessage());
			  }
			  
			  Debug::AddMessage("queries", ++self::$count . '. count | <i>total: ' . (($result) . ' ' . ($error ? '<b>(error)</b>' : null)) . '</i>', $this->interpolateQuery(), "session");
			  return $result;
		  }
      }
 
      /**
       * Db::customWhere()
       * 
       * @param mixed $array_data
       * @return
       */
      public function customWhere($array_data = array())
      {
          $syntax = '';
          foreach ($array_data as $key => $value) {
              $key = trim($key);
              if (strstr($key, ' ')) {
                  $array = explode(' ', $key);
                  if (count($array) == '2') {
                      $random = ''; //"_".rand(1,100);
                      $field = $array[0];
                      $operator = $array[1];
                      $tmp[] = "$field $operator :s_$field" . "$random";
                      $syntax .= " $field $operator :s_$field" . "$random ";
                  } elseif (count($array) == '3') {
                      $random = ''; //"_".rand(1,100);
                      $condition = $array[0];
                      $field = $array[1];
                      $operator = $array[2];
                      $tmp[] = "$condition $field $operator :s_$field" . "$random";
                      $syntax .= " $condition $field $operator :s_$field" . "$random ";
                  }
              }
          }
          return array('where' => $syntax, 'bind' => implode(' ', $tmp));
      }

      /**
       * Db::_bindPdoNameSpace()
       * 
       * @param mixed $array
       * @return
       */
      private function _bindPdoNameSpace($array = array())
      {
          if (strstr(key($array), ' ')) {
              foreach ($array as $f => $v) {
                  $field = $this->getFieldFromArrayKey($f);
                  switch (gettype($array[$f])):
                      case 'string':
                          $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_STR);
                          break;
                      case 'integer':
                          $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_INT);
                          break;
                      case 'boolean':
                          $this->_oSTH->bindParam(":s" . "_" . "$field", $array[$f], PDO::PARAM_BOOL);
                          break;
                  endswitch;
              }
          } else {
              foreach ($array as $f => $v) {
				  switch (gettype($array[$f])):
					  case 'string':
					      switch($array[$f]) {
							  case 'NULL':
							     $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_NULL);
							  break;
							  default:
							     $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_STR);
							  break;
						  }
						  break;
					  case 'integer':
						  $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_INT);
						  break;
					  case 'boolean':
						  $this->_oSTH->bindParam(":s" . "_" . "$f", $array[$f], PDO::PARAM_BOOL);
						  break;
				  endswitch;
              }
          }
      }

      /**
       * Db::_bindPdoParam()
       * 
       * @param mixed $array
       * @return
       */
      private function _bindPdoParam($array = array())
      {
          foreach ($array as $f => $v) {
              switch (gettype($array[$f])):
                  case 'string':
                      $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_STR);
                      break;
                  case 'integer':
                      $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_INT);
                      break;
                  case 'boolean':
                      $this->_oSTH->bindParam($f + 1, $array[$f], PDO::PARAM_BOOL);
                      break;
              endswitch;
          }
      }

      /**
       * Db::interpolateQuery()
       * 
       * @return
       */
      protected function interpolateQuery()
      {
          $sql = $this->_oSTH->queryString;
          if (!$this->batch) {
              $params = ((is_array($this->aData)) && (count($this->aData) > 0)) ? $this->aData : $this->sSql;
              if (is_array($params)) {
                  foreach ($params as $key => $value) {
                      if (strstr($key, ' ')) {
                          $real_key = $this->getFieldFromArrayKey($key);
                          $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                          $keys[] = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                      } else {
                          $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                          $keys[] = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                      }
                  }
                  $sql = preg_replace($keys, $params, $sql, 1, $count);

                  if (strstr($sql, ':s_')) {
                      foreach ($this->aWhere as $key => $value) {
                          if (strstr($key, ' ')) {
                              $real_key = $this->getFieldFromArrayKey($key);
                              $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                              $keys[] = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                          } else {
                              $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                              $keys[] = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                          }
                      }
                      $sql = preg_replace($keys, $params, $sql, 1, $count);
                  }
                  return $sql;
              } else {
                  return $params;
              }
          } else {
              $params_batch = ((is_array($this->aData)) && (count($this->aData) > 0)) ? $this->aData : $this->sSql;
              $batch_query = '';
              if (is_array($params_batch)) {
                  foreach ($params_batch as $keys => $params) {
                      foreach ($params as $key => $value) {
                          if (strstr($key, ' ')) {
                              $real_key = $this->getFieldFromArrayKey($key);
                              $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                              $array_keys[] = is_string($real_key) ? '/:s_' . $real_key . '/' : '/[?]/';
                          } else {
                              $params[$key] = is_string($value) ? '"' . $value . '"' : $value;
                              $array_keys[] = is_string($key) ? '/:s_' . $key . '/' : '/[?]/';
                          }
                      }
                      $batch_query .= "<br />" . preg_replace($array_keys, $params, $sql, 1, $count);
                  }
                  return $batch_query;
              } else {
                  return $params_batch;
              }
          }
      }

      /**
       * Db::getFieldFromArrayKey()
       * 
       * @param mixed $array_key
       * @return
       */
      public function getFieldFromArrayKey($array_key = array())
      {
          $key_array = explode(' ', $array_key);
          return (count($key_array) == '2') ? $key_array[0] : ((count($key_array) > 2) ? $key_array[1] : $key_array[0]);
      }

      /**
       * Db::getError()
       * 
       * @return
       */
      public static function getError()
      {
          return self::$_error;
      }

      /**
       * Db::getErrorMessage()
       * 
       * @return
       */
      public static function getErrorMessage()
      {
          return self::$_errorMessage;
      }

      /**
       * Db::_errorLog()
       * 
       * @param mixed $debugMessage
       * @param mixed $errorMessage
       * @return
       */
      private function _errorLog($debugMessage, $errorMessage)
      {
          self::$_error = true;
          self::$_errorMessage = $errorMessage;
          Debug::AddMessage('errors', $debugMessage, $errorMessage, "session");
      }

      /**
       * Db::toDate()
       * 
       * @param mixed $date
	   * @param bool $hastime
       * @return
       */
      public static function toDate($date = null, $hastime = true)
      {

          if (is_int($date)) {
              return date('Y-m-d H:i:s', $date);
          } else {
              if (is_string($date)) {
				  if($hastime) {
					  return date('Y-m-d H:i:s', strtotime($date));
				  } else {
                  	return date('Y-m-d', strtotime($date));
				  }
              } else {
				  if($hastime) {
					  return date('Y-m-d H:i:s');
				  } else {
                  	return date('Y-m-d');
				  }
              }
		  }
      }

      /**
       * Db::_fatalErrorPageContent()
       * 
       * @return
       */
      private static function _fatalErrorPageContent()
      {
          return '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Database Fatal Error</title>
        <style type="text/css">
            html{background:#f9f9f9}
            body{background:#fff; color:#333; font-family:sans-serif; margin:2em auto; padding:1em 2em 2em; -webkit-border-radius:3px; border-radius:3px; border:1px solid #dfdfdf; max-width:750px; text-align:left;}
            #error-page{margin-top:50px}
            #error-page h2{border-bottom:1px dotted #ccc;}
            #error-page p{font-size:16px; line-height:1.5; margin:2px 0 15px}
            #error-page .code-wrapper{color:#400; background-color:#f1f2f3; padding:5px; border:1px dashed #ddd}
            #error-page code{font-size:15px; font-family:Consolas,Monaco,monospace;}
            a{color:#21759B; text-decoration:none}
            a:hover{color:#D54E21}
            #footer{font-size:14px; margin-top:50px; color:#555;}
        </style>
        </head>
        <body id="error-page">
            <h2>Database connection error!</h2>
            {DESCRIPTION}
            <div class="code-wrapper">
            <code>{CODE}</code>
            </div>
        </body>
        </html>';
      }

  }