<?php include_once('../../db.php');
 //session_start();
 define("_YOYO", true);
include_once('../../init.php');  
//global $current_user;
//get_currentuserinfo();
//$cUser = $current_user->ID;
//$spoint = '450';
//$spoint = '450';
//$stmt = $pdo->query("SELECT id,chips FROM users ORDER BY id DESC LIMIT 1");
//$user = $stmt->fetch();

if(App::Auth()->is_User()){
	$usid=App::Auth()->uid;
	$gu = Db::run()->first(Users::mTable, array("stripe_cus", "chips", "promo", "afid"), array("id" => $usid));
	$curr=$gu->stripe_cus; 
	$moo=$gu->chips;
	$promo=$gu->promo;
	$agent_id = $gu->afid;
	
  } else {
	 die('Direct access to this location is not allowed.');
  }
  
 function get_exchange($curr,$conn){
		$query="SELECT rate FROM currencies WHERE name='$curr'";
		$cur=mysqli_query($conn,$query);
		return mysqli_fetch_assoc($cur);
	}
  
  var_dump($exch);
 //$excg=get_exchange($curr,$conn); // Currency of users
 $ks = "SELECT rate FROM currencies WHERE name='$curr'";
  $sr = Db::run()->pdoQuery($ks);
  $cur = $sr->aResults[0]->rate;
 $minimumbet = round($cur * 0.1); //Min bet in USD
 $maximumbet = round($cur * 100); //Max bet in USD
 if($moo > $minimumbet){
	 $spoint = number_format((float)$moo, 2, '.', '');
	 $type = 'chips';
 }else{
	 $spoint = number_format((float)$promo, 2, '.', '');
	 $type = 'promo';
 }

?>


