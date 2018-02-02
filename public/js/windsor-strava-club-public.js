var WindsorStravaClub = {

	initMap:function(resp, atts) {
		
		if (resp.length > 1) {			
			// Retrieve first activity date
			var firstActivity = resp.length-1;			
			// Add i18n
			moment.locale(atts.locale);
			var mDate = moment(resp[firstActivity].start_date_local);
			jQuery('.wsc-date').text(mDate.format("L"));			
		}		
		var zoom = parseInt(atts.zoom, 10);

	    // var myLatlng = new google.maps.LatLng(39.7469, -105.2108);
	    var myLatlng = new google.maps.LatLng(atts.lat, atts.lng);
	    var myOptions = {
	        zoom: zoom,
	        center: myLatlng,
	        mapTypeId: google.maps.MapTypeId.TERRAIN
	    }
	    var map = new google.maps.Map(document.getElementById("map"), myOptions);

	    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(WindsorStravaClub.fullScreenControl(map));

	    for (var i = resp.length - 1; i >= 0; i--) {

	    	// // console.log(resp[i]);
	    	var infowindow = new google.maps.InfoWindow({ pixelOffset: new google.maps.Size(0,-48)});
	    	var marker, i;

	    	// // Decode 

	    	if (resp[i].map.summary_polyline != null) {
		    	var decodedPath = google.maps.geometry.encoding.decodePath(resp[i].map.summary_polyline); 
		    	var decodedLevels = WindsorStravaClub.decodeLevels("BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB");

		    	// Draw polyline
		    	var setRegion = new google.maps.Polyline({
		    	    path: decodedPath,
		    	    levels: decodedLevels,
		    	    strokeColor: "#FF0000",
		    	    strokeOpacity: .5,
		    	    strokeWeight: 2,
		    	    map: map
		    	});

		    	if (resp[i].athlete.profile_medium == 'avatar/athlete/medium.png') {
		    		var avatar = 'https://d3nn82uaxijpm6.cloudfront.net/assets/avatar/athlete/medium-989c4eb40a5532739884599ed662327c.png';
		    	} else {
		    		var avatar = resp[i].athlete.profile_medium;
		    	}

		    	marker = new RichMarker({
		    	  position: new google.maps.LatLng(decodedPath[0].lat(), decodedPath[0].lng()),
		    	  map: map,
		    	  shadow: 'none',
		    	  content: '<div class="wsc-label"><img src="' + avatar + '"/></div>',
		    	  // labelClass: 'wsc-label'
		    	});


		    	google.maps.event.addListener(marker, 'click', (function(marker, i) {
		    	  return function() {
		    	  	var mc = '<div class="athlete"><strong>' + resp[i].athlete.firstname + ' ' + resp[i].athlete.lastname + '</strong></div>';
		    	  		mc += '<div class="athlete">' + resp[i].name + '</div>';
		    	  		// mc += '<div class="athlete-page"><a target="_blank" href="https://www.strava.com/athletes/' + resp[i].athlete.id + '">Athlete Page</a></div>';
		    	  		// mc += '<div class="on-strava"><a target="_blank" href="https://www.strava.com/activities/' + resp[i].id + '">View on Strava</a></div>';
		    	    infowindow.setContent(mc);
		    	    infowindow.open(map, marker);
		    	  }
		    	})(marker, i));
		    }
	    }     
	},

	decodeLevels:function(encodedLevelsString) {
	    var decodedLevels = [];

	    for (var i = 0; i < encodedLevelsString.length; ++i) {
	        var level = encodedLevelsString.charCodeAt(i) - 63;
	        decodedLevels.push(level);
	    }
	    return decodedLevels;
	},

	googleMapButton:function (text, className) {
	    "use strict";
	    var controlDiv = document.createElement("div");
	    controlDiv.className = className;
	    controlDiv.index = 1;
	    controlDiv.style.padding = "10px";
	    // set CSS for the control border.
	    var controlUi = document.createElement("div");
	    controlUi.style.backgroundColor = "rgb(255, 255, 255)";
	    controlUi.style.color = "#565656";
	    controlUi.style.cursor = "pointer";
	    controlUi.style.textAlign = "center";
	    controlUi.style.boxShadow = "rgba(0, 0, 0, 0.298039) 0px 1px 4px -1px";
	    controlDiv.appendChild(controlUi);
	    // set CSS for the control interior.
	    var controlText = document.createElement("div");
	    controlText.style.fontFamily = "Roboto,Arial,sans-serif";
	    controlText.style.fontSize = "11px";
	    controlText.style.paddingTop = "8px";
	    controlText.style.paddingBottom = "8px";
	    controlText.style.paddingLeft = "8px";
	    controlText.style.paddingRight = "8px";
	    controlText.innerHTML = text;
	    controlUi.appendChild(controlText);
	    jQuery(controlUi).on("mouseenter", function () {
	        controlUi.style.backgroundColor = "rgb(235, 235, 235)";
	        controlUi.style.color = "#000";
	    });
	    jQuery(controlUi).on("mouseleave", function () {
	        controlUi.style.backgroundColor = "rgb(255, 255, 255)";
	        controlUi.style.color = "#565656";
	    });
	    return controlDiv;
	},
	
	fullScreenControl:function(map, enterFull, exitFull) {
	    "use strict";
	    if (enterFull === void 0) { enterFull = null; }
	    if (exitFull === void 0) { exitFull = null; }
	    if (enterFull == null) {
	        enterFull = "Full screen";
	    }
	    if (exitFull == null) {
	        exitFull = "Exit full screen";
	    }
	    var controlDiv = WindsorStravaClub.googleMapButton(enterFull, "fullScreen");
	    var fullScreen = false;
	    var interval;
	    var mapDiv = map.getDiv();
	    var divStyle = mapDiv.style;
	    if (mapDiv.runtimeStyle) {
	        divStyle = mapDiv.runtimeStyle;
	    }
	    var originalPos = divStyle.position;
	    var originalWidth = divStyle.width;
	    var originalHeight = divStyle.height;
	    // ie8 hack
	    if (originalWidth === "") {
	        originalWidth = mapDiv.style.width;
	    }
	    if (originalHeight === "") {
	        originalHeight = mapDiv.style.height;
	    }
	    var originalTop = divStyle.top;
	    var originalLeft = divStyle.left;
	    var originalZIndex = divStyle.zIndex;
	    var bodyStyle = document.body.style;
	    if (document.body.runtimeStyle) {
	        bodyStyle = document.body.runtimeStyle;
	    }
	    var originalOverflow = bodyStyle.overflow;
	    controlDiv.goFullScreen = function () {
	        var center = map.getCenter();
	        mapDiv.style.position = "fixed";
	        mapDiv.style.width = "100%";
	        mapDiv.style.height = "100%";
	        mapDiv.style.top = "0";
	        mapDiv.style.left = "0";
	        mapDiv.style.zIndex = "100";
	        document.body.style.overflow = "hidden";
	        jQuery(controlDiv).find("div div").html(exitFull);
	        fullScreen = true;
	        google.maps.event.trigger(map, "resize");
	        map.setCenter(center);
	        // this works around street view causing the map to disappear, which is caused by Google Maps setting the 
	        // css position back to relative. There is no event triggered when Street View is shown hence the use of setInterval
	        interval = setInterval(function () {
	            if (mapDiv.style.position !== "fixed") {
	                mapDiv.style.position = "fixed";
	                google.maps.event.trigger(map, "resize");
	            }
	        }, 100);
	    };
	    controlDiv.exitFullScreen = function () {
	        var center = map.getCenter();
	        if (originalPos === "") {
	            mapDiv.style.position = "relative";
	        }
	        else {
	            mapDiv.style.position = originalPos;
	        }
	        mapDiv.style.width = originalWidth;
	        mapDiv.style.height = originalHeight;
	        mapDiv.style.top = originalTop;
	        mapDiv.style.left = originalLeft;
	        mapDiv.style.zIndex = originalZIndex;
	        document.body.style.overflow = originalOverflow;
	        jQuery(controlDiv).find("div div").html(enterFull);
	        fullScreen = false;
	        google.maps.event.trigger(map, "resize");
	        map.setCenter(center);
	        clearInterval(interval);
	    };
	    // setup the click event listener
	    google.maps.event.addDomListener(controlDiv, "click", function () {
	        if (!fullScreen) {
	            controlDiv.goFullScreen();
	        }
	        else {
	            controlDiv.exitFullScreen();
	        }
	    });
	    return controlDiv;
	}

}

// WindsorStravaClub.init();

