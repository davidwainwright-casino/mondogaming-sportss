<?php include('../db.php');error_reporting(0);
$usid =  $_POST['usid'];
$getlogo = $_POST['getlogo'];
$gameId = $_POST['gameId'];

require_once('../../init.php');

if(isset($_POST['method']) && $_POST['method'] == 'slot_view'){?>
  <?php if($usid =='999999999'):?>
  
  <div id="dctn">
      <?= Lang::$word->PLEASE_LOGIN_TO_ACCESS_THIS_PAGE; ?>
  </div>
  
  
  
  <?php else:?>
  <div class="framewrapper">
  <div id="framecloser">X</div>
  <ul class="ttbrace" style="display:none">
  <li class="cloleft"><div class="framebacker">Close X</div></li>
  <li class="rtlog"><img id="logower" src="<?php echo $getlogo;?>"></li>
  </ul>
  <iframe class="frame" id="iframe" name="myIframe" frameborder="0" width="100%" height="100%"></iframe>
  </div>
  <div id="dctn">
  
  
  
  
  
  
  
  
  
  <?php
//api key

$url = 'https://api.riseupgames.net/'.$gameId.'/games/gambabet';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
curl_setopt($ch, CURLOPT_TIMEOUT, 100);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$data = curl_exec($ch) or die(curl_error($ch));
if ($data === false) {
    $info = curl_getinfo($ch);
    curl_close($ch);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($ch);
$obj = json_decode($data, true);  

//var_dump($obj);

foreach($obj['data'] as $result){
	$dev = $result['device'];
	if($dev == 'html5'){?>
	<div class="slotwrap">
	<img id="slimg" src="<?php echo $result['imgUrl'];?>">
	<div id="gswrapper">
	<div class="gmname"><?php echo $result['name'];?></div>
	<a class="generateslot cls" id="<?php echo $result['id'];?>">Click Play</a>
	</div>
	</div>
	
	 
	<?php }
}


?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  </div>



















<?php endif;?>


<?php }