function success(position) {

	var mapcanvas = document.createElement('div');
	mapcanvas.id = 'mapcanvas';
	mapcanvas.style.height = '300px';
	mapcanvas.style.width = '500px';
	
	document.querySelector('#map').appendChild(mapcanvas);
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	var myOptions = {
		zoom: 15,
		center: latlng,
		mapTypeControl: false,
		navigationControlOptions: {
			style: google.maps.NavigationControlStyle.SMALL
		},
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);8
	 
	var marker = new google.maps.Marker({
		position: latlng,
		map: map
	});
	
		
	$.get("getMap.php", {
		key: "AIzaSyDujnKfXNxmr3QImfauSlei1CbR0YOl_cs",
		longlat: position.coords.latitude+","+position.coords.longitude,
		keyword: window.sport
	}, function (data) {
		var data = JSON.parse(data);
		var thisplace = null;
		var location = null;
		var markers = new Array();
		
		data = data["results"];
		
		console.log(data);
		
		for (x in data) {
		
			thisplace = data[x];
			location = thisplace["geometry"]["location"];
			var location = new google.maps.LatLng(location["lat"], location["lng"]);
			
			markers[x] = new google.maps.Marker({
				position: location,
				map: map
			});
			
		}
	});
}

function error(msg) {
	alert("Unable to get your location!");
}

var sport;

function displayMap(sport) {
	if (navigator.geolocation) {
		window.sport = sport;
		navigator.geolocation.getCurrentPosition(success, error);
	} else {
		error('Not supported'); //HTML Support
	}
}