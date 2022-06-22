<?php
  /**
   * iDeal IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_WOJO", true);
  require_once ("../../init.php");

  if (!App::Auth()->is_User())
      exit;

  if (Validator::get('order_id')) {
      require_once (dirname(__file__) . '/initialize.php');
      $apikey = Db::run()->first(Core::gTable, array("extra"), array("name" => "ideal"));

      $mollie = new Mollie_API_Client;
      $mollie->setApiKey($apikey->extra);

      $o = Validator::sanitize($_GET['order_id'], "string");
      $cart = Db::run()->select(Membership::cTable, null, array("order_id" => $o))->result();

      if ($cart) {
          $payment = $mollie->payments->get($cart->cart_id);
          if ($payment->isPaid() == true and Validator::compareNumbers($payment->amount, $cart->totalprice, "=")) {
              $row = Db::run()->first(Membership::mTable, null, array("id" => $cart->mid));
              $data = array(
                  'txn_id' => $payment->metadata->order_id,
                  'membership_id' => $row->id,
                  'user_id' => App::Auth()->uid,
                  'rate_amount' => $cart->total,
                  'coupon' => $cart->coupon,
                  'total' => $cart->totalprice,
                  'tax' => $cart->totaltax,
                  'currency' => "EUR",
                  'ip' => Url::getIP(),
                  'pp' => "iDeal",
                  'status' => 1,
                  );

              $last_id = Db::run()->insert(Membership::pTable, $data)->getLastInsertId();

              //insert user membership
              $udata = array(
                  'tid' => $last_id,
                  'uid' => App::Auth()->uid,
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
              Db::run()->update(Users::mTable, $xdata, array("id" => App::Auth()->uid));
              Db::run()->delete(Membership::cTable, array("uid" => App::Auth()->uid));

              //update membership status
              Auth::$udata->membership_id = App::Session()->set('membership_id', $row->id);
              Auth::$udata->mem_expire = App::Session()->set('mem_expire', $xdata['mem_expire']);

              $json['type'] = 'success';
              $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = Lang::$word->STR_POK;
			  
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
				  App::Auth()->name,
				  $row->{'title' . Lang::$lang},
				  $data['total'],
				  "Completed",
				  "iDeal",
				  Url::getIP(),
				  $core->social->facebook,
				  $core->social->twitter), $tpl->body);

			  $msg = Swift_Message::newInstance()
					->setSubject($tpl->subject)
					->setTo(array($core->site_email => $core->company))
					->setFrom(array(App::Auth()->email => App::Auth()->name))
					->setBody($body, 'text/html');
			  $mailer->send($msg);
          } else {
              $json['type'] = 'error';
              $json['title'] = Lang::$word->ERROR;
              $json['message'] = Lang::$word->STR_ERR1;
          }
      } else {
          $json['type'] = 'error';
          $json['title'] = Lang::$word->ERROR;
          $json['message'] = Lang::$word->STR_ERR1;
      }
      print json_encode($json);
  } else {
	  $json['type'] = 'error';
	  $json['title'] = Lang::$word->ERROR;
	  $json['message'] = Lang::$word->STR_ERR1;
	  print json_encode($json);
  }