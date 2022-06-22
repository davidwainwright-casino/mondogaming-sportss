<?php
  /**
   * Load Database Backup
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="item">
  <div class="content"> <span class="yoyo tiny bold text half-right-padding">-1.</span> <?php echo str_replace(".sql", "", $this->backup);?></div>
  <div class="content shrink"><span class="yoyo tiny basic label"><?php echo File::getFileSize($this->dbdir . '/' . $this->backup, "kb", true);?></span> 
  <a href="<?php echo UPLOADURL . '/backups/' . $this->backup;?>" data-content="<?php echo Lang::$word->DOWNLOAD;?>" class="yoyo icon circular positive small button"><i class="download icon link"></i></a> 
  <a data-set='{"option":[{"restore": "restoreBackup","title": "<?php echo $this->backup;?>","id":1}],"action":"restore","parent":".item"}' class="yoyo icon circular small primary button action"> <i class="refresh icon link"></i></a> 
  <a data-set='{"option":[{"delete": "deleteBackup","title": "<?php echo $this->backup;?>","id":1}],"action":"delete","parent":".item"}' class="yoyo icon circular small negative button action"> <i class="icon trash"></i> </a> </div>
</div>