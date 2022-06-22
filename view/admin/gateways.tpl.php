<?php
  /**
   * Gateways
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if (!Auth::checkAcl("owner")) : print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->GW_TITLE1;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->GW_NAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->GW_NAME;?>" value="<?php echo $this->data->displayname;?>" name="displayname">
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo $this->data->extra_txt;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo $this->data->extra_txt;?>" value="<?php echo $this->data->extra;?>" name="extra">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo $this->data->extra_txt2;?></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo $this->data->extra_txt2;?>" value="<?php echo $this->data->extra2;?>" name="extra2">
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo $this->data->extra_txt3;?>
        </label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo $this->data->extra_txt3;?>" value="<?php echo $this->data->extra3;?>" name="extra3">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <div class="fitted field">
          <label><?php echo Lang::$word->GW_LIVE;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="live" type="radio" value="1" id="live_1" <?php Validator::getChecked($this->data->live, 1); ?>>
            <label for="live_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="live" type="radio" value="0" id="live_0" <?php Validator::getChecked($this->data->live, 0); ?>>
            <label for="live_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <div class="field five wide">
        <div class="fitted field">
          <label><?php echo Lang::$word->ACTIVE;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="1" id="active_1" <?php Validator::getChecked($this->data->active, 1); ?>>
            <label for="active_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="0" id="active_0" <?php Validator::getChecked($this->data->active, 0); ?>>
            <label for="active_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->GW_IPNURL;?></label>
        <?php echo SITEURL.'/gateways/' . $this->data->dir . '/ipn.php';?>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/gateways");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processGateway" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->GW_UPDATE;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php default: ?>
<h3><?php echo Lang::$word->GW_TITLE;?></h3>
<p class="yoyo small text"><?php echo Lang::$word->GW_SUB;?></p>
<?php if($this->data):?>
<div class="row screen-block-3 tablet-block-3 mobile-block-2 mobile-block-1 phone-block-1 gutters align-center">
  <?php foreach ($this->data as $row):?>
  <div class="column">
    <div class="yoyo attached segment">
      <div id="item_<?php echo $row->id;?>"<?php if($row->active == 0):?> class="dimmable dimmed"<?php endif;?>><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><img src="<?php echo SITEURL;?>/gateways/<?php echo $row->dir;?>/logo_large.png" alt=""></a>
        <?php if($row->active == 0):?>
        <div class="yoyo dimmer inverted transition visible active"></div>
        <?php endif;?>
      </div>
      <div class="yoyo divider"></div>
      <div class="basic footer">
        <div class="row align-middle half-vertical-gutters no-gutters">
          <div class="columns"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><?php echo $row->displayname;?></a>
          </div>
          <div class="columns shrink">
            <div class="yoyo fitted toggle checkbox is_dimmable" data-set='{"option":[{"quickStatus": 1,"id":<?php echo $row->id;?>,"status":"gateway"}],"parent":"#item_<?php echo $row->id;?>"}'>
              <input name="active" type="checkbox" value="1" <?php Validator::getChecked($row->active, 1); ?> id="gateway_<?php echo $row->id;?>">
              <label for="gateway_<?php echo $row->id;?>"><?php echo Lang::$word->ACTIVE;?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>