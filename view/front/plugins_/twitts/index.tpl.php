<?php
  /**
   * Twitts
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(APLUGPATH . 'twitts/'));
?>
<?php if(App::Twitts()->username):?>
<!-- Start Latest Twitts -->
<?php $conf = Utility::findInArray($data['all'], "id", $data['id']);?>
<?php
  function twitterStyle($tweet)
  {	  
      echo '<div class="item"">';
      if (App::Twitts()->show_image) {
          echo '<div class="content shrink margin-right"><img src="' . $tweet['user']['profile_image_url'] . '" class="yoyo small circular basic image" alt=""/></div>';
      }
		echo '<div class="content">
				 <div class="summary"><a href="https://www.twitter.com/' . $tweet['user']['screen_name'] . '">#' . $tweet['user']['screen_name'] . '</a>
				   <div class="yoyo small text"><a href="' . $tweet['twitter_link'] . '" class="secondary">' . Date::doDate('long_date', $tweet['created_at']) . '</a></div>
				 </div>
				 <div class="extra text">' . $tweet['text'] . '</div>
				 ' . ($tweet['is_retweet'] ? '<div class="meta"> Retweeted by ' . $tweet['retweeter']['name'] . ' </div>' : '');
		echo '</div>
           </div>';
  }
?>
<div class="yoyo plugin segment <?php echo ($conf[0]->alt_class) ? ' ' . $conf[0]->alt_class : null;?>">
  <?php if($conf[0]->show_title):?>
  <h3 class="content-center"><?php echo $conf[0]->title;?></h3>
  <?php endif;?>
  <div class="yoyo flex list align-middle">
    <?php App::Twitts()->PrintFeed('twitterStyle');?>
  </div>
</div>
<?php endif;?>