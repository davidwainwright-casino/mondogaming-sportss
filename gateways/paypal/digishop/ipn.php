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
      require_once ("../../../init.php");
	  Bootstrap::Autoloader(array(AMODPATH . 'digishop/'));
	  
	  require_once (BASEPATH . 'gateways/paypal/paypal.class.php');

	  $pp = Db::run()->first(Core::gTable, array("live", "extra"), array("name" => "paypal"));

      $listener = new IpnListener();
      $listener->use_live = $pp->live;
      $listener->use_ssl = false;
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
	  $mc_currency = Validator::sanitize($_POST['mc_currency'], "string", 4);
      list($user_id, $sesid) = explode("_", $_POST['item_number']);
      $mc_gross = $_POST['mc_gross'];
	  $sesid = Validator::sanitize($sesid);
      $txn_id = Validator::sanitize($_POST['txn_id']);

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
			  $usr = Db::run()->first(Users::mTable, null, array("id" => intval($user_id)));
			  $cart = Digishop::getCart($sesid);
			  $totals = Digishop::getCartTotal($sesid);
			  $tax = Membership::calculateTax(intval($user_id));
			  
			  $amount = Utility::formatNumber($tax * $totals->grand + $totals->grand); 
			  $v1 = Validator::compareNumbers($mc_gross, $amount, "=");
			  
			  if ($cart and $v1 and $receiver_email == $pp->extra) {
				  foreach ($cart as $item) {
					  $dataArray[] = array(
						  'user_id' => $usr->id,
						  'item_id' => $item->pid,
						  'txn_id' => $txn_id,
						  'tax' => Validator::sanitize($totals->sub * $tax, "float"),
						  'amount' => Validator::sanitize($totals->grand, "float"),
						  'total' => Validator::sanitize($amount, "float"),
						  'token' => Utility::randomString(16),
						  'pp' => "PayPal",
						  'ip' => Url::getIP(),
						  'currency' => strtoupper($mc_currency),
						  'status' => 1,
						  );
				  }
				  
				  Db::run()->insertBatch(Digishop::xTable, $dataArray);

				  /* == Notify User == */
				  $mailer = Mailer::sendMail();
				  $core = App::Core();
				  $etpl = Db::run()->first(Content::eTable, array("body" . Lang::$lang, "subject" . Lang::$lang), array('typeid' => 'digiNotifyUser'));
				  
				  $tpl = App::View(FMODPATH . 'digishop/snippets/'); 
				  $tpl->rows = Digishop::getCartContent($sesid);
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
					  $usr->fname . ' ' . $usr->lname,
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
						->setTo(array($usr->email => $usr->fname . ' ' . $usr->lname))
						->setFrom(array($core->site_email => $core->company))
						->setBody($body, 'text/html'
						);
				  $mailer->send($msg);
	
				  Db::run()->delete(Digishop::qTable, array("uid" => $sesid));
              }
          } else {
              /* == Failed Transaction= = */
          }
      }
  }