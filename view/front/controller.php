<?php
  /**
   * Controller
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  define("_YOYO", true);
  require_once("../../init.php");
	  
  $delete = Validator::post('delete');
  $trash = Validator::post('trash');
  $action = Validator::request('action');
  $restore = Validator::post('restore');
  $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
  
  /* == Actions == */
  switch ($action):
      /* == Admin Login == */
      case "adminLogin":
	  case "userLogin":
          App::Auth()->login($_POST['username'], $_POST['password']);
      break;
	  
      /* == Admin Password Reset == */
      case "aResetPass":
          App::Admin()->passReset();
      break;
	  
      /* == User Password Reset == */
      case "uResetPass":
          App::Front()->passReset();
      break;

      /* == Register == */
      case "register":
          App::Front()->Registration();
      break;
	  
	   /* == Register == */
      case "aregister":
          App::Front()->aRegistration();
      break;

      /* == Update Profile == */
      case "profile":
	      if(!App::Auth()->is_User())
			  exit;
          App::Users()->updateProfile();
      break;

      /* == Update Avatar == */
      case "avatar":
	      if(!App::Auth()->is_User())
			  exit;
		  if (!empty($_FILES['avatar']['name'])) :
			  if($avatar = File::upload("avatar", 2097152, "png,jpg,jpeg")) {
			  
				  $apath = UPLOADS . '/avatars/'; 
				  $img = File::process($avatar, $apath, "AVT_", false, false);
				  
				  File::deleteFile($apath . Auth::$udata->avatar);
				  try {
					  $image = new Image($apath . $img['fname']);
					  $image->thumbnail(App::Core()->avatar_w, App::Core()->avatar_h)->save($apath . $img['fname']);
					  
					  Db::run()->update(Users::mTable, array("avatar" => $img['fname']), array("id" => Auth::$udata->uid));
					  Auth::$udata->avatar = App::Session()->set('avatar', $img['fname']);
				  }
				  catch (exception $e) {
					  Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
				  }
			  }

		  endif;
      break;
	  
      /* == Process Contact Form == */
      case "processContact":
          App::Front()->processContact();
      break;

      /* == Posters == */
      case "posters":
		  if (!App::Auth()->is_User())
			  exit;
		  $html = '';
		  $images = File::findFiles(THEMEBASE . '/images/userbg', array(
			  'fileTypes' => array('jpg'),
			  'level' => 0,
			  'returnType' => 'fileOnly'));
		  if ($images):
			  foreach ($images as $img):
				  $html .= '<img src="' . THEMEURL . '/images/userbg/' . $img . '" class="yoyo small image">';
			  endforeach;
		  endif;
		  print $html;
      break;

      /* == Select Membership == */
      case "buyMembership":
	      if(!App::Auth()->is_User())
			  exit;
          App::Membership()->buyMembership();
      break;

      /* == Select Gateway == */
      case "selectGateway":
	      if(!App::Auth()->is_User())
			  exit;
          App::Membership()->selectGateway();
      break;
	  
      /* == Apply Coupon == */
      case "getCoupon":
	      if(!App::Auth()->is_User())
			  exit;
          App::Membership()->getCoupon();
      break;
	  
      /* == Membership Invoice == */
      case "invoice":
		  if(!App::Auth()->is_User())
			  exit;
			  
		  if($row = Users::getUserInvoice(Filter::$id)):
			  $tpl = App::View(THEMEBASE . '/snippets/'); 
			  $tpl->row = $row;
			  $tpl->user = Auth::$userdata;
			  $tpl->core = App::Core();
			  $tpl->template = 'invoice.tpl.php'; 
			  
			  $title = Validator::sanitize($row->title, "alpha");
			  
			  require_once (BASEPATH . 'lib/mPdf/vendor/autoload.php');
			  $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8']);
			  $mpdf->SetTitle($title);
			  $mpdf->WriteHTML($tpl->render());
			  $mpdf->Output($title . ".pdf", "D");
			  exit;
		  else:
			  exit;
		  endif;
      break;
  endswitch;
  
    /* == Clear Session Temp Queries == */
  if (isset($_GET['ClearSessionQueries'])):
      App::Session()->remove('debug-queries');
	  App::Session()->remove('debug-warnings');
	  App::Session()->remove('debug-errors');
	  print 1;
  endif;