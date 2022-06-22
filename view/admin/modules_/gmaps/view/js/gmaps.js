(function($) {
    "use strict";
    $.Gmaps = function(settings) {
        var config = {
            url: "",
            murl: "",
            furl: "",
        };
        if (settings) {
            $.extend(config, settings);
        }

        var map;
        var geocoder;
        var markers = [];
        geocoder = new google.maps.Geocoder();

        loadMap();

        // Loads the maps
        function loadMap() {
            if ($.inArray("edit", $.url().segment()) !== -1) {
                $.get(config.url, {
                    action: "loadMap",
                    id: $.url().segment(-1)
                }, function(json) {
                    var minmaxzoom = json.minmaxzoom.split(",");
                    var newMapOptions = {
                        center: new google.maps.LatLng(json.lat, json.lng),
                        zoom: json.zoom,
                        minZoom: minmaxzoom[0],
                        maxZoom: minmaxzoom[1],
                        zoomControlOptions: {
                            style: google.maps.ZoomControlStyle.SMALL
                        },
                        scaleControl: true,
                        mapTypeId: json.type,
                        mapTypeControl: json.type_control,
                        streetViewControl: json.streetview,
                        styles: JSON.parse(json.style),
                    };
                    map = new google.maps.Map(document.getElementById("google_map"), newMapOptions);

                    //set marker
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(json.lat, json.lng),
                        map: map,
                        draggable: true,
                        animation: google.maps.Animation.DROP,
                        raiseOnDrag: false,
                        icon: config.furl + 'view/images/pins/' + json.pin,
                        title: json.title
                    });
                    markers.push(marker);
					
                    google.maps.event.addListener(marker, 'dragend', function(event) {
                        $("input[name=lat]").val(this.getPosition().lat());
                        $("input[name=lng]").val(this.getPosition().lng());
                    });

                    google.maps.event.addListener(map, 'zoom_changed', function() {
                        $("input[name=zoom]").val(map.getZoom());
                    });

                }, 'json');
            } else {
				var newMapOptions = {
					center: new google.maps.LatLng(43.6532, -79.3832),
					zoom: 12,
					minZoom: 5,
					maxZoom: 18,
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL
					},
					scaleControl: true,
					mapTypeId: "roadmap",
					mapTypeControl: false,
					streetViewControl: true,
					styles: [],
				};
				map = new google.maps.Map(document.getElementById("google_map"), newMapOptions);
			}

        }

        // find address
        $("button[name=find_address]").on('click', function() {
            var address = $("input[name=address]").val();
            geocoder.geocode({
                'address': address
            }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }
                    map.setCenter(results[0].geometry.location);
                    var marker = new google.maps.Marker({
                        map: map,
                        draggable: true,
                        raiseOnDrag: false,
                        animation: google.maps.Animation.DROP,
                        icon: config.furl + 'view/images/pins/basic.png',
                        position: results[0].geometry.location
                    });
                    $("textarea[name=body]").val(results[0].formatted_address);
                    $("#pinMode").on('click', 'a', function() {
                        var type = $(this).data('type');
                        $("#pinMode img").removeClass('highlite');
                        $(this).children('img').addClass('highlite');
                        $("input[name=pin]").val(type);
                        marker.setIcon(config.furl + 'view/images/pins/' + type);
                    });
					$("input[name=lat]").val(results[0].geometry.location.lat());
					$("input[name=lng]").val(results[0].geometry.location.lng());
					google.maps.event.addListener(marker, 'dragend', function (event) {
						$("#lat").val(this.getPosition().lat());
						$("#lng").val(this.getPosition().lng());
					});
                } else {
                    $.notice('Geocode was not successful for the following reason: ' + status, {
                        type: 'error'
                    });
                }

            });
        });

        // select layout
        $("#layoutMode").on('click', 'a', function() {
            var type = $(this).data('type');
            $("#layoutMode .segment").removeClass('selected');
            $(this).parent().addClass('selected');
            $("input[name=layout]").val(type);
            $.getJSON(config.murl + 'snippets/' + type + '.json', function(json) {
                map.setOptions({
                    styles: json
                });
            });
        });

        // select pin
        $("#pinMode").on('click', 'a', function() {
            var type = $(this).data('type');
            $("#pinMode img").removeClass('highlite');
            $(this).children('img').addClass('highlite');
            $("input[name=pin]").val(type);
            markers[0].setIcon(config.furl + 'view/images/pins/' + type);
        });

        // select map type
        $("select[name=type]").on('change', function() {
            var type = $(this).val();
            map.setOptions({
                mapTypeId: type
            });
        });

        // street view
        $("input[name=streetview]").on('change', function() {
            map.setOptions({
                streetViewControl: parseInt($(this).val()) === 1 ? true : false
            });
        });

        // type control
        $("input[name=type_control]").on('change', function() {
            map.setOptions({
                mapTypeControl: parseInt($(this).val()) === 1 ? true : false
            });
        });

        // zoom level
        $("input[name=zoom]").on('change', function() {
            map.setOptions({
                zoom: parseInt($(this).val())
            });
        });
    };
})(jQuery);