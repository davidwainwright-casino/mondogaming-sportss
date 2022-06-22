<?php
  /**
   * Timeline
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="row half-gutters align-middle">
  <div class="column mobile-100 mobile-order-1">
    <h3><?php echo Lang::$word->_MOD_TML_SUB10;?>
      <span class="yoyo small text"> // <?php echo $this->row->name;?></span> </h3>
  </div>
  <div class="columns shrink mobile-50 mobile-content-left mobile-order-2">
    <a href="<?php echo Url::url("/admin/modules/timeline/inew", $this->row->id);?>" class="yoyo secondary button"><i class="icon plus alt"></i>
      <?php echo Lang::$word->_MOD_TML_SUB11;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->_MOD_TML_NOITM;?></p>
</div>
<?php else:?>
<div class="yoyo segment">
  <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string"><?php echo Lang::$word->TYPE;?></th>
        <th data-sort="string"><?php echo Lang::$word->NAME;?></th>
        <th data-sort="int"><?php echo Lang::$word->CREATED;?></th>
        <th class="disabled right aligned"><?php echo Lang::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <?php foreach ($this->data as $row):?>
    <tr id="item_<?php echo $row->id;?>">
      <td><span class="yoyo tiny label"><?php echo $row->type;?></span></td>
      <td><a href="<?php echo Url::url("/admin/modules/timeline/iedit", $this->row->id . '/' . $row->id);?>">
          <?php echo $row->{'title' . Lang::$lang};?></a></td>
      <td data-sort-value="<?php echo strtotime($row->created);?>"><?php echo Date::Dodate("short_date", $row->created);?></td>
      <td class="collapsing"><a href="<?php echo Url::url("/admin/modules/timeline/iedit", $this->row->id . '/' . $row->id);?>" class="yoyo icon positive button"><i class="icon note"></i></a>
        <a data-set='{"option":[{"delete": "deleteItem","title": "<?php echo Validator::sanitize($row->{'title' . Lang::$lang}, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","parent":"#item_<?php echo $row->id;?>","url":"modules_/timeline"}' class="yoyo icon simple button action">
          <i class="icon negative trash"></i></a></td>
    </tr>
    <?php endforeach;?>
  </table>
  </table>
</div>
<?php endif;?>
<div class="row half-gutters-mobile half-gutters-phone align-middle">
  <div class="columns shrink mobile-100 phone-100">
    <div class="yoyo small thick text"><?php echo Lang::$word->TOTAL.': '.$this->pager->items_total;?> / <?php echo Lang::$word->CURPAGE.': '.$this->pager->current_page.' '.Lang::$word->OF.' '.$this->pager->num_pages;?></div>
  </div>
  <div class="columns mobile-100 phone-100 content-right mobile-content-left"><?php echo $this->pager->display_pages('small');?></div>
</div>