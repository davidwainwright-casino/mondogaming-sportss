<?php error_reporting(0);include('../db.php');

if(isset($_POST['method']) && $_POST['method'] == 'ccountry'){
  //group by country
  //$sql = "SELECT COUNT(id) AS tcount FROM users";	 
   //$result = $conn->query($sql);
   
   $sql = "SELECT DISTINCT country FROM users";
   $result = $conn->query($sql);?>
<div class="hopperf">
 <div class="gobacker">X Close</div>
   
<?php 
foreach($conn->query('SELECT country,COUNT(*)
FROM users
GROUP BY country') as $row) {?>
 <ul id="fotl">
  <li><?php $og = $row['country']; if(empty($og)){ echo 'unknown';}else{ echo $og;}?> (<?php echo $row['COUNT(*)'];?>)</li>
</ul> 
 
 <?php }?>
 
 </div>


<?php }

?>