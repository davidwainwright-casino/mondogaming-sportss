<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row horizontal-gutters align-bottom">
  <div class="columns">
    <h3><?php echo Lang::$word->_MOD_EM_TITLE;?></h3>
    <a href="<?php echo Url::url("/admin/modules/events", "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->_MOD_EM_SUB;?></a>
  </div>
  <div class="columns shrink">
    <a href="<?php echo Url::url("/admin/modules/events");?>" class="yoyo primary icon button"><i class="icon unordered list"></i></a>
    <a class="yoyo basic disabled icon button"><i class="icon grid"></i></a>
    <a href="<?php echo Url::url(Router::$path, "calendar/");?>" class="yoyo white circular icon button"><i class="icon calendar"></i></a>
  </div>
</div>
<div class="yoyo divider"></div>
<div class="half-top-padding">
  <div class="yoyo divided horizontal link list align-center">
    <div class="disabled item yoyo bold text">
      <?php echo Lang::$word->SORTING_O;?>
    </div>
    <a href="<?php echo Url::url(Router::$path);?>" class="item<?php echo Url::setActive("order", false);?>">
      <?php echo Lang::$word->RESET;?>
    </a>
    <a href="<?php echo Url::url(Router::$path, "?order=title|DESC");?>" class="item<?php echo Url::setActive("order", "title");?>">
      <?php echo Lang::$word->NAME;?>
    </a>
    <a href="<?php echo Url::url(Router::$path, "?order=venue|DESC");?>" class="item<?php echo Url::setActive("order", "venue");?>">
      <?php echo Lang::$word->_MOD_EM_SUB1;?>
    </a>
    <a href="<?php echo Url::url(Router::$path, "?order=contact|DESC");?>" class="item<?php echo Url::setActive("order", "contact");?>">
      <?php echo Lang::$word->_MOD_EM_SUB2;?>
    </a>
    <a href="<?php echo Url::url(Router::$path, "?order=ending|DESC");?>" class="item<?php echo Url::setActive("order", "ending");?>">
      <?php echo Lang::$word->_MOD_EM_SUB23;?>
    </a>
    <div class="item"><a href="<?php echo Url::sortItems(Url::url(Router::$path), "order");?>" data-content="ASC/DESC"><i class="icon triangle unfold more link"></i></a>
    </div>
  </div>
</div>
<div class="half-top-padding">
  <?php echo Validator::alphaBits(Url::url(Router::$path), "letter", "yoyo small bold text horizontal link divided list align-center");?>
</div>
<div class="yoyo space divider"></div>
<form method="get" id="yoyo_form" action="<?php echo Url::url(Router::$path);?>" name="yoyo_form">
  <div class="row align-center align-middle">
    <div class="column screen-40 phone-100">
      <div class="yoyo transparent fluid right icon input" id="fromdate">
        <input name="fromdate" type="text" placeholder="<?php echo Lang::$word->FROM;?>" readonly>
        <i class="icon calendar"></i>
      </div>
    </div>
    <div class="column screen-40 phone-100">
      <div class="yoyo transparent fluid left right icon input" id="enddate">
        <i class="calendar icon"></i>
        <input name="enddate" type="text" placeholder="<?php echo Lang::$word->TO;?>" readonly>
        <button id="doDates" class="yoyo white circular icon button"><i class="icon find"></i></button>
      </div>
    </div>
  </div>
</form>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->_MOD_EM_NOEVENTS;?></p>
</div>
<?php else:?>
<div class="yoyo big space divider"></div>
<div class="row screen-block-3 tablet-block-2 mobile-block-1 phone-block-1 gutters align-center">
  <?php foreach($this->data as $row):?>
  <div class="column" id="item_<?php echo $row->id;?>">
    <div class="yoyo attached card">
      <div class="grey header content-center">
        <p class="yoyo huge white light text"><?php echo Date::doDate("dd", $row->date_start);?></p>
        <p class="yoyo small white bold text"><?php echo Date::doDate("MMMM", $row->date_start);?>, <?php echo Date::doDate("yyyy", $row->date_start);?></p>
      </div>
      <div class="content">
        <h4><a href="<?php echo Url::url("/admin/modules/events/edit", $row->id);?>"><?php echo $row->title;?></a>
        </h4>
        <p><?php echo $row->venue;?></p>
        <p><?php echo $row->contact ? $row->contact : '-/-';?></p>
        <div class="yoyo horizontal small list">
          <div class="item">
            <div class="header">
              <span class="yoyo caps light text"><?php echo Lang::$word->_MOD_EM_TIME_S;?></span>
              <span class="yoyo caps text"><?php echo Date::doTime($row->time_start) . '/' . Date::doTime($row->time_end);?></span>
            </div>
          </div>
        </div>
        <div class="content-center">
          <a class="yoyo positive icon button" href="<?php echo Url::url("/admin/modules/events/edit", $row->id);?>"><i class="icon pencil"></i></a>
          <a data-set='{"option":[{"delete": "deleteEvent","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/events"}' class="yoyo negative icon button action">
            <i class="icon trash"></i></a>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small thick text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>