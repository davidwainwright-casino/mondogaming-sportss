<?php include_once('../db.php');

$query = "SELECT sum(stake) AS totusers, DAY(FROM_UNIXTIME(date)) AS created FROM `sh_sf_slips_history` WHERE FROM_UNIXTIME(date) > (NOW() - INTERVAL 15 DAY) GROUP BY DAY(FROM_UNIXTIME(date)) ORDER BY date ASC";   
     $result = $conn->query($query);
	 $data = array('cols' => array(array('label' => 'Day', 'type' => 'string'),
                              array('label' => 'Stake Volume', 'type' => 'number')
							  ),
              'rows' => array());
			  
	 
	 foreach ($result as $row) {
		 $month = $row['created'];
		 
		 
		 $data['rows'][] = array('c' => array(array('v' => $month), array('v' => $row['totusers'])));
		 }
		 echo json_encode($data);
		
	 ?>
	 
	 