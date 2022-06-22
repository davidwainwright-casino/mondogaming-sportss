<?php
  /**
   * Paypal Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($this->gateway->live) ? 'www.paypal.com' : 'www.sandbox.paypal.com';?>
<form action="https://<?php echo $url;?>/cgi-bin/webscr" method="post" id="pp_form" name="pp_form" class="content-center">
<input type="image" src="<?php echo SITEURL;?>/gateways/paypal/logo_large.png" style="width:150px" name="submit" class="yoyo basic primary button" title="Pay With Paypal" alt="" onclick="document.pp_form.submit();">
  <?php if($this->row->recurring == 1):?>
  <input type="hidden" name="cmd" value="_xclick-subscriptions">
  <input type="hidden" name="a3" value="<?php echo $this->cart->totalprice;?>">
  <input type="hidden" name="p3" value="<?php echo $this->row->days;?>">
  <input type="hidden" name="t3" value="<?php echo $this->row->period;?>">
  <input type="hidden" name="src" value="1" />
  <input type="hidden" name="sr1" value="1" />
  <?php else:?>
  <input type="hidden" name="cmd" value="_xclick" />
  <input type="hidden" name="amount" value="<?php echo $this->cart->totalprice;?>">
  <?php endif;?>
  <input type="hidden" name="business" value="<?php echo $this->gateway->extra;?>">
  <input type="hidden" name="item_name" value="<?php echo $this->row->{'title' . Lang::$lang};?>">
  <input type="hidden" name="item_number" value="<?php echo $this->row->id . '_' . App::Auth()->uid;?>">
  <input type="hidden" name="return" value="<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, "history");?>">
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="notify_url" value="<?php echo SITEURL . '/gateways/'. $this->gateway->dir;?>/ipn.php">
  <input type="hidden" name="cancel_return" value="<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, "history");?>">
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="currency_code" value="<?php echo ($this->gateway->extra2) ? $this->gateway->extra2 : App::Core()->currency;?>">
</form>