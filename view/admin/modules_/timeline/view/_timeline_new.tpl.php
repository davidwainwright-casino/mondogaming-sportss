<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3>
  <?php echo Lang::$word->_MOD_TML_NEW;?>
</h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="name">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_LMODE;?></label>
        <select name="colmode" class="yoyo fluid dropdown">
          <?php echo Utility::loopOptionsSimpleAlt($this->layoutlist);?>
        </select>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB3;?></label>
        <input data-ranger='{"step":5,"from":5, "to":50, "format":"item", "tip": false, "range":false}' type="text" name="limiter" value="5" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB4;?></label>
        <input data-ranger='{"step":1,"from":0, "to":20, "format":"item", "tip": false, "range":false}' type="text" name="maxitems" value="0" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB5;?></label>
        <input data-ranger='{"step":1,"from":0, "to":20, "format":"item", "tip": false, "range":false}' type="text" name="showmore" value="0" class="rangers">
      </div>
    </div>
    <div class="yoyo space divider"></div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB9;?></label>
        <select name="type" class="yoyo fluid dropdown">
          <?php echo Utility::loopOptionsSimpleAlt($this->typelist);?>
        </select>
      </div>
      <div class="field">
      </div>
    </div>
    <div id="fbconf" class="hide-all">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB6;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB6;?>" name="fbid">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB20;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB20;?>" name="fbpage">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB7;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB7;?>" name="fbtoken">
        </div>
      </div>
    </div>
    <div id="rssconf" class="hide-all">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB8;?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB8;?>" name="rssurl">
        </div>
        <div class="field">
        </div>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "timeline/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/timeline" data-action="processTimeline" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_TML_NEW;?></button>
  </div>
</form>