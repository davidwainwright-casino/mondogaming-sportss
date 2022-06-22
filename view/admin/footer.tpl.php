<?php
  /**
   * Footer
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
  <footer> Copyright &copy;<?php echo date('Y') . ' '. $this->core->company;?> <i class="icon middle yoyologo"></i> Powered by: CRIC International </footer>
  </div>
</main>
<!-- wrapper end -->
</div>
<!-- Footer -->
<?php Debug::displayInfo();?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/editor.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/alignment.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/definedlinks.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/fontcolor.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/fullscreen.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/imagemanager.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/video.js"></script> 
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/editor/wicons.js"></script> 
<script type="text/javascript" src="<?php echo ADMINVIEW;?>/js/master.js"></script> 
<script type="text/javascript"> 
// <![CDATA[  
<?php if($this->core->ploader):?>
$(window).on('load', function() {
	setTimeout(function() {
		$("body").addClass("loaded");
	}, 200);
});
<?php endif;?>
$(document).ready(function() {
    $.Master({
        weekstart: <?php echo($this->core->weekstart);?>,
		ampm: <?php echo ($this->core->time_format) == "HH:mm" ? 0 : 1;?>,
		url: "<?php echo ADMINVIEW;?>",
		surl: "<?php echo SITEURL;?>",
		editorCss: ["<?php echo FRONTVIEW . '/themes/' . $this->core->theme . '/cache/master_main.css';?>"],
        lang: {
            monthsFull: [ <?php echo Date::monthList(false);?> ],
            monthsShort: [ <?php echo Date::monthList(false, false);?> ],
            weeksFull: [ <?php echo Date::weekList(false); ?> ],
            weeksShort: [ <?php echo Date::weekList(false, false);?> ],
			weeksMed: [ <?php echo Date::weekList(false, false, true);?> ],
			selPic: "<?php echo Lang::$word->SELPIC;?>",
            today: "<?php echo Lang::$word->TODAY;?>",
			now: "<?php echo Lang::$word->NOW;?>",
            clear: "<?php echo Lang::$word->CLEAR;?>",
            delBtn: "<?php echo Lang::$word->DELETE_REC;?>",
			trsBtn: "<?php echo Lang::$word->MTOTRASH;?>",
			restBtn: "<?php echo Lang::$word->RFCOMPLETE;?>",
			canBtn: "<?php echo Lang::$word->CANCEL;?>",
			sellected: "<?php echo Lang::$word->SELECTED;?>",
			allBtn: "<?php echo Lang::$word->SELALL;?>",
			allSel: "<?php echo Lang::$word->ALLSELL;?>",
			sellOne: "<?php echo Lang::$word->SELECTMULTI;?>",
			doSearch: "<?php echo Lang::$word->SEARCH;?> ...",
			noMatch: "No matches for",
			ok: "<?php echo Lang::$word->OK;?>",
            delMsg1: "<?php echo Lang::$word->DELCONFIRM1;?>",
            delMsg2: "<?php echo Lang::$word->DELCONFIRM2;?>",
			delMsg3: "<?php echo Lang::$word->TRASH;?>",
			delMsg5: "<?php echo Lang::$word->DELCONFIRM4;?>",
			delMsg6: "<?php echo Lang::$word->DELCONFIRM6;?>",
			delMsg7: "<?php echo Lang::$word->DELCONFIRM10;?>",
			delMsg8: "<?php echo Lang::$word->DELCONFIRM3;?>",
            working: "<?php echo Lang::$word->WORKING;?>"
        }
    });
});
// ]]>
</script>
</body>
</html>