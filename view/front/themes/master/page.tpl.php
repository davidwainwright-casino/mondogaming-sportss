<?php
  /**
   * Page
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<?php if($this->data->show_header):?>
<!-- Page Caption & breadcrumbs-->
<?php $str = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $last = explode("/",$str,3);?>
<div class="<?php echo str_replace('/','', $last[2]);?>" id="pageCaption"<?php echo Content::pageHeading();?>>
  <div class="yoyo-grid">
    <div class="row gutters">
      <div class="columns screen-100 tablet-100 mobile-100 phone-100 phone-content-center">
        <?php if($this->data->{'caption' . Lang::$lang}):?>
        <h1><?php echo $this->data->{'caption' . Lang::$lang};?></h1>
        <?php endif;?>
      </div>
      <?php if($this->core->showcrumbs):?>
      <div class="columns screen-100 tablet-100 mobile-100 phone-100 content-right  phone-content-left align-self-bottom">
        <div class="yoyo small white breadcrumb">
          <?php echo Url::crumbs($this->crumbs ? $this->crumbs : $this->segments, "/", Lang::$word->HOME);?>
        </div>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php endif;?>

<!-- Page Content-->
<main<?php echo Content::pageBg();?> id="<?php echo str_replace('/','', $last[2]);?>">
  <!-- Validate page access-->
  <?php if(Content::validatePage()):?>
  <!-- Run page-->
  <?php echo Content::parseContentData($this->data->{'body' . Lang::$lang});?>
  
  <!-- Parse javascript -->
  <?php if ($this->data->jscode):?>
  <?php echo Validator::cleanOut(json_decode($this->data->jscode));?>
  <?php endif;?>
  <?php endif;?>
  <?php if($this->data->is_comments):?>
  <?php include_once(FMODPATH . 'comments/index.tpl.php');?>
  <?php endif;?>
</main>