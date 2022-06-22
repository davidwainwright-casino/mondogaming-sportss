<?php
  /**
   * Routes
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  //Poll
  $router->mount('/poll', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/poll/@Poll@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/poll/@Poll@Edit');
	  $router->get('/new', '/view/admin/plugins_/poll/@Poll@Save');
  });
  
  //Slider
  $router->mount('/slider', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/slider/@Slider@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/slider/@Slider@Edit');
	  $router->get('/preview/(\d+)', '/view/admin/plugins_/slider/@Slider@Preview');
	  $router->get('/new', '/view/admin/plugins_/slider/@Slider@Save');
	  $router->get('/builder/(\d+)', '/view/admin/plugins_/slider/@Slider@Build');
  });
  
  //Rss
  $router->mount('/rss', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/rss/@Rss@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/rss/@Rss@Edit');
	  $router->get('/new', '/view/admin/plugins_/rss/@Rss@Save');
  });

  //Youtibe Player
  $router->mount('/yplayer', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/yplayer/@Yplayer@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/yplayer/@Yplayer@Edit');
	  $router->get('/new', '/view/admin/plugins_/yplayer/@Yplayer@Save');
  });

  //Carousel Player
  $router->mount('/carousel', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/carousel/@Carousel@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/carousel/@Carousel@Edit');
	  $router->get('/new', '/view/admin/plugins_/carousel/@Carousel@Save');
  });
  
  //Donate
  $router->mount('/donation', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/donation/@Donate@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/plugins_/donation/@Donate@Edit');
	  $router->get('/new', '/view/admin/plugins_/donation/@Donate@Save');
  });
  
  //Newsletter
  $router->mount('/newsletter', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/newsletter/@Newsletter@AdminIndex');
  });

  //Twitts
  $router->mount('/twitts', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/twitts/@Twitts@AdminIndex');
  });
  
  //Upcoming Event
  $router->mount('/upevent', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/plugins_/upevent/@UpEvent@AdminIndex');
  });