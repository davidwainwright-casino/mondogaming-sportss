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
<input type="image" src="<?php echo SITEURL;?>/gateways/skrill/logo_large.png" style="width:150px" name="submit" class="yoyo basic primary button" title="Pay With Skrill" alt="" onclick="document.mb_form.submit();">
  <input type="hidden" name="pay_to_email" value="<?php echo $this->gateway->extra;?>">
  <input type="hidden" name="return_url" value="<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, "history");?>">
  <input type="hidden" name="cancel_url" value="<?php echo Url::url('/' . App::Core()->system_slugs->account[0]->{'slug' . Lang::$lang}, "history");?>">
  <input type="hidden" name="status_url" value="<?php echo SITEURL.'/gateways/' . $this->gateway->dir;?>/ipn.php" />
  <input type="hidden" name="merchant_fields" value="session_id, item, custom" />
  <input type="hidden" name="item" value="<?php echo $this->row->title;?>" />
  <input type="hidden" name="session_id" value="<?php echo md5(time())?>" />
  <input type="hidden" name="custom" value="<?php echo $this->row->id . '_' . App::Auth()->uid;?>" />
  <?php if($this->row->recurring == 1):?>
  <input type="hidden" name="rec_amount" value="<?php echo $this->cart->totalprice;?>" />
  <input type="hidden" name="rec_period" value="<?php echo Membership::calculateDays($this->row->id);?>" />
  <input type="hidden" name="rec_cycle" value="day" />
  <?php else: ?>
  <input type="hidden" name="amount" value="<?php echo $this->cart->totalprice;?>" />
  <?php endif; ?>
  <input type="hidden" name="currency" value="<?php echo ($this->gateway->extra2) ? $this->gateway->extra2 : App::Core()->currency;?>" />
  <input type="hidden" name="detail1_description" value="<?php echo $this->row->{'title' . Lang::$lang};?>" />
  <input type="hidden" name="detail1_text" value="<?php echo $this->row->{'description' . Lang::$lang};?>" />
</form>