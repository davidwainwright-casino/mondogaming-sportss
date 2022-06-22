<?php
  /**
   * PayPal Donations IPN
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
      require_once (BASEPATH . "gateways/paypal/paypal.class.php");

      $listener = new IpnListener();
      $listener->use_live = true;
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
      $mc_gross = $_POST['mc_gross'];
	  $item = $_POST['item_number'];

      if ($ppver) {
          if ($payment_status == 'Completed') {
			  Bootstrap::Autoloader(array(APLUGPATH . 'donation/'));
			  $data = array(
				  'parent_id' => Validator::sanitize($item, "int"),
				  'name' => Validator::sanitize($_POST['first_name'] . ' ' . $_POST['last_name']),
				  'email' => isset($_POST['payer_email']) ? Validator::sanitize($_POST['payer_email'], "email") : "NULL",
				  'amount' => Validator::sanitize($mc_gross, "float"),
				  'pp' => "PayPal",
				  );
			  
			  Db::run()->insert(Donate::dTable, $data);
          } else {
              /* == Failed Transaction= = */
          }
      }
  }