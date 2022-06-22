<?php
  /**
   * Menus
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_menus')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->META_T13;?></h3>
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
            <label><?php echo Lang::$word->MEN_NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->MEN_NAME;?>" value="<?php echo $this->data->{'name_' . $lang->abbr};?>" name="name_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->MEN_CAP;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->MEN_CAP;?>" value="<?php echo $this->data->{'caption_' . $lang->abbr};?>" name="caption_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEN_CTYPE;?></label>
          <select name="content_type" id="contenttype" class="yoyo fluid dropdown">
            <option value=""><?php echo Lang::$word->MEN_SUB1;?></option>
            <?php echo Utility::loopOptionsSimpleAlt($this->contenttype, $this->data->content_type);?>
          </select>
          <input type="hidden" name="cols" value="1">
        </div>
        <div class="field" id="contentid" style="display:<?php echo ($this->data->content_type != "web") ? 'block' : 'none';?>">
          <label><?php echo Lang::$word->MEN_SUB2;?></label>
          <select name="<?php echo ($this->data->content_type == "page" ? "page_id" : ($this->data->content_type == "module" ? "mod_id" : "web_id"));?>" id="page_id" class="yoyo fluid dropdown">
            <?php if($this->data->content_type == "page"):?>
            <?php echo Utility::loopOptions($this->pagelist, "id", "title" . Lang::$lang, $this->data->page_id);?>
            <?php endif;?>
            <?php if($this->data->content_type == "module"):?>
            <?php echo Utility::loopOptions($this->modulelist, "id", "title" . Lang::$lang, $this->data->mod_id);?>
            <?php endif;?>
          </select>
        </div>
      </div>
      <div id="webid" style="display:<?php echo ($this->data->content_type == "web") ? 'block' : 'none';?>">
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->MEN_SUB2;?></label>
            <input type="text" name="web" placeholder="<?php echo Lang::$word->MEN_TARGET_T;?>" value="<?php echo $this->data->link;?>" >
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MEN_TARGET_L;?></label>
            <select name="target" class="yoyo fluid dropdown">
              <option value=""><?php echo Lang::$word->MEN_TARGET;?></option>
              <option value="_blank" <?php Validator::getSelected($this->data->target, "_blank");?>><?php echo Lang::$word->MEN_TARGET_B;?></option>
              <option value="_self" <?php Validator::getSelected($this->data->target, "_self");?>><?php echo Lang::$word->MEN_TARGET_S;?></option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEN_ICONS;?></label>
          <div class="scrollbox_right" id="mIcons" style="height:500px;">
            <?php include(ADMINBASE . "/snippets/icons.tpl.php");?>
          </div>
          <input name="icon" type="hidden" value="<?php echo $this->data->icon;?>">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->SORTING;?></label>
          <div id="mSort">
            <div id="sortlist" class="dd">
              <?php if($this->droplist) : echo $this->sortlist; endif;?>
            </div>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label class="inverted"><?php echo Lang::$word->PAG_PGHOME;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="home_page" type="radio" value="1" id="home_page_1" <?php Validator::getChecked($this->data->home_page, 1);?>>
            <label for="home_page_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="home_page" type="radio" value="0" id="home_page_0" <?php Validator::getChecked($this->data->home_page, 0);?>>
            <label for="home_page_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label class="inverted"><?php echo Lang::$word->PUBLISHED;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="1" id="active_1" <?php Validator::getChecked($this->data->active, 1);?>>
            <label for="active_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="active" type="radio" value="0" id="active_0" <?php Validator::getChecked($this->data->active, 0);?>>
            <label for="active_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/menus");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processMenu" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->MEN_SUB4;?></button>
  </div>
  <!--<input type="hidden" name="content_type" value="<?php echo $this->data->content_type;?>">-->
  <input type="hidden" name="parent_id" value="<?php echo $this->data->parent_id;?>">
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php default: ?>
<h3 class="header"><?php echo Lang::$word->ADM_MENUS;?></h3>
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
            <label><?php echo Lang::$word->MEN_NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->MEN_NAME;?>" name="name_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->MEN_CAP;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->MEN_CAP;?>" name="caption_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEN_PARENT;?></label>
          <select id="parent_id" name="parent_id" class="yoyo fluid dropdown">
            <option value="0"><?php echo Lang::$word->MEN_SUB;?></option>
            <?php echo $this->droplist;?>
          </select>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->MEN_CTYPE;?></label>
          <select name="content_type" id="contenttype" class="yoyo fluid dropdown">
            <option value=""><?php echo Lang::$word->MEN_SUB1;?></option>
            <?php echo Utility::loopOptionsSimpleAlt($this->contenttype);?>
          </select>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <input type="hidden" name="cols" value="1">
        </div>
        <div class="field" id="contentid">
          <label><?php echo Lang::$word->MEN_SUB2;?></label>
          <select name="content_id" id="page_id" class="yoyo fluid dropdown">
            <option value="0"><?php echo Lang::$word->NONE;?></option>
          </select>
        </div>
      </div>
      <div id="webid" style="display:none">
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->MEN_SUB2;?></label>
            <input type="text" name="web" placeholder="<?php echo Lang::$word->MEN_TARGET_T;?>">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->MEN_TARGET_L;?></label>
            <select name="target" class="yoyo fluid dropdown">
              <option value=""><?php echo Lang::$word->MEN_TARGET;?></option>
              <option value="_blank"><?php echo Lang::$word->MEN_TARGET_B;?></option>
              <option value="_self"><?php echo Lang::$word->MEN_TARGET_S;?></option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="yoyo form card">
    <div class="content">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEN_ICONS;?></label>
          <div class="scrollbox_right" id="mIcons" style="height:500px;">
            <?php include(ADMINBASE . "/snippets/icons.tpl.php");?>
          </div>
          <input name="icon" type="hidden">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->SORTING;?></label>
          <div id="mSort">
            <div id="sortlist" class="dd">
              <?php if($this->droplist) : echo $this->sortlist; endif;?>
            </div>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label class="inverted"><?php echo Lang::$word->PAG_PGHOME;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="home_page" type="radio" value="1" id="home_page_1">
            <label for="home_page_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="home_page" type="radio" value="0" id="home_page_0" checked="checked">
            <label for="home_page_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label class="inverted"><?php echo Lang::$word->PUBLISHED;?></label>
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
    <button type="button" data-action="processMenu" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->MEN_SUB3;?></button>
  </div>
</form>
<?php break;?>
<?php endswitch;?>
<script src="<?php echo SITEURL;?>/assets/nestable.js"></script>
<script src="<?php echo ADMINVIEW;?>/js/menu.js"></script>
<script type="text/javascript"> 
// <![CDATA[	
  $(document).ready(function() {
	  $.Menu({
		  url: "<?php echo ADMINVIEW;?>",
            lang: {
                delMsg3: "<?php echo Lang::$word->TRASH;?>",
				delMsg8: "<?php echo Lang::$word->DELCONFIRM3;?>",
				canBtn: "<?php echo Lang::$word->CANCEL;?>",
				trsBtn: "<?php echo Lang::$word->MTOTRASH;?>",
				nonBtn: "<?php echo Lang::$word->NONE;?>",
            }
	  });
  });
// ]]>
</script>