<?php
  /**
   * Language Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<h3><?php echo Lang::$word->LG_TITLE1;?></h3>
<p><?php echo Lang::$word->LG_SUB2;?></p>
<div class="yoyo space divider"></div>
<div class="row half-gutters align-middle">
  <div class="columns screen-50 mobile-100">
    <div class="yoyo fluid transparent right action icon input">
      <input id="filter" type="text" placeholder="<?php echo Lang::$word->SEARCH;?>">
      <i class="icon find"></i>
    </div>
  </div>
  <div class="columns screen-25 mobile-50">
    <select name="pgroup" id="pgroup" class="yoyo small fluid dropdown" data-abbr="<?php echo $this->row->abbr;?>">
      <option data-type="all" value="all"><?php echo Lang::$word->LG_SUB4;?></option>
      <?php foreach($this->sections as $rows):?>
      <option data-type="filter" value="<?php echo $rows;?>"><?php echo $rows;?></option>
      <?php endforeach;?>
      <?php unset($rows);?>
    </select>
  </div>
  <div class="columns screen-25 mobile-50">
    <select name="group" id="group" class="yoyo small fluid dropdown" data-abbr="<?php echo $this->row->abbr;?>">
      <option value="all"><?php echo Lang::$word->LG_SUB3;?></option>
      <optgroup label="<?php echo Lang::$word->PLUGINS;?>">
      <?php foreach($this->pluglang as $rows):?>
      <option data-type="plugins" value="<?php echo 'plugins' . '/' . basename($rows, '.lang.plugin.xml') .'.lang.plugin.xml';?>"><?php echo ucfirst(basename($rows, '.lang.plugin.xml'));?></option>
      <?php endforeach;?>
      <?php unset($rows);?>
      </optgroup>
      <optgroup label="<?php echo Lang::$word->MODULES;?>">
      <?php foreach($this->modlang as $rows):?>
      <option data-type="modules" value="<?php echo 'modules' . '/' . basename($rows, '.lang.module.xml') .'.lang.module.xml';?>"><?php echo ucfirst(basename($rows, '.lang.module.xml'));?></option>
      <?php endforeach;?>
      <?php unset($rows);?>
      </optgroup>
    </select>
  </div>
</div>
<div class="yoyo segment">
  <?php $i = 0;?>
  <div class="yoyo small divided flex list align-middle" id="editable">
    <?php foreach ($this->data as $pkey) :?>
    <?php $i++;?>
    <div class="item">
      <div class="content"><span data-editable="true" data-set='{"type": "phrase", "id": <?php echo $i;?>,"key":"<?php echo $pkey['data'];?>", "path":"<?php echo $this->row->abbr;?>/lang.xml"}'><?php echo $pkey;?></span></div>
      <div class="content shrink"><span class="yoyo tiny basic label"><?php echo $pkey['data'];?></span></div>
    </div>
    <?php endforeach;?>
  </div>
</div>