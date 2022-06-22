<?php include_once('../db.php');error_reporting(0);
$usid = $_POST['usid'];

 //for casino/slot
 if(isset($_POST['method']) && $_POST['method'] == 'topcash'){?>
 
 <?php include_once('../db.php');error_reporting(0);
$usid = $_POST['usid'];

	// Very important to set the page number first.
if (!(isset($_POST['pagenum']))) { 
	 $pagenum = 1; 
} else {
	$pagenum = intval($_POST['pagenum']); 		
}

//Number of results displayed per page 	by default its 10.
$page_limit =  ($_POST["show"] <> "" && is_numeric($_POST["show"]) ) ? intval($_POST["show"]) : 5;

// Get the total number of rows in the table
$cnt = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM `sh_slot_casino_dealers` WHERE user_id=$usid"));

//Calculate the last page based on total number of rows and rows per page. 
$last = ceil($cnt/$page_limit); 

//this makes sure the page number isn't below one, or more than our maximum pages 
if ($pagenum < 1) { 
	$pagenum = 1; 
} elseif ($pagenum > $last)  { 
	$pagenum = $last; 
}
$lower_limit = ($pagenum - 1) * $page_limit;



$qr = mysqli_query($conn," SELECT * FROM sh_slot_casino_dealers WHERE user_id=$usid ORDER BY id DESC LIMIT ". ($lower_limit)." ,  ". ($page_limit). "");

//show the content here.....foreach
while($record=mysqli_fetch_assoc($qr)){?>
<ul class="deptshow" style="margin-top:0px">
	 <li>
	  <div class="ccredit"><?php echo $record['game_name'];?><span class="deptright"><?php echo date ("m-d-y H:i", $record['updated_at']);?></span></div>
	  <div class="ccredit"> Stake: <span class="deptright"><?php echo $record['stake'];?></span></div>
	  <div class="ccredit"><span id="wnnk">Win</span><span class="deptright"><?php echo $record['user_win'];?></span></div>
	 </li>
	</ul>
	
<?php }
?>


<div class="height30"></div>
<table width="100%" border="0" cellspacing="0" cellpadding="2"  align="center" id="rankPagnation" class="acontest_det">
<tr>
  <td valign="top" align="left">
	
<label class="ranklim">Rows: 
<select name="show" onChange="xchangeDisplayRowCount(this.value);" id="<?php echo $usid;?>" class="changeR">
  <option value="20" <?php if ($_POST["show"] == 20 || $_POST["show"] == "" ) { echo ' selected="selected"'; }  ?> >20</option>
  <option value="100" <?php if ($_POST["show"] == 100) { echo ' selected="selected"'; }  ?> >100</option>
  <option value="200" <?php if ($_POST["show"] == 200) { echo ' selected="selected"'; }  ?> >200</option>
</select>
</label>

	</td>
  <td valign="top" align="center" >
 
	<?php
	if ( ($pagenum-1) > 0) {
	?>	
	 <a href="javascript:void(0);" class="links sr" onclick="xdisplayRecords('<?php echo $page_limit;  ?>', '<?php echo 1; ?>');">First</a>
	<a href="javascript:void(0);" class="links sr"  onclick="xdisplayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum-1; ?>');">Previous</a>
	<?php
	}
	//Show page links
 
if ( ($pagenum+1) <= $last) {
?>
	<a href="javascript:void(0);" onclick="xdisplayRecords('<?php echo $page_limit;  ?>', '<?php echo $pagenum+1; ?>');" class="links sr">Next</a>
<?php } if ( ($pagenum) != $last) { ?>	
	<a href="javascript:void(0);" onclick="xdisplayRecords('<?php echo $page_limit;  ?>', '<?php echo $last; ?>');" class="links sr" >Last</a> 
<?php
	} 
?>
</td>
	<td align="right" valign="top">
	Page <?php echo $pagenum; ?> of <?php echo $last; ?>
	</td>
</tr>
</table>
  
 <?php }
 

 
 
 //for virtual
 else if(isset($_POST['method']) && $_POST['method'] == 'topvirtual'){
   $usid = $_POST['usid'];
   $query = "SELECT * FROM sh_virtual_games WHERE user_id=$usid ORDER BY updated_at DESC LIMIT 50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   echo '<h2 style="padding-left:15px">Play History</h2>';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $game_name = $row['game_name'];
     $stake = $row['stake'];
     $date = date ("m-d-y H:i", $row['updated_at']);
     $user_win = $row['user_win'];
	 //$status = $row['status'];
	 ?> 
 
    <ul class="deptshow" style="margin-top:0px">
	 <li>
	  <div class="ccredit"><?php echo $game_name;?><span class="deptright"><?php echo $date;?></span></div>
	  <div class="ccredit"> Stake: <span class="deptright"><?php echo $stake;?></span></div>
	  <div class="ccredit"> <?php if($user_win >=1){ echo '<span id="wnnk">Win</span>';}else{ echo '<span id="llsk">Lose</span>';};?>   <span class="deptright"><?php echo $user_win;?></span></div>
	 </li>
	</ul>
	 
	 
<?php }
  }
  if ($result->num_rows > 1) {
	echo '<div id="virtualmore" class="virtualmore">Load More...</div>';
  } else {
	  echo 'No more records Found..';
   }
	 
 }
 
 
 //virtual more
 else if(isset($_POST['method']) && $_POST['method'] == 'virtualmore'){
   $rc = $_POST['rc'];
   $usid = $_POST['usid'];
   $query = "SELECT * FROM sh_virtual_games WHERE user_id=$usid ORDER BY updated_at DESC LIMIT $rc,50";
   $result = mysqli_query($conn, $query);
   echo '<input type="hidden" class="cfvalue" value="50">';
   if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
     $game_name = $row['game_name'];
     $stake = $row['stake'];
     $date = date ("m-d-y H:i", $row['updated_at']);
     $user_win = $row['user_win'];
	 //$status = $row['status'];
	 ?> 
 
    <ul class="deptshow" style="margin-top:0px">
	 <li>
	  <div class="ccredit"><?php echo $game_name;?><span class="deptright"><?php echo $date;?></span></div>
	  <div class="ccredit"> Stake: <span class="deptright"><?php echo $stake;?></span></div>
	  <div class="ccredit">
	  <?php if($user_win >=1){ echo '<span id="wnnk">Win</span>';}else{ echo '<span id="llsk">Lose</span>';};?>   <span class="deptright"><?php echo $user_win;?></span>
	  </div>
	 </li>
	</ul>
	 
	 
<?php }
  }
 
  if ($result->num_rows > 1) {
	echo '<div id="virtualmore" class="virtualmore">Load More...</div>';
  } else {
	  echo 'No more records Found..';
   }
 
 }
 
 //for other games
 else if(isset($_POST['method']) && $_POST['method'] == 'topgames'){
 
   echo 'No active records found...';
 
 }


?>
 