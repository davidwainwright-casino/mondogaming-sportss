<?php
  /**
   * Gmaps
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3 class="header"> <?php echo Lang::$word->_MOD_GM_TITLE2;?> </h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?> <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="name">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_SUB;?> <i class="icon asterisk"></i></label>
        <select name="type" class="yoyo fluid dropdown">
          <?php echo Utility::loopOptionsSimpleAlt($this->mtype);?>
        </select>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_SUB1;?></label>
        <input data-ranger='{"step":1,"from":1, "to":20, "format":"level", "tip": false, "range":false}' type="text" name="zoom" value="14" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_SUB1_1;?></label>
        <input data-ranger='{"step":1,"from":1, "to":20, "format":"level", "tip": false, "range":true}' type="text" name="minmaxzoom" value="5,20" class="rangers">
      </div>
    </div>
    <div class="yoyo space divider"></div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_SUB3;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="streetview" type="radio" value="1" id="streetview_1" checked="checked">
          <label for="streetview_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="streetview" type="radio" value="0" id="streetview_0">
          <label for="streetview_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_SUB2;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="type_control" type="radio" value="1" id="type_control_1">
          <label for="type_control_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="type_control" type="radio" value="0" id="type_control_0" checked="checked">
          <label for="type_control_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->M_ADDRESS;?> <i class="icon asterisk"></i></label>
        <textarea placeholder="<?php echo Lang::$word->M_ADDRESS;?>" name="body"></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_GM_PINS;?></label>
        <div class="scrollbox_right" style="height:180px;">
          <div class="row phone-block-1 mobile-block-2 tablet-block-3 screen-block-4 half-gutters content-center" id="pinMode">
            <?php foreach($this->pins as $row):?>
            <div class="column"> <a data-type="<?php echo $row;?>"><img src="<?php echo FMODULEURL;?>gmaps/view/images/pins/<?php echo $row;?>" alt="" class="yoyo basic image<?php echo ("basic.png" == $row) ? " highlite" :'';?>"></a> </div>
            <?php endforeach;?>
            <?php unset($row);?>
          </div>
        </div>
      </div>
    </div>
    <a class="yoyo icon button" data-trigger="#uStyles" data-type="slide down" data-transition="true" ><i class="icon chevron down"></i></a>
    <div class="hide-all" id="uStyles">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_GM_SUB4;?></label>
          <div class="row phone-block-1 mobile-block-2 tablet-block-3 screen-block-4 gutters" id="layoutMode">
            <?php foreach($this->styles as $row):?>
            <div class="column">
              <div class="yoyo boxed basic attached segment<?php echo ("basic" == pathinfo($row, PATHINFO_FILENAME)) ? " selected" :'';?>"><a data-type="<?php echo pathinfo($row, PATHINFO_FILENAME);?>"><img src="<?php echo AMODULEURL;?>gmaps/view/images/styles/<?php echo $row;?>" alt=""></a> </div>
            </div>
            <?php endforeach;?>
            <?php unset($row);?>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <div class="yoyo fluid action left icon input"> <i class="icon small find"></i>
          <input name="address" placeholder="<?php echo Lang::$word->_MOD_GM_SUB5;?>" type="text">
          <button type="button" name="find_address" class="yoyo small basic button"><?php echo Lang::$word->FIND;?></button>
        </div>
        <div class="yoyo space divider"></div>
        <div class="yoyo boxed basic attached segment" id="google_map" style="height:400px"></div>
      </div>
    </div>
  </div>
  <div class="content-center"> <a href="<?php echo Url::url("/admin/modules", "gmaps/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/gmaps" data-action="processMap" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_GM_SUB7;?></button>
  </div>
  <input type="hidden" name="layout" value="basic">
  <input type="hidden" name="lat" value="43.6532">
  <input type="hidden" name="lng" value="-79.3832">
  <input type="hidden" name="pin" value="basic">
</form>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?php echo App::Core()->mapapi;?>"></script> 
<script src="<?php echo AMODULEURL;?>gmaps/view/js/gmaps.js"></script> 
<script type="text/javascript"> 
// <![CDATA[  
  $(document).ready(function() {
	  $.Gmaps({
		  url: "<?php echo AMODULEURL;?>gmaps/controller.php",
		  murl: "<?php echo AMODULEURL;?>gmaps/",
		  furl: "<?php echo FMODULEURL;?>gmaps/",
	  });
  });
// ]]>
</script>