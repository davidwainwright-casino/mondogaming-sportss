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
<h3> <?php echo Lang::$word->_MOD_TML_SUB;?></h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->NAME;?> <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->name;?>" name="name">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_LMODE;?></label>
        <select name="colmode" class="yoyo fluid dropdown">
          <?php echo Utility::loopOptionsSimpleAlt($this->layoutlist, $this->data->colmode);?>
        </select>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB3;?></label>
        <input data-ranger='{"step":5,"from":5, "to":50, "format":"item", "tip": false, "range":false}' type="text" name="limiter" value="<?php echo $this->data->limiter;?>" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB4;?></label>
        <input data-ranger='{"step":1,"from":0, "to":20, "format":"item", "tip": false, "range":false}' type="text" name="maxitems" value="<?php echo $this->data->maxitems;?>" class="rangers">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB5;?></label>
        <input data-ranger='{"step":1,"from":0, "to":20, "format":"item", "tip": false, "range":false}' type="text" name="showmore" value="<?php echo $this->data->showmore;?>" class="rangers">
      </div>
    </div>
    <?php if($this->data->type == "facebook"):?>
    <div class="yoyo space divider"></div>
    <div id="fbconf">
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB6;?> <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB6;?>" value="<?php echo $this->data->fbid;?>" name="fbid">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB20;?> <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB20;?>" value="<?php echo $this->data->fbpage;?>" name="fbpage">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->_MOD_TML_SUB7;?> <i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB7;?>" value="<?php echo $this->data->fbtoken;?>" name="fbtoken">
        </div>
      </div>
    </div>
    <?php endif;?>
    <?php if($this->data->type == "rss"):?>
    <div class="yoyo space divider"></div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_TML_SUB8;?> <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->_MOD_TML_SUB8;?>" value="<?php echo $this->data->rssurl;?>" name="rssurl">
      </div>
    </div>
    <?php endif;?>
  </div>
  <div class="content-center"> <a href="<?php echo Url::url("/admin/modules", "timeline/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/timeline" data-action="processTimeline" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_TML_SUB2;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
  <input type="hidden" name="type" value="<?php echo $this->data->type;?>">
</form>