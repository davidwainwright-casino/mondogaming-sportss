<?php
  /**
   * Upcoming Event
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkPlugAcl('upevent')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<h3><?php echo Lang::$word->_PLG_UE_TITLE1;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="row align-center">
    <div class="columns screen-50 tablet-100 mobile-100 phone-100">
      <div class="yoyo form segment">
        <div class="yoyo block fields">
          <div class="field">
            <label><?php echo Lang::$word->_PLG_UE_SELECT;?></label>
            <select name="event_id[]" class="yoyo fluid dropdown" multiple>
              <?php if($this->events):?>
              <?php echo Utility::loopOptionsMultiple($this->events, "id", "title" . Lang::$lang, $this->data->event_id);?>
              <?php else:?>
              <option value=""><?php echo Lang::$word->_PLG_UE_NOEVENT;?></option>
              <?php endif;?>
            </select>
          </div>
        </div>
        <div class="field">
          <div class="content-right">
            <a href="<?php echo Url::url("/admin/plugins");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
            <button type="button" data-url="plugins_/upevent" data-action="processConfig" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>