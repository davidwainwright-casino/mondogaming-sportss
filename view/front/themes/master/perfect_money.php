 <?php 
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

class PerfectMoneyVoucher{
	
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
    All deposits uses USD as base currency, and it's converted to your local currency once deposit is success.
   </div>
			
	<?php

	//$retrieve_data = $wpdb->get_col( "DESC sh_sf_points_log",0 );
	//print_r($retrieve_data);
	//
	
	$errMessage = '';

	if(isset($_GET['state'])){
		$state = $_GET['state'];
		if($state == 'canceled'){
			$payment_id = $_POST['PAYMENT_ID'];
			Db::run()->pdoQuery("UPDATE sh_sf_deposits SET status = 'Canceled' WHERE transaction_id='$payment_id'");
			/*$wpdb->udpate('sh_sf_points_log', array(
				'status' => 'canceled'
			), array(
				'payment_id' => $payment_id
			));
			*/

			$errMessage = 'Payment Canceled';
		}else if($state == 'success'){
			
			$payment_amount = $_POST['PAYMENT_AMOUNT'];
			$curr= Db::run()->pdoQuery("SELECT stripe_cus FROM users WHERE id=$usid");
			$gcur = $curr->aResults[0]->stripe_cus;
			$valcur= Db::run()->pdoQuery("SELECT rate FROM currencies WHERE name='$gcur'");
			$vcur = $valcur->aResults[0]->rate;
			$amt = $vcur * $payment_amount;

			Db::run()->pdoQuery("UPDATE users SET chips=chips + $amt WHERE id=$usid");
			
			//for first deposit bonus
			$ksg = Db::run()->pdoQuery("SELECT transaction_id FROM sh_sf_deposits WHERE user_id=$usid AND status = 'Received'");
			$csg = $ksg->aResults[0]->transaction_id;
			if(empty($csg)){
			$gs = Db::run()->pdoQuery("SELECT fdb FROM risk_management");
			$pgs = $gs->aResults[0]->fdb;
			$promo = $amt * $pgs/100;
			Db::run()->pdoQuery("UPDATE users SET promo=promo + $promo WHERE id=$usid");
			}
			
			
			
			
			
			
			
			
			
			
			
			
			$okMessage = 'Payment successfully done! Your account will be charged/credited shortly';
		}else if($state == 'check'){

		}
	}
	
	
	$valid = true;
	if(isset($_POST['step']) && $_POST['step']==1){

      //start		
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
				$dt = time();

				$statusUrl = 'https://cricmarkets.com/bt_accounts/?pg105=pf_money&state=check';
				$paymentUrl = 'https://cricmarkets.com/bt_accounts/?pg105=pf_money&state=success';
				$nopaymentUrl = 'https://cricmarkets.com/bt_accounts/?pg105=pf_money&state=canceled';
				
				
				Db::run()->pdoQuery("INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `ac_name`, `account_no`, `remarks`, `date`, `type`, `status`) VALUES ($usid, '$uniqueToken','$amountFormated', NULL, NULL, NULL, $dt,'CryptoCurrency','Pending')");
				
				//mysqli_query($conn,"INSERT INTO `sh_sf_deposits` (`user_id`, `transaction_id`, `amount`, `ac_name`, `account_no`, `remarks`, `date`, `type`, `status`) VALUES ($usid,'$ref','$amt', NULL, NULL, NULL, '$date','CryptoCurrency','Received')");
				
				
				/*
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
				*/


				?>
	
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
	}

?>	


<div class="dwrapper">	
		
		<h2>Deposit using Perfect Money</h2>
	<form method="post" autocomplete="off">
		<?php if(!empty($errMessage)){ ?>
			<p style="color: red;"><?php echo $errMessage; ?></p>
		<?php } ?>
		<?php if(isset($okMessage)){ ?>
			<p style="color: green;"><?php echo $okMessage; ?></p><br>
		<?php } ?>
		<div class="amtpy">Deposit Amount</div>
		<input type="hidden" name="step" value="1">
	
		<input type="text" class="form-control" value="" id="pfmoney" placeholder="type amount" name="amount" required="" autofocus="" autocomplete="off"><br>
		<button class="btn btn-success btn-block" id="subpfmoney">Continue</button>
	</form>		
			
</div>			

	<br>
	
	