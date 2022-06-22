<?php
  /**
   * Layout Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
	  
  if(!Auth::hasPrivileges('manage_layout')): print Message::msgError(Lang::$word->NOACCESS); return; endif;
?>

<div class="row half-gutters align-middle">
  <div class="column  mobile-100">
    <h3><?php echo Lang::$word->LMG_TITLE;?></h3>
  </div>
  <div class="columns shrink content-right mobile-100 mobile-content-left">
    <?php if($this->modulelist):?>
    <select name="mod_id" class="yoyo fluid small dropdown">
      <option value="0"><?php echo Lang::$word->LMG_SUB1;?></option>
      <?php echo Utility::loopOptions($this->modulelist, "id", "title" . Lang::$lang, Validator::get('mod_id'));?>
    </select>
    <?php endif;?>
  </div>
</div>
<div class="yoyo divider"></div>
<div class="yoyo<?php echo ($this->layoutlist->page or $this->layoutlist->mod) ? null : ' readonly';?>">
  <div class="row gutters">
    <div class="column">
      <div class="yoyo attached segment">
        <a data-section="top" class="yoyo small top left simple icon attached button pEdit"><i class="icon disabled apps"></i></a>
        <a data-section="top" class="yoyo small top right simple attached button pAdd"><?php echo Lang::$word->LMG_TOP;?>
          <i class="icon small chevron down"></i></a>
        <ol data-position="top" class="yoyo sortable celled simple">
          <?php if($topside = Utility::findInArray($this->layoutlist->row, "place", "top")):?>
          <?php foreach ($topside as $row): ?>
          <li class="item" data-id="<?php echo $row->plug_id;?>" id="item_<?php echo $row->plug_id;?>">
            <div class="handle"><i class="icon reorder"></i></div>
            <div class="content"><?php echo $row->title;?></div>
            <a class="actions"><i class="icon negative trash"></i></a>
          </li>
          <?php endforeach;?>
          <?php unset($row);?>
          <?php endif;?>
        </ol>
      </div>
    </div>
  </div>
  <div class="row gutters">
    <div class="column screen-40">
      <div class="yoyo attached segment"><a data-section="left" class="yoyo small top right simple basic attached button pAdd"><?php echo Lang::$word->LMG_LEFT;?>
          <i class="icon small chevron down"></i></a>
        <ol data-position="left" class="yoyo sortable celled simple">
          <?php if($leftlide = Utility::findInArray($this->layoutlist->row, "place", "left")):?>
          <?php foreach ($leftlide as $row): ?>
          <li class="item" data-id="<?php echo $row->plug_id;?>" id="item_<?php echo $row->plug_id;?>">
            <div class="handle"><i class="icon reorder"></i></div>
            <div class="content"><?php echo Validator::truncate($row->title, 40);?></div>
            <a class="actions"><i class="icon negative trash"></i></a>
          </li>
          <?php endforeach;?>
          <?php unset($row);?>
          <?php endif;?>
        </ol>
      </div>
    </div>
    <div class="column">
      <div class="yoyo attached segment secondary"><span class="yoyo small simple fluid button"><?php echo Lang::$word->LMG_MAIN;?></span></div>
    </div>
    <div class="column screen-40">
      <div class="yoyo attached segment"><a data-section="right" class="yoyo small top right simple basic attached button pAdd"><?php echo Lang::$word->LMG_RIGHT;?>
          <i class="icon small chevron down"></i></a>
        <ol data-position="right" class="yoyo sortable celled simple">
          <?php if($rightside = Utility::findInArray($this->layoutlist->row, "place", "right")):?>
          <?php foreach ($rightside as $row): ?>
          <li class="item" data-id="<?php echo $row->plug_id;?>" id="item_<?php echo $row->plug_id;?>">
            <div class="handle"><i class="icon reorder"></i></div>
            <div class="content"><?php echo Validator::truncate($row->title, 40);?></div>
            <a class="actions"><i class="icon negative trash"></i></a>
          </li>
          <?php endforeach;?>
          <?php unset($row);?>
          <?php endif;?>
        </ol>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="column">
      <div class="yoyo attached segment">
        <a data-section="bottom" class="yoyo small top left simple icon attached button pEdit"><i class="icon disabled apps"></i></a>
        <a data-section="bottom" class="yoyo small top right simple attached button pAdd"><?php echo Lang::$word->LMG_BOTTOM;?>
          <i class="icon small chevron down"></i></a>
        <ol data-position="bottom" class="yoyo sortable celled simple">
          <?php if($bottomside = Utility::findInArray($this->layoutlist->row, "place", "bottom")):?>
          <?php foreach ($bottomside as $row): ?>
          <li class="item" data-id="<?php echo $row->plug_id;?>" id="item_<?php echo $row->plug_id;?>">
            <div class="handle"><i class="icon reorder"></i></div>
            <div class="content"><?php echo $row->title;?></div>
            <a class="actions"><i class="icon negative trash"></i></a>
          </li>
          <?php endforeach;?>
          <?php unset($row);?>
          <?php endif;?>
        </ol>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo ADMINVIEW;?>/js/layout.js"></script>
<script type="text/javascript"> 
// <![CDATA[	
  $(document).ready(function() {
	  $.Layout({
		  url: "<?php echo ADMINVIEW;?>",
		  lurl: "<?php echo Url::url("/admin/layout");?>",
		  page_id:<?php echo $this->layoutlist->page;?>,
		  mod_id:[<?php echo json_encode($this->layoutlist->mod);?>],
		  type:'<?php echo $this->layoutlist->type;?>',
            lang: {
                edit: "<?php echo Lang::$word->EDIT;?>",
				delete: "<?php echo Lang::$word->DELETE;?>",
            }
	  });
  });
// ]]>
</script>