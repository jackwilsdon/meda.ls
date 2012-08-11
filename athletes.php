<?PHP
require("include/include.php");
$data = getAthleteData();
$output = array();

foreach ($data as &$dat) {
	if ($dat["location"] == $_GET['place'] && !empty($dat[strtolower($_GET['medal'])])) {
		array_push($output, $dat);
	}
}

foreach ($output as &$person) {
	echo "<p><a href=\"#\" class=\"person\">".$person["name"]."</a></p>";
}

?>