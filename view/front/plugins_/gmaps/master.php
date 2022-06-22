<?php
  /**
   * Gmaps
   *
   * @package Yoyo Framework
   * @author yoyostack.com
   * @copyright 2015
   */
  
  if (!defined("_YOYO"))
	  die('Direct access to this location is not allowed.');
  
  Bootstrap::Autoloader(array(AMODPATH . 'gmaps/'));
?>
<?php if($row = App::Gmaps()->render($data['plugin_id'])):?>
<div id="gmap_<?php echo $row->id;?>" style="height:500px"></div>
<script type="text/javascript"> 
// <![CDATA[  
function bootstrap() {
    if (typeof google === 'object' && typeof google.maps === 'object') {
        runMap();
    } else {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "//maps.google.com/maps/api/js?key=<?php echo App::Core()->mapapi;?>&callback=runMap";
        document.body.appendChild(script);
    }
}
function runMap() {
	  var markers = [];
	  var map;
	  
	  <?php $minmaxzoom = explode(",", $row->minmaxzoom);?>
	  var newMapOptions = {
		  center: new google.maps.LatLng(<?php echo $row->lat;?>, <?php echo $row->lng;?>),
		  zoom: <?php echo $row->zoom;?>,
		  minZoom: <?php echo $minmaxzoom[0];?>,
		  maxZoom: <?php echo $minmaxzoom[1];?>,
		  zoomControlOptions: {
			  style: google.maps.ZoomControlStyle.SMALL
		  },
		  scaleControl: true,
		  mapTypeId: "<?php echo $row->type;?>",
		  mapTypeControl: <?php echo $row->type_control;?>,
		  streetViewControl: <?php echo $row->streetview;?>,
		  styles: <?php echo $row->style;?>,
	  };
	  map = new google.maps.Map(document.getElementById("gmap_<?php echo $row->id;?>"), newMapOptions);

	  //set marker
	  var marker = new google.maps.Marker({
		  position: new google.maps.LatLng(<?php echo $row->lat;?>, <?php echo $row->lng;?>),
		  map: map,
		  draggable: false,
		  animation: google.maps.Animation.DROP,
		  raiseOnDrag: false,
		  icon: "<?php echo FMODULEURL . 'gmaps/view/images/pins/' . $row->pin;?>",
		  title: "<?php echo $row->name;?>"
	  });
	  
	  //set infowindow
	  var content = 
		  '<div class="container">' +
			'<h5><?php echo $row->name;?></h5>' +
			'<div class="content">' +
			  '<?php echo $row->body;?>' +
			'</div>' +
		  '</div>';
		  
	  var infowindow = new google.maps.InfoWindow({
		  content: content,
		  maxWidth: 350,
		  maxHeight: 350
	  });
	  
	  marker.addListener('click', function() {
		infowindow.open(map, marker);
	  });
	
	  markers.push(marker);
}
bootstrap();
// ]]>
</script>
<?php endif;?>