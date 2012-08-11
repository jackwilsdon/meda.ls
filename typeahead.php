<?PHP

function getAthleteData()
{
	$data = json_decode(file_get_contents("data/moreathletedata.json"), true);
	$finalData = array();
	$dd = array();
	foreach ($data as &$dat) {
		foreach (array_keys($dat) as $d) {
			if ($d != "r" && $d != "rT" && $d != "odfID") {
				array_push($dd, $dat[$d]);
			}
		}
		
		$tmp = explode(" ", $dd[1]);
		$tmp2 = array_shift($tmp);
		$tmp3 = array();
		array_push($tmp, $tmp2);
		foreach ($tmp as &$t) {
			array_push($tmp3, ucfirst(strtolower($t)));
		}
		$dd[1] = implode(" ", $tmp3);
		
		array_push($finalData, $dd[1]);
		$dd = array();
		
	}
	return $finalData;
	
}

$athletes = getAthleteData();

echo json_encode($athletes);
?>