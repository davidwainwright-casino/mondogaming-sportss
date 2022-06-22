<?php // include('./db.php');


$query = "SELECT * FROM users WHERE id=$usid";
$row = Db::run()->pdoQuery($query);
 $fname = $row->aResults[0]->fname;
 $lname = $row->aResults[0]->lname;

?>








<div id="jinx">
 <div id="fetchodd">
  
  <div class="ifield_wrapper">
  
    <div class="ilabel">First Name</div>
     <div class="ifield">
      <input type="text" placeholder="First Name" value="<?php echo $fname;?>" name="fname">
     </div></br>
	 
	<div class="ilabel">Last Name</div>
     <div class="ifield">
      <input type="text" placeholder="Last Name" value="<?php echo $lname;?>" name="lname">
     </div></br>
	 
	 
	 
	</div>







 </div>
</div>