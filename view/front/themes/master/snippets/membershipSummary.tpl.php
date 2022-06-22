<?php
  /**
   * Payment Summary
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<table class="yoyo basic small unstackable table">
  <thead>
    <tr>
      <th colspan="2"><?php echo Lang::$word->M_SUB26;?></th>
    </tr>
  </thead>
  <tr>
    <td><strong><?php echo Lang::$word->MEM_NAME;?></strong></td>
    <td><?php echo $this->row->{'title' . Lang::$lang};?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->MEM_PRICE;?></strong></td>
    <td><?php echo Utility::formatMoney($this->cart->total);?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->DC_SUB4;?> </strong></td>
    <td class="disc">0.00</td>
  </tr>
  <?php if (App::Core()->enable_tax):?>
  <tr>
    <td><strong><?php echo Lang::$word->TRX_TAX;?></strong></td>
    <td class="totaltax"><?php echo Utility::formatMoney($this->cart->total * $this->cart->tax);?></td>
  </tr>
  <?php endif;?>
  <tr>
    <td><strong><?php echo Lang::$word->TRX_TOTAMT;?></strong></td>
    <td class="totalamt"><?php echo Utility::formatMoney($this->cart->tax * $this->cart->total + $this->cart->total);?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->MEM_DAYS;?> </strong></td>
    <td><?php echo $this->row->days;?> <?php echo Date::getPeriodReadable($this->row->period);?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->MEM_REC1;?></strong></td>
    <td><?php echo ($this->row->recurring) ? Lang::$word->YES : Lang::$word->NO;?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->MEM_VALID;?></strong></td>
    <td><?php echo Membership::calculateDays($this->row->id);?></td>
  </tr>
  <tr>
    <td><strong><?php echo Lang::$word->DESCRIPTION;?></strong></td>
    <td><?php echo $this->row->{'description' . Lang::$lang};?></td>
  </tr>
  <?php if(!$this->row->recurring):?>
  <tr>
    <td><strong><?php echo Lang::$word->DC_CODE;?></strong></td>
    <td><div class="yoyo small icon right input">
        <input type="text" placeholder="<?php echo Lang::$word->DC_CODE_I;?>" name="coupon">
        <i data-id="<?php echo $this->row->id;?>" id="cinput" class="find icon link"></i> </div></td>
  </tr>
  <?php endif;?>
  <tr id="gatedata">
    <td><strong><?php echo Lang::$word->M_SUB25;?></strong></td>
    <td><div class="row phone-block-2 mobile-block-3 tablet-block-4 screen-block-5 half-gutters content-center">
        <?php foreach ($this->gateways as $grows):?>
        <?php if ($this->row->recurring and !$grows->is_recurring):?>
        <?php continue;?>
        <?php else:?>
        <div class="column"> <a class="yoyo basic icon fluid button sGateway" data-id="<?php echo $grows->id;?>"><?php echo $grows->displayname;?></a></div>
        <?php endif;?>
        <?php endforeach;?>
      </div></td>
  </tr>
  <tr>
    <td colspan="2" id="gdata"></td>
  </tr>
</table>