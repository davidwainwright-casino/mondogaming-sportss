<?php
  /**
   * Comments
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::checkModAcl('comments')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments, 3)): case "settings": ?>
<h3 class="header">
  <?php echo Lang::$word->_MOD_CM_TITLE1;?>
</h3>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo form segment">
    <div class="yoyo fields">
      <div class="field four wide">
        <label><?php echo Lang::$word->_MOD_CM_SORTING;?>
          <i class="icon asterisk"></i></label>
        <select name="sorting" class="yoyo fluid dropdown">
          <option value="DESC" <?php echo Validator::getSelected($this->data->sorting, "DESC");?>><?php echo Lang::$word->_MOD_CM_SORTING_T;?></option>
          <option value="ASC" <?php echo Validator::getSelected($this->data->sorting, "ASC");?>><?php echo Lang::$word->_MOD_CM_SORTING_B;?></option>
        </select>
      </div>
      <div class="field">
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->_MOD_CM_DATE;?>
              <i class="icon asterisk"></i></label>
            <select name="dateformat" class="yoyo fluid dropdown">
              <?php echo Date::getShortDate($this->data->dateformat);?>
              <?php echo Date::getLongDate($this->data->dateformat);?>
            </select>
          </div>
          <div class="field two wide">
            <label><?php echo Lang::$word->_MOD_CM_TSINCE;?></label>
            <div class="yoyo checkbox fitted inline">
              <input name="timesince" type="checkbox" value="1" id="timesince" <?php Validator::getChecked($this->data->timesince, 1); ?>>
              <label for="timesince"><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label>
          <?php echo Lang::$word->_MOD_CM_CHAR;?>
        </label>
        <input data-ranger='{"step":10,"from":20, "to":400, "format":"chars", "tip": false, "range":false}' type="hidden" name="char_limit" value="<?php echo $this->data->char_limit;?>" class="rangers">
      </div>
      <div class="field">
        <label>
          <?php echo Lang::$word->_MOD_CM_PERPAGE;?>
        </label>
        <input data-ranger='{"step":5,"from":5, "to":50, "format":"item", "tip": false, "range":false}' type="hidden" name="perpage" value="<?php echo $this->data->perpage;?>" class="rangers">
      </div>
    </div>
    <div class="yoyo space divider"></div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_UNAME_R;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="username_req" type="radio" value="1" id="username_req_1" <?php Validator::getChecked($this->data->username_req, 1); ?>>
          <label for="username_req_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="username_req" type="radio" value="0" id="username_req_0" <?php Validator::getChecked($this->data->username_req, 0); ?>>
          <label for="username_req_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_RATING;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="rating" type="radio" value="1" id="rating_1" <?php Validator::getChecked($this->data->rating, 1); ?>>
          <label for="rating_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="rating" type="radio" value="0" id="rating_0" <?php Validator::getChecked($this->data->rating, 0); ?>>
          <label for="rating_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_CAPTCHA;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_captcha" type="radio" value="1" id="show_captcha_1" <?php Validator::getChecked($this->data->show_captcha, 1); ?>>
          <label for="show_captcha_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="show_captcha" type="radio" value="0" id="show_captcha_0" <?php Validator::getChecked($this->data->show_captcha, 0); ?>>
          <label for="show_captcha_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_REG_ONLY;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="public_access" type="radio" value="1" id="public_access_1" <?php Validator::getChecked($this->data->public_access, 1); ?>>
          <label for="public_access_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="public_access" type="radio" value="0" id="public_access_0" <?php Validator::getChecked($this->data->public_access, 0); ?>>
          <label for="public_access_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_AA;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="auto_approve" type="radio" value="1" id="auto_approve_1" <?php Validator::getChecked($this->data->auto_approve, 1); ?>>
          <label for="auto_approve_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="auto_approve" type="radio" value="0" id="auto_approve_0" <?php Validator::getChecked($this->data->auto_approve, 0); ?>>
          <label for="auto_approve_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_NOTIFY;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="notify_new" type="radio" value="1" id="notify_new_1" <?php Validator::getChecked($this->data->notify_new, 1); ?>>
          <label for="notify_new_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="notify_new" type="radio" value="0" id="notify_new_0" <?php Validator::getChecked($this->data->notify_new, 0); ?>>
          <label for="notify_new_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->_MOD_CM_WORDS;?></label>
        <textarea placeholder="<?php echo Lang::$word->_MOD_CM_WORDS;?>" name="blacklist_words"><?php echo $this->data->blacklist_words;?></textarea>
      </div>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules/comments");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/comments" data-action="processConfig" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->SAVECONFIG;?></button>
  </div>
</form>
<?php break;?>
<?php default: ?>
<div class="row gutters align-middle">
  <div class="column mobile-100 phone-100">
    <h3><?php echo Lang::$word->_MOD_CM_TITLE2;?></h3>
  </div>
  <div class="column shrink mobile-100 phone-100">
    <a href="<?php echo Url::url(Router::$path, "settings/");?>" class="yoyo icon button"><i class="icon cogs"></i>
    </a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->_MOD_CM_SUB3;?></p>
</div>
<?php else:?>
<?php foreach($this->data as $row):?>
<div class="yoyo card" id="item_<?php echo $row->id;?>">
  <div class="content">
    <div class="row">
      <div class="columns">
        <p class="yoyo semi medium text">
          <?php echo ($row->uname)? $row->uname : $row->username;?>
        </p>
        <div class="yoyo small text"><?php echo $row->body;?></div>
        <span class="yoyo tiny semi caps text">
        <?php echo Date::doDate("long_date", $row->created);?>
        </span>
      </div>
      <div class="columns shrink"><a data-set='{"option":[{"simpleAction": "1","action":"approve", "id":<?php echo $row->id;?>}], "url":"/modules_/comments/controller.php", "after":"remove", "parent":"#item_<?php echo $row->id;?>"}' data-content="<?php echo Lang::$word->_MOD_CM_SUB4;?>" class="yoyo small circular icon button positive simpleAction"><i class="icon check"></i></a>
        <a data-set='{"option":[{"delete": "deleteComment","title": "ID","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/comments"}'  class="yoyo small circular icon button negative action"><i class="icon trash"></i></a>
      </div>
    </div>
  </div>
</div>
<?php endforeach;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small thick text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>