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
        <title></title>
        <link rel="stylesheet" href="css/reset.css" type="text/css">
        <link rel="stylesheet" href="css/main.css" type="text/css">
        <link rel="stylesheet" href="css/orientation_utils.css" type="text/css">
        <link rel="stylesheet" href="css/ios_fullscreen.css" type="text/css">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, minimal-ui" />
	<meta name="msapplication-tap-highlight" content="no"/>

        <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/createjs-2015.11.26.min.js"></script>
        <script type="text/javascript" src="js/howler.min.js"></script>
        <script type="text/javascript" src="js/screenfull.js"></script>
        <script type="text/javascript" src="js/platform.js"></script>
        <script type="text/javascript" src="js/ios_fullscreen.js"></script>
        <script type="text/javascript" src="js/ctl_utils.js"></script>
        <script type="text/javascript" src="js/sprite_lib.js"></script>
        <script type="text/javascript" src="js/settings.js"></script>
        <script type="text/javascript" src="js/CSlotSettings.js"></script>
        <script type="text/javascript" src="js/CLang.js"></script>
        <script type="text/javascript" src="js/CPreloader.js"></script>
        <script type="text/javascript" src="js/CMain.js"></script>
        <script type="text/javascript" src="js/CTextButton.js"></script>
        <script type="text/javascript" src="js/CGfxButton.js"></script>
        <script type="text/javascript" src="js/CToggle.js"></script>
        <script type="text/javascript" src="js/CBetBut.js"></script>
        <script type="text/javascript" src="js/CMenu.js"></script>
        <script type="text/javascript" src="js/CGame.js"></script>
        <script type="text/javascript" src="js/CReelColumn.js"></script>
        <script type="text/javascript" src="js/CInterface.js"></script>
        <script type="text/javascript" src="js/CPayTablePanel.js"></script>
        <script type="text/javascript" src="js/CStaticSymbolCell.js"></script>
        <script type="text/javascript" src="js/CTweenController.js"></script>
        <script type="text/javascript" src="js/CCreditsPanel.js"></script>
        
    </head>
    <body ondragstart="return false;" ondrop="return false;" >
	<div style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%"></div>
          <script>
            $(document).ready(function(){
                     var oMain = new CMain({
                                    win_occurrence:100,        //WIN PERCENTAGE.SET A VALUE FROM 0 TO 100.
                                    slot_cash: 100,          //THIS IS THE CURRENT SLOT CASH AMOUNT. THE GAME CHECKS IF THERE IS AVAILABLE CASH FOR WINNINGS.
                                    min_reel_loop:2,          //NUMBER OF REEL LOOPS BEFORE SLOT STOPS  
                                    reel_delay: 6,            //NUMBER OF FRAMES TO DELAY THE REELS THAT START AFTER THE FIRST ONE
                                    time_show_win:2000,       //DURATION IN MILLISECONDS OF THE WINNING COMBO SHOWING
                                    time_show_all_wins: 2000, //DURATION IN MILLISECONDS OF ALL WINNING COMBO
                                    money:<?= $spoint ?>,               //STARING CREDIT FOR THE USER
									
                                    /***********PAYTABLE********************/
                                    //EACH SYMBOL PAYTABLE HAS 5 VALUES THAT INDICATES THE MULTIPLIER FOR X1,X2,X3,X4 OR X5 COMBOS
                                    paytable_symbol_1: [0,0,100,150,200], //PAYTABLE FOR SYMBOL 1
                                    paytable_symbol_2: [0,0,50,100,150],  //PAYTABLE FOR SYMBOL 2
                                    paytable_symbol_3: [0,10,25,50,100],  //PAYTABLE FOR SYMBOL 3
                                    paytable_symbol_4: [0,10,25,50,100],  //PAYTABLE FOR SYMBOL 4
                                    paytable_symbol_5: [0,5,15,25,50],    //PAYTABLE FOR SYMBOL 5
                                    paytable_symbol_6: [0,2,10,20,35],    //PAYTABLE FOR SYMBOL 6
                                    paytable_symbol_7: [0,1,5,10,15],     //PAYTABLE FOR SYMBOL 7
                                    /*************************************/
                                    fullscreen:true,           //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                                    check_orientation:true,    //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                                    show_credits:true,         //ENABLE/DISABLE CREDITS BUTTON IN THE MAIN SCREEN
                                    ad_show_counter:3         //NUMBER OF SPIN PLAYED BEFORE AD SHOWING
                                    // 
                                    //// THIS FEATURE  IS ACTIVATED ONLY WITH CTL ARCADE PLUGIN./////////////////////////// 
                                    /////////////////// YOU CAN GET IT AT: ///////////////////////////////////////////////////////// 
                                    // http://codecanyon.net/item/ctl-arcade-wordpress-plugin/13856421///////////

                                });

                    $(oMain).on("start_session", function (evt) {
                        if(getParamValue('ctl-arcade') === "true"){
                            parent.__ctlArcadeStartSession();
                        }
                        //...ADD YOUR CODE HERE EVENTUALLY
                    });

                    $(oMain).on("end_session", function (evt) {
                        if(getParamValue('ctl-arcade') === "true"){
                            parent.__ctlArcadeEndSession();
                        }
                        //...ADD YOUR CODE HERE EVENTUALLY
                    });
                    
                    $(oMain).on("bet_placed", function (evt, oBetInfo) {
                        var iBet = oBetInfo.bet;
                        var iTotBet = oBetInfo.tot_bet;
                        //...ADD YOUR CODE HERE EVENTUALLY
						window.localStorage.setItem("testdata", iBet);
                    });
                
                    $(oMain).on("save_score", function (evt, iMoney) {
                        if(getParamValue('ctl-arcade') === "true"){
                            parent.__ctlArcadeSaveScore({score:iMoney});
                        }
                        //...ADD YOUR CODE HERE EVENTUALLY
						var mu =  window.localStorage.getItem("testdata");
							
                            //...ADD YOUR CODE HERE EVENTUALLY
							$.ajax({
							  type: "POST",
							  url: "process.php",
							  data: {
								  name:"update",
								  iMoney:iMoney,
								  stake: mu
							  },
							  success: function (response) {
							   // you will get response from your php page (what you echo or print)                 
								//alert(response);
							  },
							  
							});
						
                    });
                    
                    $(oMain).on("show_preroll_ad", function (evt) {
                        //...ADD YOUR CODE HERE EVENTUALLY
                    });
                    
                    $(oMain).on("show_interlevel_ad", function (evt) {
                        if(getParamValue('ctl-arcade') === "true"){
                            parent.__ctlArcadeShowInterlevelAD();
                        }
                        //...ADD YOUR CODE HERE EVENTUALLY
                    });

                    $(oMain).on("share_event", function(evt, iScore) {
                           if(getParamValue('ctl-arcade') === "true"){
                               parent.__ctlArcadeShareEvent({   
                                                                img: TEXT_SHARE_IMAGE,
                                                                title: TEXT_SHARE_TITLE,
                                                                msg: TEXT_SHARE_MSG1 + iScore+ TEXT_SHARE_MSG2,
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
        
        <canvas id="canvas" class='ani_hack' width="1500" height="640"> </canvas>
        <div data-orientation="landscape" class="orientation-msg-container"><p class="orientation-msg-text">Please rotate your device</p></div>
        <div id="block_game" style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none"></div>
    </body>
</html>