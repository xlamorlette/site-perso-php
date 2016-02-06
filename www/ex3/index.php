<?
  $repertoireCourant = dirname($_SERVER['SCRIPT_FILENAME']);
  $relatif = substr(strstr($repertoireCourant, "/www/"), strlen("/www/"));
  $categories = explode("/", $relatif);
  $nbNiveaux = count($categories);
  $racine = "";
  for ($i = 1; $i <= $nbNiveaux; $i++)
  {
    $racine .= "../";
  }
  require_once($racine . "commun.php");
?>
