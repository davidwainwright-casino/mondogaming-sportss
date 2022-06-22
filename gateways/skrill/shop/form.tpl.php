<?php
  /**
   * Moneybookers Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
?>
<form action="https://www.skrill.com/app/payment.pl" method="post" id="mb_form" name="mb_form" class="content-center">
<input type="image" src="<?php echo SITEURL;?>/gateways/skrill/logo_large.png" style="width:200px" name="submit" class="yoyo basic primary button" title="Pay With Skrill" alt="" onclick="document.mb_form.submit();">
  <input type="hidden" name="pay_to_email" value="<?php echo $this->gateway->extra;?>">
  <input type="hidden" name="return_url" value="<?php echo Url::url('/' . $this->core->system_slugs->account[0]->{'slug' . Lang::$lang}, "shop");?>">
  <input type="hidden" name="cancel_url" value="<?php echo Url::url('/' . $this->core->system_slugs->account[0]->{'slug' . Lang::$lang}, "shop");?>">
  <input type="hidden" name="status_url" value="<?php echo SITEURL.'/gateways/' . $this->gateway->dir;?>/shop/ipn.php" />
  <input type="hidden" name="merchant_fields" value="session_id, item, custom" />
  <input type="hidden" name="item" value="<?php echo $this->core->company . ' - ' . Lang::$word->CHECKOUT;?>" />
  <input type="hidden" name="session_id" value="<?php echo md5(time())?>" />
  <input type="hidden" name="custom" value="<?php echo App::Auth()->uid . '_' . App::Auth()->sesid;?>" />
  <input type="hidden" name="amount" value="<?php echo Utility::numberParse(($this->shipping->total) + $this->tax * $this->cart->grand + $this->cart->grand);?>" />
  <input type="hidden" name="currency" value="<?php echo ($this->gateway->extra2) ? $this->gateway->extra2 : $this->core->currency;?>" />
  <!--<<input type="hidden" name="detail1_description" value="<?php //echo $this->row->{'title' . Lang::$lang};?>" />
  input type="hidden" name="detail1_text" value="<?php //echo $this->row->{'description' . Lang::$lang};?>" />-->
</form>