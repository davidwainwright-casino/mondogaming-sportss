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
        <title>WHEEL OF FORTUNE</title>
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
        <script type="text/javascript" src="js/screenfull.js"></script>
        <script type="text/javascript" src="js/howler.min.js"></script>
        <script type="text/javascript" src="js/ios_fullscreen.js"></script>
        <script type="text/javascript" src="js/platform.js"></script>
        <script type="text/javascript" src="js/ctl_utils.js"></script>
        <script type="text/javascript" src="js/sprite_lib.js"></script>
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/CLang.js"></script>
        <script type="text/javascript" src="js/CPreloader.js"></script>
        <script type="text/javascript" src="js/CMain.js"></script>
        <script type="text/javascript" src="js/CTextButton.js"></script>
        <script type="text/javascript" src="js/CToggle.js"></script>
        <script type="text/javascript" src="js/CGfxButton.js"></script>
        <script type="text/javascript" src="js/CMenu.js"></script>
        <script type="text/javascript" src="js/CGame.js"></script>
        <script type="text/javascript" src="js/CVector2.js"></script>
        <script type="text/javascript" src="js/CFormatText.js"></script>
        <script type="text/javascript" src="js/CInterface.js"></script>
        <script type="text/javascript" src="js/CHelpPanel.js"></script>
        <script type="text/javascript" src="js/CEndPanel.js"></script>
        <script type="text/javascript" src="js/CWheel.js"></script>
        <script type="text/javascript" src="js/CLeds.js"></script>
        <script type="text/javascript" src="js/CCreditsPanel.js"></script>
        <script type="text/javascript" src="js/CCTLText.js"></script>
        
    </head>
    <body ondragstart="return false;" ondrop="return false;" >
	<div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
          <script>
            $(document).ready(function(){
                     var oMain = new CMain({                                            
                                            start_credit: <?= $spoint ?>, //Starting credits value
                                            start_bet: 1,     //Base starting bet. Will increment with multiplier in game
                                            bet_offset: 1,    //Bet Offset
                                            max_bet: 50,      //Max multiplier value
             
                                            bank_cash : 500,  //Starting credits owned by the bank. When a player win, founds will be subtract from here. When a player lose or bet, founds will be added here. If 0 players always lose.
                                            //wheel_settings sets the values and probability of each prize in the wheel ([prize, win occurence percentage]). Value*max_bet can't exceed 9999999.
                                            //PAY ATTENTION: the total sum of win occurences must be 100! 
                                            //prize=0 or less, is considered as "lose". So Leds will play a lose animation.
                                            wheel_settings: [
                                                    {prize:10,win_occurence:2}, {prize:2,win_occurence:7}, {prize:1,win_occurence:1},  {prize:8,win_occurence:1},   {prize:0,win_occurence:10},     
                                                    {prize:2,win_occurence:1}, {prize:10,win_occurence:1}, {prize:1,win_occurence:10}, {prize:2,win_occurence:1},  {prize:0,win_occurence:15},    
                                                    {prize:15,win_occurence:1}, {prize:15,win_occurence:1}, {prize:20,win_occurence:1},  {prize:10,win_occurence:3},   {prize:0,win_occurence:12},     
                                                    {prize:2,win_occurence:10}, {prize:7,win_occurence:2}, {prize:5,win_occurence:1},  {prize:500,win_occurence:0}, {prize:0,win_occurence:20}        
                                            ],                                            
                                            
                                            anim_idle_change_frequency: 10000,  //Duration (in ms) of current led idle animation, before it change with another.
                                            led_anim_idle1_timespeed: 2000,     //Time speed (in ms) of led animation idle 1. Less is faster.
                                            led_anim_idle2_timespeed: 100,      //Time speed (in ms) of led animation idle 2. Less is faster.
                                            led_anim_idle3_timespeed: 150,      //Time speed (in ms) of led animation idle 3. Less is faster.
                                            
                                            led_anim_spin_timespeed: 50,        //Time speed (in ms) of led animation spin. Less is faster.
                                            
                                            led_anim_win_duration: 5000,        //Duration (in ms) of current led win animation, before it change with the idle.
                                            led_anim_win1_timespeed: 300,       //Time speed (in ms) of led animation win 1. Less is faster.
                                            led_anim_win2_timespeed: 50,        //Time speed (in ms) of led animation win 2. Less is faster.
                                            
                                            led_anim_lose_duration: 5000,        //Duration (in ms) of led lose animation, before it change with the idle.
                                            
                                            show_credits:true,                   //SET THIS VALUE TO FALSE IF YOU DON'T WANT TO SHOW CREDITS BUTTON
                                            fullscreen:true,                     //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                                            check_orientation:true,              //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                                            audio_enable_on_startup:false,       //ENABLE/DISABLE AUDIO WHEN GAME STARTS 
                                            
                                            //////////////////////////////////////////////////////////////////////////////////////////
                                            ad_show_counter: 5     //NUMBER OF SPIN BEFORE AD SHOWN
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
                    
                    $(oMain).on("bet_placed", function (evt, iTotBet) {
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
                    
                    $(oMain).on("share_event", function(evt, iScore) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShareEvent({   img: TEXT_SHARE_IMAGE,
                                                                title: TEXT_SHARE_TITLE,
                                                                msg: TEXT_SHARE_MSG1 + iScore + TEXT_SHARE_MSG2,
                                                                msg_share: TEXT_SHARE_SHARE1 + iScore + TEXT_SHARE_SHARE1});
                           }
                    });
                     
                     if(isIOS()){ 
                        setTimeout(function(){sizeHandler();},200); 
                    }else{ 
                        sizeHandler(); 
                    }
           });

        </script>
        
        <div class="check-fonts">
            <p class="check-font-1">test 1</p>
        </div> 
        
        
        <canvas id="canvas" class='ani_hack' width="1920" height="1080"> </canvas>
        <div data-orientation="landscape" class="orientation-msg-container"><p class="orientation-msg-text">Please rotate your device</p></div>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>
    </body>
</html>
