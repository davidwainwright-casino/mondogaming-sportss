<?php
  /**
   * Slider
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3><?php echo Lang::$word->_PLG_SL_TITLE1;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo big fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="title">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->_PLG_SL_AUTOPLAY;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoplay" type="radio" value="1" id="autoplay_1" checked="checked">
          <label for="autoplay_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoplay" type="radio" value="0" id="autoplay_0">
          <label for="autoplay_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->_PLG_SL_LOOPS;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoloop" type="radio" value="1" id="autoloop_1" checked="checked">
          <label for="autoloop_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoloop" type="radio" value="0" id="autoloop_0">
          <label for="autoloop_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->_PLG_SL_PONHOVER;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoplayHoverPause" type="radio" value="1" id="autoplayHoverPause_1"  checked="checked">
          <label for="autoplayHoverPause_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="autoplayHoverPause" type="radio" value="0" id="autoplayHoverPause_0">
          <label for="autoplayHoverPause_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->_PLG_SL_ASPEED;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid right labeled input">
          <input placeholder="<?php echo Lang::$word->_PLG_SL_ASPEED;?>" type="text" value="1000" name="autoplaySpeed">
          <div class="yoyo basic label"> ms </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->_PLG_SL_HEIGHT;?></label>
        <input data-ranger='{"step":5,"from":5, "to":100, "scale":[5,20,50,70,100], "format":"vh", "tip": false, "range":false}' type="text" name="height" value="50" class="rangers">
      </div>
    </div>
    <div class="yoyo divider"></div>
    <div class="row phone-block-1 tablet-block-2 screen-block-3 vertical-gutters content-center" id="layoutMode">
      <div class="column">
        <div class="yoyo attached simple segment active"><a data-type="basic"><img src="<?php echo APLUGINURL;?>slider/view/images/basic.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Basic</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="standard"><img src="<?php echo APLUGINURL;?>slider/view/images/standard.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Standard</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="dots"><img src="<?php echo APLUGINURL;?>slider/view/images/dots.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Bullets Only</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="dots_right"><img src="<?php echo APLUGINURL;?>slider/view/images/dots_right.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Vertical Bullets Right</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="dots_left"><img src="<?php echo APLUGINURL;?>slider/view/images/dots_left.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Vertical Bullets Left</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="thumbs"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Thumbnails Only</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="thumbs_down"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_down.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Thumbnails</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="thumbs_left"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_left.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Thumbnails Left</p>
        </div>
      </div>
      <div class="column">
        <div class="yoyo attached simple segment"><a data-type="thumbs_right"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_right.png" alt=""></a>
          <p class="yoyo small bold text half-top-padding">Thumbnails Right</p>
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/plugins", "slider");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="saveConfig" data-url="plugins_/slider" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_PLG_SL_SUB8;?></button>
  </div>
  <input type="hidden" name="layout" value="basic">
</form>
<link href="<?php echo APLUGINURL;?>slider/view/css/slider.css" rel="stylesheet" type="text/css" />
<script src="<?php echo APLUGINURL;?>slider/view/js/slider.js"></script>
<script type="text/javascript"> 
// <![CDATA[  
  $(document).ready(function() {
	  $.Slider({
          url: "<?php echo APLUGINURL;?>slider/controller.php",
          aurl: "<?php echo ADMINVIEW;?>",
          surl: "<?php echo SITEURL;?>",
		  turl: "<?php echo THEMEURL;?>",
          lang: {
              canBtn: "<?php echo Lang::$word->CANCEL;?>",
              updBtn: "<?php echo Lang::$word->UPDATE;?>",
          }
	  });
  });
// ]]>
</script>