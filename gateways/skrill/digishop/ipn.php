<?php
  /**
   * Skrill IPN
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once ("../../../init.php");
  Bootstrap::Autoloader(array(AMODPATH . 'digishop/'));
  
  /* only for debuggin purpose. Create logfile.txt and chmot to 0777
   ob_start();
   echo '<pre>';
   print_r($_POST);
   echo '</pre>';
   $logInfo = ob_get_contents();
   ob_end_clean();
   
   $file = fopen('logfile.txt', 'a');
   fwrite($file, $logInfo);
   fclose($file);
   */
  
  /* Check for mandatory fields */
  $r_fields = array(
		'status', 
		'md5sig', 
		'merchant_id', 
		'pay_to_email', 
		'mb_amount', 
		'mb_transaction_id', 
		'currency', 
		'amount', 
		'transaction_id', 
		'pay_from_email', 
		'mb_currency'
  );
  $skrill = Db::run()->first(Core::gTable, array("extra3"), array("name" => "skrill"));
  
  foreach ($r_fields as $f)
      if (!isset($_POST[$f]))
          die();
  
  /* Check for MD5 signature */
  $md5 = strtoupper(md5($_POST['merchant_id'] . $_POST['transaction_id'] . strtoupper(md5($skrill->extra3)) . $_POST['mb_amount'] . $_POST['mb_currency'] . $_POST['status']));
  if ($md5 != $_POST['md5sig'])
      die();
  
  if (intval($_POST['status']) == 2) {
      $mb_currency = Validator::sanitize($_POST['mb_currency']);
	  $mc_gross = $_POST['amount'];
	  $txn_id = Validator::sanitize($_POST['mb_transaction_id']);
	  list($user_id, $sesid) = explode("_", $_POST['custom']);

	  $cart = Digishop::getCart($sesid);
	  $usr = Db::run()->first(Users::mTable, null, array("id" => intval($user_id)));
	  $totals = Digishop::getCartTotal($sesid);
	  $tax = Membership::calculateTax(intval($user_id));
	  
	  $amount = ($tax * $totals->grand + $totals->grand); 
	  $v1 = Validator::compareNumbers($mc_gross, $amount, "=");
	  
	  if ($cart and $v1) {
		  
		  foreach ($cart as $item) {
			  $dataArray[] = array(
				  'user_id' => $usr->id,
				  'item_id' => $item->pid,
				  'txn_id' => $charge['balance_transaction'],
				  'tax' => Validator::sanitize($totals->sub * $tax, "float"),
				  'amount' => Validator::sanitize($totals->grand, "float"),
				  'total' => Validator::sanitize($amount, "float"),
				  'token' => Utility::randomString(16),
				  'pp' => "Skrill",
				  'ip' => Url::getIP(),
				  'currency' => strtoupper($mb_currency),
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