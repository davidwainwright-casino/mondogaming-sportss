<?php
  /**
   * Routers
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  //Gallery
  $router->mount('/' . $core->modname['gallery'], function() use ($core, $router, $tpl) {
    $router->get('/', '/view/admin/modules_/gallery/@Gallery@FrontIndex');
	$router->get('/' . $core->modname['gallery-album'] . '/([a-z0-9_-]+)', '/view/admin/modules_/gallery/@Gallery@Render');

  });
  
  //Blog
  if(File::is_File(FMODPATH . 'blog/index.tpl.php')) {
	  $router->mount('/' . $core->modname['blog'], function() use ($core, $router, $tpl) {
		$router->get('/', '/view/admin/modules_/blog/@Blog@FrontIndex');
		$router->get('/' . $core->modname['blog-cat'] . '/([a-z0-9_-]+)', '/view/admin/modules_/blog/@Blog@Category');
		$router->get('/' . $core->modname['blog-archive'] . '/([0-9]+)-([0-9]+)', '/view/admin/modules_/blog/@Blog@Archive');
		$router->get('/' . $core->modname['blog-tag'] . '/([a-z0-9_-]+)', '/view/admin/modules_/blog/@Blog@Tags');
		$router->get('/([a-z0-9_-]+)', '/view/admin/modules_/blog/@Blog@Render');
	  });
  }
  
  //Portfolio
  if(File::is_File(FMODPATH . 'portfolio/index.tpl.php')) {
	  $router->mount('/' . $core->modname['portfolio'], function() use ($core, $router, $tpl) {
		$router->get('/', '/view/admin/modules_/portfolio/@Portfolio@FrontIndex');
		$router->get('/' . $core->modname['portfolio-cat'] . '/([a-z0-9_-]+)', '/view/admin/modules_/portfolio/@Portfolio@Category');
		$router->get('/([a-z0-9_-]+)', '/view/admin/modules_/portfolio/@Portfolio@Render');
	  });
  }
  
  //Digishop
  if(File::is_File(FMODPATH . 'digishop/index.tpl.php')) {
	  $router->mount('/' . $core->modname['digishop'], function() use ($core, $router, $tpl) {
		$router->get('/', '/view/admin/modules_/digishop/@Digishop@FrontIndex');
		$router->get('/' . $core->modname['digishop-checkout'], '/view/admin/modules_/digishop/@Digishop@Checkout');
		$router->get('/' . $core->modname['digishop-cat'] . '/([a-z0-9_-]+)', '/view/admin/modules_/digishop/@Digishop@Category');
		$router->get('/([a-z0-9_-]+)', '/view/admin/modules_/digishop/@Digishop@Render');
	  });
	  
	  //Digishop history
	  $router->get('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang} . '/digishop', '/view/admin/modules_/digishop/@Digishop@userHistory');
  }
  
  //Shop
  if(File::is_File(FMODPATH . 'shop/index.tpl.php')) {
	  $router->mount('/' . $core->modname['shop'], function() use ($core, $router, $tpl) {
		$router->get('/', '/view/admin/modules_/shop/@Shop@FrontIndex');
		$router->get('/' . $core->modname['shop-checkout'], '/view/admin/modules_/shop/@Shop@Checkout');
		$router->get('/' . $core->modname['shop-cart'], '/view/admin/modules_/shop/@Shop@Cart');
		$router->get('/' . $core->modname['shop-cat'] . '/([a-z0-9_-]+)', '/view/admin/modules_/shop/@Shop@Category');
		$router->get('/([a-z0-9_-]+)', '/view/admin/modules_/shop/@Shop@Render');
	  });
	  
	  //Shop history
	  $router->get('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang} . '/shop', '/view/admin/modules_/shop/@Shop@userHistory');
	  //Shop wishlist
	  $router->get('/' . $core->system_slugs->account[0]->{'slug' . Lang::$lang} . '/shop/wishlist', '/view/admin/modules_/shop/@Shop@userWishlist');
  }