<?php
  /**
   * PayFast Form
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  if (!defined("_YOYO"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($this->gateway->live) ? 'www.payfast.co.za' : 'sandbox.payfast.co.za';?>
  <form action="https://<?php echo $url;?>/eng/process" class="content-center" method="post" id="pf_form" name="pf_form">
    <input type="image" src="<?php echo SITEURL.'/gateways/payfast/logo_large.png';?>" style="width:200px" class="yoyo basic primary button" name="submit" title="Pay With PayFast" alt="" onclick="document.pf_form.submit();"/>
	<?php
      $html = '';
      $string = '';
      
      $array = array(
          'merchant_id' => $this->gateway->extra,
          'merchant_key' => $this->gateway->extra2,
          'return_url' => Url::url('/' . $this->core->system_slugs->account[0]->{'slug' . Lang::$lang}, "digishop"),
          'cancel_url' => Url::url('/' . $this->core->system_slugs->account[0]->{'slug' . Lang::$lang}, "digishop"),
          'notify_url' => SITEURL . '/gateways/' . $this->gateway->dir . '/digishop/ipn.php',
		  'name_first' => Auth::$userdata->fname,
		  'name_last' => Auth::$userdata->lname,
          'email_address' => Auth::$userdata->email,
          'm_payment_id' => time(),
          'amount' => Utility::formatNumber($this->tax * $this->cart->grand + $this->cart->grand),
          'item_name' => $this->core->company . ' - ' . Lang::$word->CHECKOUT,
          //'item_description' => $this->row->{'description' . Lang::$lang},
          'custom_int1' => App::Auth()->uid . '_' . App::Auth()->sesid,
          );
    
      foreach ($array as $k => $v) {
          $html .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
          $string .= $k . '=' . urlencode($v) . '&';
      }
      $string = substr($string, 0, -1);
	  if($this->gateway->extra3){
		  $string .= '&passphrase='. urlencode(trim($this->gateway->extra3));
	  }
      $sig = md5($string);
      $html .= '<input type="hidden" name="signature" value="' . $sig . '" />';
    
      print $html;
    ?>
  </form>