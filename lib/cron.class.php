<?php

  /**
   * Cron Class
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');

  class Cron
  {


      /**
       * Cron::Run()
       * 
       * @return
       */
      public static function Run($days)
      {
		  $data = self::expireMemberships($days);
          self::runStripe($days);
		  self::sendEmails($data);
      }

      /**
       * Cron::expireMemberships()
       * 
       * @param integer $days
       * @return
       */
      public static function expireMemberships($days)
      {

          $sql = "
		  SELECT 
			u.id, CONCAT(u.fname,' ',u.lname) as fullname,
			u.email, u.mem_expire, m.id AS mid, m.title" . Lang::$lang . " as title  
		  FROM
			`" . Users::mTable . "` AS u 
			LEFT JOIN `" . Membership::mTable . "` AS m 
			  ON m.id = u.membership_id
		  WHERE u.active = ?
		  AND u.membership_id <> 0
		  AND u.mem_expire <= DATE_ADD(DATE(NOW()), INTERVAL $days DAY);";

          $result = Db::run()->pdoQuery($sql, array("y"))->results();

          if ($result) {
              $query = "UPDATE `" . Users::mTable . "` SET mem_expire = NULL, membership_id = CASE ";
              $idlist = '';
              foreach ($result as $usr) {
                  $query .= " WHEN id = " . $usr->id . " THEN membership_id = 0";
                  $idlist .= $usr->id . ',';
              }
              $idlist = substr($idlist, 0, -1);
              $query .= "
				  END
				  WHERE id IN (" . $idlist . ")";
              Db::run()->pdoQuery($query);
          }

      }

      /**
       * Cron::sendEmails()
       * 
       * @param array $data
       * @return
       */
      public static function sendEmails($data)
      {

          if ($data) {
              $numSent = 0;
              $mailer = Mailer::sendMail();
              $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
			  $core = App::Core();

              $tpl = Db::run()->first(Content::eTable, array("body", "subject"), array('typeid' => 'memExpired'));

              $replacements = array();
              foreach ($data as $cols) {
                  $replacements[$cols->email] = array(
                      '[COMPANY]' => $core->company,
                      '[LOGO]' => Utility::getLogo(),
                      '[NAME]' => $cols->fullname,
                      '[ITEMNAME]' => $cols->title,
					  '[EXPIRE]' => Date::doDate("short_date", $cols->mem_expire),
                      '[SITEURL]' => SITEURL,
                      '[DATE]' => date('Y'),
					  '[FB]' => $core->social->facebook,
					  '[TW]' => $core->social->twitter,
					  );
              }

              $decorator = new Swift_Plugins_DecoratorPlugin($replacements);
              $mailer->registerPlugin($decorator);

              $message = Swift_Message::newInstance()
					  ->setSubject($tpl->subject)
					  ->setFrom(array($core->site_email => $core->company))
					  ->setBody($tpl->body, 'text/html');

              foreach ($data as $row) {
                  $message->setTo(array($row->email => $row->fullname));
                  $numSent++;
                  $mailer->send($message, $failedRecipients);
              }
              unset($row);
          }

      }

      /**
       * Cron::runStripe()
       * 
       * @param bool $days
       * @return
       */
      public static function runStripe($days)
      {
          $sql = "
			  SELECT 
				um.*,
				m.title" . Lang::$lang . " as title,
				u.id as uid,
				m.price,
				u.email,
				u.stripe_cus,
				CONCAT(u.fname,' ',u.lname) as name
			  FROM
				`" . Membership::umTable . "` AS um 
				LEFT JOIN `" . Membership::mTable . "` AS m 
				  ON m.id = um.mid
				LEFT JOIN `" . Users::mTable . "` AS u 
				  ON u.id = um.uid 
			  WHERE um.active = ?
			  AND um.recurring = ?
			  AND TRIM(IFNULL(u.stripe_cus,'')) <> ''
			  AND u.mem_expire <= DATE_ADD(DATE(NOW()), INTERVAL $days DAY)
			  ORDER BY expire DESC;";

          $data = Db::run()->pdoQuery($sql, array(1, 1))->results();

          require_once (BASEPATH . 'gateways/stripe/init.php');
          $key = Db::run()->first(Core::gTable, array("extra"), array("name" => "stripe"));
          \Stripe\Stripe::setApiKey($key->extra);

          if ($data) {
              try {
                  foreach ($data as $row) {
                      $tax = Membership::calculateTax($row->uid);
                      $charge = \Stripe\Charge::create(array(
                          "amount" => round(($row->price + $tax) * 100, 0), // amount in cents, again
                          "currency" => "usd",
                          "customer" => $row->stripe_cus,
                          ));

                      // insert transaction
                      $data = array(
                          'txn_id' => $charge['balance_transaction'],
                          'membership_id' => $row->mid,
                          'user_id' => $row->uid,
                          'rate_amount' => $row->price,
                          'total' => Validator::sanitize($row->price * $tax, "float"),
                          'tax' => Validator::sanitize($tax, "float"),
                          'currency' => $charge['currency'],
                          'pp' => "Stripe",
                          'status' => 1,
                          );

                      $last_id = Db::run()->insert(Membership::pTable, $data)->getLastInsertId();

                      //update user membership
                      $udata = array(
                          'tid' => $last_id,
                          'uid' => $row->uid,
                          'mid' => $row->mid,
                          'expire' => Membership::calculateDays($row->mid),
                          'recurring' => 1,
                          'active' => 1,
                          );

                      //update user record
                      $xdata = array(
                          'membership_id' => $row->id,
                          'mem_expire' => $udata['expire'],
                          );

                      Db::run()->insert(Membership::umTable, $udata);
                      Db::run()->update(Users::mTable, $xdata, array("id" => $row->uid));
                  }

              }
              catch (\Stripe\CardError $e) {
              }
          }
      }
  }