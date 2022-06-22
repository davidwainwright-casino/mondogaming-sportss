<?php
  /**
   * Twitts
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  if(!Auth::checkPlugAcl('twitts')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<h3>
  <?php echo Lang::$word->_PLG_TW_TITLE;?>
</h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_KEY;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_TW_KEY;?>" value="<?php echo App::Twitts()->consumer_key;?>" name="consumer_key">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_SECRET;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_TW_SECRET;?>" value="<?php echo App::Twitts()->consumer_secret;?>" name="consumer_secret">
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_TOKEN;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_TW_TOKEN;?>" value="<?php echo App::Twitts()->access_token;?>" name="access_token">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_TSECRET;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_TW_TSECRET;?>" value="<?php echo App::Twitts()->access_secret;?>" name="access_secret">
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_USER;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_PLG_TW_USER;?>" value="<?php echo App::Twitts()->username;?>" name="username">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_SHOW_IMG;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_image" type="radio" value="1" id="show_image_1" <?php Validator::getChecked(App::Twitts()->show_image, 1); ?>>
          <label for="show_image_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_image" type="radio" value="0" id="show_image_0" <?php Validator::getChecked(App::Twitts()->show_image, 0); ?>>
          <label for="show_image_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_COUNT;?></label>
        <input data-ranger='{"step":1,"from":1, "to":20, "format":"item", "tip": false, "range":false}' type="hidden" name="counter" value="<?php echo App::Twitts()->counter;?>" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_TRANS;?></label>
        <input data-ranger='{"step":100,"from":100, "to":1200, "format":"ms", "tip": false, "range":false}' type="hidden" name="speed" value="<?php echo App::Twitts()->speed;?>" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_TW_TRANS_T;?></label>
        <input data-ranger='{"step":1000,"from":1000, "to":15000, "format":"ms", "tip": false, "range":false}' type="hidden" name="timeout" value="<?php echo App::Twitts()->timeout;?>" class="rangers">
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="plugins_/twitts" data-action="processConfig" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
  </div>
</form>