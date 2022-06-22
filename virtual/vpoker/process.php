<?php
if(isset($_POST['name']) && $_POST['name'] == "update" || $_POST['name'] == "stake")
{
	 session_start();
	include( '../../app-load.php');
	global $wpdb;
	
	
	global $current_user;
	get_currentuserinfo();
	$cUser = $current_user->ID;
	//echo $cUser;
	$spoint = get_user_meta( $cUser, 'sf_points', true );
	$cbal = get_user_meta( $cUser, 'sf_points', true );
	$stk = $_POST['stake'];
	$gross = round($_POST['iMoney'], 2);
	
	//for agent
	$netwin = round($gross - $spoint, 2);
	
	if($netwin >= 1)
	{
		//won money

		
		$amount = round($spoint - $_POST['iMoney'], 2);
		$amount1 = '-'.$amount;
		//$spoint = update_user_meta( $cUser, 'sf_points',  $_POST['iMoney']);
	    $af = $cbal - $amount;
	    $tm = time();
		
	
		
		$tablex = $wpdb->prefix."sportsfy_casino";
				$wpdb->insert( 
                $tablex, 
              array( 
                'user_id' => $cUser,
                'game_name' => 'vpoker',
                'amount_win_loss' => $netwin
                )
              );
			  
			  //for users
	
		 $table1 = $wpdb->prefix."users_credit_records";
				$wpdb->insert( 
                $table1, 
              array( 
                'sl_id' => 'vpoker',
                'u_id' => $cUser,
                'onmae' => 'Game',
				'stake' => $stk,
                'ab' => $cbal,
                'amt' => $netwin,
                'af' => $af,
                'status' => 'winning',
				'date' => time(),

                )
              );
			  
		
		
		//for agents 
	$aid = get_user_meta( $cUser, 'aid', true );
	$afId = $aid - 2050;
	$agpoints = get_user_meta( $afId, 'agpoints', true );
	$cpayout = get_user_meta( 1, 'commi', true );
	$agt = $stake * $cpayout/100;
	$fin = $agpoints + $agt;
	$anetwin = $gross - $spoint;
	$agaf = $agpoints - $anetwin;
		
		$table2 = $wpdb->prefix."agent_credit_records";
				$wpdb->insert( 
                $table2, 
              array( 
                'sl_id' => 'vpoker',
                'u_id' => $afId,
                'onmae' => 'Game',
				'stake' => $stk,
                'ab' => $agpoints,
                'amt' => $netwin,
                'af' => $agaf,
                'status' => 'debit',
				'date' => time(),
                )
              );	
		
		
		
		
		
		if (false !== $table2){
			$userupdate = update_user_meta( $cUser, 'sf_points',  $gross);
			$agentupdate = update_user_meta( $afId, 'agpoints',  $agaf);
			
		};
		
		
		die();
		
		
		
		
		
		
	}

	else if($netwin < 1)
	
	{
		
		
		
		
		$amount = round($spoint - $_POST['iMoney'], 2);
		$amount1 = '-'.$amount;
		$netloss = round($spoint - $gross, 2);
		$netl = '-'.$netloss;
		//$spoint = update_user_meta( $cUser, 'sf_points',  $_POST['iMoney']);
	    $af = $cbal - $amount;
	    $tm = time();
		
	
		
		$tablex = $wpdb->prefix."sportsfy_casino";
				$wpdb->insert( 
                $tablex, 
              array( 
                'user_id' => $cUser,
                'game_name' => 'vpoker',
                'amount_win_loss' => $netl
                )
              );
			  
			  //for users
	
		 $table1 = $wpdb->prefix."users_credit_records";
				$wpdb->insert( 
                $table1, 
              array( 
                'sl_id' => 'vpoker',
                'u_id' => $cUser,
                'onmae' => 'Game',
				'stake' => $stk,
                'ab' => $cbal,
                'amt' => $netloss,
                'af' => $af,
                'status' => 'losing',
				'date' => time(),

                )
              );
			  
		
		
		//for agents 
	$aid = get_user_meta( $cUser, 'aid', true );
	$afId = $aid - 2050;
	$agpoints = get_user_meta( $afId, 'agpoints', true );
	$cpayout = get_user_meta( 1, 'commi', true );
	$agt = $stake * $cpayout/100;
	$fin = $agpoints + $agt;
	$anetwin = $spoint - $gross;
	$agaf = $agpoints + $anetwin;
		
		$table2 = $wpdb->prefix."agent_credit_records";
				$wpdb->insert( 
                $table2, 
              array( 
                'sl_id' => 'vpoker',
                'u_id' => $afId,
                'onmae' => 'Game',
				'stake' => $stk,
                'ab' => $agpoints,
                'amt' => $anetwin,
                'af' => $agaf,
                'status' => 'credit',
				'date' => time(),
                )
              );	
		
		
		
		
		
		if (false !== $table2){
			$userupdate = update_user_meta( $cUser, 'sf_points',  $af);
			$agentupdate = update_user_meta( $afId, 'agpoints',  $agaf);
			
		};
		
		
		die();
		
		
		
		
		


   } //for if

}
?>