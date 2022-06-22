<?php
  /**
   * Resend Notification
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!$this->data) : Message::invalid("ID" . Filter::$id); return; endif;
?>
<div class="yoyo small form content">
  <form method="post" id="modal_form" name="modal_form">
    <div class="content-center">
      <p><i class="huge circular icon positive email"></i></p>
      <p class="half-top-padding"> <?php echo str_replace("[NAME]", '<span class="yoyo bold text">' . $this->data->email  . '</span>', Lang::$word->M_INFO4);?> </p>
    </div>
  </form>
</div>