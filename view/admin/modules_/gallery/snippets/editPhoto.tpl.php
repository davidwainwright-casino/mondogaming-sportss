<?php
  /**
   * Edit Photo
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<ul class="yoyo basic wide tabs align-center">
  <?php foreach($this->langlist as $lang):?>
      <li<?php echo ($lang->abbr == $this->core->lang) ? ' class="active"' : null;?>><a style="background:<?php echo $lang->color;?>;color:#fff" data-tab="#lang_<?php echo $lang->abbr;?>"><span class="flag icon <?php echo $lang->abbr;?>"></span><?php echo $lang->name;?></a>
      </li>
  <?php endforeach;?>
</ul>
<form method="post" id="modal_form" name="modal_form">
  <div class="yoyo small segment form">
    <?php foreach($this->langlist as $lang):?>
    <div id="lang_<?php echo $lang->abbr;?>" class="yoyo tab item">
      <div class="yoyo block fields">
        <div class="field">
          <label><?php echo Lang::$word->NAME;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'title_' . $lang->abbr};?>" name="title_<?php echo $lang->abbr?>">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DESCRIPTION;?></label>
          <textarea type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" name="description_<?php echo $lang->abbr?>"><?php echo $this->data->{'description_' . $lang->abbr};?></textarea>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</form>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
	$(".yoyo.tab.item").hide();
	$(".yoyo.tab.item:first").show();
	$(".yoyo.tabs:not(.responsive) a:first").parent().addClass('active');
	$(".yoyo.tabs:not(.responsive) a").on('click', function() {
		$(".yoyo.tabs:not(.responsive) li").removeClass("active");
		$(this).parent().addClass("active");
		$(".yoyo.tab.item").hide();
		var activeTab = $(this).data("tab");
		if($(activeTab).is(':first-child')) {
			$(activeTab).parent().addClass('tabbed');
		} else {
			$(activeTab).parent().removeClass('tabbed');
		}
		$(activeTab).show();
		return false;
	});
});
// ]]>
</script>