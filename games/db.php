<?php
    $servername = "localhost"; //server name
	$dbname = "aayan_1967"; //database name
	$username = "aayan_user11"; // username
	$password = "aayan@1967"; // password
    // Create connection
    $con = mysqli_connect($servername, $username, $password, $dbname);
	mysqli_set_charset($con,"utf8");
?>