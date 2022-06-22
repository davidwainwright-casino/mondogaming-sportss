<?php
  /**
   * Donate
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'donation/'));
?>
<!-- Donate -->
<?php if($conf = Utility::findInArray($data['all'], "id", $data['id'])):?>
<div class="yoyo basic plugin poll segment<?php echo ($conf[0]->alt_class) ? ' ' . $conf[0]->alt_class : null;?>">
  <?php if($conf[0]->show_title):?>
  <h3 class="content-center"><?php echo $conf[0]->title;?></h3>
  <?php endif;?>
  <?php if($conf[0]->body):?>
  <?php echo Url::out_url($conf[0]->body);?>
  <?php endif;?>
  <?php if($row = App::Donate()->Render($data['plugin_id'])):?>
  <?php $percent = Utility::doPercent($row->total, $row->target_amount);?>
  <div data-percent="<?php echo $percent;?>" class="yoyo indicating progress active">
    <div class="bar" style="width: <?php echo $percent;?>%;"></div>
    <div class="label"><?php echo $percent;?>%</div>
  </div>
  <div class="yoyo fluid buttons">
    <div class="yoyo normal button"><?php echo Utility::formatMoney($row->total);?></div>
    <div class="or" data-text="<?php echo Lang::$word->OF;?>"></div>
    <div class="yoyo normal primary button"><?php echo Utility::formatMoney($row->target_amount);?></div>
  </div>
  <div class="yoyo divider"></div>
  <div class="content-center">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="pp_form_<?php echo $row->id;?>" name="pp_form">
      <input type="hidden" name="cmd" value="_donations" />
      <input type="hidden" name="business" value="<?php echo $row->pp_email;?>" />
      <input type="hidden" name="item_name" value="Donations For <?php echo App::Core()->company;?>" />
      <input type="hidden" name="item_number" value="<?php echo $row->id;?>" />
      <input type="hidden" name="return" value="<?php echo Url::url('/' . App::Core()->pageslug, $row->page);?>" />
      <input type="hidden" name="rm" value="2" />
      <input type="hidden" name="notify_url" value="<?php echo SITEURL;?>/gateways/paypal/donate/ipn.php" />
      <input type="hidden" name="cancel_return" value="<?php echo SITEURL;?>" />
      <input type="hidden" name="no_note" value="1" />
      <input type="hidden" name="currency_code" value="<?php echo App::Core()->currency;?>" />
      <button class="yoyo fluid positive button" type="submit" name="pp_form"><?php echo LANG::$word->_PLG_DP_DONATE;?></button>
    </form>
  </div>
  <?php endif;?>
</div>
<?php endif;?>