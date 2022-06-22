<?php
  /**
   * PayFast IPN
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2019
   * @version $Id: ipn.php, 2019-07-10 21:12:05 gewa Exp $
   */
  define("_WOJO", true);

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../../init.php");
	  Bootstrap::Autoloader(array(AMODPATH . 'shop/'));
	  
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
		  $cart = Shop::getCartContent($sesid);
		  $totals = Shop::getCartTotal($sesid);
		  $shipping = Db::run()->first(Shop::qxTable, null, array("user_id" => $sesid));
		  $tax = Membership::calculateTax(intval($user_id));
		  
		  $amount = (($shipping->total) + $tax * $totals->grand + $totals->grand);
		  $v1 = Validator::compareNumbers($mc_gross, $amount, "=");
		  $items = array();
			  
          if ($v1 == true) {
			  foreach ($cart as $item) {
				  $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
				  //get stock
				  $stock = Db::run()->first(Shop::mTable, array("subtract"), array('id' => $item->pid));
				  
				  $dataArray[] = array(
					  'user_id' => $usr->id,
					  'item_id' => $item->pid,
					  'txn_id' => $txn_id,
					  'tax' => Validator::sanitize($item->tax, "float"),
					  'amount' => Validator::sanitize($item->total, "float"),
					  'total' => Validator::sanitize($item->totalprice, "float"),
					  'variant' => $vars,
					  'pp' => "PayFast",
					  'ip' => Url::getIP(),
					  'currency' => strtoupper($mc_currency),
					  'status' => 1,
					  );
					  
				  $items[$k]['title'] = $item->title;
				  $items[$k]['price'] = $item->totalprice;
				  $items[$k]['variant'] = $vars;
				  
				  //update stock
				  if($stock->subtract) {
					  Db::run()->pdoQuery("
						  UPDATE `" . Shop::mTable . "` 
						  SET quantity = quantity - 1
						  WHERE id = '" . $item->pid . "'
					  ");
				  }
			  }
			  
			  Db::run()->insertBatch(Shop::xTable, $dataArray);

			  // shiping data
			  $xdata = array(
				'invoice_id' => substr(time(), 5),
				'transaction_id' => $txn_id,
				'user_id' => $usr->id,
				'user' => $usr->fname . ' ' . $usr->lname,
				'items' => json_encode($items),
				'total' => Validator::sanitize($amount, "float"),
				'shipping' => $shipping->total,
				'address' => $shipping->address,
				'name' => $shipping->name,
			  ); 
			  
			  Db::run()->insert(Shop::shTable, $xdata); 
			  
			  
			  /* == Notify User == */
              $mailer = Mailer::sendMail();
			  $core = App::Core();
			  $etpl = Db::run()->first(Content::eTable, array("body" . Lang::$lang, "subject" . Lang::$lang), array('typeid' => 'shopNotifyUser'));
			  
			  $tpl = App::View(FMODPATH . 'shop/snippets/'); 
			  $tpl->rows = $cart;
			  $tpl->tax = $tax;
			  $tpl->totals = $totals;
			  $tpl->shipping = $shipping;
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
				  Url::url('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang}, "shop"),
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

			  Db::run()->delete(Shop::qTable, array("user_id" => $sesid));
			  Db::run()->delete(Shop::qxTable, array("user_id" => $sesid));
          }

      } else {
          /* == Failed or Pending Transaction == */
      }
  }