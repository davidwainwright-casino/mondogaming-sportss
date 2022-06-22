<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'forums';
$route['404_override'] = 'forums';
$route['translate_uri_dashes'] = FALSE;

$route['api/v1/(:any)'] = 'api/forumapi/$1';
$route['api/v1/(:any)/(:any)'] = 'api/forumapi/$1/$2';

$route['login'] = 'auth/login';
$route['login_modal'] = 'auth/login_modal';
$route['logout'] = 'auth/logout';
$route['register'] = 'auth/register';
$route['register_modal'] = 'auth/register_modal';
$route['forgot_password'] = 'auth/forgot_password';
$route['forgot_password_modal'] = 'auth/forgot_password_modal';
$route['reset_password/(:any)'] = 'auth/reset_password/$1';
$route['activate/(:num)/(:any)'] = 'auth/activate/$1/$2';
$route['resend_activation/(:num)'] = 'auth/resend_activation/$1';
$route['delete_avatar/(:num)'] = 'auth/delete_avatar/$1';
$route['change_password'] = 'auth/change_password';
$route['ajax_calls/get_user_details/(:num)'] = 'ajax_calls/get_user_details/$1';
$route['ajax_calls/(:any)'] = 'ajax_calls/$1';
$route['users'] = 'auth/users';
$route['users/edit/(:num)'] = 'auth/edit_user/$1';
$route['users/profile/(:any)'] = 'auth/profile/$1';
$route['users/add'] = 'auth/create_user';
$route['users/(:any)'] = 'auth/$1';
$route['users/(:num)/(:num)'] = 'auth/$1/$2';
$route['users/(:any)/(:num)'] = 'auth/$1/$2';

$route['social_auth'] = 'hauth/index';
$route['social_auth/(:any)'] = 'hauth/$1';
$route['social_auth/(:any)/(:any)'] = 'hauth/$1/$2';
$route['members'] = 'forums/members';

$route['get_slug'] = 'forums/get_slug';
$route['topics'] = 'forums/index';
$route['user_topics/(:num)'] = 'forums/index/0/$1';
$route['user_topics/(:any)/(:num)'] = 'forums/index/$1/$2';
$route['topics/(:num)'] = 'topics/index/$1';
$route['topics/(:any)'] = 'topics/$1';
$route['topics/(:num)/(:num)'] = 'topics/$1/$2';
$route['topics/(:any)/(:num)'] = 'topics/$1/$2';

$route['messages'] = 'messages/index';
$route['messages/(:any)'] = 'messages/$1';
$route['messages/(:any)/(:any)'] = 'messages/$1/$2';

$route['pages'] = 'pages/index';
$route['pages/add'] = 'pages/add';
$route['pages/(:num)'] = 'pages/index/$1';
$route['pages/(:num)/(:num)'] = 'pages/$1/$2';
$route['pages/(:any)/(:num)'] = 'pages/$1/$2';

$route['categories'] = 'categories/index';
$route['categories/(:num)'] = 'categories/index/$1';
$route['categories/(:any)'] = 'categories/$1';
$route['categories/(:num)/(:num)'] = 'categories/$1/$2';
$route['categories/(:any)/(:num)'] = 'categories/$1/$2';

$route['settings'] = 'settings/index';
$route['settings/(:num)'] = 'settings/index/$1';
$route['settings/(:any)'] = 'settings/$1';
$route['settings/(:num)/(:num)'] = 'settings/$1/$2';
$route['settings/(:any)/(:num)'] = 'settings/$1/$2';

$route['search'] = 'forums/search';
$route['terms'] = 'forums/terms';
$route['upload/(:any)'] = 'forums/upload/$1';
$route['pages/(:any)'] = 'forums/page/$1';
$route['archive/(:any)/(:any)'] = 'forums/archive/$1/$2';
$route['reset/demo'] = 'reset/demo';
$route['complain/(:any)'] = 'forums/complain/$1';
$route['reviews'] = 'manage_reviews/index';
$route['reviews/(:any)'] = 'manage_reviews/$1';
$route['wp_login'] = 'wp_login';
$route['(:any)'] = 'forums/index/$1';
$route['(:any)/(:any)'] = 'forums/topic/$1/$2';
