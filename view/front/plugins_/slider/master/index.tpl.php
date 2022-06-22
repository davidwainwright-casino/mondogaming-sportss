<?php
  /**
   * Slider
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'slider/'));
?>
<?php if($conf = App::Slider()->Render($data['plugin_id'])) :?>
<!-- Start Slider -->
<?php $data = App::Slider()->getSlides($conf->id);?>
<?php if($data):?>
<?php $conf->transition = 0;?>
<?php $conf->slidesEaseIn = 0;?>
<?php $conf->slidesEaseIn = 0;?>
<?php $conf->slidesTime = 0;?>
<div class="wSlider <?php echo $conf->layout;?>" data-wslider='<?php echo $conf->settings;?>'>
    <?php foreach($data as $row):?>
    <div class="holder" style="
    <?php if($row->mode == "bg"):?>
        background-position: top center; 
        background-repeat: no-repeat; 
        background-size: cover;
		background-image: url(<?php echo UPLOADURL . '/' . $row->image;?>);
    <?php elseif($row->mode == "tr"):?>
    background-color: transparent; 
    <?php else:?>
        background-color: <?php echo $row->color;?>; 
    <?php endif;?>
    min-height:<?php echo ($conf->height == 100) ? $conf->height . 'vh' : $conf->height . '0px';?>"
		data-in="<?php echo $conf->transition;?>"
		data-ease-in="<?php echo $conf->slidesEaseIn;?>"
		data-out="fade"
		data-ease-out="<?php echo $conf->slidesEaseIn;?>"
        data-time="<?php echo $conf->slidesTime;?>"
    >
      <div class="inner <?php echo $row->attrib;?>" style="min-height:<?php echo ($conf->height == 100) ? $conf->height . 'vh' : $conf->height . '0px';?>"><?php echo Url::out_url($row->html);?></div>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
<?php endif;?>