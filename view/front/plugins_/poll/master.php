<?php
  /**
   * Poll
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'poll/'));
?>
<!-- Poll -->
<?php if($conf = Utility::findInArray($data['all'], "id", $data['id'])):?>
<div class="yoyo poll plugin segment<?php echo ($conf[0]->alt_class) ? ' ' . $conf[0]->alt_class : null;?>">
  <?php if($conf[0]->show_title):?>
  <h3 class="content-center"><?php echo $conf[0]->title;?></h3>
  <?php endif;?>
  <?php if($conf[0]->body):?>
  <?php echo Url::out_url($conf[0]->body);?>
  <?php endif;?>
  <?php if($data = App::Poll()->Render($data['plugin_id'])):?>
  <?php $voted = Session::cookieExists("CMSPRO_voted", $conf[0]->plugin_id);?>
  <?php foreach($data as $rows):?>
  <div class="yoyo semi text half-bottom-padding content-center"><?php echo $rows->name;?></div>
  <div class="yoyo very relaxed flex list align-middle pollResult" style="display:<?php echo $voted == true ? 'block' : 'none';?>">
    <?php foreach($rows->opts as $i => $row):?>
    <?php $percent = Utility::doPercent($row->total, $rows->totals);?>
    <div class="item relative">
      <div class="content"><?php echo $row->value;?></div>
      <div class="content shrink"><span data-total-id="<?php echo $row->oid;?>" class="yoyo small basic label"><?php echo $row->total;?></span></div>
    </div>
    <div class="yoyo tiny fitted indicating progress" data-percent="<?php echo $percent;?>">
      <div class="bar"></div>
    </div>
    <?php endforeach;?>
  </div>
  <div class="yoyo very relaxed middle aligned divided selection list pollDisplay" style="display:<?php echo $voted == true ? 'none' : 'block';?>">
    <?php foreach($rows->opts as $i => $row):?>
    <div class="item dovote" data-poll='{"id":<?php echo $rows->id;?>,"oid":<?php echo $row->oid;?>,"total":<?php echo $row->total;?>}'> <i class="icon line chart"></i>
      <div class="content">
        <div class="header"><?php echo $row->value;?></div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
  <?php endforeach;?>
  <div class="content-center half-top-padding hide-all goBack"> <a class="yoyo small bold text pollBack"><?php echo Lang::$word->BACK;?></a> </div>
  <div class="content-center half-top-padding goFront" style="display:<?php echo $voted == true ? 'none' : 'block';?>"> <a class="yoyo small primary button pollVote"><?php echo Lang::$word->_PLG_PL_VOTE;?></a> <a class="yoyo small semi text pollView"><?php echo Lang::$word->_PLG_PL_RESULT;?></a> </div>
  <?php endif;?>
</div>
<?php endif;?>