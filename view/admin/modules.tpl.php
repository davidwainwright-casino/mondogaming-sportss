<?php
  /**
   * Modules
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_modules')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->META_T30;?></h3>
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
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->DESCRIPTION;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" value="<?php echo $this->data->{'info_' . $lang->abbr};?>" name="info_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->METAKEYS;?></label>
            <textarea class="small" placeholder="<?php echo Lang::$word->METAKEYS;?>" name="keywords_<?php echo $lang->abbr;?>"><?php echo $this->data->{'keywords_' . $lang->abbr};?></textarea>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->METADESC;?></label>
            <textarea class="small" placeholder="<?php echo Lang::$word->METADESC;?>" name="description_<?php echo $lang->abbr;?>"><?php echo $this->data->{'description_' . $lang->abbr};?></textarea>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processModule" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->MDL_SUB1;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<h3><?php echo Lang::$word->MDL_TITLE;?></h3>
<p><?php echo Lang::$word->MDL_SUB;?></p>
<div class="yoyo big space divider"></div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->MDL_NOMOD;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-3 gutters content-center">
  <?php foreach ($this->data as $row):?>
  <?php if(Auth::checkModAcl($row->modalias)):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo attached segment content-center">
      <img src="<?php echo $row->icon ? AMODULEURL . $row->icon : SITEURL . '/assets/images/basic_plugin.svg';?>" class="yoyo normal basic image" alt="">
      <div class="yoyo half space divider"></div>
      <p class="yoyo small bold text"><?php echo $row->{'title' . Lang::$lang};?></p>
      <div class="yoyo divider"></div>
      <div class="content-left">
        <div class="row no-all-gutters">
          <div class="columns">
            <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo icon positive circular button"><i class="icon pencil"></i></a>
            <a data-set='{"option":[{"delete":"deleteModule","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon negative circular button action">
              <i class="icon trash"></i>
            </a>
          </div>
          <div class="columns shrink">
            <a href="<?php echo Url::url(Router::$path, $row->modalias);?>" class="yoyo icon circular button"><i class="icon cogs"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
  <?php endforeach;?>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>