<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="css/reset.css" type="text/css">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link rel="stylesheet" href="css/orientation_utils.css" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
        <meta name="msapplication-tap-highlight" content="no"/>

        <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="js/createjs-2015.11.26.min.js"></script>
        <script type="text/javascript" src="js/howler.min.js"></script>
        <script type="text/javascript" src="js/screenfull.js"></script>
        <script type="text/javascript" src="js/ctl_utils.js"></script>
        <script type="text/javascript" src="js/sprite_lib.js"></script>
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/CGameSettings.js"></script>
        <script type="text/javascript" src="js/CLang.js"></script>
        <script type="text/javascript" src="js/CPreloader.js"></script>
        <script type="text/javascript" src="js/CMain.js"></script>
        <script type="text/javascript" src="js/CTextButton.js"></script>
        <script type="text/javascript" src="js/CToggle.js"></script>
        <script type="text/javascript" src="js/CGfxButton.js"></script>
        <script type="text/javascript" src="js/CMenu.js"></script>
        <script type="text/javascript" src="js/CGame.js"></script>
        <script type="text/javascript" src="js/CInterface.js"></script>
        <script type="text/javascript" src="js/CCreditsPanel.js"></script>
        <script type="text/javascript" src="js/CBetPanel.js"></script>
        <script type="text/javascript" src="js/CChipPanel.js"></script>
        <script type="text/javascript" src="js/CSimpleBetPanel.js"></script>
        <script type="text/javascript" src="js/CForecastPanel.js"></script>
        <script type="text/javascript" src="js/CBetList.js"></script>
        <script type="text/javascript" src="js/CFichesController.js"></script>
        <script type="text/javascript" src="js/CButBet.js"></script>
        <script type="text/javascript" src="js/CVector2.js"></script>
        <script type="text/javascript" src="js/CMsgBox.js"></script>
        <script type="text/javascript" src="js/CTrackBg.js"></script>
        <script type="text/javascript" src="js/CGreyhound.js"></script>
        <script type="text/javascript" src="js/CTweenController.js"></script>
        <script type="text/javascript" src="js/CRankingGui.js"></script>
        <script type="text/javascript" src="js/CArrivalPanel.js"></script>
        <script type="text/javascript" src="js/CWinPanel.js"></script>
        <script type="text/javascript" src="js/CLosePanel.js"></script>
        <script type="text/javascript" src="js/CButStartRace.js"></script>
        <script type="text/javascript" src="js/CAreYouSurePanel.js"></script>

    </head>
    <body ondragstart="return false;" ondrop="return false;" >
        <div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
        <script>
            $(document).ready(function () {
                var oMain = new CMain({
                    money:<?= $spoint ?>,           //USER MONEY
                    min_bet:<?= $minimumbet ?>,           //MINIMUM BET
                    max_bet:<?= $maximumbet ?>,           //MAXIMUM BET
                    win_occurrence: 20,   //WIN OCCURRENCE
                    game_cash:1,        //GAME CASH. STARTING MONEY THAT THE GAME CAN DELIVER.
                    chip_values:[1,5,10,25,50,100], //VALUE OF CHIPS
                    show_credits:true, //SET THIS VALUE TO FALSE IF YOU DON'T TO SHOW CREDITS BUTTON
                    fullscreen:true, //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                    check_orientation:true,     //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                    num_levels_for_ads: 2 //NUMBER OF TURNS PLAYED BEFORE AD SHOWING //
                            //////// THIS FEATURE  IS ACTIVATED ONLY WITH CTL ARCADE PLUGIN./////////////////////////// 
                            /////////////////// YOU CAN GET IT AT: ///////////////////////////////////////////////////////// 
                            // http://codecanyon.net/item/ctl-arcade-wordpress-plugin/13856421///////////

                });

                $(oMain).on("start_session", function (evt) {
                    if (getParamValue('ctl-arcade') === "true") {
                        parent.__ctlArcadeStartSession();
                    }
                });

                $(oMain).on("end_session", function (evt) {
                    if (getParamValue('ctl-arcade') === "true") {
                        parent.__ctlArcadeEndSession();
                    }
                });
				
				$(oMain).on("bet_placed", function (evt, iTotBet) {
					
					
					    //...ADD YOUR CODE HERE EVENTUALLY
							//alert(iTotBet);
							//alert('bet placed');
							window.localStorage.setItem("istake", iTotBet);
							$.ajax({
							  type: "POST",
							  url: "<?php echo SITEURL;?>/shell/virtual/greyhound_process",
							  data: {
								  method:"debit_balance",
								  usid:"<?php echo $usid;?>",
								  stake:iTotBet,
								  type:"<?php echo $type;?>"
							  },
							  success: function (response) {
								 //alert(response);
							   // you will get response from your php page (what you echo or print)                 
								//alert(response);
							  },
							  
							});
							
							
				});
					
                $(oMain).on("save_score", function (evt, iScore) {
                    if (getParamValue('ctl-arcade') === "true") {
                        parent.__ctlArcadeSaveScore({score: iScore});
                    }
					var istake =  window.localStorage.getItem("istake");
							 //alert(iScore);
                            //...ADD YOUR CODE HERE EVENTUALLY
							$.ajax({
							  type: "POST",
							  url: "<?php echo SITEURL;?>/shell/virtual/greyhound_process",
							  data: {
								  method:"set_records",
								  usid:"<?php echo $usid;?>",
								  afid:"<?php echo $agent_id;?>",
								  istake:istake,
								  iMoney:iScore,
								  curr:"<?php echo $cur;?>",
								  type:"<?php echo $type;?>"
							  },
							  success: function (response) {
								 
								  //alert(response);
							   // you will get response from your php page (what you echo or print)                 
								//alert(response);
							  },
							  
							});
							
                });

                $(oMain).on("show_interlevel_ad", function (evt) {
                    if (getParamValue('ctl-arcade') === "true") {
                        parent.__ctlArcadeShowInterlevelAD();
                    }
                });

                $(oMain).on("share_event", function (evt, iScore) {
                    if (getParamValue('ctl-arcade') === "true") {
                        parent.__ctlArcadeShareEvent({img: TEXT_SHARE_IMAGE,
                            title: TEXT_SHARE_TITLE,
                            msg: TEXT_SHARE_MSG1 + iScore
                                    + TEXT_SHARE_MSG2,
                            msg_share: TEXT_SHARE_SHARE1
                                    + iScore + TEXT_SHARE_SHARE1});
                    }
                });

                if (isIOS()) {
                    setTimeout(function () {
                        sizeHandler();
                    }, 200);
                } else {
                    sizeHandler();
                }
            });

        </script>
        <canvas id="canvas" class='ani_hack' width="1024" height="768"> </canvas>
        <div data-orientation="landscape" class="orientation-msg-container"><p class="orientation-msg-text">Please rotate your device</p></div>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>

    </body>
</html>