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
	  
  if(!Auth::checkPlugAcl('newsletter')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100">
    <h3><?php echo Lang::$word->_PLG_NSL_TITLE;?></h3>
  </div>
  <div class="columns shrink"> <a href="<?php echo APLUGINURL . 'newsletter/controller.php?exportEmails';?>" class="yoyo secondary button"><i class="icon wysiwyg table"></i><?php echo Lang::$word->EXPORT;?></a> </div>
</div>
<p class="yoyo bold text content-center"><?php echo str_replace("[TOTAL]", $this->data, Lang::$word->_PLG_NSL_INFO);?></p>