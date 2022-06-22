<?php
  /**
   * Membership Error
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo-grid">
  <div class="vertical-padding">
    <div class="yoyo soft shadow segment negative bg">
      <div class="row">
        <div class="columns shrink margin-right"><i class="icon big white lock"></i></div>
        <div class="columns">
          <h1 class="yoyo white basic text"><?php echo Lang::$word->FRT_MERROR;?></h1>
        </div>
      </div>
    </div>
    <p><?php echo Lang::$word->FRT_MERROR_2;?></p>
    <?php if($data):?>
    <ul class="yoyo list">
      <?php foreach ($data as $row):?>
      <li><?php echo $row->title;?></li>
      <?php endforeach;?>
    </ul>
    <?php endif;?>
  </div>
</div>