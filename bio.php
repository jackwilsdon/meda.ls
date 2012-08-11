<?PHP
require("include/include.php");

$extra = getMoreAthleteData();

foreach ($extra as &$data) {
	if (strtolower($data[1].$data[0]) == strtolower($_GET["name"])) {
		$extraData = $data;
	}
}

$name = $_GET["name"];

if (empty($_GET["medal"])) {
	$searchName = urlencode($name." olympics 2012");
} else {
	$searchName = urlencode($name." ".$_GET["medal"]." olympics");
}

$result = getGoogleImage($searchName);
if (!empty($name) && !empty($result)) {
	if (empty($extraData)) {
		echo "<p><img src=\"".$result."\" align=\"left\" class=\"profile\">We were unable to find any extra data about this athlete.</p>";
	} else {
		echo "<p><img src=\"".$result."\" align=\"left\"/ class=\"profile\">Height: {$extraData[2]}cm <br />Weight: {$extraData[3]}kg <br />Sport: {$extraData[8]} <br />Extra data: {$extraData[9]}</p><br /><p><div id=\"map\" width=\"500px\" height=\"300px\"></div></p>";
	}
} else {
	echo "Something went wrong!";
}
?>