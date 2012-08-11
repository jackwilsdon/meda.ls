<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/
1.4.2/jquery.min.js"></script>
<script type="text/javascript">

function error(msg) {
	var s = document.querySelector('#status');
	s.innerHTML = typeof msg == 'string' ? msg : "failed";
	s.className = 'Fail';
}
if (navigator.geolocation) {
	navigator.geolocation.getCurrentPosition(success, error);
} else {
	error('Not supported'); //HTML Support
}

</script>
<div id="map"></div>