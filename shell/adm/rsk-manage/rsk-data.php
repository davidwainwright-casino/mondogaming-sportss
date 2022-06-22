<?php error_reporting(0);include('../../db.php');

//for data
if(isset($_POST['method']) && $_POST['method'] == 'predisable'){
	$pred = $_POST['pred'];
	if($pred == 'Disable'){
		$conn -> query("UPDATE af_pre_bet_events_cats SET yn = 1 ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	} else if($pred == 'Enable'){
		$conn -> query("UPDATE af_pre_bet_events_cats SET yn = NULL ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	}
		
} else if(isset($_POST['method']) && $_POST['method'] == 'idisable'){
	$pred = $_POST['pred'];
	if($pred == 'Disable'){
		$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = 1 ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	} else if($pred == 'Enable'){
		$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = NULL ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	}
		
} else if(isset($_POST['method']) && $_POST['method'] == 'ienable'){
	$pred = $_POST['pred'];
	if($pred == 'Disable'){
		$conn -> query("UPDATE af_pre_bet_events_cats SET yn = 2 ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	} else if($pred == 'Enable'){
		$conn -> query("UPDATE af_pre_bet_events_cats SET yn = NULL ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	}
		
} else if(isset($_POST['method']) && $_POST['method'] == 'iactive'){
	$pred = $_POST['pred'];
	if($pred == 'Disable'){
		$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = 2 ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	} else if($pred == 'Enable'){
		$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = NULL ");
		if (mysqli_affected_rows($conn) > 0){
			echo 'done';
		}
	}
		
} 

//individual sports search disabled
else if(isset($_POST['method']) && $_POST['method'] == 'spsearch'){
	$amt = $_POST['amt'];
	$res = $conn -> query("SELECT DISTINCT spid, sname, is_active FROM af_pre_bet_events WHERE spid='$amt'");
	$inpla = $conn -> query("SELECT DISTINCT spid, sname, is_active FROM af_inplay_bet_events WHERE spid='$amt'");
	$row = $res->fetch_assoc();
	$rin = $inpla->fetch_assoc();
	?>
     <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">SportID</th>
        <th data-sort="string">SName</th>
        <th data-sort="int">Prematch</th>
		<th data-sort="int">In-Play</th>
      </tr>
    </thead>
	<?php if(!empty($row['spid'])):?>
	 <tr>
	 <td><?php echo $row['spid'];?> [Ex & Sp]</td>
	 <td><?php echo $row['sname'];?></td>
	 <td><?php $isa = $row['is_active'];if($isa == '0'){ echo '<a class="exsp" id="'.$row['spid'].'">In-Active (exp)</a>';}else{echo '<a class="exsp" id="'.$row['spid'].'">Active (exp)</a>';};?></td>
	 <td><?php $isa = $rin['is_active'];if($isa == '0'){ echo '<a class="exsp" id="'.$row['spid'].'">In-Active (exi)</a>';}else{echo '<a class="exsp" id="'.$row['spid'].'">Active (exi)</a>';};?></td>
	 </tr>
	<?php endif;?>
	
	
	
	</table>
	
<?php }

//indsports actions
else if(isset($_POST['method']) && $_POST['method'] == 'indsports'){
	$pred = $_POST['pred'];
	$amt = $_POST['amt'];
	if($pred == 'Active (exp)'){
		$conn -> query("UPDATE af_pre_bet_events SET is_active = 0 WHERE spid = $amt ");
		//$conn -> query("UPDATE af_pre_bet_events_cats SET yn = 1 WHERE spid = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Updated!';
		}
		
	} else if($pred == 'In-Active (exp)'){
		$conn -> query("UPDATE af_pre_bet_events SET is_active = 1 WHERE spid = $amt ");
		//$conn -> query("UPDATE af_pre_bet_events_cats SET yn = NULL WHERE spid = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Updated!';
		}
		
	} 
	// for inplay
	else if($pred == 'Active (exi)'){
		$conn -> query("UPDATE af_inplay_bet_events SET is_active = 0 WHERE spid = $amt ");
		//$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = 1 WHERE spid = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Updated!';
		}
		
	} else if($pred == 'In-Active (exi)'){
		$conn -> query("UPDATE af_inplay_bet_events SET is_active = 1 WHERE spid = $amt ");
		//$conn -> query("UPDATE af_inplay_bet_events_cats SET yn = NULL WHERE spid = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Updated!';
		}
		
	} 
}
//cstake
else if(isset($_POST['method']) && $_POST['method'] == 'cstake'){
	$pred = $_POST['pred'];
	$amt = $_POST['amt'];
	if($pred == 'imnb'){
		$conn -> query("UPDATE risk_management SET mn_b = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'imxb'){
		$conn -> query("UPDATE risk_management SET mx_b = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'pmnb'){
		$conn -> query("UPDATE risk_management SET pro_min = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'pmxb'){
		$conn -> query("UPDATE risk_management SET pro_max = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'pmnbm'){
		$conn -> query("UPDATE risk_management SET max_win = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	}
		
} 
//commission
else if(isset($_POST['method']) && $_POST['method'] == 'commission'){
	$pred = $_POST['pred'];
	$amt = $_POST['amt'];
	if($pred == 'cmnb'){
		$conn -> query("UPDATE risk_management SET ex_comi = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'cmxb'){
		$conn -> query("UPDATE risk_management SET sp_comi = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'kmnb'){
		$conn -> query("UPDATE risk_management SET ex_sagents = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'kmxb'){
		$conn -> query("UPDATE risk_management SET sp_sagents = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	}
		
} 
//deadline
else if(isset($_POST['method']) && $_POST['method'] == 'deadline'){
	$amt = $_POST['amt'];
	$conn -> query("UPDATE risk_management SET deadline = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
}
//esearch
else if(isset($_POST['method']) && $_POST['method'] == 'esearch'){
	$amt = $_POST['amt'];
	$res = $conn -> query("SELECT id, fname, lname, email, max_bet FROM users WHERE id='$amt' OR email = '$amt'");
	$row = $res->fetch_assoc();
	?>
     <table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">ID</th>
        <th data-sort="string">Name</th>
        <th data-sort="int">Email</th>
		<th data-sort="int">Max Bet</th>
      </tr>
    </thead>
	
	<tr>
	<td><?php echo $row['id'];?></td>
	<td><?php echo $row['fname'];?> <?php echo $row['lname'];?></td>
	<td><?php echo $row['email'];?></td>
	<td><input type="number" class="maxbet pr-<?php echo $row['id'];?>" id="minvalmax" value="<?php echo $row['max_bet'];?>"></td>
	</tr>
	</table>
	
<?php }

//update maxbet
else if(isset($_POST['method']) && $_POST['method'] == 'maxbet'){
	$amt = $_POST['amt'];
	$wid = $_POST['wid'];
	if(empty($amt)){
		$conn -> query("UPDATE users SET max_bet = NULL WHERE id=$wid");
		echo '<i class="icon check all"></i> Successfully Updated!';
		die();
	}
	$conn -> query("UPDATE users SET max_bet = $amt WHERE id=$wid");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
}

//cleani

else if(isset($_POST['method']) && $_POST['method'] == 'cleani'){
	  $que="SELECT bet_event_id FROM af_pre_bet_events WHERE is_active = 0";
		$agids=mysqli_query($conn,$que);
		$agent_ids=array();
		while($row=mysqli_fetch_assoc($agids)){
			$agent_ids[]=$row['bet_event_id'];
		}

	$aids = implode (", ", $agent_ids);
	$conn -> query("DELETE FROM af_pre_bet_events_cats WHERE bet_event_id IN($aids) ");
		if (mysqli_affected_rows($conn) > 0){
			$conn -> query("DELETE FROM af_pre_bet_events WHERE is_active = 0");
			echo '<i class="icon check all"></i> Done!';
		}
		
		//delete inplay
		$inp="SELECT bet_event_id FROM af_inplay_bet_events WHERE is_active = 0";
		$agidi=mysqli_query($conn,$inp);
		$agent_idi=array();
		while($row=mysqli_fetch_assoc($agidi)){
			$agent_idi[]=$row['bet_event_id'];
		}

	$aidi = implode (", ", $agent_idi);
	$conn -> query("DELETE FROM af_inplay_bet_events_cats WHERE bet_event_id IN($aidi) ");
		if (mysqli_affected_rows($conn) > 0){
			$conn -> query("DELETE FROM af_inplay_bet_events WHERE is_active = 0");
			echo '<i class="icon check all"></i> Done!';
		}
	
}

//clean up prematch
else if(isset($_POST['method']) && $_POST['method'] == 'clean_prematch'){
	$siteuri = $_POST['siteuri'];
	mysqli_query($conn, "TRUNCATE TABLE `af_pre_bet_events`");
	mysqli_query($conn, "TRUNCATE TABLE `af_pre_bet_events_cats`");
	mysqli_query($conn, "TRUNCATE TABLE `af_pre_bet_options`");
	
	function httpGet($url){
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    $output=curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	echo httpGet("".$siteuri."/shell/exchange/prematch");
	//echo 'Success';
}
//clean up inplay
else if(isset($_POST['method']) && $_POST['method'] == 'clean_inplay'){
	$siteuri = $_POST['siteuri'];
	mysqli_query($conn, "TRUNCATE TABLE `af_inplay_bet_events`");
	mysqli_query($conn, "TRUNCATE TABLE `af_inplay_bet_events_cats`");
	mysqli_query($conn, "TRUNCATE TABLE `af_inplay_bet_options`");
	
	function httpGet($url){
	    $ch = curl_init();  
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    $output=curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}
	echo httpGet("".$siteuri."/shell/inplay_exchange/alist");
}

//tickets
else if(isset($_POST['method']) && $_POST['method'] == 'pre_tickets'){
	     $pred = $_POST['amt'];
		 
		if($pred == 'Settle All'){
		$conn -> query("UPDATE sh_sf_slips_history SET status = 'trashed' WHERE status = 'awaiting' ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Done!';
		}
		
	} else if($pred == 'Empty trashed'){
		$conn -> query("DELETE FROM sh_sf_slips_history WHERE status = 'trashed' ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> emptied!';
		}
		
	} else if($pred == 'Empty all'){
		$conn -> query("TRUNCATE TABLE sh_sf_slips_history");
			echo '<i class="icon check all"></i> dropped!';
	}
} else if(isset($_POST['method']) && $_POST['method'] == 'inp_tickets'){
	     $pred = $_POST['amt'];
		 
		if($pred == 'Settle All'){
		$conn -> query("UPDATE sh_sf_tickets_history SET status = 'trashed' WHERE status = 'awaiting' ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Done!';
		}
		
	} else if($pred == 'Empty trashed'){
		$conn -> query("DELETE FROM sh_sf_tickets_history WHERE status = 'trashed' ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> emptied!';
		}
		
	} else if($pred == 'Empty all'){
		$conn -> query("TRUNCATE TABLE sh_sf_tickets_history");
			echo '<i class="icon check all"></i> dropped!';
	}
}
//deldays
else if(isset($_POST['method']) && $_POST['method'] == 'deldays'){
	$amt = $_POST['amt'];
	$conn -> query("DELETE FROM sh_sf_slips_history WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");
	$conn -> query("DELETE FROM sh_sf_tickets_history WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");

		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No active tickets Found';
			print_r($cok);
		}
}

//delete rest history
else if(isset($_POST['method']) && $_POST['method'] == 'del_rest'){
	$whatis = $_POST['whatis'];
	$amt = $_POST['amt'];
	
	if($whatis == 'ucredit'){
	  $conn -> query("DELETE FROM sh_users_credit_records WHERE FROM_UNIXTIME(dt) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
	}else if($whatis == 'acredit'){
	   $conn -> query("DELETE FROM sh_agents_credit_records WHERE FROM_UNIXTIME(dt) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}else if($whatis == 'plogs'){
	   $conn -> query("DELETE FROM sh_sf_points_log WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}else if($whatis == 'dephs'){
	   $conn -> query("DELETE FROM sh_sf_deposits WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}else if($whatis == 'withs'){
	   $conn -> query("DELETE FROM sh_sf_withdraws WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}else if($whatis == 'trhs'){
	   $conn -> query("DELETE FROM sh_sf_transfers WHERE FROM_UNIXTIME(date) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}else if($whatis == 'inboxhs'){
	   $conn -> query("DELETE FROM sh_sf_inbox WHERE FROM_UNIXTIME(date_record) <= (NOW() - INTERVAL $amt DAY)");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Deleted!';
		} else {
			echo 'No Records Found';
		}
		
	}
		
		
		
}

//sign up credit

else if(isset($_POST['method']) && $_POST['method'] == 'ccredit'){
	$pred = $_POST['pred'];
	$amt = $_POST['amt'];
	if($pred == 'amnb'){
		$conn -> query("UPDATE risk_management SET sup_credit = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'bmxb'){
		$conn -> query("UPDATE risk_management SET sup_cpromo = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'cmnb'){
		$conn -> query("UPDATE users SET chips = chips + $amt WHERE active = 'y' ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'dmxb'){
		$conn -> query("UPDATE users SET promo = promo + $amt WHERE active = 'y'");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	}
		
}

//deposit withdrawals limits

else if(isset($_POST['method']) && $_POST['method'] == 'depwith'){
	$pred = $_POST['pred'];
	$amt = $_POST['amt'];
	if($pred == 'mindeposit'){
		$conn -> query("UPDATE risk_management SET min_deposit = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'maxdeposit'){
		$conn -> query("UPDATE risk_management SET max_deposit = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'minwithdraw'){
		$conn -> query("UPDATE risk_management SET min_withdraw = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'maxwithdraw'){
		$conn -> query("UPDATE risk_management SET max_withdraw = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	}
		
}  

//resetme

else if(isset($_POST['method']) && $_POST['method'] == 'resetme'){
	$pred = $_POST['pred'];
	if($pred == 'Reset Chips'){
		$conn -> query("UPDATE users SET chips = 0");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	} else if($pred == 'Reset Promo'){
		$conn -> query("UPDATE users SET promo = 0");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
		
	}
		
} 


//restrict users temporarily

else if(isset($_POST['method']) && $_POST['method'] == 'restric'){
	$pred = $_POST['pred'];
	if($pred == 'Allow'){
		$conn -> query("UPDATE users SET active = 'y' WHERE active = 'x'");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Allowed!';
		}
		
	} else if($pred == 'Restrict'){
		$conn -> query("UPDATE users SET active = 'x' WHERE active = 'y'");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Restricted!';
		}
		
	}
		
} 

//first deposit bonus
else if(isset($_POST['method']) && $_POST['method'] == 'xfdb'){
	$amt = $_POST['amt'];
		$conn -> query("UPDATE risk_management SET fdb = $amt ");
		if (mysqli_affected_rows($conn) > 0){
			echo '<i class="icon check all"></i> Successfully Updated!';
		}
}





?>