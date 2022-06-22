<?php
  /**
   * My Account
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<form method="post" id="yoyo_form" name="yoyo_form">
  <div class="row">
    <div class="mobile-100 columns mobile-order-2 padding">
      <div class="yoyo segment form">
        <div class="yoyo fields align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->M_FNAME;?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo $this->data->fname;?>" name="fname">
            </div>
          </div>
        </div>
        <div class="yoyo fields align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->M_LNAME;?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo $this->data->lname;?>" name="lname">
            </div>
          </div>
        </div>
        <div class="yoyo fields align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->M_EMAIL;?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo $this->data->email;?>" name="email">
            </div>
          </div>
        </div>
        <div class="yoyo fields disabled align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->CREATED;?></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo Date::doDate("short_date", $this->data->created);?>" readonly>
            </div>
          </div>
        </div>
        <div class="yoyo fields disabled align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->M_LASTLOGIN;?></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo Date::doDate("short_date", $this->data->lastlogin);?>">
            </div>
          </div>
        </div>
        <div class="yoyo fields disabled align-middle">
          <div class="field four wide labeled">
            <label class="content-right mobile-content-left"><?php echo Lang::$word->M_LASTIP;?></label>
          </div>
          <div class="field">
            <div class="yoyo fluid input">
              <input type="text" value="<?php echo $this->data->lastip;?>">
            </div>
          </div>
        </div>
        <div class="content-right">
          <button type="button" data-action="updateAccount" name="dosubmit" class="yoyo primary button"><?php echo Lang::$word->M_UPDATE;?></button>
        </div>
      </div>
    </div>
    <div class="shrink columns mobile-100 mobile-order-1">
      <div class="yoyo segment form">
        <div class="basic field">
          <input type="file" name="avatar" data-type="image" data-exist="<?php echo ($this->data->avatar) ? UPLOADURL . '/avatars/' . $this->data->avatar : UPLOADURL . '/avatars/blank.png';?>" accept="image/png, image/jpeg">
        </div>
      </div>
    </div>
  </div>
</form>