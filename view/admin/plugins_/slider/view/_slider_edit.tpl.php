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
<div class="row half-gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->_PLG_SL_TITLE2;?></h3>
  </div>
  <div class="column shrink mobile-100 phone-100">
  <a id="addnew" class="yoyo positive icon button"><i class="icon plus"></i></a>
    <div class="yoyo secondary button" data-transition="true" data-type="slide down" data-trigger="#settings">
      <i class="cogs icon"></i>
      <?php echo Lang::$word->SETTINGS;?>
    </div>
  </div>
</div>
<div id="settings" class="transition hidden">
  <!-- Configuration -->
  <form method="post" id="yoyo_form" name="yoyo_form">
    <div class="yoyo form segment">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->NAME;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo big fluid input">
            <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->title;?>" name="title">
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->_PLG_SL_AUTOPLAY;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoplay" type="radio" value="1" id="autoplay_1" <?php Validator::getChecked($this->data->autoplay, 1); ?>>
            <label for="autoplay_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoplay" type="radio" value="0" id="autoplay_0" <?php Validator::getChecked($this->data->autoplay, 0); ?>>
            <label for="autoplay_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->_PLG_SL_LOOPS;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoloop" type="radio" value="1" id="autoloop_1" <?php Validator::getChecked($this->data->autoloop, 1); ?>>
            <label for="autoloop_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoloop" type="radio" value="0" id="autoloop_0" <?php Validator::getChecked($this->data->autoloop, 0); ?>>
            <label for="autoloop_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->_PLG_SL_PONHOVER;?></label>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoplayHoverPause" type="radio" value="1" id="autoplayHoverPause_1" <?php Validator::getChecked($this->data->autoplayHoverPause, 1); ?>>
            <label for="autoplayHoverPause_1"><?php echo Lang::$word->YES;?></label>
          </div>
          <div class="yoyo checkbox radio fitted inline">
            <input name="autoplayHoverPause" type="radio" value="0" id="autoplayHoverPause_0" <?php Validator::getChecked($this->data->autoplayHoverPause, 0); ?>>
            <label for="autoplayHoverPause_0"><?php echo Lang::$word->NO;?></label>
          </div>
        </div>
        <div class="field five wide">
          <label><?php echo Lang::$word->_PLG_SL_ASPEED;?>
            <i class="icon asterisk"></i></label>
          <div class="yoyo fluid right labeled input">
            <input placeholder="<?php echo Lang::$word->_PLG_SL_ASPEED;?>" type="text" value="<?php echo $this->data->autoplaySpeed;?>" name="autoplaySpeed">
            <div class="yoyo basic label"> ms </div>
          </div>
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field five wide">
          <label><?php echo Lang::$word->_PLG_SL_HEIGHT;?></label>
          <input class="rangers" type="text" value="<?php echo $this->data->height;?>" name="height" data-ranger='{"step":5,"from":5, "to":100, "format":"vh", "tip": false, "range":false}'>
        </div>
      </div>
      <div class="yoyo divider"></div>
      <div class="row phone-block-1 tablet-block-2 screen-block-3 vertical-gutters content-center" id="layoutMode">
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "basic") ? " active" :'';?>"><a data-type="basic"><img src="<?php echo APLUGINURL;?>slider/view/images/basic.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Basic</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "standard") ? " active" :'';?>"><a data-type="standard"><img src="<?php echo APLUGINURL;?>slider/view/images/standard.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Standard</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "dots") ? " active" :'';?>"><a data-type="dots"><img src="<?php echo APLUGINURL;?>slider/view/images/dots.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Bullets Only</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "dots_right") ? " active" :'';?>"><a data-type="dots_right"><img src="<?php echo APLUGINURL;?>slider/view/images/dots_right.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Vertical Bullets Right</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "dots_left") ? " active" :'';?>"><a data-type="dots_left"><img src="<?php echo APLUGINURL;?>slider/view/images/dots_left.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Vertical Bullets Left</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "thumbs") ? " active" :'';?>"><a data-type="thumbs"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Thumbnails Only</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "thumbs_down") ? " active" :'';?>"><a data-type="thumbs_down"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_down.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Thumbnails</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "thumbs_left") ? " active" :'';?>"><a data-type="thumbs_left"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_left.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Thumbnails Left</p>
          </div>
        </div>
        <div class="column">
          <div class="yoyo attached simple segment<?php echo ($this->data->layout == "thumbs_right") ? " active" :'';?>"><a data-type="thumbs_right"><img src="<?php echo APLUGINURL;?>slider/view/images/thumbs_right.png" alt=""></a>
            <p class="yoyo small bold text half-top-padding">Thumbnails Right</p>
          </div>
        </div>
      </div>
    </div>
    <div class="content-center">
      <a  data-transition="true" data-type="slide up" data-trigger="#settings" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
      <button type="button" data-action="saveConfig" data-url="plugins_/slider" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_PLG_SL_SUB8;?></button>
    </div>
    <input type="hidden" name="layout" value="<?php echo $this->data->layout;?>">
    <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
  </form>
