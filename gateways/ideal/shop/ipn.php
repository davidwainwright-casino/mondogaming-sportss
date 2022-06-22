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
	  Bootstrap::Autoloader(array(AMODPATH . 'shop/'));
	  
	  require_once (BASEPATH . 'gateways/ideal/initialize.php');
      $apikey = Db::run()->first(Core::gTable, array("extra"), array("name" => "ideal"));

      $mollie = new Mollie_API_Client;
      $mollie->setApiKey($apikey->extra);

      $o = Validator::sanitize($_GET['order_id'], "string");
      $cart = Shop::getCartContent();

	  $totals = Shop::getCartTotal();
	  $tax = Membership::calculateTax();
	  $shipping = Db::run()->first(Shop::qxTable, null, array("user_id" => App::Auth()->sesid));
	  
	  $amount = (($shipping->total) + $tax * $totals->grand + $totals->grand);
			  
      if ($cart) {
          $payment = $mollie->payments->get($totals->cart_id);
          if ($payment->isPaid() == true and Validator::compareNumbers($payment->amount, $amount, "=")) {
			  foreach ($cart as $item) {
				  $vars = ($item->variants ? Shop::formatVariantFromJson(json_decode($item->variants)) : 'NULL');
				  //get stock
				  $stock = Db::run()->first(Shop::mTable, array("subtract"), array('id' => $item->pid));
				  
				  $dataArray[] = array(
					  'user_id' => App::Auth()->uid,
					  'item_id' => $item->pid,
					  'txn_id' => $payment->metadata->order_id,
					  'tax' => Validator::sanitize($item->tax, "float"),
					  'amount' => Validator::sanitize($item->total, "float"),
					  'total' => Validator::sanitize($item->totalprice, "float"),
					  'variant' => $vars,
					  'pp' => "iDeal",
					  'ip' => Url::getIP(),
					  'currency' => "EUR",
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
				'transaction_id' => $payment->metadata->order_id,
				'user_id' => App::Auth()->uid,
				'user' => App::Auth()->fname . ' ' . App::Auth()->lname,
				'items' => json_encode($items),
				'total' => Validator::sanitize($amount, "float"),
				'shipping' => $shipping->total,
				'address' => $shipping->address,
				'name' => $shipping->name,
			  ); 
			  
			  Db::run()->insert(Shop::shTable, $xdata);  
			  
              $json['type'] = 'success';
              $json['title'] = Lang::$word->SUCCESS;
              $json['message'] = Lang::$word->STR_POK;
			  
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