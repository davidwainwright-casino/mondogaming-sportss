 <?php
include_once('../../app-load.php');  
global $current_user;
get_currentuserinfo();
$cUser = $current_user->ID;
$spoint = get_user_meta( $cUser, 'sf_points', true );
?>

<!DOCTYPE html>
<html>
    <head>
        <title>KENO</title>
        <link rel="stylesheet" href="css/reset.css" type="text/css">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link rel="stylesheet" href="css/orientation_utils.css" type="text/css">
        <link rel="stylesheet" href="css/ios_fullscreen.css" type="text/css">
        <link rel='shortcut icon' type='image/x-icon' href='./favicon.ico' />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="msapplication-tap-highlight" content="no"/>
        
        <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/createjs.min.js"></script>
        <script type="text/javascript" src="js/platform.js"></script>
        <script type="text/javascript" src="js/ios_fullscreen.js"></script>
        <script type="text/javascript" src="js/howler.min.js"></script>
        <script type="text/javascript" src="js/screenfull.js"></script>
        <script type="text/javascript" src="js/ctl_utils.js"></script>
        <script type="text/javascript" src="js/sprite_lib.js"></script>
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/CLang.js"></script>
        <script type="text/javascript" src="js/CPreloader.js"></script>
        <script type="text/javascript" src="js/CMain.js"></script>
        <script type="text/javascript" src="js/CTextButton.js"></script>
        <script type="text/javascript" src="js/CTextToggle.js"></script>
        <script type="text/javascript" src="js/CToggle.js"></script>
        <script type="text/javascript" src="js/CNumToggle.js"></script>
        <script type="text/javascript" src="js/CGfxButton.js"></script>
        <script type="text/javascript" src="js/CMenu.js"></script>
        <script type="text/javascript" src="js/CGame.js"></script>
        <script type="text/javascript" src="js/CInterface.js"></script>
        <script type="text/javascript" src="js/CEndPanel.js"></script>
        <script type="text/javascript" src="js/CPayouts.js"></script>
        <script type="text/javascript" src="js/CAnimBalls.js"></script>
        <script type="text/javascript" src="js/CCreditsPanel.js"></script>
        <script type="text/javascript" src="js/CDisplayPanel.js"></script>
        <script type="text/javascript" src="js/CCTLText.js"></script>
        
    </head>
    <body ondragstart="return false;" ondrop="return false;" >
	<div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
          <script>
            $(document).ready(function(){
                     var oMain = new CMain({  
                         
                                            bank_money : <?= $spoint ?>,
                                            start_player_money: <?= $spoint ?>,
                                    
                                            win_occurrence : [    
                                                                "-",
                                                                10, //WIN OCCURRENCE WITH 2 NUMBERS CHOSEN
                                                                10, //WIN OCCURRENCE WITH 3 NUMBERS CHOSEN
                                                                9, //WIN OCCURRENCE WITH 4 NUMBERS CHOSEN
                                                                8, //WIN OCCURRENCE WITH 5 NUMBERS CHOSEN
                                                                7, //WIN OCCURRENCE WITH 6 NUMBERS CHOSEN
                                                                5, //WIN OCCURRENCE WITH 7 NUMBERS CHOSEN
                                                                5, //WIN OCCURRENCE WITH 8 NUMBERS CHOSEN
                                                                3, //WIN OCCURRENCE WITH 9 NUMBERS CHOSEN
                                                                1  //WIN OCCURRENCE WITH 10 NUMBERS CHOSEN
                                                            ],
                                                            
                                            
                                            //PAYOUT VALUES TABLE: {#HITS, BET MULTIPLY, HITS OCCURRENCE}
                                            payouts : [                
														{hits: ["-"],           pays: ["-"],                            occurrence: [0]},                   //PAYOUTS FOR 1 NUMBERS
                                                        {hits: [2,1],           pays: [9,1],                            occurrence: [20,80]},               //PAYOUTS FOR 2 NUMBERS
                                                        {hits: [3,2],           pays: [47,2],                           occurrence: [20,80]},               //PAYOUTS FOR 3 NUMBERS
                                                        {hits: [4,3,2],         pays: [91,5,2],                         occurrence: [10,30,60]},            //PAYOUTS FOR 4 NUMBERS
                                                        {hits: [5,4,3],         pays: [820,12,3],                       occurrence: [10,30,60]},            //PAYOUTS FOR 5 NUMBERS
                                                        {hits: [6,5,4,3],       pays: [1600,70,4,3],                    occurrence: [10,20,30,40]},         //PAYOUTS FOR 6 NUMBERS
                                                        {hits: [7,6,5,4,3],     pays: [7000,400,21,2,1],                occurrence: [5,10,20,30,35]},       //PAYOUTS FOR 7 NUMBERS
                                                        {hits: [8,7,6,5,4],     pays: [10000,1650,100,12,2],            occurrence: [5,10,20,30,35]},       //PAYOUTS FOR 8 NUMBERS
                                                        {hits: [9,8,7,6,5,4],   pays: [10000,4700,335,44,6,1],          occurrence: [1,4,10,20,30,35]},     //PAYOUTS FOR 9 NUMBERS
                                                        {hits: [10,9,8,7,6,5],  pays: [10000,4500,1000,142,24,5],       occurrence: [1,4,10,15,30,40]}      //PAYOUTS FOR 10 NUMBERS

                                                    ],
                                                    
                                                    
                                            animation_speed : 300,
                                            audio_enable_on_startup:false, //ENABLE/DISABLE AUDIO WHEN GAME STARTS 
                                            fullscreen:true, //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                                            check_orientation:true,     //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                                            show_credits:true,           //SET THIS VALUE TO FALSE IF YOU DON'T TO SHOW CREDITS BUTTON
                                            //////////////////////////////////////////////////////////////////////////////////////////
                                            ad_show_counter: 5     //NUMBER OF TURNS PLAYED BEFORE AD SHOWN
                                            //
                                            //// THIS FUNCTIONALITY IS ACTIVATED ONLY WITH CTL ARCADE PLUGIN.///////////////////////////
                                            /////////////////// YOU CAN GET IT AT: /////////////////////////////////////////////////////////
                                            // http://codecanyon.net/item/ctl-arcade-wordpress-plugin/13856421?s_phrase=&s_rank=27 ///////////
                                            
                                           });
                                           
                                           
                    $(oMain).on("start_session", function(evt) {
                            if(getParamValue('ctl-arcade') === "true"){
                                parent.__ctlArcadeStartSession();
                            }
                            //...ADD YOUR CODE HERE EVENTUALLY
                    });
                     
                    $(oMain).on("end_session", function(evt) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeEndSession();
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });
                    
                    $(oMain).on("bet_placed", function (evt, oBetInfo) {
                        var iTotBet = oBetInfo.tot_bet;
                        var iMoney = oBetInfo.money;
                        var iNumSelected = oBetInfo.num_selected;
                        //...ADD YOUR CODE HERE EVENTUALLY
						window.localStorage.setItem("testdata", iTotBet);
                    });
                    
                    $(oMain).on("save_score", function(evt,iScore) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeSaveScore({score:iScore});
                           }
                           var mu =  window.localStorage.getItem("testdata");
							
                            //...ADD YOUR CODE HERE EVENTUALLY
							$.ajax({
							  type: "POST",
							  url: "process.php",
							  data: {
								  name:"update",
								  iMoney:iScore,
								  stake: mu
							  },
							  success: function (response) {
							   // you will get response from your php page (what you echo or print)                 
								//alert(response);
							  },
							  
							});
                    });

                    $(oMain).on("show_interlevel_ad", function(evt) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShowInterlevelAD();
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });
                    
                    $(oMain).on("share_event", function(evt, iMoney) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShareEvent({ img:"200x200.jpg",
                                                                title:TEXT_CONGRATULATIONS,
                                                                msg:TEXT_SHARE_1 + iMoney + TEXT_SHARE_2,
                                                                msg_share:TEXT_SHARE_3 + iMoney + TEXT_SHARE_4
                                                            });
                           }
                           //...ADD YOUR CODE HERE EVENTUALLY
                    });
                     
                    if(isIOS()){ 
                        setTimeout(function(){sizeHandler();},200); 
                    }else{ 
                        sizeHandler(); 
                    }
           });

        </script>
        
        <div class="check-fonts">
            <p class="check-font-1">Lora</p>
        </div> 
        
        <canvas id="canvas" class='ani_hack' width="1920" height="1080"> </canvas>
        <div data-orientation="landscape" class="orientation-msg-container"><p class="orientation-msg-text">Please rotate your device</p></div>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>
        
    </body>
</html>
