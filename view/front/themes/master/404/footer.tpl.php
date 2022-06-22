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
<!-- Footer -->
<footer>
  <div class="yoyo-grid">
    <div class="vertical-padding">
      <div class="row">
        <div class="columns phone-100"><span class="yoyo small secondary text">&copy; <?php echo date('Y') . ' '. $this->core->company;?> | Powered by YOYO Stack v.<?php echo $this->core->yoyov;?></span></div>
        <div class="columns phone-100">
          <div class="content-right">
            <a href="<?php echo SITEURL;?>" class="yoyo small simple icon secondary button"><i class="icon home"></i></a>
            <a href="//validator.w3.org/check/referer" target="_blank" class="yoyo small simple icon secondary button"><i class="icon html5"></i></a>
            <a href="<?php echo URl::url('/' . $this->core->system_slugs->sitemap[0]->{'slug' . Lang::$lang});?>" class="yoyo small simple icon secondary button"><i class="icon apps"></i></a>
            <a href="<?php echo SITEURL;?>/rss.php" class="yoyo small simple icon secondary button"><i class="icon rss"></i></a>
            <a href="//<?php echo $this->core->social->facebook;?>" class="yoyo small simple icon secondary button"><i class="icon facebook"></i></a>
            <a href="//<?php echo $this->core->social->twitter;?>" class="yoyo small simple icon secondary button"><i class="icon twitter"></i></a>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</footer>
<?php Debug::displayInfo();?>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
	$('.logo img').each(function() {
		var $img = $(this);
		var imgID = $img.attr('id');
		var imgClass = $img.attr('class');
		var imgURL = $img.attr('src');

		$.get(imgURL, function(data) {
			var $svg = $(data).find('svg');
			if (typeof imgID !== 'undefined') {
				$svg = $svg.attr('id', imgID);
			}
			if (typeof imgClass !== 'undefined') {
				$svg = $svg.attr('class', imgClass + ' replaced-svg');
			}
			$svg = $svg.removeAttr('xmlns:a');
			$img.replaceWith($svg);
		}, 'xml');
	});
});
// ]]>
</script>
</body></html>