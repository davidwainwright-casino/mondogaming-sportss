<?php
  /**
   * Memberships
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo-grid">
  <h4>
    <?php echo Lang::$word->ADM_MEMBS;?>
  </h4>
  <p><?php echo Lang::$word->M_INFO13;?></p>
  <?php if($this->memberships):?>
  <div id="membershipSelect" class="yoyo cards screen-block-3 tablet-block-2 mobile-block-1 phone-block-1 align-center">
    <?php foreach($this->memberships as $row):?>
    <div class="card <?php echo Auth::$udata->membership_id == $row->id ? ' active' : null;?>" id="item_<?php echo $row->id;?>">
      <div class="content">
        <figure class="yoyo basic image margin-bottom">
          <?php if($row->thumb):?>
          <img src="<?php echo UPLOADURL;?>/memberships/<?php echo $row->thumb;?>" alt="">
          <?php else:?>
          <img src="<?php echo UPLOADURL;?>/memberships/default.png" alt="">
          <?php endif;?>
        </figure>
        <h5 class="yoyo primary text content-center">
          <?php echo Utility::formatMoney($row->price);?>
          <?php echo $row->{'title' . Lang::$lang};?>
        </h5>
        <div class="yoyo list">
          <div class="item">
            <?php echo Lang::$word->MEM_REC1;?>
            <?php echo ($row->recurring) ? Lang::$word->YES : Lang::$word->NO;?>
          </div>
          <div class="item">
            <?php echo $row->days;?> @<?php echo Date::getPeriodReadable($row->period);?>
          </div>
          <div class="item">
            <span class="yoyo tiny secondary text"><?php echo $row->{'description' . Lang::$lang};?></span>
          </div>
        </div>
      </div>
      <div class="footer">
        <?php if(Auth::$udata->membership_id != $row->id):?>
        <a class="yoyo fluid secondary button add-membership" data-id="<?php echo $row->id;?>"><?php echo ($row->price <> 0) ? Lang::$word->SELECT : Lang::$word->ACTIVATE;?></a>
        <?php endif;?>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <div id="mResult"></div>
  <?php endif;?>
</div>