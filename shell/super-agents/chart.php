<?php error_reporting(0);include('../db.php');
	$query = "SELECT count(id) AS totusers, MONTH(created) AS created FROM `users` WHERE said = ".$_GET['usid']." GROUP BY MONTH(created)";   
     $result = $conn->query($query);
	 $data = array('cols' => array(array('label' => 'Month', 'type' => 'string'),
                              array('label' => 'Agents', 'type' => 'number')
							  ),
              'rows' => array());
			  
	 
	 foreach ($result as $row) {
		 $mo = $row['created'];
		 if($mo == 1){
			 $month = 'Jan';
		 } elseif($mo == 2) {
			 $month = 'Feb';
		 } elseif($mo == 3) {
			 $month = 'Mar';
		 } elseif($mo == 4) {
			 $month = 'Apr';
		 } elseif($mo == 5) {
			 $month = 'May';
		 } elseif($mo == 6) {
			 $month = 'Jun';
		 } elseif($mo == 7) {
			 $month = 'Jul';
		 } elseif($mo == 8) {
			 $month = 'Aug';
		 } elseif($mo == 9) {
			 $month = 'Sep';
		 } elseif($mo == 10) {
			 $month = 'Oct';
		 } elseif($mo == 11) {
			 $month = 'Nov';
		 } elseif($mo == 12) {
			 $month = 'Dec';
		 }
		 
		 $data['rows'][] = array('c' => array(array('v' => $month), array('v' => $row['totusers'])));
		 }
		 echo json_encode($data);