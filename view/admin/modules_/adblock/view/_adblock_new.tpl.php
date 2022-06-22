<?php
  /**
   * Adblock
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3 class="header"><?php echo Lang::$word->_MOD_AB_ADD;?></h3>
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
          <div class="field">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB;?>
            <i class="icon asterisk"></i></label>
          <div class="row">
            <div class="column">
              <div class="yoyo fluid right icon input" id="fromdate">
                <input name="start_date" type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB10;?>" value="<?php echo Date::today();?>">
                <i class="icon date"></i>
              </div>
            </div>
            <div class="column">
              <div class="yoyo fluid right icon input" id="enddate">
                <input name="end_date" type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB11;?>">
                <i class="icon date"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB1;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB1;?>" name="max_views">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB2;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB2;?>" name="max_clicks">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB3;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB3;?>" name="min_ctr">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_AB_SUB12;?></label>
          <div class="yoyo checkbox toggle fitted inline">
            <input name="btype" type="radio" value="yes" id="btype_yes" checked="checked">
            <label for="btype_yes"><?php echo Lang::$word->_MOD_AB_SUB4;?></label>
          </div>
          <div class="yoyo checkbox toggle fitted inline">
            <input name="btype" type="radio" value="no" id="btype_no">
            <label for="btype_no"><?php echo Lang::$word->_MOD_AB_SUB7;?></label>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field" id="imgType">
          <label><?php echo Lang::$word->_MOD_AB_SUB4;?>
            <i class="icon asterisk"></i></label>
          <input type="file" data-buttonText="<?php echo Lang::$word->BROWSE;?>" name="image" id="image" class="filefield" data-input="false">
          <div class="yoyo space divider"></div>
          <label><?php echo Lang::$word->_MOD_AB_SUB5;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB5;?>" name="image_link">
          <div class="yoyo space divider"></div>
          <label><?php echo Lang::$word->_MOD_AB_SUB6;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_AB_SUB6;?>" name="image_alt">
        </div>
        <div class="field hide-all" id="htmlType">
          <label><?php echo Lang::$word->_MOD_AB_SUB7;?>
            <i class="icon asterisk"></i></label>
          <textarea name="html" placeholder="<?php echo Lang::$word->_MOD_AB_SUB7;?>"></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "adblock/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/adblock" data-action="processCampaign" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_AB_NEW;?></button>
  </div>
  <input type="hidden" name="banner_type" value="yes">
</form>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $("input[name=btype]").on('change', function() {
		if($(this).val() === "no") {
			$("#imgType").hide();
			$("#htmlType").show();
		} else {
			$("#imgType").show();
			$("#htmlType").hide();
		}
		$("input[name=banner_type]").val($(this).val())
   });
});
// ]]>
</script>