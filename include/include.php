<?PHP

function xml2array($contents, $get_attributes = 1, $priority = 'tag')
{
	if (!$contents)
		return array();
	
	if (!function_exists('xml_parser_create')) {
		return array();
	}
	
	$parser = xml_parser_create('');
	xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parse_into_struct($parser, trim($contents), $xml_values);
	xml_parser_free($parser);
	
	if (!$xml_values)
		return;
	
	$xml_array   = array();
	$parents     = array();
	$opened_tags = array();
	$arr         = array();
	
	$current =& $xml_array;
	
	$repeated_tag_index = array();
	foreach ($xml_values as $data) {
		unset($attributes, $value);
		
		extract($data);
		
		$result          = array();
		$attributes_data = array();
		
		if (isset($value)) {
			if ($priority == 'tag')
				$result = $value;
			else
				$result['value'] = $value;
		}
		
		if (isset($attributes) and $get_attributes) {
			foreach ($attributes as $attr => $val) {
				if ($priority == 'tag')
					$attributes_data[$attr] = $val;
				else
					$result['attr'][$attr] = $val;
			}
		}
		
		if ($type == "open") {
			$parent[$level - 1] =& $current;
			if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
				$current[$tag] = $result;
				if ($attributes_data)
					$current[$tag . '_attr'] = $attributes_data;
				$repeated_tag_index[$tag . '_' . $level] = 1;
				
				$current =& $current[$tag];
				
			} else {
				if (isset($current[$tag][0])) {
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					$repeated_tag_index[$tag . '_' . $level]++;
				} else {
					$current[$tag]                           = array(
						$current[$tag],
						$result
					);
					$repeated_tag_index[$tag . '_' . $level] = 2;
					
					if (isset($current[$tag . '_attr'])) {
						$current[$tag]['0_attr'] = $current[$tag . '_attr'];
						unset($current[$tag . '_attr']);
					}
					
				}
				$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
				$current =& $current[$tag][$last_item_index];
			}
			
		} elseif ($type == "complete") {
			if (!isset($current[$tag])) {
				$current[$tag]                           = $result;
				$repeated_tag_index[$tag . '_' . $level] = 1;
				if ($priority == 'tag' and $attributes_data)
					$current[$tag . '_attr'] = $attributes_data;
				
			} else {
				if (isset($current[$tag][0]) and is_array($current[$tag])) {
					$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
					
					if ($priority == 'tag' and $get_attributes and $attributes_data) {
						$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
					}
					$repeated_tag_index[$tag . '_' . $level]++;
					
				} else {
					$current[$tag]                           = array(
						$current[$tag],
						$result
					);
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $get_attributes) {
						if (isset($current[$tag . '_attr'])) {
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset($current[$tag . '_attr']);
						}
						
						if ($attributes_data) {
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
					}
					$repeated_tag_index[$tag . '_' . $level]++;
				}
			}
			
		} elseif ($type == 'close') {
			$current =& $parent[$level - 1];
		}
	}
	
	return ($xml_array);
}

function getYoutubeVideo($string)
{
	$url     = "http://gdata.youtube.com/feeds/api/videos?q=" . $string . "&safeSearch=strict&orderby=rating&v=2";
	$crl     = curl_init();
	$timeout = 5;
	curl_setopt($crl, CURLOPT_URL, $url);
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	return $ret;
	
}

function getGoogleImage($string)
{
	$url     = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=" . $string;
	$crl     = curl_init();
	$timeout = 5;
	curl_setopt($crl, CURLOPT_URL, $url);
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	$result = json_decode($ret, true);
	return $result["responseData"]["results"][0]["tbUrl"];
}

function arraySortTwoDimByValueKey($array, $by, $type = 'asc')
{
	$sortField =& $by;
	$multArray =& $array;
	$fixed = array();
	
	$tmpKey   = '';
	$ResArray = array();
	$maIndex  = array_keys($multArray);
	$maSize   = count($multArray) - 1;
	for ($i = 0; $i < $maSize; $i++) {
		$minElement = $i;
		$tempMin    = $multArray[$maIndex[$i]][$sortField];
		$tmpKey     = $maIndex[$i];
		for ($j = $i + 1; $j <= $maSize; $j++) {
			if ($multArray[$maIndex[$j]][$sortField] < $tempMin) {
				$minElement = $j;
				$tmpKey     = $maIndex[$j];
				$tempMin    = $multArray[$maIndex[$j]][$sortField];
			}
		}
		$maIndex[$minElement] = $maIndex[$i];
		$maIndex[$i]          = $tmpKey;
	}
	if ($type == 'asc') {
		for ($j = 0; $j <= $maSize; $j++) {
			$ResArray[$maIndex[$j]] = $multArray[$maIndex[$j]];
		}
	} else {
		for ($j = $maSize; $j >= 0; $j--) {
			$ResArray[$maIndex[$j]] = $multArray[$maIndex[$j]];
		}
	}
	
	foreach (array_keys($ResArray) as $name) {
		if ($ResArray[$name]["place"] == NULL) {
			unset($ResArray[$name]);
		}
	}
	
	return $ResArray;
}

