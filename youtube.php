<?PHP
require("include/include.php");
$data = xml2array(getYoutubeVideo(urlencode($_GET["name"]." ".$_GET["medal"]." medal 2012 london")));
$videoURL = $data["feed"]["entry"][0]["media:group"]["media:content"]["0_attr"]["url"];
$videoURL = explode("/v/", $videoURL);
$videoURL = explode("?", $videoURL[1]);
$videoURL = $videoURL[0];
if (!empty($videoURL)) {
	echo "<a href=\"http://www.youtube.com/watch?v=".$videoURL."\" target=\"_blank\"><img src=\"http://www.four-pillars.co.uk/images/youtube-logo.png\"></a>";
}
?>