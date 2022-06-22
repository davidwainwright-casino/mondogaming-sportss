<?php include('../db.php');

$coption = $_POST['coption'];
echo '<input id="copt" value="'.$coption.'" hidden>';
//for prematch active slips
if(isset($_POST['method']) && $_POST['method'] == 'casino_records'){
	
	if (!(isset($_POST['pagenum']))) { 
		 $pagenum = 1; 
	} else {
		$pagenum = intval($_POST['pagenum']); 		
	}
	//count total number of rows
	if($coption=='all'){
	$cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM sh_slot_casino_dealers WHERE admin is null"));
	}else{
	$cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM sh_slot_casino_dealers WHERE game_name='$coption' AND admin is null"));	
	}
	
	echo '<div class="showtxrr">Showing '.$coption.' records. Total<b> '.$cnt.' </b>records found</div>';

	//Number of results displayed per page 	by default its 10.
	$page_limit =  ($_POST["show"] <> "" && is_numeric($_POST["show"]) ) ? intval($_POST["show"]) : 50;

	// Get the total number of rows in the table

	//Calculate the last page based on total number of rows and rows per page. 
	$last = ceil($cnt/$page_limit); 

	//this makes sure the page number isn't below one, or more than our maximum pages 
	if ($pagenum < 1) { 
		$pagenum = 1; 
	} elseif ($pagenum > $last)  { 
		$pagenum = $last; 
	}
	$lower_limit = ($pagenum - 1) * $page_limit;

  //for entire events
  if($coption=='all'){
   $fsd=mysqli_query($conn, "SELECT * FROM sh_slot_casino_dealers WHERE admin is null ORDER BY updated_at DESC LIMIT ". ($lower_limit)." ,  ". ($page_limit). "");
  }else{
	$fsd=mysqli_query($conn, "SELECT * FROM sh_slot_casino_dealers WHERE game_name='$coption' AND admin is null ORDER BY updated_at DESC LIMIT ". ($lower_limit)." ,  ". ($page_limit). "");  
  }
   ?>

	<div style="overflow-x:auto;">
	<table class="yoyo sorting basic table">
    <thead>
      <tr>
        <th data-sort="string">UserID</th>
        <th data-sort="string">AgentID</th>
        <th data-sort="int">gameID</th>
        <th data-sort="int">gameName</th>
        <th data-sort="int">Stake</th>
		<th data-sort="int">userWin</th>
		<th data-sort="int">agentWin</th>
		<th data-sort="int">transactionID</th>
		<th data-sort="int">Date</th>
      </tr>
    </thead>
	<?php
	while($crr=mysqli_fetch_assoc($fsd)){
		?>
		<tr class="gamcasino">
		<td><a target="_blank" href="/admin/users/edit/<?php echo $crr['user_id'];?>/"><?php echo $crr['user_id'];?></a></td>
		<td><?php echo $crr['agent_id'];?></td>
		<td><?php echo $crr['game_id'];?></td>
		<td><?php echo $crr['game_name'];?></td>
		<td><b><?php echo $crr['stake'];?></b></td>
		<td><?php echo $crr['user_win'];?></td>
		<td><?php $awin = $crr['agent_win'];if(!empty($awin)){
			echo $awin;
		}else{
			echo'NA';
		}?></td>
		<td><?php echo $crr['transaction_id'];?></td>
		<td><?php $tm = $crr['updated_at'];echo date ("m-d-y H:i", $tm);?></td>
		
		</tr>
		
	<?php
	}
	?>
	</table>
</div>







<div class="height30"></div>
	<table width="100%" border="0" cellspacing="0" cellpadding="2"  align="center" id="rankPagnation" class="acontest_det">
	<tr>
	  <td valign="top" align="left">
		
	<label class="ranklim">Rows: 
	<select name="show" onChange="cchangeDisplayRowCount(this.value);" id="<?php echo $usid;?>" class="changeR">
	  <option value="50" <?php if ($_POST["show"] == 50 || $_POST["show"] == "" ) { echo ' selected="selected"'; }  ?> >50</option>
	  <option value="100" <?php if ($_POST["show"] == 100) { echo ' selected="selected"'; }  ?> >100</option>
	  <option value="200" <?php if ($_POST["show"] == 200) { echo ' selected="selected"'; }  ?> >200</option>
	</select>
	</label>
	</td>
	
	<td valign="top" align="center" id="dgipag">
	 
		<?php
		if ( ($pagenum-1) > 0) {
		?>	
		 <a href="javascript:void(0);" class="links sr" onclick="casinoRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>', '<?php echo $coption;?>');">First</a>
		<a href="javascript:void(0);" class="links sr"  onclick="casinoRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>', '<?php echo $coption;?>');">Prev</a>
		<?php
		}
		//Show page links
	 
	if ( ($pagenum+1) <= $last) {
	?>
		<a href="javascript:void(0);" onclick="casinoRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>', '<?php echo $coption;?>');" class="links sr">Next</a>
	<?php } if ( ($pagenum) != $last) { ?>	
		<a href="javascript:void(0);" onclick="casinoRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>', '<?php echo $coption;?>');" class="links sr" >Last</a> 
	<?php
		} 
	?>
	</td>
		<td align="right" valign="top">
		Page <?php echo $pagenum; ?> of <?php echo $last; ?>
		</td>
	</tr>
	</table>
</div>
	
<?php	
	
}