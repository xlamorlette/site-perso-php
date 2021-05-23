<?
$current_path = dirname($_SERVER['SCRIPT_FILENAME']);
$relative_path = substr(strstr($current_path, "/www/"), strlen("/www/"));
$path_elements = explode("/", $relative_path);
$root = str_repeat("../", count($path_elements));
require_once($root . "common.php");
?>
