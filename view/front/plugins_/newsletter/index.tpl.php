<?php
  /**
   * Newsletter
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'newsletter/'));
?>
<?php if(!App::Auth()->is_User()):?>
<div class="yoyo segment form">
  <form id="wojo_form_newsletter" name="wojo_form_newsletter" method="post">
    <div class="yoyo block fields">
      <div class="field">
        <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" name="email">
      </div>
    </div>
    <div class="field">
      <button type="button" data-url="plugins_/newsletter" data-hide="true" data-action="processNewsletter" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SUBMIT;?></button>
    </div>
  </form>
</div>
<?php endif;?>