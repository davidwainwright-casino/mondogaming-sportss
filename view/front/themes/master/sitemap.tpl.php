<?php
  /**
   * Sitemap
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<main class="margin-top">
  <?php if(File::is_File(FMODPATH . 'portfolio/_sitemap.tpl.php')):?>
  <?php include_once(FMODPATH . 'portfolio/_sitemap.tpl.php');?>
  <?php endif;?>
  <?php if(File::is_File(FMODPATH . 'digishop/_sitemap.tpl.php')):?>
  <?php include_once(FMODPATH . 'digishop/_sitemap.tpl.php');?>
  <?php endif;?>
  <?php if(File::is_File(FMODPATH . 'blog/_sitemap.tpl.php')):?>
  <?php include_once(FMODPATH . 'blog/_sitemap.tpl.php');?>
  <?php endif;?>
  <?php if(File::is_File(FMODPATH . 'shop/_sitemap.tpl.php')):?>
  <?php include_once(FMODPATH . 'shop/_sitemap.tpl.php');?>
  <?php endif;?>
  <div class="yoyo-grid">
    <h2><?php echo $this->data->{'title' . Lang::$lang};?></h2>
    <p><?php echo $this->data->{'caption' . Lang::$lang};?></p>
    <div class="row double-gutters">
      <div class="columns screen-50 tablet-50 mobile-100 phone-100">
        <h5><?php echo Lang::$word->ADM_PAGES;?> </h5>
        <?php if($this->pagedata):?>
        <!-- Page -->
        <div class="yoyo relaxed divided list">
          <?php foreach($this->pagedata as $row):?>
          <div class="item"><i class="icon small chevron right"></i>
            <div class="content"> <a href="<?php echo Url::url('/' . $this->core->pageslug, $row->slug);?>"><?php echo $row->title;?></a></div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>
        
        <!-- Portfolio -->
        <?php if($this->portadata):?>
        <h5><?php echo ucfirst($this->core->modname['portfolio']);?></h5>
        <!-- Page -->
        <div class="yoyo relaxed divided list">
          <?php foreach($this->portadata as $row):?>
          <div class="item"><i class="icon small chevron right"></i>
            <div class="content"> <a href="<?php echo Url::url('/' . $this->core->modname['portfolio'], $row->slug);?>"><?php echo $row->title;?></a></div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>
      </div>
      <div class="columns screen-50 tablet-50 mobile-100 phone-100"> 
        
        <!-- Blog -->
        <?php if($this->blogdata):?>
        <h5><?php echo ucfirst($this->core->modname['blog']);?></h5>
        <!-- Page -->
        <div class="yoyo relaxed divided list">
          <?php foreach($this->blogdata as $row):?>
          <div class="item"><i class="icon small chevron right"></i>
            <div class="content"> <a href="<?php echo Url::url('/' . $this->core->modname['blog'], $row->slug);?>"><?php echo $row->title;?></a></div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>
        
        <!-- Digishop -->
        <?php if($this->digidata):?>
        <h5><?php echo ucfirst($this->core->modname['digishop']);?></h5>
        <!-- Page -->
        <div class="yoyo relaxed divided list">
          <?php foreach($this->digidata as $row):?>
          <div class="item"><i class="icon small chevron right"></i>
            <div class="content"> <a href="<?php echo Url::url('/' . $this->core->modname['digishop'], $row->slug);?>"><?php echo $row->title;?></a></div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>

        
        <!-- Shop -->
        <?php if($this->shopdata):?>
        <h5><?php echo ucfirst($this->core->modname['shop']);?></h5>
        <!-- Page -->
        <div class="yoyo relaxed divided list">
          <?php foreach($this->shopdata as $row):?>
          <div class="item"><i class="icon small chevron right"></i>
            <div class="content"> <a href="<?php echo Url::url('/' . $this->core->modname['shop'], $row->slug);?>"><?php echo $row->title;?></a></div>
          </div>
          <?php endforeach;?>
          <?php unset($row);?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</main>