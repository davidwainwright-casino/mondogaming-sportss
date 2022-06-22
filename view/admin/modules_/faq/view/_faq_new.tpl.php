<?php
  /**
   * F.A.Q.
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3 class="header"><?php echo Lang::$word->_MOD_FAQ_TITLE2;?></h3>
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
            <label><?php echo Lang::$word->_MOD_FAQ_QUESTION;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->_MOD_FAQ_QUESTION;?>" name="question_<?php echo $lang->abbr?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="field">
            <textarea class="altpost" name="answer_<?php echo $lang->abbr;?>"></textarea>
          </div>
        </div>
      </div>
      <?php endforeach;?>
      <div class="yoyo fields">
        <div class="field">
          <label><?php echo Lang::$word->CATEGORY;?> <i class="icon asterisk"></i></label>
          <select name="category_id" class="yoyo fluid dropdown">
            <?php echo Utility::loopOptions($this->categories, "id", "name" . Lang::$lang);?>
          </select>
        </div>
        <div class="field">
        </div>
      </div>
      
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/modules", "faq/");?>" class="yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-url="modules_/faq" data-action="processFaq" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->_MOD_FAQ_NEW;?></button>
  </div>
</form>