<?php
  /**
   * AdBlock Plugin
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'adblock/'));
?>
<!-- Ad Block -->
<?php if($conf = Utility::findInArray($data['all'], "id", $data['id'])):?>
<div class="yoyo fitted plugin segment<?php echo ($conf[0]->alt_class) ? ' ' . $conf[0]->alt_class : null;?>">
  <?php if($conf[0]->show_title):?>
  <h3><?php echo $conf[0]->title;?></h3>
  <?php endif;?>
  <?php if($conf[0]->body):?>
  <?php echo Url::out_url($conf[0]->body);?>
  <?php endif;?>
  <?php if($row = Adblock::Render($data['plugin_id'])):?>
  <?php if(Adblock::isOnline($row)):?>
  <?php $href = (strpos($row->image_link,'http://') === 0) ? $row->image_link:'http://' . $row->image_link;?>
  <?php $ad_content = ($row->image) ? ('<a href="' . $href . '" id="b_' . $row->id . '" title="' . $row->image_alt . '"><img src="' . FPLUGINURL . $row->plugin_id . '/'  . $row->image . '" alt="' . $row->image_alt . '" class="yoyo basic rounded image" /></a>') : Validator::cleanOut($row->banner_html);?>
  <?php echo $ad_content;?>
  <?php Adblock::udateView($row->id);?>
  <?php endif;?>
  <?php endif;?>
</div>
<?php if($conf[0]->jscode):?>
<script><?php echo $conf[0]->jscode;?></script>
<?php endif;?>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function () {
    $("#b_<?php echo $row->id;?>").on('click', function() {
        $.post("<?php echo FMODULEURL . "adblock/controller.php";?>", {action:'click', id:"<?php echo $row->id;?>"});
   });
});
// ]]>
</script>
<?php endif;?>