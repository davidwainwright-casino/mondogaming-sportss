<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*
*  ==============================================================================
*  Author   : Mian Saleem
*  Email    : saleem@tecdiary.com
*  Web      : http://tecdiary.com
*  ==============================================================================
*/

use PHPMailer\PHPMailer\PHPMailer;

class Tec_mail
{
    public function __construct()
    {
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function send_mail($to, $subject, $body, $from = null, $from_name = null, $attachment = null, $cc = null, $bcc = null)
    {
        $mail          = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        // $mail->SMTPDebug = 1;
        try {
            if ($this->Settings->protocol == 'mail') {
                $mail->isMail();
            } elseif ($this->Settings->protocol == 'sendmail') {
                $mail->isSendmail();
            } elseif ($this->Settings->protocol == 'smtp') {
                $mail->isSMTP();
                $mail->Host       = $this->Settings->smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $this->Settings->smtp_user;
                $mail->Password   = $this->Settings->smtp_pass;
                $mail->SMTPSecure = !empty($this->Settings->smtp_crypto) ? $this->Settings->smtp_crypto : false;
                $mail->Port       = $this->Settings->smtp_port;
            // $mail->SMTPDebug = 2;
            } else {
                $mail->isMail();
            }

            if ($from && $from_name) {
                $mail->setFrom($from, $from_name);
                $mail->addReplyTo($from, $from_name);
            } elseif ($from) {
                $mail->setFrom($from, $this->Settings->site_name);
                $mail->addReplyTo($from, $this->Settings->site_name);
            } else {
                $mail->setFrom($this->Settings->default_email, $this->Settings->site_name);
                $mail->addReplyTo($this->Settings->default_email, $this->Settings->site_name);
            }

            $mail->addAddress($to);
            if ($cc) {
                $mail->addCC($cc);
            }
            if ($bcc) {
                $mail->addBCC($bcc);
            }
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = $body;
            if ($attachment) {
                if (is_array($attachment)) {
                    foreach ($attachment as $attach) {
                        $mail->addAttachment($attach);
                    }
                } else {
                    $mail->addAttachment($attachment);
                }
            }

            if ($mail->send()) {
                return true;
            }
            log_message('error', $mail->ErrorInfo);
            return false;
        } catch (\Exception $e) {
            log_message('error', $e->errorMessage());
            throw new \Exception($e->errorMessage());
        }
    }
}
