<?php error_reporting(0);
	include_once('../db.php');
	
 if(isset($_POST['method']) && $_POST['method'] == 'cricket_cat'){

	$event_id = $_POST['evid'];
	$evname = $_POST['evname'];
	$catid = $_POST['catid'];
	$usid = $_POST['usid'];
	//conditions
	if($catid == 'Popularc'){
	$caton = array("Player of the Match","Player to Score Most Sixes","Player to Score Most Fours","Player Performance");
	//$caton = '1';
	//$caton = join("','",$catlist);
	} else if($catid == 'Runsc'){
	$term = 'Runs';
	} else if($catid == 'Wicketsc'){
	$term = 'Bowler';
	} else if($catid == 'Scoresc'){
	$term = 'Batsman';
	} else if($catid == 'Oversc'){
	$term = 'Over';
	} 

	function getEventData($event_id,$caton,$term, $conn){
		$catid = $_POST['catid'];
		if($catid == 'Popularc'){
		$query="SELECT * FROM af_pre_bet_events_cats JOIN af_pre_bet_options on af_pre_bet_events_cats.bet_event_cat_id=af_pre_bet_options.bet_event_cat_id WHERE af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name NOT IN ( '" . implode( "', '" , $caton ) . "' )";
		
		} else if($catid == 'Wicketsc'){
			$query="SELECT * FROM af_pre_bet_events_cats JOIN af_pre_bet_options on af_pre_bet_events_cats.bet_event_cat_id=af_pre_bet_options.bet_event_cat_id WHERE af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%".$term."%' OR af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%Wicket%'";
			
		} else if($catid == 'Oversc'){
			$query="SELECT * FROM af_pre_bet_events_cats JOIN af_pre_bet_options on af_pre_bet_events_cats.bet_event_cat_id=af_pre_bet_options.bet_event_cat_id WHERE af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%".$term."%' OR af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%Wicket%' OR af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%Player%'";
		}else {
		 $query="SELECT * FROM af_pre_bet_events_cats JOIN af_pre_bet_options on af_pre_bet_events_cats.bet_event_cat_id=af_pre_bet_options.bet_event_cat_id WHERE af_pre_bet_events_cats.bet_event_id=$event_id AND af_pre_bet_events_cats.bet_event_cat_name LIKE '%".$term."%'";	
		}
		
		$event_data=mysqli_query($conn,$query);
		$structured_data=array();
		$temp_cat_id='';
		
		while($row=mysqli_fetch_assoc($event_data)){
	
			if($temp_cat_id==$row['bet_event_cat_id']){
				$option_name=$row['bet_option_name'];
				$option_id=$row['bet_option_id'];
				$odd=$row['bet_option_odd'];
				$o_sort=$row['o_sort'];
				$bet_option=['bet_option_name'=>$option_name,'bet_option_id'=>$option_id,'odd'=>$odd,'o_sort'=>$o_sort];
				array_push($structured_data[strval($temp_cat_id)]['bet_options'],$bet_option);
			}else{
				$temp_cat_id=$row['bet_event_cat_id'];
				$cat_name=$row['bet_event_cat_name'];
				$cat_id=$row['bet_event_cat_id'];
				$spid=$row['spid'];
				$option_name=$row['bet_option_name'];
				$option_id=$row['bet_option_id'];
				$odd=$row['bet_option_odd'];
				$c_sort=$row['c_sort'];
				$o_sort=$row['o_sort'];
				$bet_option=['bet_option_name'=>$option_name,'bet_option_id'=>$option_id,'odd'=>$odd,'o_sort'=>$o_sort];	
				$structured_data[strval($temp_cat_id)]=['cat_name'=>$row['bet_event_cat_name'],'c_sort'=>$c_sort,'spid'=>$spid,'bet_options'=>[$bet_option]];
			}
		}


		return array('categories'=>$structured_data);
		//var_dump($event_data);
	}
	
	$bet_event_data=getEventData($event_id, $caton,$term, $conn);
	
 };
 
$usid = $_POST['usid'];
$slip = "SELECT * FROM sh_sf_slips WHERE user_id='$usid' AND status='awaiting' AND event_id = $event_id AND bet_info IS NULL AND type <> 'sbook'";
$shslips = mysqli_query($conn,$slip);

$avaiting_odd_list_back=array();
$avaiting_odd_list_lay=array();

while($shsl = $shslips->fetch_assoc()){
            $winnings = $shsl['winnings'];
			$option_id = $shsl['bet_option_id'];
			$option_name = $shsl['bet_option_name'];
			$bet_info = $shsl['bet_info'];
			$event_id = $shsl['event_id'];
			$type = $shsl['type'];
			$okk = $shsl['bet_option_name']. ' ' .$shsl['bet_option_id'];
			
			
              if($type == "back"){
                  $avaiting_odd_list_back[$okk]+=$winnings;
				  $c_back[$okk]=$shsl['stake'];
				  $c_odd[$okk]= $shsl['odd'];
				  $s_back[$okk]= $shsl['slip_id'];
				  //for laybet
              }else if($type == "lay"){
                  $avaiting_odd_list_lay[$okk]+=$winnings;
                  //$avaiting_odd_list_lay[$data[0]['bid_id'].'-count']=$data[0]['count']+1; for sort_order
              }
            
		};
		
		//function format update slip back
	function bgetOdd($bodk){	 
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	  $decimal_odd = $bodk;
	  if (2 > $decimal_odd) {
                $plus_minus = '-';
                $result = 100 / ($decimal_odd - 1);  
            } else {              
                $plus_minus = '+';
                $result = ($decimal_odd - 1) * 100;
            }       
            return ($plus_minus . round($result, 2));
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $bodk;
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{ 
  return $bodk;
  }
};
	 
