<?php
  /**
   * My Password
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<!-- Start password -->
<div class="row align-center">
  <div class="columns screen-70 tablet-100 mobile-100 phone-100">
    <form method="post" id="yoyo_form" name="yoyo_form">
      <div class="yoyo segment form">
        <div class="yoyo fields align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->NEWPASS;?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" name="password">
            </div>
          </div>
        </div>
        <div class="yoyo fields align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->CONPASS;?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" name="password2">
            </div>
          </div>
        </div>
        <div class="content-right">
          <a href="<?php echo Url::url("/admin/myaccount");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
          <button type="button" data-action="updatePassword" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->M_PASSUPDATE;?></button>
        </div>
      </div>
    </form>
  </div>
</div>