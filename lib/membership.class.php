<?php
  /**
   * Membership Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Membership
  {
      const mTable = "memberships";
	  const umTable = "user_memberships";
      const pTable = "payments";
	  const cTable = "cart";

      /**
       * Membership::__construct()
       * 
       * @return
       */
      public function __construct()
      {

      }
	  
      /**
       * Membership::Index()
       * 
       * @return
       */
      public function Index()
      {
		  
		  $sql = "
		  SELECT 
			m.*,
			m.title" . Lang::$lang . " as title,
			m.description" . Lang::$lang . " as description,
			(SELECT 
			  COUNT(p.membership_id) 
			FROM
			  `" . self::pTable . "` as p 
			WHERE p.membership_id = m.id) AS total
		  FROM
			memberships as m";

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->template = 'admin/memberships.tpl.php';
		  $tpl->data = Db::run()->pdoQuery($sql)->results(); 
		  $tpl->title = Lang::$word->META_T4; 
	  }

      /**
       * Membership::Edit()
       * 
	   * @param mixed $id
       * @return
       */
	  public function Edit($id)
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T5;
		  $tpl->crumbs = ['admin', 'memberships', 'edit'];
	
		  if (!$row = Db::run()->first(self::mTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [membership.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {
			  $tpl->data = $row;
			  $tpl->langlist = App::Core()->langlist;
			  $tpl->template = 'admin/memberships.tpl.php';
		  }
	  }

      /**
       * Membership::Save()
       * 
       * @return
       */
	  public function Save()
	  {
		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->langlist = App::Core()->langlist;
		  $tpl->title = Lang::$word->META_T6;
		  $tpl->template = 'admin/memberships.tpl.php';
	  }

      /**
       * Membership::History()
       * 
	   * @param mixed $id
       * @return
       */
	  public function History($id)
	  {

		  $tpl = App::View(BASEPATH . 'view/');
		  $tpl->dir = "admin/";
		  $tpl->title = Lang::$word->META_T7;
		  $tpl->crumbs = ['admin', 'memberships', 'history'];
	
		  if (!$row = Db::run()->first(self::mTable, null, array("id =" => $id))) {
			  $tpl->template = 'admin/error.tpl.php';
			  $tpl->error = DEBUG ? "Invalid ID ($id) detected [membership.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
		  } else {

			  $pager = Paginator::instance();
			  $pager->items_total = Db::run()->count(self::pTable, 'membership_id = ' . $id . ' AND status = 1');
			  $pager->default_ipp = App::Core()->perpage;
			  $pager->path = Url::url(Router::$path, "?");
			  $pager->paginate();
			  
			  $sql = "
			  SELECT 
				p.rate_amount,
				p.tax,
				p.coupon,
				p.total,
				p.currency,
				p.created,
				p.user_id,
				CONCAT(u.fname,' ',u.lname) as name
			  FROM
				`" . self::pTable . "` AS p 
				LEFT JOIN " . Users::mTable . " AS u 
				  ON u.id = p.user_id 
			  WHERE p.membership_id = ?
			  AND p.status = ?
			  ORDER BY p.created DESC" . $pager->limit . ";";

			  $tpl->data = $row;
			  $tpl->plist = Db::run()->pdoQuery($sql, array($id, 1))->results();
			  $tpl->pager = $pager;
			  $tpl->template = 'admin/memberships.tpl.php';
		  }
	  }
	  
      /**
       * Membership::processMembership()
       * 
       * @return
       */
	  public function processMembership()
	  {
	
		  $rules = array(
			  'price' => array('required|numeric', Lang::$word->MEM_PRICE),
			  'days' => array('required|numeric', Lang::$word->MEM_DAYS),
			  'period' => array('required|string|min_len,1|max_len,1', Lang::$word->MEM_DAYS),
			  'recurring' => array('required|numeric', Lang::$word->MEM_REC),
			  'private' => array('required|numeric', Lang::$word->MEM_PRIVATE),
			  'active' => array('required|numeric', Lang::$word->PUBLISHED),
			  );

		  foreach (App::Core()->langlist as $lang) {
			  $rules['title_' . $lang->abbr] = array('required|string|min_len,3|max_len,60', Lang::$word->NAME . ' <span class="flag icon ' . $lang->abbr . '"></span>');
			  $filters['title_' . $lang->abbr] = 'trim|string';
			  $filters['description_' . $lang->abbr] = 'trim|string';
		  }
			  
		  if (!empty($_FILES['thumb']['name']) and empty(Message::$msgs)) {
			  $upl = Upload::instance(3145728, "png,jpg");
			  $upl->process("thumb", UPLOADS .'/memberships/', "MEM_");
		  }

		  $validate = Validator::instance();
		  $safe = $validate->doValidate($_POST, $rules);
		  $safe = $validate->doFilter($_POST, $filters);
		  
		  if (empty(Message::$msgs)) {
			  foreach (App::Core()->langlist as $i => $lang) {
				  $datam['title_' . $lang->abbr] = $safe->{'title_' . $lang->abbr};
				  $datam['description_' . $lang->abbr] = $safe->{'description_' . $lang->abbr};
			  }
			  $datax = array(
				  'price' => $safe->price,
				  'days' => $safe->days,
				  'period' => $safe->period,
				  'recurring' => $safe->recurring,
				  'private' => $safe->private,
				  'active' => $safe->active,
				  );
				  
			  if (!empty($_FILES['thumb']['name'])) {
				  $datax['thumb'] = $upl->fileInfo['fname'];
			  }
			  
			  $data = array_merge($datam, $datax);
			  (Filter::$id) ? Db::run()->update(self::mTable, $data, array("id" => Filter::$id)) : $last_id = Db::run()->insert(self::mTable, $data)->getLastInsertId(); 
			  
			  $message = Filter::$id ? 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->MEM_UPDATE_OK) : 
			  Message::formatSuccessMessage($datam['title' . Lang::$lang], Lang::$word->MEM_ADDED_OK);
			  
			  Message::msgReply(Db::run()->affected(), 'success', $message);
			  Logger::writeLog($message);
		  } else {
			  Message::msgSingleStatus();
		  }
	  }
	  
      /**
       * Membership::getMembershipList()
       * 
       * @return
       */
	  public function getMembershipList()
	  {
	
		  $row = Db::run()->select(self::mTable, array("id","title" . Lang::$lang))->results();
		  return ($row) ? $row : 0;
	  }

      /**
       * Membership::buyMembership()
       * 
       * @return
       */
      public function buyMembership()
      {
		  
		  if($row = Db::run()->first(self::mTable, null, array("id" => Filter::$id, "private" => 0))) {
			  $gaterows = Db::run()->select(Core::gTable, null, array("active" => 1))->results();
			  
			  if ($row->price == 0)  {
				  $data = array(
					  'membership_id' => $row->id,
					  'mem_expire' => Membership::calculateDays($row->id),
					  );
	
				  Db::run()->update(Users::mTable, $data, array("id" => App::Auth()->uid));
				  Auth::$udata->membership_id = App::Session()->set('membership_id', $row->id);
				  Auth::$udata->mem_expire = App::Session()->set('mem_expire', $data['mem_expire']);
				  
				  $json['message'] = Message::msgSingleOk(str_replace("[NAME]", $row->{'title_' . $lang->abbr}, Lang::$word->M_INFO12), false);
			  } else {
				  $recurring = ($row->recurring) ? Lang::$word->YES : Lang::$word->NO;
				  Db::run()->delete(self::cTable, array("uid" => App::Auth()->uid));
				  $tax = self::calculateTax();
				  
				  $data = array(
					  'uid' => App::Auth()->uid,
					  'mid' => $row->id,
					  'originalprice' => Validator::sanitize($row->price, "float"),
					  'tax' => Validator::sanitize($tax, "float"),
					  'totaltax' => Validator::sanitize($row->price * $tax, "float"),
					  'total' => Validator::sanitize($row->price, "float"),
					  'totalprice' => Validator::sanitize($tax * $row->price + $row->price, "float"),
					  );
				  Db::run()->insert(self::cTable, $data);
				  $cart = self::getCart();
				  
				  $tpl = App::View(THEMEBASE . '/snippets/'); 
				  $tpl->row = $row;
				  $tpl->gateways = $gaterows;
				  $tpl->cart = $cart;
				  $tpl->template = 'membershipSummary.tpl.php'; 
				  $json['message'] = $tpl->render();
			  }
		  } else {
			  $json['type'] = "error";
		  }
		  print json_encode($json);
      }

      /**
       * Membership::getCoupon()
       * 
       * @return
       */
	  public function getCoupon()
	  {
	      $sql = "SELECT * FROM `" . Content::dcTable . "` WHERE FIND_IN_SET(" . Filter::$id . ", membership_id) AND code = ? AND ctype = ? AND active = ?";
		  if ($row = Db::run()->pdoQuery($sql, array(Validator::sanitize($_POST['code']), "membership", 1))->result()) {
			  $row2 = Db::run()->first(self::mTable, null, array("id" => Filter::$id));
			  
			  Db::run()->delete(self::cTable, array("uid" => App::Auth()->uid));
			  $tax = self::calculateTax();
			  
			  if($row->type == "p") {
				  $disc = Validator::sanitize($row2->price / 100 * $row->discount, "float");
				  $gtotal = Validator::sanitize($row2->price - $disc, "float");
			  } else {
				  $disc = Validator::sanitize($row->discount, "float");
				  $gtotal = Validator::sanitize($row2->price - $disc, "float");
			  }

			  $data = array(
				  'uid' => App::Auth()->uid,
				  'mid' => $row2->id,
				  'cid' => $row->id,
				  'tax' => Validator::sanitize($tax, "float"),
				  'totaltax' => Validator::sanitize($gtotal * $tax, "float"),
				  'coupon' => $disc,
				  'total' => $gtotal,
				  'originalprice' => Validator::sanitize($row2->price, "float"),
				  'totalprice' => Validator::sanitize($tax * $gtotal + $gtotal, "float"),
				  );
			  Db::run()->insert(self::cTable, $data);
		  
			  $json['type'] = "success";
			  $json['disc'] = "- " . Utility::formatMoney($disc);
			  $json['tax'] = Utility::formatMoney($data['totaltax']);
			  $json['gtotal'] = Utility::formatMoney($data['totalprice']);
		  } else {
			  $json['type'] = "error";
		  }
		  print json_encode($json);
	  }

      /**
       * Membership::selectGateway()
       * 
       * @return
       */
	  public function selectGateway()
	  {
	
		  if ($cart = self::getCart()) {
			  $gateway = Db::run()->first(Core::gTable, null, array("id" => Filter::$id, "active" => 1));
			  $row = Db::run()->first(self::mTable, null, array("id" => $cart->mid));
			  $tpl = App::View(BASEPATH . 'gateways/' . $gateway->dir . '/');
			  $tpl->cart = $cart;
			  $tpl->gateway = $gateway;
			  $tpl->row = $row;
			  $tpl->template = 'form.tpl.php';
			  $json['gateway'] = $gateway->name;
			  $json['message'] = $tpl->render();
		  } else {
			  $json['message'] = Message::msgSingleError(Lang::$word->SYSERROR, false);
		  }
		  print json_encode($json);
	  }
	  
      /**
       * Membership::getAccessList()
       * 
       * @param bool $sel
       * @return
       */
      public static function getAccessList($sel = false)
	  {
		  $arr = array(
				 'Public' => Lang::$word->PUBLIC,
				 'Registered' => Lang::$word->REGISTERED,
				 'Membership' =>Lang::$word->MEMBERSHIP
		  );
          return $arr;
      }

      /**
       * Membership::calculateTax()
       * 
	   * @param bool $uid
       * @return
       */
	  public static function calculateTax($uid = false)
	  {
		  if (App::Core()->enable_tax) {
			  if ($uid) {
				  $cnt = Db::run()->first(Users::mTable, array("country"), array("id" => $uid));
				  if ($cnt) {
					  $row = Db::run()->first(Content::cTable, array("vat"), array("abbr" => $cnt->country));
					  return ($row->vat / 100);
				  } else {
					  return 0;
				  }
			  } else {
				  if (App::Auth()->country) {
					  $row = Db::run()->first(Content::cTable, array("vat"), array("abbr" => App::Auth()->country));
					  return ($row->vat / 100);
				  } else {
					  return 0;
				  }
			  }
		  } else {
			  return 0;
		  }
	  }

      /**
       * Membership::getCart()
       * 
	   * @param bool $uid
       * @return
       */
	  public static function getCart($uid = false)
	  {
		  $id = ($uid) ? intval($uid) : App::Auth()->uid;
		  $row = Db::run()->first(self::cTable, null, array("uid" => $id));
		  
		  return ($row) ? $row : 0; 
	  }

      /**
       * Membership::is_valid()
       * 
       * @return
       */
	  public static function is_valid(array $mid)
	  {
		  if (in_array(App::Auth()->membership_id, $mid)) {
			  return true;
		  } else {
			  return false;
		  }
	  }
	  
      /**
       * Membership::calculateDays()
       * 
	   * @param bool $membership_id
       * @return
       */
      public static function calculateDays($membership_id)
      {

          $row = Db::run()->first(self::mTable, array('days', 'period'), array('id' => $membership_id));
          if ($row) {
              switch ($row->period) {
                  case "D":
                      $diff = ' day';
                      break;
                  case "W":
					  $diff = ' week';
                      break;
                  case "M":
					  $diff = ' month';
                      break;
                  case "Y":
					  $diff = ' year';
                      break;
              }
              $expire = Date::NumberOfDays('+' . $row->days . $diff);
          } else {
              $expire = "";
          }
          return $expire;
      }
  }