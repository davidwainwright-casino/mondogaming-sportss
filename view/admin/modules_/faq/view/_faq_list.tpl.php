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
<div class="row gutters align-middle">
  <div class="column shrink mobile-100 phone-100">
    <h3><?php echo Lang::$word->_MOD_FAQ_TITLE;?></h3>
  </div>
  <div class="columns content-right mobile-50 mobile-content-left phone-100">
    <a href="<?php echo Url::url(Router::$path, "new/");?>" class="yoyo secondary button"><i class="icon plus alt"></i><?php echo Lang::$word->_MOD_FAQ_NEW;?></a>
    <a href="<?php echo Url::url(Router::$path, "categories/");?>" class="yoyo primary button"><i class="icon unordered list"></i>
      <?php echo Lang::$word->CATEGORIES;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="content-center"><img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="yoyo small bold caps text"><?php echo Lang::$word->_MOD_FAQ_NOFAQS;?></p>
</div>
<?php else:?>
<div class="yoyo blocks">
  <?php foreach($this->data as $cat):?>
  <div class="item">
    <div class="yoyo card attached">
      <div class="content">
        <h4>
          <?php echo $cat['name'];?>
        </h4>
        <div class="yoyo relaxed divided flex list align-middle sortable_faq">
          <?php foreach ($cat['items'] as $row) :?>
          <div class="item" id="item_<?php echo $row['id'];?>" data-id="<?php echo $row['id'];?>">
            <div class="content shrink half-right-padding">
              <div class="yoyo simple small icon button draggable"><i class="icon reorder"></i></div>
            </div>
            <div class="content">
            <a href="<?php echo Url::url(Router::$path . '/edit', $row['id']);?>"><?php echo $row['question'];?></a> 
            </div>
            <div class="content shrink half-left-padding"><a data-set='{"option":[{"delete": "deleteFaq","title": "<?php echo Validator::sanitize($row['question'], "chars");?>","id":<?php echo $row['id'];?>}],"action":"delete","parent":"#item_<?php echo $row['id'];?>","url":"modules_/faq"}' class="yoyo small negative icon button action"><i class="icon trash"></i></a>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>