//function format update slip laybet
	function lgetOdd($lodk){
	 if(isset($_COOKIE["theme"]) && $_COOKIE['theme']== "american"){
	   $decimal_oddlay = $lodk;
	    if (2 > $decimal_oddlay) {
                $plus_minusl = '-';
                $resultl = 100 / ($decimal_oddlay - 1);  
            } else {              
                $plus_minusl = '+';
                $resultl = ($decimal_oddlay - 1) * 100;
            }       
            return ($plus_minusl . round($resultl, 2));
			
     }else if(isset($_COOKIE['theme']) && $_COOKIE['theme']== "fraction" ){
	  //for back
	  $decimal_odd = $lodk;
	  if (2 == $decimal_odd) {
                return '1/1';
            }         
            $dividend = intval(strval((($decimal_odd - 1) * 100)));
            $divisor = 100;
            
            $smaller = ($dividend > $divisor) ? $divisor : $dividend;
            
            //worst case: 100 iterations
            for ($common_denominator = $smaller; $common_denominator > 0; $common_denominator --) {
                if ( (0 === ($dividend % $common_denominator)) && (0 === ($divisor % $common_denominator)) ) {              
                    $dividend /= $common_denominator;
                    $divisor /= $common_denominator;                 
                    return ($dividend . '/' . $divisor);
                }
            }           
            return ($dividend . '/' . $divisor);
	  
  }else{
	  
  return $lodk;
  }
};	
		
 
     if(empty($bet_event_data['categories'])){
		 echo '<div class="opun"><div class="evsus op"><i class="icon warning sign"></i> Options Suspended</div></div>';
		 die();
	 }
	 echo '<div class="masterwrapper">';
    foreach ($bet_event_data['categories'] as $key => $category) {
		if($category['c_sort'] !== '1'):?>
		
		<div class="cs modelwrap xp">
		<div class="catTop1 xp"><i class="icon timeline"></i> <?php echo $category['cat_name'];?> <span class="sfright xp"><i class="icon checkbox checked alt" title="Cashout Available"></i> <i class="icon star full" title="Top Category"></i><i class="icon ellipsis vertical"></i><a href="/" id="rul">Rules</a></span></div>
		<div class="matvhrf xp">Matched: <span class="oftrack xp"><?php echo rand(10000,99000);?></span> <span class="laback xp">Back Lay</span></div>
		<?php foreach ($category['bet_options'] as $key_options => $option) {
			//for odd format
			$bodk = $option['odd'];
			$lodk = $bodk + 0.02;
			$bod = bgetOdd($bodk);
			$lod = lgetOdd($lodk);
			$backrm = rand(1000,9000);
			$layrm = rand(10,100);
			$okid = $option['bet_option_name']. ' ' .$option['bet_option_id'];
			$cstake = $c_back[$okid];
		    $curod = $option['odd'];	//current odd		  
			$codd = $c_odd[$okid];
			
			?>
        <div class="b_option_wrapper xp">		
        <span class="b_option_name xp">
		  <div class="onamebg">.</div> <?php echo $option['bet_option_name']; ?>
		  <span class="cashwrapper">
		   <a><?php echo $avaiting_odd_list_back[$okid];?></a> 
		   <?php if(!empty($cstake)):
		   $ult = $avaiting_odd_list_back[$okid]/$curod; $nt = $ult * 20/100; $ut = $ult - $nt;
		   echo '<span class="casout mo-'.$bodk.'" id="'.$s_back[$okid].'">Cash '.round($ut, 2).'</span>'; endif;?>		  
		  <span class="lyod"><?php $ck = $avaiting_odd_list_lay[$okid]; if(!empty($ck)){echo '-'.$ck.'';};?></span>
		  </span> 
	  </span>
		  <div class="b_option_odd evn-<?php echo $option['bet_option_name'];?>" id="bet__option__btn__<?php echo $option['bet_option_id'] ?>__<?php echo $event_id;?>__<?php echo $key ;?>__<?php echo $evname;?>__<?php echo $category['cat_name'];?>__<?php echo $bodk;?>__<?php echo $lodk;?>__<?php echo $category['spid'];?>__<?php echo $bod;?>__<?php echo $lod;?>">
		  
		  <span class="bback" id="cor-<?php echo $option['bet_option_id'];?>"><?php echo $bod;?>
		  <ft class="bm"><?php echo $backrm;?></ft></span> <span class="blay" id="corx-<?php echo $option['bet_option_id'];?>" style="display:block!important"><?php echo $lod;?><ft class="lm"><?php echo $layrm;?></ft></span>
           </div>   
        </div>		
        <?php }?>
	</div>
	<?php endif;?>
	<?php } 
		
?>
</div>