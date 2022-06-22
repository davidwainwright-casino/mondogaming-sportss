<?php
  /**
   * F.A.Q. Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'faq/'));
  $data = App::Faq()->Render();
  $cats = App::Faq()->categoryTree();
?>
<!-- Start F.A.Q. Manager -->
<div class="content-center">
  <h1 class="yoyo primary text"><?php echo Lang::$word->_MOD_FAQ_SUB;?></h1>
  <p><?php echo Lang::$word->_MOD_FAQ_INFO;?></p>
</div>
<div class="yoyo divider"></div>
<?php if($data):?>
<div class="row gutters">
  <div class="columns relative screen-20 tablet-20 mobile-hide phone-hide">
    <?php if($cats):?>
    <div class="yoyo sticky" data-sticky='{"pushing": false, "context":"#context","observeChanges":true,"offset":100,"bottomOffset":10}'>
      <div class="yoyo primary basic card">
        <div class="padding">
          <div class="yoyo very relaxed list">
            <?php foreach($cats as $crow):?>
            <div class="item">
              <a href="#cat_<?php echo $crow->{'name' . Lang::$lang};?>" class="white" data-scroll="true" data-offset="150"><?php echo $crow->{'name' . Lang::$lang};?></a>
            </div>
            <?php endforeach;?>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
  </div>
  <div class="columns screen-80 tablet-80 mobile-100 phone-100" id="context">
    <?php foreach($data as $cat):?>
    <h3 class="yoyo primary semi text vertical-margin">
      <?php echo $cat['name'];?>
    </h3>
    <?php foreach ($cat['items'] as $row) :?>
    <div class="yoyo accordion" data-accordion-options='{"openSingle": true}' id="cat_<?php echo $cat['name'];?>">
      <div class="header"><span><?php echo $row['question'];?></span>
        <i class="icon angle down"></i></div>
      <div class="content">
        <?php echo $row['answer'];?>
      </div>
    </div>
    <?php endforeach;?>
    <?php endforeach;?>
  </div>
</div>
<?php else:?>
<?php echo Message::msgSingleInfo(Lang::$word->_MOD_FAQ_NOFAQF);?>
<?php endif;?>