</div>
<!-- Slides -->
<div class="row screen-block-5 tablet-block-3 mobile-block-2 phone-block-1 gutters align-center wedit margin-top" id="sortable">
  <?php if($this->slides):?>
  <?php foreach($this->slides as $rows):?>
  <div class="column" id="item_<?php echo $rows->id;?>" data-id="<?php echo $rows->id;?>">
    <div class="yoyo card attached" data-mode="<?php echo $rows->mode;?>" data-color="<?php echo $rows->color;?>" data-image="<?php echo $rows->image;?>" 
        <?php switch($rows->mode): case "tr": ?>
        style="background-image:url(<?php echo APLUGINURL . '/slider/view/images/transbg.png';?>);background-repeat: repeat;"
        <?php break;?>
        <?php case "cl": ?>
          style="background-color:<?php echo $rows->color;?>"
        <?php break;?>
        <?php default: ?>
          style="background-image:url(<?php echo UPLOADURL . '/thumbs/' . basename($rows->image);?>);background-size: cover; background-position: center center; background-repeat: no-repeat;"
        <?php break;?>
        <?php endswitch;?>
      >
    <div class="yoyo simple small icon top left attached button handle"><i class="icon white reorder link"></i></div>
      <div class="content">
        <div class="yoyo white dimmed bg content-center half-bottom-margin" data-editable="true" data-set='{"type": "sltitle", "id":<?php echo $rows->id;?>, "url":"<?php echo APLUGINURL;?>slider/controller.php"}'><?php echo Validator::truncate($rows->title, 20);?></div>
        <div class="yoyo divider"></div>
        <div class="yoyo fluid buttons eMenu">
          <a class="yoyo small icon primary button" data-tooltip="<?php echo Lang::$word->PROP;?>" data-set='{"mode":"prop","id":<?php echo $rows->id;?>,"type":"<?php echo $rows->mode;?>"}'>
            <i class="icon select"></i>
          </a>
          <a href="<?php echo Url::url('/admin/plugins/slider/builder', $rows->id);?>" class="yoyo small icon positive button" data-tooltip="<?php echo Lang::$word->EDIT;?>" data-set='{"mode":"edit","id":<?php echo $rows->id;?>}'>
            <i class="icon note"></i>
          </a>
          <a class="yoyo small icon secondary button" data-tooltip="<?php echo Lang::$word->DUPLICATE;?>" data-set='{"mode":"duplicate","id":<?php echo $rows->id;?>}'>
            <i class="icon copy"></i>
          </a>
          <a class="yoyo small icon negative button" data-tooltip="<?php echo Lang::$word->DELETE;?>" data-set='{"mode":"delete","id":<?php echo $rows->id;?>}'>
            <i class="icon trash"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
<?php endforeach;?>
<?php endif;?>
</div>
<!-- Slide Source-->
<div class="yoyo small form segment hide-all" id="source">
  <a id="closeSource" class="yoyo simple top right attached icon button"><i class="icon delete"></i></a>
  <div class="yoyo fields align-middle">
    <div class="field two wide labeled">
      <label class="content-right mobile-content-left"><?php echo Lang::$word->_PLG_SL_SUB12;?></label>
    </div>
    <div class="field shrink">
      <div class="yoyo checkbox inline radio fitted">
        <input name="source" type="radio" value="bg" id="source_bg" checked="checked">
        <label for="source_bg">&nbsp;</label>
      </div>
    </div>
    <div data-id="bg_asset" class="field shrink hide-all">
      <a class="yoyo small primary button bg_image"><?php echo Lang::$word->_PLG_SL_SUB13;?></a>
      <input type="hidden" name="bg_img" value="" id="bg_img">
    </div>
  </div>
  <div class="yoyo fields">
    <div class="field two wide labeled">
      <label class="content-right mobile-content-left">Transparent</label>
    </div>
    <div class="field shrink">
      <div class="yoyo checkbox inline radio fitted">
        <input name="source" type="radio" id="source_tr" value="tr">
        <label for="source_tr">&nbsp;</label>
      </div>
    </div>
  </div>
  <div class="yoyo fields">
    <div class="field two wide labeled">
      <label class="content-right mobile-content-left">Solid Color</label>
    </div>
    <div class="field shrink">
      <div class="yoyo checkbox inline radio fitted">
        <input name="source" type="radio" id="source_cl" value="cl">
        <label for="source_cl">&nbsp;</label>
      </div>
    </div>
    <div data-id="cl_asset" class="shrink hide-all">
      <a class="yoyo small basic icon button"><i class="icon contrast"></i></a>
      <input type="hidden" id="bg_color" value="">
    </div>
  </div>
</div>
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