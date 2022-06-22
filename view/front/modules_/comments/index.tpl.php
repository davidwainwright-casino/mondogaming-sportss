<?php
  /**
   * Comments
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'comments/'));
  
  $pager = Paginator::instance();
  $conf = App::Comments();
  $comments = Comments::Render($this->segments[0], isset($this->data->id) ? $this->data->id : $this->row->id);
?>
<div class="yoyo-grid" id="comments">
  <h3><?php echo ($pager->items_total) ? $pager->items_total . ' ' . Lang::$word->COMMENTS : Lang::$word->_MOD_CM_SUB;?></h3>
  <?php echo $comments;?>
  <div class="padding content-center">
    <?php echo $pager->display_pages();?>
  </div>
  <?php if($conf->public_access or App::Auth()->logged_in):?>
  <?php include(FMODPATH . 'comments/snippets/form.tpl.php');?>
  <?php else:?>
  <?php echo Message::msgSingleAlert(Lang::$word->_MOD_CM_SUB1);?>
  <?php endif;?>
</div>