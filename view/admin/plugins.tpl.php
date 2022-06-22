<?php
  /**
   * Plugins
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_plugins')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->META_T28;?></h3>
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
        <textarea class="bodypost" placeholder="<?php echo Lang::$word->CONTENT;?>" name="body_<?php echo $lang->abbr;?>"><?php echo Url::out_url($this->data->{'body_' . $lang->abbr});?></textarea>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field ">
          <label><?php echo Lang::$word->PLG_CLASS;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->PLG_CLASS;?>" value="<?php echo $this->data->alt_class;?>" name="alt_class">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PLG_SHOWTITLE;?></label>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="show_title" type="radio" value="1" id="show_title_1" <?php Validator::getChecked($this->data->show_title, 1); ?>>
            <label for="show_title_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="show_title" type="radio" value="0" id="show_title_0" <?php Validator::getChecked($this->data->show_title, 0); ?>>
            <label for="show_title_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ACTIVE;?></label>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="active" type="radio" value="1" id="active_1" <?php Validator::getChecked($this->data->active, 1); ?>>
            <label for="active_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="active" type="radio" value="0" id="active_0" <?php Validator::getChecked($this->data->active, 0); ?>>
            <label for="active_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->PAG_JSCODE;?></label>
          <textarea placeholder="<?php echo Lang::$word->PAG_JSCODE;?>" name="jscode"><?php echo json_decode($this->data->jscode);?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processPlugin" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->PLG_SUB2;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php case "new": ?>
<!-- Start new -->
<h3 class="header"><?php echo Lang::$word->META_T29;?></h3>
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
            <label><?php echo Lang::$word->DESCRIPTION;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" name="info_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
        <textarea class="altpost" placeholder="<?php echo Lang::$word->CONTENT;?>" name="body_<?php echo $lang->abbr;?>"></textarea>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field ">
          <label><?php echo Lang::$word->PLG_CLASS;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->PLG_CLASS;?>" name="alt_class">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PLG_SHOWTITLE;?></label>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="show_title" type="radio" value="1" id="show_title_1">
            <label for="show_title_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="show_title" type="radio" value="0" id="show_title_0" checked="checked">
            <label for="show_title_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->ACTIVE;?></label>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="active" type="radio" value="1" id="active_1" checked="checked">
            <label for="active_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio toggle fitted inline">
            <input name="active" type="radio" value="0" id="active_0">
            <label for="active_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->PAG_JSCODE;?></label>
          <textarea placeholder="<?php echo Lang::$word->PAG_JSCODE;?>" name="jscode"></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processPlugin" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->PLG_SUB1;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<!-- Start default -->
<div class="row half-gutters align-middle">
  <div class="column shrink mobile-100 mobile-order-1">
    <h3><?php echo Lang::$word->PLG_TITLE;?></h3>
    <p><?php echo Lang::$word->PLG_SUB;?></p>
  </div>
  <div class="columns content-right mobile-50 mobile-content-left mobile-order-2">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->PLG_SUB1;?></a>
  </div>
</div>
<div class="half-top-padding"><?php echo Validator::alphaBits(Url::url(Router::$path), "letter", "yoyo small bold text horizontal link divided list align-center");?>
</div>
<div class="yoyo big space divider"></div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->PLG_NOPLG;?></p>
</div>
<?php else:?>
<div class="row phone-block-1 mobile-block-1 tablet-block-2 screen-block-3 gutters content-center">
  <?php foreach ($this->data as $row):?>
  <?php if(Auth::checkPlugAcl($row->plugalias)):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo attached segment content-center">
      <img src="<?php echo $row->icon ? APLUGINURL . $row->icon : SITEURL . '/assets/images/basic_plugin.svg';?>" class="yoyo normal basic image" alt="">
      <div class="yoyo half space divider"></div>
      <p class="yoyo small bold text"><?php echo $row->{'title' . Lang::$lang};?></p>
      <div class="yoyo divider"></div>
      <div class="content-left">
        <div class="row no-all-gutters">
          <div class="columns">
            <a href="<?php echo Url::url(Router::$path . "/edit", $row->id);?>" class="yoyo icon positive circular button"><i class="icon pencil"></i></a>
            <a data-set='{"option":[{"<?php echo $row->plugalias ? "delete" : "trash";?>": "<?php echo $row->plugalias ? "deletePlugin" : "trashPlugin";?>","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"<?php echo $row->plugalias ? "delete" : "trash";?>","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon negative circular button action">
            <i class="icon trash"></i>
            </a>
          </div>
          <?php if($row->hasconfig):?>
          <div class="columns shrink">
            <a href="<?php echo Url::url(Router::$path, $row->plugalias);?>" class="yoyo icon circular button"><i class="icon cogs"></i></a>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
  <?php endforeach;?>
</div>
<?php endif;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small thick text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>
<?php break;?>
<?php endswitch;?>