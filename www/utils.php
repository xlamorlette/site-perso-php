<?
// ajoute 5 espaces
function tab()
{
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

// ajoute 3 espaces
function mini_tab()
{
  echo "&nbsp;&nbsp;&nbsp;";
}

// retourne l'extension d'un fichier
function extension($nom_fichier)
{
  // on cherche le dernier '.'
  $pos = strrpos($nom_fichier, ".");
  if ($pos <= 0)
  {
    return "";
  }
  else
  {
    return substr($nom_fichier, $pos+1);
  }
}

// retourne le nom d'un fichier sans son extension
function nomSansExtension($nom_fichier)
{
  // on cherche le dernier '.'
  $pos = strrpos($nom_fichier, ".");
  if ($pos <= 0)
  {
    return "";
  }
  else
  {
    return substr($nom_fichier, 0, $pos);
  }
}

// determine si le fichier est un repertoire, contenant index.php et index.txt
function estPage($nomFichier)
{
  if (basename($nomFichier) === "." || basename($nomFichier) === "..")
  {
    return false;
  }
  if (! is_dir($nomFichier))
  {
    return false;
  }
  if (! file_exists("${nomFichier}/index.php"))
  {
    return false;
  }
  if (! file_exists("${nomFichier}/index.txt"))
  {
    return false;
  }
  return true;
}

// fonction de comparaison pour le tri des pages selon leur nom
function compareNomsPages($a, $b)
{
  return strcmp($a["nom"], $b["nom"]);
}

// construit la structure page pour la structure du site en lisant le fichier index.txt
function construitStructurePage($nomPage, $repertoireCourant)
{
  $page["url"] = $nomPage;
  $fichier = fopen($repertoireCourant . "/" . $nomPage . "/index.txt", "r");
  if (! $fichier)
  {
    return NULL;
  }
  while (! feof($fichier))
  {
    $ligne = fgets($fichier);
    $ligne = rtrim($ligne);
    if (! array_key_exists("nom", $page))
    {
      $page["nom"] = $ligne;
    }
    elseif (! array_key_exists("descriptif", $page))
    {
      $page["descriptif"] = $ligne;
    }
  }
  fclose($fichier);

  $repertoireCourant .= "/" . $nomPage;
  $repertoire = opendir($repertoireCourant);
  while ($nomFichier = readdir($repertoire))
  {
    if (estPage($repertoireCourant . "/" . $nomFichier))
    {
      $sousPage = construitStructurePage($nomFichier, $repertoireCourant);
      $page["pages"][$nomFichier] = $sousPage;
    }
  }
  closedir($repertoire);

  if (array_key_exists("pages", $page))
  {
    usort($page["pages"], "compareNomsPages");
  }

  return $page;
}

// lit la structure des pages du site
function litStructureSite($racine)
{
  $repertoire = opendir($racine);
  while ($nomFichier = readdir($repertoire))
  {
    $nomFichierComplet = $racine . $nomFichier;
    if (estPage($nomFichierComplet))
    {
      $page = construitStructurePage($nomFichierComplet, ".");
      $structure[$nomFichier] = $page;
    }
  }
  closedir($repertoire);
  usort($structure, "compareNomsPages");
  return $structure;
}

// affiche une page et eventuellement ses sous-pages
function affichePage($page, $urlCourante)
{
  $urlCourante .= $page["url"];
  if (array_key_exists("pages", $page))
  {
    echo "<li>- <a href=\"" . $urlCourante . "\">" . $page["nom"] . "</a> : "  .$page["descriptif"] . " :\n<ul>\n";
    $urlCourante .= "/";
    foreach($page["pages"] as $sousPage)
    {
      affichePage($sousPage, $urlCourante);
    }
    echo "</ul></li>\n";
  }
  else
  {
    echo "<li>- <a href=\"" . $urlCourante . "\">" . $page["nom"] . "</a> : " .$page["descriptif"] . ".</li>\n";
  }
}
?>
