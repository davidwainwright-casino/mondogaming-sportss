<?php include_once('../db.php');

$res = mysqli_query($conn,'SELECT sum(chips) FROM users');
$row = mysqli_fetch_row($res);
echo $sum = $row[0];