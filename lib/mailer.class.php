<?php
  /**
   * Mailer Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');

  class Mailer
  {
	  
	  private static $instance;

      /**
       * Mailer::__construct()
       * 
       * @return
       */
      private function __construct(){}

      /**
       * Mailer::instance()
       * 
       * @return
       */
	  public static function instance(){
		  if (!self::$instance){ 
			  self::$instance = new Mailer(); 
		  } 
	  
		  return self::$instance;  
	  }

      /**
       * Mailer::sendMail()
       * 
       * @return
       */
      public static function sendMail()
      {
          require_once (BASEPATH . 'lib/swift/swift_required.php');
          
		  $core = App::Core();
          if ($core->mailer == "SMTP") {
			  $SSL = ($core->is_ssl) ? 'ssl' : null;
              $transport = Swift_SmtpTransport::newInstance($core->smtp_host, $core->smtp_port, $SSL)
						  ->setUsername($core->smtp_user)
						  ->setPassword($core->smtp_pass);
		  } elseif ($core->mailer == "SMAIL") {
			  $transport = Swift_SendmailTransport::newInstance($core->sendmail);
          } else {
              $transport = Swift_MailTransport::newInstance();
		  }
          
          return Swift_Mailer::newInstance($transport);
	  }
  }