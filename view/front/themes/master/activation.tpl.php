<?php
  /**
   * Activation
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo-grid">
  <?php if(Validator::get('done')):?>
  <?php Message::msgOk(Lang::$word->M_INFO9 . '<a href="' . Url::url('/' . $this->core->system_slugs->login[0]->{'slug' . Lang::$lang}) . '">' . Lang::$word->M_INFO9_1 . '</a>');?>
  <?php else:?>
  <?php echo Message::msgError(Lang::$word->M_INFO10);?>
  <?php endif;?>
</div>