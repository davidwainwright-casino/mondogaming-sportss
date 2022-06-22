<?php
  /**
   * Profile
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<main class="margin-top">
  <div class="yoyo-grid">
    <h1 class="vertical-margin"><?php echo Lang::$word->META_T32;?></h1>
    <div class="row gutters">
      <div class="columns shrink mobile-30 phone-100">
        <div class="yoyo boxed card content-center">
          <figure class="yoyo basic medium circular image content-center"><img src="<?php echo UPLOADURL;?>/avatars/<?php echo $this->data->avatar ? $this->data->avatar : "blank.png" ;?>" alt=""></figure>
          <p class="half-top-margin"><?php echo Lang::$word->M_JOINED;?>: <?php echo Date::doDate("yyyy", $this->data->created);?></p>
        </div>
      </div>
      <div class="columns mobile-70 phone-100">
        <div class="padding">
          <h2><?php echo Lang::$word->M_SUB32;?>
            <span class="yoyo semi primary text"><?php echo $this->data->fname;?>
            <?php echo $this->data->lname;?></span></h2>
          <p><?php echo Lang::$word->M_LASTSEEN;?>: <?php echo Date::timesince($this->data->lastlogin);?></p>
          <div class="yoyo small secondary text"><?php echo $this->data->info;?></div>
          <div class="yoyo divider"></div>
          <a href="<?php echo $this->data->tw_link;?>" target="_blank" class="yoyo primary icon button"><i class="twitter icon"></i></a>
          <a href="<?php echo $this->data->fb_link;?>" target="_blank" class="yoyo primary icon button"><i class="facebook icon"></i></a>
          <a href="<?php echo $this->data->gp_link;?>" target="_blank" class="yoyo primary icon button"><i class="google plus icon"></i></a>
        </div>
      </div>
    </div>
  </div>
</main>