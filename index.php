<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Meda.ls</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" type="image/ico" href="/img/favicon.ico" />
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
	</head>
	
	<body>
	<div class="container">
		<div class="page-header">
			<h1>Meda.ls</h1>
		</div>
		<div class="modal hide fade" id="modal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&amp;times;</button>
				<h3>Modal header</h3>
			</div>
			<div class="modal-body">
				<p>Error! Unable to load content.</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal" style="width: 50px">Close</a>
			</div>
		</div>
		<div class="row">
			<div class="span8 offset2">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Place</th>
							<th>Medals</th>
							<th>Country</th>
							<th class="searchTH">
								<form class="searchForm" onsubmit="submitForm(event)">
									<div class="input-append">
										<input type="text" class="span3" id="search" style="margin: 0 auto;" data-provide="typeahead" data-items="8" placeholder="Search"><button id="searchBTN" data-loading-text="Erm..." class="btn"><i class="icon-search"></i></button>
									</div>
								</form>
							</th>
						</tr>
					</thead>
					<tbody>
<?PHP
require("include/include.php");
$medals = getMedalData();
$extraData = getExtraData();
foreach(array_keys($medals) as $name) {
	$medals[$name]["place"] = $extraData[$name][40];
}

$medals = arraySortTwoDimByValueKey($medals,'place','asc');
//var_dump($medals);
	
foreach(array_keys($medals) as $name) {
	$placename = $medals[$name]["name"];
	if ($medals[$name]["place"] != NULL) {
		$fact = $medals[$name]["place"];
	}
	$id = $fact;
	echo "						<tr>
							<!--$name-->
							<td class=\"id\">
								<a href=\"#\" class=\"btn btn-danger pop\" data-content=\"<a href='#'><h3>Gold</h3></a><a href='#'><h3>Silver</h3></a><a href='#'><h3>Bronze</h3></a>\" data-original-title=\"Select a category\">$id</a>
							</td>
							<td class=\"medals\">
								<img src=\"medal.php?type=gold&amp;c=$name\" alt=\"gold-".strtolower($name)."\">
								<img src=\"medal.php?type=silver&amp;c=$name\" alt=\"silver-".strtolower($name)."\"> 
								<img src=\"medal.php?type=bronze&amp;c=$name\" alt=\"bronze-".strtolower($name)."\">
							</td>
							<td colspan=\"2\" class=\"countryName\">$placename</td>
						</tr>\n";
}
?>
					</tbody>
				</table>
			</div>
		</div>
		
		<hr>
	
		<footer>
			<p class="centered"><strong>Meda.ls</strong> uses open data available on the web. <strong>Meda.ls</strong> is updated dynamically from these data sourves.</p>
			<p class="centered"><img src="img/meda.ls.logo.png" alt="meda.ls.logo" width="100" height="100" /></p>
		</footer>
	</div> 
	
	<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/map.js"></script>
	<script src="js/custom.js"></script>
	<script src="js/search.js"></script>
	</body>
</html>