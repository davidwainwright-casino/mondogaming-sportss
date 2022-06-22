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
	  Bootstrap::Autoloader(array(AMODPATH . 'shop/'));
	  
	  require_once (BASEPATH . 'gateways/paypal/paypal.class.php');

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
	  $mc_currency = Validator::sanitize($_POST['mc_currency'], "string", 4);
      list($user_id, $sesid) = explode("_", $_POST['item_number']);
      $mc_gross = $_POST['mc_gross'];
	  $sesid = Validator::sanitize($sesid);
      $txn_id = Validator::sanitize($_POST['txn_id']);

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
			  $usr = Db::run()->first(Users::mTable, null, array("id" => intval($user_id)));
			  $cart = Shop::getCartContent($sesid);
			  $totals = Shop::getCartTotal($sesid);
			  $tax = Membership::calculateTax(intval($user_id));
			  $shipping = Db::run()->first(Shop::qxTable, null, array("user_id" => $sesid));
			  
			  $amount = (($shipping->total) + $tax * $totals->grand + $totals->grand);
			  $items = array();
			  $v1 = Validator::compareNumbers($mc_gross, $amount, "=");
			  
			  if ($cart and $v1 and $receiver_email == $pp->extra) {
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
						  'pp' => "PayPal",
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
              /* == Failed Transaction= = */
          }
      }
  }