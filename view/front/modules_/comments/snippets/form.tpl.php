<?php
  /**
   * Comments Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
?>
<div class="row double-vertical-gutters">
  <div class="columns">
    <div class="content-center margin-bottom">
      <h4><?php echo Lang::$word->_MOD_CM_SUB2;?></h4>
    </div>
    <div class="wojo form">
      <form id="wojo_form" name="wojo_form" method="post">
        <div class="wojo fields">
          <div class="field">
            <label><?php echo Lang::$word->RATING;?></label>
            <div class="wojo small checkbox radio fitted inline">
              <input name="star" type="radio" value="1" id="star_1">
              <label for="star_1">1</label>
            </div>
            <div class="wojo small checkbox radio fitted inline">
              <input name="star" type="radio" value="2" id="star_2">
              <label for="star_2">2</label>
            </div>
            <div class="wojo small checkbox radio fitted inline">
              <input name="star" type="radio" value="3" checked="checked" id="star_3">
              <label for="star_3">3</label>
            </div>
            <div class="wojo small checkbox radio fitted inline">
              <input name="star" type="radio" value="4" id="star_4">
              <label for="star_4">4</label>
            </div>
            <div class="wojo small checkbox radio fitted inline">
              <input name="star" type="radio" value="5" id="star_5">
              <label for="star_5">5</label>
            </div>
          </div>
        </div>
        <div class="wojo fields">
          <div class="field">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="wojo fluid input">
              <input name="name" placeholder="<?php echo Lang::$word->NAME;?>" type="text" value="<?php if (App::Auth()->logged_in) echo App::Auth()->name;?>">
            </div>
          </div>
          <?php if($conf->show_captcha):?>
          <div class="field">
            <label><?php echo Lang::$word->CAPTCHA;?>
              <i class="icon asterisk"></i></label>
            <div class="wojo right labeled fluid input">
              <input placeholder="<?php echo Lang::$word->CAPTCHA;?>" name="captcha" type="text">
              <span class="wojo simple passive button captcha">
              <?php echo Session::captcha();?>
              </span></div>
          </div>
          <?php endif;?>
        </div>
        <div class="wojo fields">
          <div class="field">
            <label><?php echo Lang::$word->MESSAGE;?>
              <i class="icon asterisk"></i></label>
            <div class="wojo fluid input">
              <textarea data-counter="<?php echo $conf->char_limit;?>" class="small" id="combody" placeholder="<?php echo Lang::$word->MESSAGE;?>" name="body"></textarea>
            </div>
            <p class="wojo tiny text content-right combody_counter"><?php echo Lang::$word->_MOD_CM_CHAR . ' <span class="wojo positive text">' . $conf->char_limit . ' </span>';?></p>
          </div>
        </div>
        <div class="content-center">
          <button type="button" name="doComment" class="wojo primary button"><?php echo Lang::$word->CF_SEND;?></button>
        </div>
        <input name="parent_id" type="hidden" value="<?php echo isset($this->data->id) ? $this->data->id : $this->row->id;?>">
        <input name="section" type="hidden" value="<?php echo $this->segments[0];?>">
        <input name="url" type="hidden" value="<?php echo Url::uri();?>">
      </form>
    </div>
  </div>
</div>