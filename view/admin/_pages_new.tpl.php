<?php
  /**
   * Pages
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3 class="header"><?php echo Lang::$word->PAG_SUB4;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form card">
    <ul class="yoyo basic wide tabs">
      <?php foreach($this->langlist as $lang):?>
      <li<?php echo ($lang->abbr == $this->core->lang) ? ' class="active"' : null;?>><a style="background:<?php echo $lang->color;?>;color:#fff" data-tab="#lang_<?php echo $lang->abbr;?>"><span class="flag icon <?php echo $lang->abbr;?>"></span><?php echo $lang->name;?></a></li>
        <?php endforeach;?>
    </ul>
    <div class="content">
      <?php foreach($this->langlist as $lang):?>
      <div id="lang_<?php echo $lang->abbr;?>" class="yoyo tab item">
        <div class="yoyo fields">
          <div class="field five wide">
            <label><?php echo Lang::$word->PAG_NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->PAG_SLUG;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->PAG_SLUG;?>" name="slug_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->PAG_CAPTION;?></label>
            <input type="text" placeholder="<?php echo Lang::$word->PAG_CAPTION;?>" name="caption_<?php echo $lang->abbr;?>">
          </div>
          <div class="field">
            <label><?php echo Lang::$word->BGIMG;?></label>
            <div class="yoyo fluid right action input">
              <input id="bg_<?php echo $lang->abbr;?>" placeholder="<?php echo Lang::$word->BGIMG;?>" name="custom_bg_<?php echo $lang->abbr;?>" type="text" value="" readonly>
              <div class="yoyo basic small simple button removebg">
                <?php echo Lang::$word->REMOVE;?>
              </div>
              <div class="filepicker yoyo small icon basic button" data-parent="#bg_<?php echo $lang->abbr;?>" data-ext="images"><i class="open folder icon"></i></div>
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->METAKEYS;?></label>
            <textarea class="small" placeholder="<?php echo Lang::$word->METAKEYS;?>" name="keywords_<?php echo $lang->abbr;?>"></textarea>
          </div>
          <div class="field">
            <label><?php echo Lang::$word->METADESC;?></label>
            <textarea class="small" placeholder="<?php echo Lang::$word->METADESC;?>" name="description_<?php echo $lang->abbr;?>"></textarea>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="row gutters">
        <div class="columns screen-50 mobile-100">
          <div class="yoyo block fields">
            <div class="field">
              <label><?php echo Lang::$word->PAG_ACCLVL;?></label>
              <select name="access" id="access_id" data-id="0" class="yoyo fluid dropdown access_id">
                <?php echo Utility::loopOptionsSimpleAlt($this->access_list);?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->PAG_JSCODE;?></label>
              <textarea class="small" placeholder="<?php echo Lang::$word->PAG_JSCODE;?>" name="jscode"></textarea>
            </div>
          </div>
        </div>
        <div class="columns screen-50 mobile-100">
          <div class="yoyo block fields">
            <div class="field">
              <label><?php echo Lang::$word->PAG_MEMLVL;?></label>
              <div id="membership">
                <input disabled="disabled" type="text" value="<?php echo Lang::$word->PAG_NOMEM_REQ;?>" name="na">
              </div>
            </div>
            <div class="field" id="modshow">
              <input type="hidden" name="module_data" value="0">
            </div>
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
        <div class="field">
          <label><?php echo Lang::$word->PAG_NOHEAD;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="show_header" type="radio" value="1" id="show_header_1" checked="checked">
            <label for="show_header_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="show_header" type="radio" value="0" id="show_header_0">
            <label for="show_header_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PAG_MDLCOMMENT;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="is_comments" type="radio" value="1" id="is_comments_1" >
            <label for="is_comments_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="is_comments" type="radio" value="0" id="is_comments_0" checked="checked">
            <label for="is_comments_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->PAG_PGADM;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="is_admin" type="radio" value="1" id="is_admin_1">
            <label for="is_admin_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="is_admin" type="radio" value="0" id="is_admin_0" checked="checked">
            <label for="is_admin_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/pages");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processPage" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->PAG_SUB4;?></button>
  </div>
</form>
<script src="<?php echo ADMINVIEW;?>/js/page.js"></script>
<script type="text/javascript"> 
// <![CDATA[  
  $(document).ready(function() {
	  $.Page({
		  url: "<?php echo ADMINVIEW;?>/helper.php",
		  clang :"<?php echo Lang::$lang;?>",
		  lang :{
			  nomemreq :"<?php echo Lang::$word->PAG_NOMEM_REQ;?>",
		  }
	  });
  });
// ]]>
</script>