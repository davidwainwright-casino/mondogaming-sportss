<?php
  /**
   * iDeal IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once ("../../../init.php");

  if (!App::Auth()->is_User())
      exit;

  if (Validator::get('order_id')) {
	  Bootstrap::Autoloader(array(AMODPATH . 'digishop/'));
	  
	  require_once (BASEPATH . 'gateways/ideal/initialize.php');
      $apikey = Db::run()->first(Core::gTable, array("extra"), array("name" => "ideal"));

      $mollie = new Mollie_API_Client;
      $mollie->setApiKey($apikey->extra);

      $o = Validator::sanitize($_GET['order_id'], "string");
      $cart = Digishop::getCart();

	  $totals = Digishop::getCartTotal();
	  $tax = Membership::calculateTax();
	  
	  $amount = Utility::formatNumber($tax * $totals->grand + $totals->grand);
			  
      if ($cart) {
          $payment = $mollie->payments->get($totals->cart_id);
          if ($payment->isPaid() == true and Validator::compareNumbers($payment->amount, $amount, "=")) {
			  foreach ($cart as $item) {
				  $dataArray[] = array(
					  'user_id' => App::Auth()->uid,
					  'item_id' => $item->pid,
					  'txn_id' => $payment->metadata->order_id,
					  'tax' => Validator::sanitize($totals->sub * $tax, "float"),
					  'amount' => Validator::sanitize($totals->grand, "float"),
					  'total' => Validator::sanitize($amount, "float"),
					  'token' => Utility::randomString(16),
					  'pp' => "iDeal",
					  'ip' => Url::getIP(),
					  'currency' => "EUR",
					  'status' => 1,
					  );
			  }
			  
			  Db::run()->insertBatch(Digishop::xTable, $dataArray);

              $json['type'] = 'success';
              $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = Lang::$word->STR_POK;
			  
              /* == Notify User == */
              $mailer = Mailer::sendMail();
			  $core = App::Core();
			  $etpl = Db::run()->first(Content::eTable, array("body" . Lang::$lang, "subject" . Lang::$lang), array('typeid' => 'digiNotifyUser'));
			  
			  $tpl = App::View(FMODPATH . 'digishop/snippets/'); 
			  $tpl->rows = Digishop::getCartContent();
			  $tpl->tax = $tax;
			  $tpl->totals = $totals;
			  $tpl->template = '_userNotifyTemplate.tpl.php'; 
				
			  $body = str_replace(array(
				  '[LOGO]',
				  '[NAME]',
				  '[DATE]',
				  '[COMPANY]',
				  '[SITE_NAME]',
				  '[ITEMS]',
				  '[URL]',
				  '[FB]',
				  '[TW]',
				  '[SITEURL]'), array(
				  Utility::getLogo(),
				  App::Auth()->name,
				  date('Y'),
				  $core->company,
				  $core->site_name,
				  $tpl->render(),
				  Url::url('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang}, "digishop"),
				  $core->social->facebook,
				  $core->social->twitter,
				  SITEURL), $etpl->{'body' . Lang::$lang}); 
				  
			  $msg = Swift_Message::newInstance()
					->setSubject($etpl->{'subject' . Lang::$lang})
					->setTo(array(App::Auth()->email => App::Auth()->name))
					->setFrom(array($core->site_email => $core->company))
					->setBody($body, 'text/html'
					);
			  $mailer->send($msg);

			  Db::run()->delete(Digishop::qTable, array("uid" => App::Auth()->uid));
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