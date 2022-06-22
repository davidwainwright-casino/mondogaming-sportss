<?php
  /**
   * Ideal Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');

  include "initialize.php";
  $mollie = new Mollie_API_Client;
  $mollie->setApiKey($this->gateway->extra);
  
  $order_id = "MEM_" . md5(time());
  $payment = $mollie->payments->create(array(
      "amount" => $this->cart->totalprice,
      "method" => Mollie_API_Object_Method::IDEAL,
      "description" => $this->row->{'title' . Lang::$lang},
      "redirectUrl" => Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang} . '/validate', "?ideal=1&order_id=" . $order_id),
      "metadata" => array("order_id" => $order_id, "user_id" => App::Auth()->uid),
	  ));
	  
  Db::run()->update(Membership::cTable, array("cart_id" => $payment->id, "order_id" => $order_id), array("uid" => App::Auth()->uid));
?>
<a class="yoyo basic primary button" href="<?php echo $payment->getPaymentUrl();?>" title="Pay With Mollie"><img src="<?php echo SITEURL;?>/gateways/ideal/logo_large.png" style="width:200px"></a>
