<?php
  /**
   * Coupons
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_coupons')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3><?php echo Lang::$word->META_T26;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->NAME;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->title;?>" name="title">
          </div>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_CODE;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->DC_CODE;?>" value="<?php echo $this->data->code;?>" name="code">
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_SUB3;?>
            <i class="icon asterisk"></i></label>
          <select name="membership_id[]" class="yoyo fluid dropdown" multiple>
            <option value="">-/-</option>
            <?php echo Utility::loopOptionsMultiple($this->mlist, "id", "title" . Lang::$lang, $this->data->membership_id);?>
          </select>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_DISC;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->DC_DISC;?>" value="<?php echo $this->data->discount;?>" name="discount">
            <select class="yoyo small fluid dropdown" name="type">
              <option value="p"<?php if($this->data->type == "p") echo ' selected="selected"';?>><?php echo Lang::$word->DC_TYPE_P;?></option>
              <option value="a"<?php if($this->data->type == "a") echo ' selected="selected"';?>><?php echo Lang::$word->DC_TYPE_A;?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED;?></label>
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
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/coupons");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processCoupon" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->DC_SUB2;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php case "new": ?>
<h3><?php echo Lang::$word->META_T27;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->NAME;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title">
          </div>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_CODE;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->DC_CODE;?>" name="code">
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_SUB3;?>
            <i class="icon asterisk"></i></label>
          <select name="membership_id[]" class="yoyo fluid dropdown" multiple>
            <option value="">-/-</option>
            <?php echo Utility::loopOptionsMultiple($this->mlist, "id", "title" . Lang::$lang);?>
          </select>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->DC_DISC;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->DC_DISC;?>" name="discount">
            <select class="yoyo fluid dropdown" name="type">
              <option value="p"><?php echo Lang::$word->DC_TYPE_P;?></option>
              <option value="a"><?php echo Lang::$word->DC_TYPE_A;?></option>
            </select>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="1" id="active_1" checked="checked">
            <label for="active_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="0" id="active_0">
            <label for="active_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/coupons");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processCoupon" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->DC_SUB1;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->DC_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->DC_SUB;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->DC_SUB1;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->DC_NONDISC;?></p>
</div>
<?php else:?>
<div class="row screen-block-3 tablet-block-2 mobile-block-1 phone-block-1 gutters align-center">
  <?php foreach ($this->data as $k => $row):?>
  <?php $k++;?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo segment">
      <div class="content-center<?php if($row->active == 0):?> dimmable dimmed<?php endif;?>" id="cpn_<?php echo $row->id;?>"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>"><img src="<?php echo ADMINVIEW;?>/images/coupon.svg" alt=""></a>
        <p><?php echo $row->title;?> [<?php echo $row->ctype;?>]</p>
        <?php if($row->active == 0):?>
        <div class="yoyo dimmer inverted transition visible active"></div>
        <?php endif;?>
      </div>
      <div class="yoyo divider"></div>
      <div class="row align-middle half-vertical-gutters no-gutters">
        <div class="columns">
          <a data-set='{"option":[{"trash": "trashCoupon","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"trash","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon negative button action">
            <i class="icon trash"></i>
          </a>
        </div>
        <div class="columns shrink">
          <div class="yoyo checkbox fitted toggle is_dimmable" data-set='{"option":[{"quickStatus": 1,"id":<?php echo $row->id;?>,"status":"coupon"}],"parent":"#cpn_<?php echo $row->id;?>"}' >
            <input name="active" type="checkbox" value="1" id="active_<?php echo $k;?>" <?php Validator::getChecked($row->active, 1); ?>>
            <label for="active_<?php echo $k;?>" ><?php echo Lang::$word->ACTIVE;?></label>
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