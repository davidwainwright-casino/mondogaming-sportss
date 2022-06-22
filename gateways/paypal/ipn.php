<?php
  /**
   * PayPal IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");
      require_once ("paypal.class.php");

	  $pp = Db::run()->first(Core::gTable, array("live", "extra"), array("name" => "paypal"));

      $listener = new IpnListener();
      $listener->use_live = $pp->live;
      $listener->use_ssl = true;
      $listener->use_curl = true;

      try {
          $listener->requirePostMethod();
          $ppver = $listener->processIpn();
      }
      catch (exception $e) {
		  error_log('Process IPN failed: ' . $e->getMessage() . " [".$_SERVER['REMOTE_ADDR']."] \n" . $listener->getResponse(), 3, "pp_errorlog.log");
          exit(0);
      }

      $payment_status = $_POST['payment_status'];
      $receiver_email = $_POST['receiver_email'];
	  $mc_currency = Validator::sanitize($_POST['mc_currency']);
      list($membership_id, $user_id) = explode("_", $_POST['item_number']);
      $mc_gross = $_POST['mc_gross'];
      $txn_id = Validator::sanitize($_POST['txn_id']);

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

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
			  if ($row and $v1 and $receiver_email == $pp->extra) {
				  $data = array(
					  'txn_id' => $txn_id,
					  'membership_id' => $row->id,
					  'user_id' => $usr->id,
					  'rate_amount' => $cart->total,
					  'coupon' => $cart->coupon,
					  'total' => $cart->totalprice,
					  'tax' => $cart->totaltax,
					  'currency' => strtoupper($mc_currency),
					  'ip' => Url::getIP(),
					  'pp' => "PayPal",
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
				  //Auth::$udata->membership_id = App::Session()->set('membership_id', $row->id);
				  //Auth::$udata->mem_expire = App::Session()->set('mem_expire', $xdata['mem_expire']);

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
					  "PayPal",
					  Url::getIP(),
					  $core->social->facebook,
					  $core->social->twitter), $tpl->body);
	
				  $msg = Swift_Message::newInstance()
						->setSubject($tpl->subject)
						->setTo(array($core->site_email => $core->company))
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
					  "PayPal",
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
              }
          } else {
              /* == Failed Transaction= = */
          }
      }
  }