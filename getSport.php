<?PHP
require("include/include.php");
$name = $_GET["name"];
$data = explode("-", getSport($name));
echo $data[0];
?>