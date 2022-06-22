<?php include_once('../db.php');
if(isset($_POST['method']) && $_POST['method'] == 'pmmoney'){

$wallet = 'U20466140';
$userid = '5706907';
$passphrase = 'PP1234pp!!';
$password = 'PP1234pp!!';
$payeeName='Pars Win';
$coff_pm = 0.3;
$coff_vpm = 0.3;
$coff_ashj = 0.3;
$dtr = 42000; // Dollar to Rial conversion
$user_id = '2';

class PerfectMoneyVoucher
{
	
	private $accountId;
	private $passPhrase;
	private $result;

	const CREATE_URL = "https://perfectmoney.is/acct/ev_create.asp";
	
	const ACTIVATE_URL = "https://perfectmoney.is/acct/ev_activate.asp";
	
	const RETURN_URL = "https://perfectmoney.is/acct/ev_remove.asp";

	

	public function __construct($accountId, $passPhrase)
	{
		$this->setAccountId($accountId);

		$this->setPassPhrase($passPhrase);

		$this->setResult(array());
	}

	public function create($payerAccount, $amount){
		$this->setResult($this->submit(self::CREATE_URL, [
			'Payer_Account' => $payerAccount,
			'Amount' => $this->formatAmount($amount),
		]));

		return $this;
	}

	public function activate($payeeAccount, $EVNumber, $EVCode){
		$this->setResult($this->submit(self::ACTIVATE_URL, [
			'Payee_Account' => $payeeAccount,
			'ev_number' => $EVNumber,
			'ev_code' => $EVCode,
		]));

		return $this;
	}

	public function remove($EVNumber){
		$this->setResult($this->submit(self::RETURN_URL, [
			'ev_number' => $EVNumber,
		]));

		return $this;
	}

	public function isOk(){
		return ! array_key_exists('ERROR', $this->getResult());
	}

	public function getErrorMessage(){
		if($this->isOk()){
			return '';
		}

		return $this->getResult()['ERROR'];
	}

	public function getVoucherNumber(){
		return ( isset($this->getResult()['VOUCHER_NUM']) ) ? $this->getResult()['VOUCHER_NUM'] : '';
	}

	public function getVoucherCode(){
		return ( isset($this->getResult()['VOUCHER_CODE']) ) ? $this->getResult()['VOUCHER_CODE'] : '';
	}


	protected function formatAmount($amount){ 
		return number_format($amount, 2, '.', '');
	}

	public function setPassPhrase($passPhrase){
		$this->passPhrase = $passPhrase;
	}

	public function getPassPhrase(){
		return $this->passPhrase;
	}

	public function setAccountId($accountId){
		$this->accountId = $accountId;
	}

	public function getAccountId(){
		return $this->accountId;
	}

	public function setResult($result){
		$this->result = $result;
	}

	public function getResult(){
		return $this->result;
	}

	protected function submit($url, $parameters, $method = "POST"){
		$curl = curl_init( $url );

		curl_setopt($curl, CURLOPT_VERBOSE, true);

		curl_setopt($curl, CURLOPT_POST, true);

		curl_setopt($curl, CURLOPT_TIMEOUT, 30);

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($curl, CURLOPT_VERBOSE, true);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$parameters = array_merge($parameters, [
			'AccountID' => $this->getAccountId(),
			'PassPhrase' => $this->getPassPhrase(),
		]);

		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));

		$response = curl_exec($curl);

		if (curl_errno($curl)){
			return [
				'ERROR' => curl_error($curl),
			];
		}

		if (empty($response)){
			return [
				'ERROR' => 'Connection failed',
			];
		}

		curl_close($curl);

        $curl = null;

        $extracted = [];

        if(!preg_match_all("/<input name='(.*)' type='hidden' value='(.*)'>/", $response, $extracted, PREG_SET_ORDER)){
           return [
           		'ERROR' => 'Ivalid output',
           ];
        }

        $ar= [];
        foreach($extracted as $item){
           $key=$item[1];
           $ar[$key]=$item[2];
        }

        return $ar;

	}
}
?>
			   	

 <div class="dpvia">
    Payment Deposit/Perfect Money</br>
	1 USD = <?php echo number_format($dtr); ?> IRT 
   </div>
  
  






	
   <?php // put all your codes within this page and theme-function.php page. On success payment response, you will need to update user's usermeta in order to credit the amount to the user account. The users balance is stores in meta_key = sf_points .. So, as you get response on payment success, update the sf_points usermeta of that user. And, also, if you want to store history of payments, you can insert the following information to payment log i.e table name sf_points_log.. The following information you can insert to the database. These are following columns: user_id, points_amount, date, type ... type you can insert it default as 'perfect money';?> 
			
	<?php

	//$retrieve_data = $wpdb->get_col( "DESC sh_sf_points_log",0 );
	//print_r($retrieve_data);
	//
	
	$errMessage = '';

	if(isset($_GET['state'])){
		$state = $_GET['state'];
		if($state == 'canceled'){
			$payment_id = $_POST['PAYMENT_ID'];
			$wpdb->udpate('sh_sf_points_log', array(
				'status' => 'canceled'
			), array(
				'payment_id' => $payment_id
			));

			$errMessage = 'Payment canceled by user';
		}else if($state == 'success'){
			$okMessage = 'Payment successfully done! your account will be charged soonly';
		}else if($state == 'check'){

			$payment_id = $_POST['PAYMENT_ID'];

			$pmTransaction = $wpdb->get_row("select * from `sh_sf_points_log` where payment_id='".$payment_id."'");

			if($pmTransaction){


				if($pmTransaction->payee_account == $_POST['PAYEE_ACCOUNT'] && 
				    $pmTransaction->payment_amount == $_POST['PAYMENT_AMOUNT'] && 
				    $pmTransaction->payment_units == $_POST['PAYMENT_UNITS']
				){
				    $hashed_passphrase = strtoupper(md5($passphrase));
				    $fields = $_POST['PAYMENT_ID'] . ':' . $_POST['PAYEE_ACCOUNT'] . ':' . $_POST['PAYMENT_AMOUNT'] . ':' . $_POST['PAYMENT_UNITS'] . ':' . $_POST['PAYMENT_BATCH_NUM'] . ':' . $_POST['PAYER_ACCOUNT'] . ':' . $hashed_passphrase . ':' . $_POST['TIMESTAMPGMT'];

				    $hashed_fields = strtoupper(md5($fields));



				    if($hashed_fields === $_POST['V2_HASH']){
				        
				        $wpdb->update('sh_sf_points_log', array(
				        	'v2_hash' => $_POST['V2_HASH'],
				        	'payer_account' => $_POST['PAYER_ACCOUNT'],
				        	'timestampgmt' => $_POST['TIMESTAMPGMT'],
				        	'payment_batch_num' => $_POST['PAYMENT_BATCH_NUM'],
				        	'status' => 'paid',
				        ), array(
				        	'payment_id' => $payment_id,
				        ));

				        $user_id = $pmTransaction->user_id;

						$points = (float) get_user_meta( $user_id, 'sf_points' , true );						
						$payment_amount_bonus = $pmTransaction->payment_amount * $dtr * $coff;
				        update_user_meta($user_id, 'sf_points', $points + $payment_amount_bonus);

				        // add Bounus
				        $pointsBuyed = (float) get_user_meta( $user_id, 'sf_points_buyed' , true );

						
						$bonus = $pmTransaction->payment_amount * $dtr * $coff_pm;


				        update_user_meta($user_id, 'sf_points_buyed', $pointsBuyed + $bonus);
				        
				        
				 
				 
				    }
				}
			}
		}
	}
	
	
	$valid = true;
	if(isset($_POST['step']) && $_POST['step']==1){ 
		if(!isset($_POST['amount'])){
			$valid = false;
			$errMessage = 'Amount not provided';
		}else if(empty($_POST['amount'])){
			$valid = false;
			$errMessage = 'Amount not provided';
		}else if(!is_numeric($_POST['amount'])){
			$valid = false;
			$errMessage = 'invalid value provided';
		}else if($_POST['amount'] <= 0){
			$valid = false;
			$errMessage = 'Amount is too low';
		}
		

			if($valid){
				do{

				    $uniqueToken = strtoupper(md5(time()));

				}
				while(mysqli_query($conn, "select count(*) from `sh_sf_deposits` WHERE transaction_id='".$uniqueToken."'")->num_rows > 0);


				$amount = $_POST['amount'];

				$amountFormated = number_format($amount, 2, ".", "");

				$units = "USD";

				$statusUrl = 'https://cricmarkets.com/bt_accounts/?pg105=pf_money&state=check';
				$paymentUrl = 'https://cricmarkets.com/bt_accounts/?pg105=pf_money&state=success';
				$nopaymentUrl = 'https://parsiwin.com/accounts/members/payments/?state=canceled';
				
				$points = (float) get_user_meta( $user_id, 'sf_points' , true );						
				$payment_amount_bonus = $pmTransaction->payment_amount * $dtr * $coff;
				$upoint = $points + $amountFormated;
				$totpoints = number_format($upoint, 2, ".", "");
				update_user_meta($user_id, 'sf_points', $totpoints);

				$wpdb->insert('sh_sf_points_log', array(
					'payment_id' => $uniqueToken,
					'user_id' => get_current_user_id(),
					'payment_units' => $units,
					'payee_account' => $wallet,
					'points_amount' => $amountFormated,
					'status' => 'success',
					'date' => time(),
					'type' => 'perfect money',
				));


				?>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
					<script>
					function post_value (payee_account, payee_name, payment_amount, payment_units, payment_id, status_url, payment_url, nopayment_url) {
					    var form = document.createElement("form");
					    form.setAttribute("method", "POST");
					    form.setAttribute("action", "https://perfectmoney.is/api/step1.asp");
					    form.setAttribute("target", "_self"); 

					    var hiddenField1 = document.createElement("input"); 
					    hiddenField1.setAttribute("name", "PAYEE_ACCOUNT"); 
					    hiddenField1.setAttribute("value",payee_account); 
					    form.appendChild(hiddenField1); 

					    var hiddenField2 = document.createElement("input"); 
					    hiddenField2.setAttribute("name", "PAYEE_NAME"); 
					    hiddenField2.setAttribute("value",payee_name); 
					    form.appendChild(hiddenField2); 

					    var hiddenField3 = document.createElement("input"); 
					    hiddenField3.setAttribute("name", "PAYMENT_AMOUNT"); 
					    hiddenField3.setAttribute("value",payment_amount); 
					    form.appendChild(hiddenField3); 

					    var hiddenField4 = document.createElement("input"); 
					    hiddenField4.setAttribute("name", "PAYMENT_UNITS"); 
					    hiddenField4.setAttribute("value",payment_units); 
					    form.appendChild(hiddenField4); 

					    var hiddenField5 = document.createElement("input"); 
					    hiddenField5.setAttribute("name", "PAYMENT_ID"); 
					    hiddenField5.setAttribute("value",payment_id); 
					    form.appendChild(hiddenField5);

					    var hiddenField6 = document.createElement("input"); 
					    hiddenField6.setAttribute("name", "STATUS_URL"); 
					    hiddenField6.setAttribute("value",status_url); 
					    form.appendChild(hiddenField6);  

					    var hiddenField7 = document.createElement("input"); 
					    hiddenField7.setAttribute("name", "PAYMENT_URL"); 
					    hiddenField7.setAttribute("value",payment_url); 
					    form.appendChild(hiddenField7);

					    var hiddenField8 = document.createElement("input"); 
					    hiddenField8.setAttribute("name", "NOPAYMENT_URL"); 
					    hiddenField8.setAttribute("value",nopayment_url); 
					    form.appendChild(hiddenField8);

					    document.body.appendChild(form); 
					    form.submit(); 
					    document.body.removeChild(form);
					}
					
					post_value('<?php echo $wallet ?>', '<?php echo $payeeName; ?>', '<?php echo $amountFormated; ?>', '<?php echo $units; ?>', '<?php echo $uniqueToken; ?>', '<?php echo $statusUrl; ?>', '<?php echo $paymentUrl; ?>', '<?php echo $nopaymentUrl; ?>');
					</script>

				<?php
			}
	}else if(isset($_POST['step']) && $_POST['step']==5){
		if(!isset($_POST['voucher'])){
			$valid = false;
			$errMessage2 = 'Voucher code not provided';
		}else if(empty($_POST['voucher'])){
			$valid = false;
			$errMessage2 = 'Voucher code not provided';
		}else if(!isset($_POST['code'])){
			$valid = false;
			$errMessage2 = 'Activation code not provided';
		}else if(empty($_POST['code'])){
			$valid = false;
			$errMessage2 = 'Activation code not provided';
		}else if(!preg_match("/^[0-9]+$/", $_POST['voucher']) || strlen($_POST['voucher']) != 10){
			$valid = false;
			$errMessage2 = 'Invalid voucher code';
		}else if(!preg_match("/^[0-9]+$/", $_POST['code'])){
			$valid = false;
			$errMessage2 = 'Invalid activation code';
		}else{
			$pmv = new PerfectMoneyVoucher($userid, $password);

			$pmv->activate($wallet, $_POST['voucher'], $_POST['code']);
 // print_r($pmv->getResult());exit;
			if($pmv->isOk()){
				$result = $pmv->getResult();

				$amount = $result['VOUCHER_AMOUNT'];

		        $user_id = get_current_user_id();

				$points = (float) get_user_meta( $user_id, 'sf_points' , true );

				
				$payment_amount_bonus = $amount * $dtr;


		        update_user_meta($user_id, 'sf_points', $points + $payment_amount_bonus);

		        // add Bounus
		        $pointsBuyed = (float) get_user_meta( $user_id, 'sf_points_buyed' , true );

				
				$bonus = $amount * $dtr * $coff_vpm;


		        update_user_meta($user_id, 'sf_points_buyed', $pointsBuyed + $bonus);

		        $okMessage2 = 'Payment successfully done! your account will be charged soonly';
			}
		}
	}else if(isset($_POST['step']) && $_POST['step']==10){
		if(!isset($_POST['voucher'])){
			$valid = false;
			$errMessage2 = 'Voucher code not provided';
		}else if(empty($_POST['voucher'])){
			$valid = false;
			$errMessage2 = 'Voucher code not provided';
		}else if(!isset($_POST['code'])){
			$valid = false;
			$errMessage2 = 'Activation code not provided';
		}else if(empty($_POST['code'])){
			$valid = false;
			$errMessage2 = 'Activation code not provided';
		}else if(!preg_match("/^[0-9]+$/", $_POST['voucher']) || strlen($_POST['voucher']) != 10){
			$valid = false;
			$errMessage2 = 'Invalid voucher code';
		}else if(!preg_match("/^[0-9]+$/", $_POST['code'])){
			$valid = false;
			$errMessage2 = 'Invalid activation code';
		}else{
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "http://hemexgroup.com/voucher/activate",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"merchant\"\r\n\r\nDS13DOPE94IL3C6XP1Z\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"code\"\r\n\r\n".$_POST['voucher']."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"pass\"\r\n\r\n".$_POST['code']."\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
			  CURLOPT_HTTPHEADER => array(
			    "Postman-Token: 4a887476-e26c-43ea-8d38-7b4c6fe81470",
			    "cache-control: no-cache",
			    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
			  $errMessage3 = 'Error occured, try again later';
			} else {
			  $resp = json_decode($response);

			  if(isset($resp->status) && $resp->status == 1){
	  			$amount = $resp->amount;

	  	        $user_id = get_current_user_id();

	  			$points = (float) get_user_meta( $user_id, 'sf_points' , true );

	  			
	  			$payment_amount_bonus = (int) ($amount / 10);


	  	        update_user_meta($user_id, 'sf_points', $points + $payment_amount_bonus);

                // add Bounus
                $pointsBuyed = (float) get_user_meta( $user_id, 'sf_points_buyed' , true );

        		
        		$bonus = $amount * $dtr * $coff_ashj;


                update_user_meta($user_id, 'sf_points_buyed', $pointsBuyed + $bonus);

	  	        $okMessage3 = 'Payment successfully done! your account will be charged soonly';
			  }else{
			  	$errMessage3 = 'Invalid response, please contact administrator';
			  }
			}

			
		}
	}

