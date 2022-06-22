<?php
  /**
   * Event Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'events/'));
?>
<!-- Start Event Manager -->
<div class="content-center">
  <h1><?php echo Lang::$word->_MOD_EM_TITLE3;?></h1>
  <p><?php echo str_replace("[YEAR]", Date::doDate("yyyy", Date::today()), Lang::$word->_MOD_EM_SUB3);?></p>
</div>
<?php if($data = Utility::groupToLoop(App::Events()->render(), "month")):?>
<?php foreach($data as $date => $rows):?>
<div class="wojo demi primary caps text">
  <?php echo Date::doDate("MMMM YYYY", $date);?></div>
<div class="wojo divided feed">
  <?php foreach($rows as $row):?>
  <div class="wojo event">
    <div class="label">
      <div class="wojo attached card content-center" style="background:<?php echo $row->color;?>;color:#fff;opacity:.8">
        <span class="wojo white big semi text"><?php echo Date::doDate("dd", $row->date_start);?></span>
        <p class="wojo white text"><?php echo Date::doDate("MMM", $row->date_start);?></p>
      </div>
    </div>
    <div class="content">
      <div class="summary">
        <?php echo $row->title;?>
        <div class="date">
          <i class="icon clock"></i>
          <?php echo $row->time_start;?> - <?php echo $row->time_end;?></div>
      </div>
      <div class="extra text">
        <?php echo Url::out_url($row->body);?>
      </div>
      <div class="meta">
        <div class="wojo small horizontal list">
          <div class="item">
            <i class="icon primary calendar"></i>
            <?php echo Date::doDate("short_date", $row->date_start);?>
            <?php if($row->date_end > $row->date_start):?>
            - <?php echo Date::doDate("short_date", $row->date_end);?>
            <?php endif;?>
          </div>
          <?php if($row->venue):?>
          <div class="item">
            <i class="icon primary marker"></i>
            <?php echo $row->venue;?>
          </div>
          <?php endif;?>
          <?php if($row->contact_phone):?>
          <div class="item">
            <i class="icon primary phone"></i>
            <?php echo $row->contact_phone;?>
          </div>
          <?php endif;?>
          <?php if($row->contact_person):?>
          <div class="item">
            <i class="icon primary user"></i>
            <?php echo $row->contact_person;?>
          </div>
          <?php endif;?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endforeach;?>
<?php else:?>
<?php echo Message::msgSingleInfo(Lang::$word->_MOD_EM_NOEVENTSF);?>
<?php endif;?>
