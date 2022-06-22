<?php
  /**
   * Carousel
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'carousel/'));
?>
<?php if($row = App::Carousel()->render($data['plugin_id'])):?>
<div class="yoyo carousel" data-wcarousel='<?php echo $row->settings;?>'>
  <?php echo Url::out_url($row->{'body' . Lang::$lang});?> 
</div>
<?php endif;?>