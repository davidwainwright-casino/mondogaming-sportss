<?php
  /**
   * Timeline Manager
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'timeline/'));
?>
<?php if(isset($data['id'])):?>
<?php if($row = App::Timeline()->render($data['id'])):?>
<div id="timeline"></div>
<script type="text/javascript">
  $(document).ready(function() {
	  var timeline_data = [];
	  var rss_feed = '<?php echo $row->rssurl;?>';
	  var feed_num = <?php echo $row->limiter;?>;

	  // Get Rss Feed via Google Feed API
	  $.getJSON('https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&num=' + feed_num + '&q=' + rss_feed + '&callback=?', function(data) {
		  if (data && data.responseData && data.responseData.feed && data.responseData.feed.entries) {
			  $(data.responseData.feed.entries).each(function(index, entry) {
				  var date = entry.publishedDate.split(' ');

				  timeline_data.push({
					  type: 'blog_post',
					  date: date[3],
					  dateDisplay: entry.publishedDate,
					  title: entry.title,
					  content: entry.contentSnippet + '<div align="right"><a href="' + entry.link + '"><?php echo Lang::$word->_MOD_TML_SUB18;?> <i class="icon angle right"></i></a></div>'
				  });
			  });

			  var timeline = new Timeline($('#timeline'), timeline_data);
			  timeline.setOptions({
				  animation: true,
				  lightbox: true,
				  separator: 'year',
				  columnMode: '<?php echo $row->colmode;?>',
				  order: 'desc'
			  });
			  timeline.display();
		  }
	  });
  });
</script>
<?php endif;?>
<?php endif;?>