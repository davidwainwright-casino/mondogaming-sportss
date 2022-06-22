<?php
  /**
   * Custom Fields
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_fields')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->META_T16;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <ul class="yoyo basic wide tabs">
      <?php foreach($this->langlist as $lang):?>
      <li<?php echo ($lang->abbr == $this->core->lang) ? ' class="active"' : null;?>><a style="background:<?php echo $lang->color;?>;color:#fff" data-tab="#lang_<?php echo $lang->abbr;?>"><span class="flag icon <?php echo $lang->abbr;?>"></span><?php echo $lang->name;?></a>
      </li>
      <?php endforeach;?>
    </ul>
    <div class="content">
      <?php foreach($this->langlist as $lang):?>
      <div id="lang_<?php echo $lang->abbr;?>" class="yoyo tab item">
        <div class="yoyo fields">
          <div class="field five wide">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo fluid huge input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->CF_TIP;?></label>
            <div class="yoyo fluid huge input">
              <input type="text" placeholder="<?php echo Lang::$word->CF_TIP;?>" value="<?php echo $this->data->{'tooltip_' . $lang->abbr};?>" name="tooltip_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->SECTION;?></label>
          <select name="section" class="yoyo fluid dropdown">
            <option value="profile"><?php echo Lang::$word->M_SUB16;?></option>
            <?php echo Utility::loopOptions($this->modlist, "modalias", "title", $this->data->section);?>
          </select>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
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
        <div class="field five wide">
          <label><?php echo Lang::$word->CF_REQUIRED;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="required" type="radio" value="1" id="required_1" <?php Validator::getChecked($this->data->required, 1); ?>>
            <label for="required_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="required" type="radio" value="0" id="required_0" <?php Validator::getChecked($this->data->required, 0); ?>>
            <label for="required_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/fields");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processField" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->CF_UPDATE;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php case "new": ?>
<h3 class="header"><?php echo Lang::$word->META_T17;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <ul class="yoyo basic wide tabs">
      <?php foreach($this->langlist as $lang):?>
      <li<?php echo ($lang->abbr == $this->core->lang) ? ' class="active"' : null;?>><a style="background:<?php echo $lang->color;?>;color:#fff" data-tab="#lang_<?php echo $lang->abbr;?>"><span class="flag icon <?php echo $lang->abbr;?>"></span><?php echo $lang->name;?></a>
      </li>
      <?php endforeach;?>
    </ul>
    <div class="content">
      <?php foreach($this->langlist as $lang):?>
      <div id="lang_<?php echo $lang->abbr;?>" class="yoyo tab item">
        <div class="yoyo fields">
          <div class="field five wide">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->CF_TIP;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->CF_TIP;?>" name="tooltip_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->SECTION;?></label>
          <select name="section" class="yoyo fluid dropdown">
            <option value="profile"><?php echo Lang::$word->M_SUB16;?></option>
            <?php echo Utility::loopOptions($this->modlist, "modalias", "title");?>
          </select>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
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
        <div class="field five wide">
          <label><?php echo Lang::$word->CF_REQUIRED;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="required" type="radio" value="1" id="required_1">
            <label for="required_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="required" type="radio" value="0" id="required_0" checked="checked">
            <label for="required_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/fields");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processField" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->CF_ADD;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->CF_TITLE;?></h3>
    <p class="yoyo small text"><?php echo Lang::$word->CF_INFO;?></p>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->CF_ADD;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->CF_NOFIELDS;?></p>
</div>
<?php else:?>
<div class="yoyo big space divider"></div>
<div class="row screen-block-3 tablet-block-2 mobile-block-1 phone-block-1 gutters align-center" id="sortable">
  <?php foreach ($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>" data-id="<?php echo $row->id;?>">
    <div class="yoyo boxed attached segment">
      <div class="yoyo simple small icon  top left attached button handle"><i class="icon reorder link"></i></div>
      <h5 class="content-center"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="inverted"><?php echo $row->{'title' . Lang::$lang};?></a>
      </h5>
      <p class="yoyo small text content-center">[<?php echo $row->section;?>]</p>
      <div class="yoyo small icon bottom simple right attached button">
        <a data-set='{"option":[{"delete": "deleteField","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>"}' class="action">
          <i class="icon negative trash"></i>
        </a>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
    $("#sortable").sortables({
        ghostClass: "ghost",
        handle: ".handle",
        animation: 600,
        onUpdate: function(e) {
            var order = this.toArray();
            $.ajax({
                type: 'post',
                url: "<?php echo ADMINVIEW . '/helper.php';?>",
                dataType: 'json',
                data: {
                    sortFields: 1,
                    sorting: order
                }
            });
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>