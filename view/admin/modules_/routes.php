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
  $router->mount('/gallery', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/gallery/@Gallery@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/gallery/@Gallery@Edit');
	  $router->get('/photos/(\d+)', '/view/admin/modules_/gallery/@Gallery@Photos');
	  $router->get('/new', '/view/admin/modules_/gallery/@Gallery@Save');
  });

  //Events
  $router->mount('/events', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/events/@Events@AdminIndex');
	  $router->get('/grid', '/view/admin/modules_/events/@Events@AdminIndex');
	  $router->get('/calendar', '/view/admin/modules_/events/@Events@Calendar');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/events/@Events@Edit');
	  $router->get('/new', '/view/admin/modules_/events/@Events@Save');
  });

  //Faq
  $router->mount('/faq', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/faq/@Faq@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/faq/@Faq@Edit');
	  $router->get('/new', '/view/admin/modules_/faq/@Faq@Save');
	  $router->get('/categories', '/view/admin/modules_/faq/@Faq@CategorySave');
	  $router->get('/category/(\d+)', '/view/admin/modules_/faq/@Faq@CategoryEdit');
  });
  
  //Adblock
  $router->mount('/adblock', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/adblock/@Adblock@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/adblock/@Adblock@Edit');
	  $router->get('/new', '/view/admin/modules_/adblock/@Adblock@Save');
  });

  //Gmaps
  $router->mount('/gmaps', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/gmaps/@Gmaps@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/gmaps/@Gmaps@Edit');
	  $router->get('/new', '/view/admin/modules_/gmaps/@Gmaps@Save');
  });
  
  //Comments
  $router->mount('/comments', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/comments/@Comments@AdminIndex');
	  $router->get('/settings', '/view/admin/modules_/comments/@Comments@Settings');
  });

  //Timeline
  $router->mount('/timeline', function() use ($router, $tpl) {
	  $router->get('/', '/view/admin/modules_/timeline/@Timeline@AdminIndex');
	  $router->get('/edit/(\d+)', '/view/admin/modules_/timeline/@Timeline@Edit');
	  $router->get('/new', '/view/admin/modules_/timeline/@Timeline@Save');
	  $router->get('/items/(\d+)', '/view/admin/modules_/timeline/@Timeline@CustomItems');
	  $router->get('/inew/(\d+)', '/view/admin/modules_/timeline/@Timeline@CustomSave');
	  $router->get('/iedit/(\d+)/(\d+)', '/view/admin/modules_/timeline/@Timeline@CustomEdit');
  });
  
  //Digishop
    if(File::is_File(FMODPATH . 'digishop/index.tpl.php')) {
	  $router->mount('/digishop', function() use ($router, $tpl) {
		  $router->get('/', '/view/admin/modules_/digishop/@Digishop@AdminIndex');
		  $router->get('list/', '/view/admin/modules_/digishop/@Digishop@AdminIndex');
		  $router->get('/edit/(\d+)', '/view/admin/modules_/digishop/@Digishop@Edit');
		  $router->get('/new', '/view/admin/modules_/digishop/@Digishop@Save');
		  $router->get('/settings', '/view/admin/modules_/digishop/@Digishop@Settings');
		  $router->get('/history/(\d+)', '/view/admin/modules_/digishop/@Digishop@History');
		  $router->get('/categories', '/view/admin/modules_/digishop/@Digishop@CategorySave');
		  $router->get('/category/(\d+)', '/view/admin/modules_/digishop/@Digishop@CategoryEdit');
		  $router->get('/payments', '/view/admin/modules_/digishop/@Digishop@Payments');
	  });
	}
  
  //Portfolio
    if(File::is_File(FMODPATH . 'portfolio/index.tpl.php')) {
	  $router->mount('/portfolio', function() use ($router, $tpl) {
		  $router->get('/', '/view/admin/modules_/portfolio/@Portfolio@AdminIndex');
		  $router->get('list/', '/view/admin/modules_/portfolio/@Portfolio@AdminIndex');
		  $router->get('/edit/(\d+)', '/view/admin/modules_/portfolio/@Portfolio@Edit');
		  $router->get('/new', '/view/admin/modules_/portfolio/@Portfolio@Save');
		  $router->get('/settings', '/view/admin/modules_/portfolio/@Portfolio@Settings');
		  $router->get('/categories', '/view/admin/modules_/portfolio/@Portfolio@CategorySave');
		  $router->get('/category/(\d+)', '/view/admin/modules_/portfolio/@Portfolio@CategoryEdit');
	  });
	}

  //Forms
  if(File::is_File(FMODPATH . 'forms/index.tpl.php')) {
	  $router->mount('/forms', function() use ($router, $tpl) {
		  $router->get('/', '/view/admin/modules_/forms/@Forms@AdminIndex');
		  $router->get('/edit/(\d+)', '/view/admin/modules_/forms/@Forms@Edit');
		  $router->get('/new', '/view/admin/modules_/forms/@Forms@Save');
		  $router->get('/design/(\d+)', '/view/admin/modules_/forms/@Forms@Design');
		  $router->get('/view/(\d+)', '/view/admin/modules_/forms/@Forms@View');
	  });
  }
  
  //Blog
  if(File::is_File(FMODPATH . 'blog/index.tpl.php')) {
	  $router->mount('/blog', function() use ($router, $tpl) {
		  $router->get('/', '/view/admin/modules_/blog/@Blog@AdminIndex');
		  $router->get('/edit/(\d+)', '/view/admin/modules_/blog/@Blog@Edit');
		  $router->get('/new', '/view/admin/modules_/blog/@Blog@Save');
		  $router->get('/categories', '/view/admin/modules_/blog/@Blog@CategorySave');
		  $router->get('/category/(\d+)', '/view/admin/modules_/blog/@Blog@CategoryEdit');
		  $router->get('/settings', '/view/admin/modules_/blog/@Blog@Settings');
	  });
  }

  //Shop
  if(File::is_File(FMODPATH . 'shop/index.tpl.php')) {
	  $router->mount('/shop', function() use ($router, $tpl) {
		  $router->get('/', '/view/admin/modules_/shop/@Shop@AdminIndex');
		  $router->get('grid/', '/view/admin/modules_/shop/@Shop@AdminIndex');
		  $router->get('/edit/(\d+)', '/view/admin/modules_/shop/@Shop@Edit');
		  $router->get('/new', '/view/admin/modules_/shop/@Shop@Save');
		  $router->get('/categories', '/view/admin/modules_/shop/@Shop@CategorySave');
		  $router->get('/category/(\d+)', '/view/admin/modules_/shop/@Shop@CategoryEdit');
		  $router->get('/settings', '/view/admin/modules_/shop/@Shop@Settings');
		  $router->get('/payments', '/view/admin/modules_/shop/@Shop@Payments');
		  $router->get('/history/(\d+)', '/view/admin/modules_/shop/@Shop@History');
		  $router->mount('/variations', function() use ($router, $tpl) {
			  $router->get('/', '/view/admin/modules_/shop/@Shop@Variations');
			  $router->get('/new', '/view/admin/modules_/shop/@Shop@VariationSave');
			  $router->get('/edit/(\d+)', '/view/admin/modules_/shop/@Shop@VariationEdit');
		  });
		  $router->mount('/shipping', function() use ($router, $tpl) {
			  $router->get('/', '/view/admin/modules_/shop/@Shop@Shipping');
			  $router->get('/view/(\d+)', '/view/admin/modules_/shop/@Shop@ShippingView');
		  });
		  
	  });
  }