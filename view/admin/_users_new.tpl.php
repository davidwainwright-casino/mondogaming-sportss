<?php
  /**
   * User Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="yoyo segment form">
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_FNAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->M_FNAME;?>" name="fname">
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->M_LNAME;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid input">
          <input type="text" placeholder="<?php echo Lang::$word->M_LNAME;?>" name="lname">
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_EMAIL;?>
          <i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->M_EMAIL;?>" name="email">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->NEWPASS;?>
          <i class="icon asterisk"></i></label>
        <div class="yoyo fluid right input icon">
          <input type="text" name="password">
          <button class="yoyo icon button" type="button" id="randPass">
          <i class="lock icon"></i>
          </button>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field five wide">
        <label><?php echo Lang::$word->M_SUB8;?>
        </label>
        <div class="row align-middle">
          <div class="column">
            <select name="membership_id" class="yoyo fluid dropdown">
              <option value="0">-/-</option>
              <?php echo Utility::loopOptions($this->mlist, "id", "title" . Lang::$lang);?>
            </select>
          </div>
          <div class="column shrink half-left-padding">
            <div class="yoyo checkbox toggle fitted inline">
              <input name="update_membership" type="checkbox" value="1" id="update_membership">
              <label for="update_membership"><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
      <div class="field five wide">
        <label><?php echo Lang::$word->M_SUB15;?></label>
        <div class="row align-middle">
          <div class="column">
            <div class="yoyo fluid right icon input" data-datepicker="true">
              <input name="mem_expire" type="text" placeholder="<?php echo Lang::$word->TO;?>">
              <i class="icon calendar alt"></i>
            </div>
          </div>
          <div class="column shrink half-left-padding">
            <div class="yoyo checkbox toggle fitted inline">
              <input name="extend_membership" type="checkbox" value="1" id="extend_membership">
              <label for="extend_membership"><?php echo Lang::$word->YES;?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo basic segment">
      <h5><?php echo Lang::$word->CF_TITLE;?></h5>
      <?php echo $this->custom_fields;?></div>
    <a class="yoyo icon button" data-trigger="#uAddress" data-type="slide down" data-transition="true"><i class="icon chevron down"></i></a>
    <div class="yoyo basic segment hide-all" id="uAddress">
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_ADDRESS;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_ADDRESS;?>" name="address">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_CITY;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_CITY;?>" name="city">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_STATE;?></label>
        </div>
        <div class="field">
          <input type="text" placeholder="<?php echo Lang::$word->M_STATE;?>" name="state">
        </div>
      </div>
      <div class="yoyo fields">
        <div class="field four wide labeled">
          <label class="content-right mobile-content-left"><?php echo Lang::$word->M_COUNTRY;?>/<?php echo Lang::$word->M_ZIP;?></label>
        </div>
        <div class="field">
          <div class="yoyo fields">
            <div class="field">
              <input type="text" placeholder="<?php echo Lang::$word->M_ZIP;?>" name="zip">
            </div>
            <div class="field">
              <select class="yoyo fluid search dropdown" name="country">
                <option value="">-/-</option>
                <?php echo Utility::loopOptions($this->clist, "abbr", "name");?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="yoyo big space divider"></div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->STATUS;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="y" id="active_y" checked="checked">
          <label for="active_y"><?php echo Lang::$word->ACTIVE;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="n" id="active_n">
          <label for="active_n"><?php echo Lang::$word->INACTIVE;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="t" id="active_t">
          <label for="active_t"><?php echo Lang::$word->PENDING;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="active" type="radio" value="b" id="active_b">
          <label for="active_b"><?php echo Lang::$word->BANNED;?></label>
        </div>
      </div>
    </div>
    <div class="yoyo fields">
      <div class="field">
        <label><?php echo Lang::$word->M_SUB9;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="type" type="radio" value="staff" id="type_staff">
          <label for="type_staff"><?php echo Lang::$word->STAFF;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="type" type="radio" value="editor" id="type_editor">
          <label for="type_editor"><?php echo Lang::$word->EDITOR;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="type" type="radio" value="member" id="type_member" checked="checked">
          <label for="type_member"><?php echo Lang::$word->MEMBER;?></label>
        </div>
		
		<div class="yoyo checkbox radio fitted inline">
          <input name="type" type="radio" value="Sagent" id="type_sagent">
          <label for="type_sagent"><?php echo 'Sagent';?></label>
        </div>
		
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_SUB10;?></label>
        <div class="yoyo checkbox radio fitted inline">
          <input name="newsletter" type="radio" value="1" id="newsletter_1">
          <label for="newsletter_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="yoyo checkbox radio fitted inline">
          <input name="newsletter" type="radio" value="0" id="newsletter_0" checked="checked">
          <label for="newsletter_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_SUB13;?></label>
        <div class="yoyo checkbox toggle inline">
          <input name="notify" type="checkbox" value="1" id="notify_0">
          <label for="notify_0"><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
    </div>
    <textarea placeholder="<?php echo Lang::$word->M_SUB11;?>" name="notes"></textarea>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/users");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processUser" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->M_TITLE5;?></button>
  </div>
</form>