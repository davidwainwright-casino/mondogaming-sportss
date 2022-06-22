<?php
  /**
   * Skrill IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../init.php");
  
  /* only for debuggin purpose. Create logfile.txt and chmot to 0777
   ob_start();
   echo '<pre>';
   print_r($_POST);
   echo '</pre>';
   $logInfo = ob_get_contents();
   ob_end_clean();
   
   $file = fopen('logfile.txt', 'a');
   fwrite($file, $logInfo);
   fclose($file);
   */
  
  /* Check for mandatory fields */
  $r_fields = array(
		'status', 
		'md5sig', 
		'merchant_id', 
		'pay_to_email', 
		'mb_amount', 
		'mb_transaction_id', 
		'currency', 
		'amount', 
		'transaction_id', 
		'pay_from_email', 
		'mb_currency'
  );
  $skrill = Db::run()->first(Core::gTable, array("extra3"), array("name" => "skrill"));
  
  foreach ($r_fields as $f)
      if (!isset($_POST[$f]))
          die();
  
  /* Check for MD5 signature */
  $md5 = strtoupper(md5($_POST['merchant_id'] . $_POST['transaction_id'] . strtoupper(md5($skrill->extra3)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
  if ($md5 != $_POST['md5sig'])
      die();
  
  if (intval($_POST['status']) == 2) {
      $mb_currency = Validator::sanitize($_POST['mb_currency']);
	  $mc_gross = $_POST['amount'];
	  $txn_id = Validator::sanitize($_POST['mb_transaction_id']);

      list($membership_id, $user_id) = explode("_", $_POST['custom']);

	  $row = Db::run()->first(Membership::mTable, null, array("id" => intval($membership_id)));
	  $usr = Db::run()->first(Users::mTable, null, array("id" => intval($user_id)));
	  $cart = Membership::getCart($usr->id);

	  if($cart) {
	      $v1 = Validator::compareNumbers($mc_gross, $cart->totalprice, "=");
	  } else {
		  $cart = new stdClass;
		  $tax =  Membership::calculateTax(intval($user_id));
		  $v1 = Validator::compareNumbers($mc_gross, $row->price, "gte");
		  
		  $cart->originalprice = $row->price;
		  $cart->total = $row->price;
		  $cart->totaltax = Validator::sanitize($row->price * $tax, "float");
		  $cart->totalprice = Validator::sanitize($tax * $row->price + $row->price, "float");
	  }

	  $data = array(
		  'txn_id' => $txn_id,
		  'membership_id' => $row->id,
		  'user_id' => $usr->id,
		  'rate_amount' => $cart->total,
		  'coupon' => $cart->coupon,
		  'total' => $cart->totalprice,
		  'tax' => $cart->totaltax,
		  'currency' => strtoupper($mb_currency),
		  'ip' => Url::getIP(),
		  'pp' => "Skrill",
		  'status' => 1,
		  );
	  
	  $last_id = Db::run()->insert(Membership::pTable, $data)->getLastInsertId();

	  //insert user membership
	  $udata = array(
		  'tid' => $last_id,
		  'uid' => $usr->id,
		  'mid' => $row->id,
		  'expire' => Membership::calculateDays($row->id),
		  'recurring' => $row->recurring,
		  'active' => 1,
		  );

	  //update user record
	  $xdata = array(
		  'membership_id' => $row->id,
		  'mem_expire' => $udata['expire'],
		  );
		  
	  Db::run()->insert(Membership::umTable, $udata);
	  Db::run()->update(Users::mTable, $xdata, array("id" => $usr->id));
	  Db::run()->delete(Membership::cTable, array("uid" => $usr->id));
  
	  //update membership status
	  Auth::$udata->membership_id = App::Session()->set('membership_id', $row->id);
	  Auth::$udata->mem_expire = App::Session()->set('mem_expire', $xdata['mem_expire']);
      
      /* == Notify Administrator == */
	  $mailer = Mailer::sendMail();
	  $tpl = Db::run()->first(Content::eTable, array("body" . Lang::$lang . " as body", "subject" . Lang::$lang . " as subject"), array('typeid' => 'payComplete'));
	  $core = App::Core();
	  $body = str_replace(array(
		  '[LOGO]',
		  '[COMPANY]',
		  '[DATE]',
		  '[SITEURL]',
		  '[NAME]',
		  '[ITEMNAME]',
		  '[PRICE]',
		  '[STATUS]',
		  '[PP]',
		  '[IP]',
		  '[FB]',
		  '[TW]'), array(
		  Utility::getLogo(),
		  $core->company,
		  date('Y'),
		  SITEURL,
		  $usr->fname . ' ' . $usr->lname,
		  $row->{'title' . Lang::$lang},
		  $data['total'],
		  "Completed",
		  "Skrill",
		  Url::getIP(),
		  $core->social->facebook,
		  $core->social->twitter), $tpl->body);

	  $msg = Swift_Message::newInstance()
			->setSubject($tpl->subject)
			->setTo(array($core->psite_email ? $core->psite_email : $core->site_email => $core->company))
			->setFrom(array($usr->email => $usr->fname . ' ' . $usr->lname))
			->setBody($body, 'text/html');
	  $mailer->send($msg);


	  /* == Notify User == */
	  $tpl2 = Db::run()->first(Content::eTable, array("body" . Lang::$lang . " as body", "subject" . Lang::$lang . " as subject"), array('typeid' => 'payCompleteUser'));
	  $ubody = str_replace(array(
		  '[LOGO]',
		  '[COMPANY]',
		  '[DATE]',
		  '[SITEURL]',
		  '[NAME]',
		  '[ITEMNAME]',
		  '[PRICE]',
		  '[COUPON]',
		  '[TAX]',
		  '[PP]',
		  '[FB]',
		  '[TW]'), array(
		  Utility::getLogo(),
		  $core->company,
		  date('Y'),
		  SITEURL,
		  $usr->fname . ' ' . $usr->lname,
		  $row->{'title' . Lang::$lang},
		  $data['total'],
		  $data['coupon'],
		  $data['tax'],
		  "Skrill",
		  Url::getIP(),
		  $core->social->facebook,
		  $core->social->twitter), $tpl2->body);
		  
	  $umailer = Mailer::sendMail();
	  $umessage = Swift_Message::newInstance()
				->setSubject($tpl2->subject)
				->setTo(array($usr->email => $usr->fname . ' ' . $usr->lname))
				->setFrom(array($core->site_email => $core->company))
				->setBody($ubody, 'text/html');
	  $umailer->send($umessage);
			  
  } else {
      /* == Failed or Pending Transaction == */
  }