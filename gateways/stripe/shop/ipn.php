<?php
  /**
   * Stripe IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once ("../../../init.php");
  
  Bootstrap::Autoloader(array(AMODPATH . 'shop/'));

  if (!App::Auth()->is_User())
      exit;

  ini_set('log_errors', true);
  ini_set('error_log', dirname(__file__) . '/ipn_errors.log');

  if (isset($_POST['processStripePayment'])) {
	  $rules = array(
		  'stripeToken' => array('required|string', "Invalid Token"),
		  );
			  
	  $validate = Validator::instance();
	  $safe = $validate->doValidate($_POST, $rules);

      if (!$cart = Shop::getCartContent()) {
          Message::$msgs['cart'] = Lang::$word->STR_ERR0;
      }
	  
      if (empty(Message::$msgs)) {
          require_once (BASEPATH . 'gateways/stripe/init.php');

          $key = Db::run()->first(Core::gTable, array("extra", "extra2"), array("name" => "stripe"));

          \Stripe\Stripe::setApiKey($key->extra);
          try {
              //Charge client
              $totals = Shop::getCartTotal();
			  $tax = Membership::calculateTax();
			  $shipping = Db::run()->first(Shop::qxTable, null, array("user_id" => App::Auth()->sesid));
			  
			  $amount = (($shipping->total) + $tax * $totals->grand + $totals->grand);
			  $items = array();
			  
              $charge = \Stripe\Charge::create(array(
                  "amount" => round($amount * 100, 0), // amount in cents, again
                  "currency" => $key->extra2,
				  'source' => $safe->stripeToken,
                  "description" => App::Core()->company . ' - ' . Lang::$word->CHECKOUT,
                  ));

			  foreach ($cart as $k => $item) {
				  $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
				  //get stock
				  $stock = Db::run()->first(Shop::mTable, array("subtract"), array('id' => $item->pid));
				  
				  $dataArray[] = array(
					  'user_id' => App::Auth()->uid,
					  'item_id' => $item->pid,
					  'txn_id' => $charge['balance_transaction'],
					  'tax' => Validator::sanitize($item->tax, "float"),
					  'amount' => Validator::sanitize($item->total, "float"),
					  'total' => Validator::sanitize($item->totalprice, "float"),
					  'variant' => $vars,
					  'pp' => "Stripe",
					  'ip' => Url::getIP(),
					  'currency' => strtoupper($charge['currency']),
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
				'transaction_id' => $charge['balance_transaction'],
				'user_id' => App::Auth()->uid,
				'user' => App::Auth()->fname . ' ' . App::Auth()->lname,
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
				  App::Auth()->name,
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
					->setTo(array(App::Auth()->email => App::Auth()->name))
					->setFrom(array($core->site_email => $core->company))
					->setBody($body, 'text/html'
					);
			  $mailer->send($msg);

			  Db::run()->delete(Shop::qTable, array("user_id" => App::Auth()->sesid));
			  Db::run()->delete(Shop::qxTable, array("user_id" => App::Auth()->sesid));

              $jn['type'] = 'success';
			  $jn['title'] = Lang::$word->SUCCESS;
              $jn['message'] = Lang::$word->STR_POK;
              print json_encode($jn);
          }
          catch (\Stripe\Error\Card $e) {
              $body = $e->getJsonBody();
              $err = $body['error'];
              $json['type'] = 'error';
              Message::$msgs['msg'] = 'Message is: ' . $err['message'] . "\n";
              Message::msgSingleStatus();
          }
      } else {
          Message::msgSingleStatus();
      }
  }