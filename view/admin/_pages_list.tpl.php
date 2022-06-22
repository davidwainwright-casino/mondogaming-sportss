<?php
  /**
   * Pages
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row half-gutters align-middle">
  <div class="column shrink mobile-100 mobile-order-1">
    <h3><?php echo Lang::$word->META_T8;?></h3>
  </div>
  <div class="columns content-right mobile-50 mobile-content-left mobile-order-2">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->PAG_SUB4;?></a>
  </div>
</div>
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
        <th data-sort="string"><?php echo Lang::$word->PAG_NAME;?></th>
        <th class="disabled center aligned"><?php echo Lang::$word->TYPE;?></th>
        <th class="disabled center aligned"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <?php foreach ($this->data as $row):?>
    <tr id="item_<?php echo $row->id;?>">
      <td class="collapsing"><span class="yoyo tiny simple label"><?php echo $row->id;?></span></td>
      <td><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="inverted">
          <?php echo $row->{'title' . Lang::$lang};?></a></td>
      <td class="collapsing center aligned"><?php if($row->page_type == "contact"):?>
        <i class="icon secondary email disabled"></i>
        <?php elseif($row->page_type == "home"):?>
        <i class="icon primary home disabled"></i>
        <?php else:?>
        <i class="icon file disabled"></i>
        <?php endif;?></td>
      <td class="collapsing"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="yoyo icon positive button"><i class="icon note"></i></a>
        <?php if($row->page_type == "normal"):?>
        <a data-set='{"option":[{"trash": "trashPage","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"trash","parent":"#item_<?php echo $row->id;?>"}' class="yoyo icon simple button action">
          <i class="icon negative trash"></i>
        </a>
        <?php else:?>
        <a class="yoyo icon circular basic disabled button">
          <i class="icon close"></i>
        </a>
        <?php endif;?></td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small light text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>