<?php
  /**
   * PayFast IPN
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
	  
	  require_once (BASEPATH . 'gateways/payfast/pf.inc.php');

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
          $mc_gross = $_POST['amount_gross'];
          $item_ = $_POST['m_payment_id'];
          $txn_id = Validator::sanitize($_POST['pf_payment_id']);
		  list($user_id, $sesid) = explode("_", $_POST['custom_int1']);
		  $sesid = Validator::sanitize($sesid);

		  $usr = Db::run()->first(Users::mTable, null, array("id" => intval($user_id)));
		  $cart = Digishop::getCart($sesid);
		  $totals = Digishop::getCartTotal($sesid);
		  $tax = Membership::calculateTax(intval($user_id));
		  
		  $amount = Utility::formatNumber($tax * $totals->grand + $totals->grand);
		  $v1 = Validator::compareNumbers($mc_gross, $amount, "=");
			  
          if ($v1 == true) {
			  foreach ($cart as $item) {
				  $dataArray[] = array(
					  'user_id' => $usr->id,
					  'item_id' => $item->pid,
					  'txn_id' => $txn_id,
					  'tax' => Validator::sanitize($totals->sub * $tax, "float"),
					  'amount' => Validator::sanitize($totals->grand, "float"),
					  'total' => Validator::sanitize($amount, "float"),
					  'token' => Utility::randomString(16),
					  'pp' => "PayFast",
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
          /* == Failed or Pending Transaction == */
      }
  }