function getMoreAthleteData()
{
	$data         = file_get_contents("data/athletes.tsv");
	$finalData    = array();
	$explodedData = explode("\n", $data);
	
	foreach ($explodedData as &$dat) {
		$subExplodedData = explode("\t", $dat);
		array_push($finalData, $subExplodedData);
	}
	
	return $finalData;
}

function getSport($name) {
	$data         = getMoreAthleteData();
	$finalData = array();
	
	foreach ($data as &$dat) {
		if (strtolower($dat["1"].$dat["0"]) == strtolower($name) && $name != null) {
			array_push($finalData, $dat);
		}
	}
	
	return $finalData[0][8];
}

function getAthleteData()
{
	$data      = json_decode(file_get_contents("data/moreathletedata.json"), true);
	$finalData = array();
	$dd        = array();
	foreach ($data as &$dat) {
		foreach (array_keys($dat) as $d) {
			if ($d != "r" && $d != "rT" && $d != "odfID") {
				array_push($dd, $dat[$d]);
			}
		}
		
		$tmp  = explode(" ", $dd[1]);
		$tmp2 = array_shift($tmp);
		$tmp3 = array();
		array_push($tmp, $tmp2);
		foreach ($tmp as &$t) {
			array_push($tmp3, ucfirst(strtolower($t)));
		}
		$dd[1] = implode(" ", $tmp3);
		
		
		
		$part = array(
			"location" => $dd[0],
			"name" => $dd[1],
			"gold" => $dd[2],
			"silver" => $dd[3],
			"bronze" => $dd[4],
			"total" => $dd[5]
		);
		$dd   = array();
		
		array_push($finalData, $part);
		
	}
	return $finalData;
	
}

function getExtraData()
{
	$file         = file_get_contents("data/moredata.tsv");
	$explodedFile = explode("\n", $file);
	$finalData    = array();
	foreach ($explodedFile as &$data) {
		$explodedData                = explode("\t", $data);
		$finalData[$explodedData[0]] = $explodedData;
	}
	return $finalData;
}

function getMedalData()
{
	$url     = "entirely.pro/yrs2012/olympic-medals.json";
	$crl     = curl_init();
	$timeout = 5;
	curl_setopt($crl, CURLOPT_URL, $url);
	curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$ret = curl_exec($crl);
	curl_close($crl);
	$data  = json_decode(file_get_contents("data/olympic-medals.json"), true);
	$final = array();
	foreach ($data as &$dat) {
		$placeName = $dat["noc"];
		array_shift($dat);
		$dat               = array(
			"name" => $dat["name"],
			"gold" => $dat["g"],
			"silver" => $dat["s"],
			"bronze" => $dat["b"]
		);
		$final[$placeName] = $dat;
	}
	return $final;
}

function resize($img, $width, $height, $stretch = false)
{
	$temp = imagecreatetruecolor($width, $height);
	imagealphablending($temp, true);
	imagesavealpha($temp, true);
	
	$bg = imagecolorallocatealpha($temp, 0, 0, 0, 127);
	imagefill($temp, 0, 0, $bg);
	
	if ($stretch) {
		imagecopyresampled($temp, img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
	} else {
		if (imagesx($img) <= $width && imagesy($img) <= $height) {
			$fwidth  = imagesx($img);
			$fheight = imagesy($img);
		} else {
			$wscale  = $width / imagesx($img);
			$hscale  = $height / imagesy($img);
			$scale   = min($wscale, $hscale);
			$fwidth  = $scale * imagesx($img);
			$fheight = $scale * imagesy($img);
		}
		imagecopyresampled($temp, $img, ($width - $fwidth) / 2, ($height - $fheight) / 2, 0, 0, $fwidth, $fheight, imagesx($img), imagesy($img));
	}
	return $temp;
}

?>