?>	


<div class="dwrapper">	
		
		<h2>Deposit using Perfect Money</h2>
	<form method="post" autocomplete="off">
		<?php if(!empty($errMessage)){ ?>
			<p style="color: red;"><?php echo $errMessage; ?></p><br>
		<?php } ?>
		<?php if(isset($okMessage)){ ?>
			<p style="color: green;"><?php echo $okMessage; ?></p><br>
		<?php } ?>
		<div class="amtpy">Amount</div>
		<input type="hidden" name="step" value="1">
	
		<input type="text" class="form-control" value="" name="amount" required="" autofocus="" autocomplete="off"><br><button class="btn btn-success btn-block">Continue</button>
	</form>		
			
			

	<br>
	
	<div style="display:none">
	<h2>Deposit using perfect money e-Voucher</h2>


	<form method="post">
		<?php if(!empty($errMessage2)){ ?>
			<p style="color: red;"><?php echo $errMessage2; ?></p><br><br>error mesg here<br><br><br>
		<?php } ?>
		<?php if(isset($okMessage2)){ ?>
			<p style="color: green;"><?php echo $okMessage2; ?></p><br>
		<?php } ?>
		<input type="hidden" name="step" value="5">
		<div class="amtpy">Voucher Code</div>
		<input type="text" class="form-control" name="voucher" required="" ><br>
		<div class="amtpy">Activation Code</div>
		<input type="text" class="form-control" name="code" required="" ><br>
		<button class="btn btn-success btn-block">Continue</button>
	</form>		



</div>

	<br><br><br><br>
	
	<div style="display:none">
	<h2>Deposit using Asan-sharj e-Voucher</h2>


	<form method="post">
		<?php if(!empty($errMessage3)){ ?>
			<p style="color: red;"><?php echo $errMessage3; ?></p><br>
		<?php } ?>
		<?php if(isset($okMessage3)){ ?>
			<p style="color: green;"><?php echo $okMessage3; ?></p><br>
		<?php } ?>
		<input type="hidden" name="step" value="10">
		<div class="amtpy">Voucher</div>
		<input type="text" class="form-control" name="voucher" required="" ><br>
		<div class="amtpy">Password</div>
		<input type="text" class="form-control" name="code" required="" ><br>
		<button class="btn btn-success btn-block">Continue</button>
	</form>	
	</div>
	
</div>

</div>
			
			

			
			
			
			
<?php } ?>			
			
			
