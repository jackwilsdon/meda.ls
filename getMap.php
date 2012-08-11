<?PHP
echo file_get_contents("https://maps.googleapis.com/maps/api/place/search/json?key=AIzaSyDujnKfXNxmr3QImfauSlei1CbR0YOl_cs&location={$_GET['longlat']}&radius=5000&sensor=false&keyword={$_GET['keyword']}&types=gym");
?>