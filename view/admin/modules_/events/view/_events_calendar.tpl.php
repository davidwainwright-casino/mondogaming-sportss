<?php
  /**
   * Events
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
?>
<div class="yoyo card">
  <div class="header">
    <div class="row align-middle">
      <div class="column content-center">
        <div id="calnav" class="yoyo small white buttons">
          <div id="prev" class="yoyo icon button"><i class="icon chevron left"></i></div>
          <div id="now" class="yoyo button"> <?php echo Date::doDate("MMMM yyyy", Date::today());?> </div>
          <div id="next" class="yoyo icon button"><i class="icon chevron right"></i></div>
        </div>
      </div>
    </div>
  </div>
  <div class="yoyo form calendar" id="mCalendar"></div>
</div>
<script src="<?php echo AMODULEURL;?>events/view/js/events.js"></script> 
<script type="text/javascript"> 
// <![CDATA[	
$(document).ready(function() {
    $('#mCalendar').Calendar({
        url: "<?php echo AMODULEURL;?>events/controller.php",
		murl: "<?php echo URL::url("admin/modules/events");?>",
        weekStart: <?php echo App::Core()->weekstart;?>,
		ampm: <?php echo (App::Core()->time_format) == "HH:mm" ? 1 : 0;?>,
        lang: {
            dayNames: [<?php echo Date::weekList(false, false);?>],
            monthNames: [<?php echo Date::monthList(false);?>],
        }
    });
});
// ]]>
</script>