<?php //<link rel="stylesheet" href="https://sp.sportsfier.com/shell/admin/str.css" type="text/css">;?>
<?php
include_once('../db.php'); ?>
	
  <?php 
  if($_POST['method'] == 'binme'){
	$post_ids = $_POST['post_id'];
	foreach($post_ids as $id){ 
  // Delete record 
   //$query = "DELETE FROM sh_sf_slips_history WHERE status <> 'awaiting' AND slip_id=".$id; 
   $query = "UPDATE sh_sf_slips_history SET status = 'trashed' WHERE status <> 'awaiting' AND slip_id=".$id; 
   mysqli_query($conn,$query);
   }  
  } else if($_POST['method'] == 'drestore'){
	$dkf = $_POST['delres'];
	if($dkf == 'rall'){
	  $query = "UPDATE sh_sf_slips_history SET status = 'trashed' WHERE status <> 'awaiting'"; 
      mysqli_query($conn,$query);
	} else if($dkf == 'rbin'){
	  $query = "DELETE FROM sh_sf_slips_history WHERE status = 'trashed'"; 
      mysqli_query($conn,$query);	
	} else if($dkf == 'rrestore'){
	  $query = "UPDATE sh_sf_slips_history SET status = 'canceled' WHERE status = 'trashed'"; 
      mysqli_query($conn,$query);
	}
   }
?>