<?php
  /**
   * PayFast IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_WOJO", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  include_once dirname(__file__) . '/pf.inc.php';

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");

	  $pp = Db::run()->first(Core::gTable, array("live", "extra3"), array("name" => "payfast"));
      $pfHost = ($pf->live) ? 'https://www.payfast.co.za' : 'https://sandbox.payfast.co.za';
      $error = false;

      pflog('ITN received from payfast.co.za');
      if (!pfValidIP($_SERVER['REMOTE_ADDR'])) {
          pflog('REMOTE_IP mismatch: ');
          $error = true;
          return false;
      }

      $data = pfGetData();

      pflog('POST received from payfast.co.za: ' . print_r($data, true));

      if ($data === false) {
          pflog('POST is empty: ' . print_r($data, true));
          $error = true;
          return false;
      }

      if (!pfValidSignature($data, $pf->extra3)) {
          pflog('Signature mismatch on POST');
          $error = true;
          return false;
      }

      pflog('Signature OK');

      $itnPostData = array();
      $itnPostDataValuePairs = array();

      foreach ($_POST as $key => $value) {
          if ($key == 'signature')
              continue;

          $value = urlencode(stripslashes($value));
          $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value);
          $itnPostDataValuePairs[] = "$key=$value";
      }

      $itnVerifyRequest = implode('&', $itnPostDataValuePairs);
      if (!pfValidData($pfHost, $itnVerifyRequest, "$pfHost/eng/query/validate")) {
          pflog("ITN mismatch for $itnVerifyRequest\n");
          pflog('ITN not OK');
          $error = true;
          return false;
      }

      pflog('ITN OK');
      pflog("ITN verified for $itnVerifyRequest\n");

      if ($error == false and $_POST['payment_status'] == "COMPLETE") {
          $user_id = intval($_POST['custom_int1']);
          $mc_gross = $_POST['amount_gross'];
          $membership_id = $_POST['m_payment_id'];
          $txn_id = Validator::sanitize($_POST['pf_payment_id']);

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

          if ($v1 == true) {
			  $data = array(
				  'txn_id' => $txn_id,
				  'membership_id' => $row->id,
				  'user_id' => $usr->id,
				  'rate_amount' => $cart->total,
				  'coupon' => $cart->coupon,
				  'total' => $cart->totalprice,
				  'tax' => $cart->totaltax,
				  'currency' => "ZAR",
				  'ip' => Url::getIP(),
				  'pp' => "PayFast",
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
				  "PayFast",
				  Url::getIP(),
				  $core->social->facebook,
				  $core->social->twitter), $tpl->body);

			  $msg = Swift_Message::newInstance()
					->setSubject($tpl->subject)
					->setTo(array($core->site_email => $core->company))
					->setFrom(array($usr->email => $usr->fname . ' ' . $usr->lname))
					->setBody($body, 'text/html');
			  $mailer->send($msg);
			  pflog("Email Notification [Admin] sent successfuly");


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
				  "PayFast",
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
			  pflog("Email Notification [User] sent successfuly");
          }

      } else {
          /* == Failed or Pending Transaction == */
      }
  }