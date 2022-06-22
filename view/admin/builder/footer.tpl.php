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
<script type="text/javascript" src="<?php echo THEMEURL . '/plugins/cache/' . Cache::pluginJsCache(THEMEBASE . '/plugins');?>"></script>
<script type="text/javascript" src="<?php echo THEMEURL . '/modules/cache/' . Cache::moduleJsCache(THEMEBASE . '/modules');?>"></script>
<script src="<?php echo SITEURL;?>/assets/builder/builder.js" type="text/javascript"></script>
<script type="text/javascript">
  $(window).on('load', function() {
	  $.get("<?php echo ADMINVIEW;?>/helper.php", {
		  doAction: 1,
		  id: <?php echo $this->segments[3];?>,
		  page: "loadPage",
		  lang: "<?php echo $this->segments[2];?>"
	  }, function(result) {
		  var data = (result) ? result : '<div class="section"><div class="yoyo-grid"><div class="row gutters"><div class="columns is_empty"></div></div></div></div>';
		  $("#builderViewer").contents().find("body").html(data);
		  $("#builderViewer").contents().find("body")
			  .Builder({
				  url: "<?php echo ADMINVIEW;?>",
				  surl: "<?php echo SITEURL;?>",
				  burl: "<?php echo Url::builderUrl($this->core->theme);?>",
				  pagename: "<?php echo htmlspecialchars($this->data->{'title' . Lang::$lang});?>",
			  });
  
		  $("#builderViewer").contents().find('.yoyo.carousel').each(function() {
			  var set = $(this).data('wcarousel');
			  $(this).owlCarousel(set);
		  });

		  $("#builderViewer").contents().find('.yoyo.blocks').waitForImages(true).done(function() {
			  $("#builderViewer").contents().find('.yoyo.blocks').each(function() {
				  var set = $(this).data('wblocks');
				  $(this).pinto(set);
				  var $this = $(this);
				  setTimeout(function() {
					  $this.addClass("loaded");
				  }, 800);
				  
			  });
		  });
		
		  $("#builderViewer").contents().find('.wSlider').each(function() {
			  var set = $(this).data('wslider');
			  $(this).owlCarousel({
				  dots: set.buttons,
				  nav: set.arrows,
				  autoplay: set.autoplay,
				  autoplaySpeed: set.autoplaySpeed,
				  autoplayHoverPause: set.autoplayHoverPause,
				  margin: 0,
				  loop: set.autoloop,
				  "responsive": {
					  "0": {
						  "items": 1
					  },
					  "769": {
						  "items": 1
					  },
					  "1024": {
						  "items": 1
					  }
				  }
			  });
		  });
		  <?php if(App::Auth()->usertype == "editor"):?>
		  $("input[name='bmode']").trigger("change");
		  <?php endif;?>
	  }).always(function() {
		  setTimeout(function() {
			  $("body").addClass("loaded");
		  }, 200);
	  });
  });
</script>
<div id="tempData" class="hidden"></div>
<div id="undoData" class="hidden"></div>
<?php include("snippets/section_helper.tpl.php");?>
<?php include("snippets/style_helper.tpl.php");?>
<?php include("snippets/misc_helper.tpl.php");?>
<?php include("snippets/element_helper.tpl.php");?>
<?php include("snippets/animation_helper.tpl.php");?>
<?php //Debug::displayInfo();?>
</body></html>