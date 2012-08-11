<?PHP
require("include/include.php");

$extra = getMoreAthleteData();
$athletes = getAthleteData();

$isName = false;
foreach ($athletes as &$athlete) {
	if (strtolower($_GET['name']) == strtolower($athlete["name"])) {
		$isName = true;
	}
}

foreach ($extra as &$data) {
	if (strtolower($data[1].$data[0]) == strtolower($_GET["name"])) {
		$extraData = $data;
	}
}
if ($isName) {
	if (!empty($_GET['name'])) {
		if (!empty($extraData[4])) {
			echo $extraData[4]." - ";
		}
	}
} else {
	echo 0;
}
?>