<?php
  /**
   * Donation
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkPlugAcl('donation')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "edit": ?>
<!-- Start edit -->
<h3><?php echo Lang::$word->_PLG_DP_SUB4;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_SUB1;?> <i class="icon asterisk"></i></label>
        <div class="yoyo big fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->_PLG_DP_SUB1;?>" value="<?php echo $this->data->title;?>" name="title">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_TARGET;?> <i class="icon asterisk"></i></label>
        <div class="yoyo fluid labeled input">
          <div class="yoyo small basic label"> <?php echo Utility::currencySymbol();?> </div>
          <input type="text" placeholder="<?php echo Lang::$word->_PLG_DP_TARGET;?>" value="<?php echo $this->data->target_amount;?>" name="target_amount">
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_SUB3;?> <i class="icon asterisk"></i></label>
        <select name="redirect_page" class="yoyo search fluid dropdown">
          <?php echo Utility::loopOptions($this->pagelist, "id", "title" . Lang::$lang, $this->data->redirect_page);?>
        </select>
      </div>
    </div>
  </div>
  <div class="content-center"> <a href="<?php echo Url::url("/admin/plugins", "donation");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="plugins_/donation" data-action="processDonate" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<h3><?php echo Lang::$word->_PLG_DP_SUB;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_SUB1;?> <i class="icon asterisk"></i></label>
        <div class="yoyo big fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->_PLG_DP_SUB1;?>" name="title">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_TARGET;?> <i class="icon asterisk"></i></label>
        <div class="yoyo fluid labeled input">
          <div class="yoyo small basic label"> <?php echo Utility::currencySymbol();?> </div>
          <input type="text" placeholder="<?php echo Lang::$word->_PLG_DP_TARGET;?>" name="target_amount">
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_SUB2;?> <i class="icon asterisk"></i></label>
        <select name="gateways[]" class="yoyo fluid multiple dropdown" multiple>
          <?php echo Utility::loopOptionsMultiple($this->gateways, "id", "name");?>
        </select>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_PLG_DP_SUB3;?> <i class="icon asterisk"></i></label>
        <select name="redirect_page" class="yoyo search fluid dropdown">
          <?php echo Utility::loopOptions($this->pagelist, "id", "title" . Lang::$lang);?>
        </select>
      </div>
    </div>
  </div>
  <div class="content-center"> <a href="<?php echo Url::url("/admin/plugins", "donation");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="plugins_/donation" data-action="processDonate" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<div class="row gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->_PLG_DP_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->_PLG_DP_INFO;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100"> <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->_PLG_DP_NEW;?></a> </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold text vertical-padding"><?php echo Lang::$word->_PLG_DP_NODON;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-2 gutters">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo segment"> <a data-content="<?php echo Lang::$word->EXPORT;?>" href="<?php echo APLUGINURL;?>donation/controller.php?action=exportDonations&amp;id=<?php echo $row->id;?>" class="yoyo top right icon simple attached button"><i class="icon wysiwyg table"></i></a>
      <h4><?php echo $row->title;?></h4>
      <p><?php echo Lang::$word->_PLG_DP_TARGET;?>: <span class="yoyo negative text"><?php echo Utility::formatMoney($row->total);?></span> / <span class="yoyo positive text"><?php echo Utility::formatMoney($row->target_amount);?></span></p>
      <div class="yoyo divider"></div>
      <div class="content-center"> <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo icon positive button"><i class="icon pencil"></i></a> <a data-set='{"option":[{"delete": "deleteDonation","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>", "url":"plugins_/donation"}' class="yoyo icon negative button action"> <i class="icon trash"></i> </a> </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>