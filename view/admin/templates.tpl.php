<?php
  /**
   * Email Templates
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_email')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>
<?php switch(Url::segment($this->segments)): case "edit": ?>
<!-- Start edit -->
<h3 class="header"><?php echo Lang::$word->META_T11;?></h3>
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
          <div class="field five wide">
            <label><?php echo Lang::$word->NAME;?>
              <i class="icon asterisk"></i></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->{'name_' . $lang->abbr};?>" name="name_<?php echo $lang->abbr?>">
            </div>
          </div>
          <div class="field five wide">
            <label><?php echo Lang::$word->DESCRIPTION;?></label>
            <div class="yoyo huge fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->DESCRIPTION;?>" value="<?php echo $this->data->{'subject_' . $lang->abbr};?>" name="subject_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields">
          <div class="basic field">
            <textarea class="bodypost" name="body_<?php echo $lang->abbr;?>"><?php echo str_replace("[SITEURL]", SITEURL, $this->data->{'body_' . $lang->abbr});?></textarea>
            <div class="yoyo space divider"></div>
            <div class="row align-middle"><i class="column shrink icon negative info sign"></i>
              <p class="column yoyo small negative text half-left-padding">
                <?php echo Lang::$word->NOTEVAR;?></p>
            </div>
          </div>
        </div>
        <div class="yoyo divider"></div>
        <div class="yoyo fields">
          <div class="field">
            <label><?php echo Lang::$word->ET_DESC;?></label>
            <div class="yoyo small fluid input">
              <input type="text" placeholder="<?php echo Lang::$word->ET_DESC;?>" value="<?php echo $this->data->{'help_' . $lang->abbr};?>" name="help_<?php echo $lang->abbr;?>">
            </div>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
  <div class="content-center">
    <a href="<?php echo Url::url("/admin/templates");?>" class="yoyo yoyo simple small button"><?php echo Lang::$word->CANCEL;?></a>
    <button type="button" data-action="processTemplate" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->ET_UPDATE;?></button>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<?php break;?>
<?php default: ?>
<h3><?php echo Lang::$word->ET_TITLE;?></h3>
<p class="yoyo small text"><?php echo Lang::$word->ET_SUB;?></p>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small thick caps text"><?php echo Lang::$word->ET_INFO;?></p>
</div>
<?php else:?>
<div class="yoyo segment">
  <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th class="disabled center aligned"></th>
        <th data-sort="string"><?php echo Lang::$word->ET_NAME;?></th>
        <th data-sort="string"><?php echo Lang::$word->ET_SUBJECT;?></th>
        <th class="disabled center aligned"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <?php foreach ($this->data as $row):?>
    <tr id="item_<?php echo $row->id;?>">
      <td class="collapsing"><span class="yoyo tiny basic label"><?php echo $row->id;?></span></td>
      <td><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="inverted">
          <?php echo $row->{'name' . Lang::$lang};?></a></td>
      <td><?php echo $row->{'subject' . Lang::$lang};?></td>
      <td class="collapsing"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="yoyo icon positive button"><i class="icon note"></i></a></td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
<?php break;?>
<?php endswitch;?>