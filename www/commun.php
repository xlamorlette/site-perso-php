<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta name="robots" content="index,follow"/>
    <meta name="author" content="Xavier Lamorlette">
    <meta name="keywords" content="Xavier Lamorlette">

<?
// ouvre le fichier index.txt
$fichier = fopen("index.txt", "r");

// prend le titre sur la première ligne
$titre = chop(fgets($fichier));
if ($titre != "")
{
  echo "<title>Xavier Lamorlette - $titre</title>\n";
}
else
{
  echo "<title>Xavier Lamorlette</title>\n";
}

// prend la description sur la deuxième ligne
$description = chop(fgets($fichier));
if ($description != "")
{
  echo "<meta name=\"description\" content=\"Xavier Lamorlette - $description\">\n";
}
else
{
  echo "<meta name=\"description\" content=\"Xavier Lamorlette\">\n";
}
?>

    <? print("<link rel=\"stylesheet\" href=\"${racine}style.css\" type=\"text/css\">\n"); ?>
    <? print("<script src=\"${racine}highlight.pack.js\"></script>\n"); ?>
    <script>hljs.initHighlightingOnLoad();</script>
    <? print("<link rel=\"stylesheet\" href=\"${racine}highlight.css\" type=\"text/css\">\n"); ?>
  </head>

  <body>
    <div id="bandeau">
      <div id="logo_gauche"></div>
      <div id="logo_droite"></div>
      <div id="logo_milieu"><p>Xavier Lamorlette</p></div>
    </div>

<?
setLocale(LC_ALL, 'fr_FR');
require_once("utils.php");
?>

    <div id="navigation">
      <div class="menu">
        <ul>
          <li class="menu-titre">Sommaire</li>

<?
// --- construction du sommaire dans la barre de navigation sur la gauche ---

echo "<li class=\"menu-item-0";
if ($categories === NULL)
{
  echo " menu-selected";
}
echo "\"><a href=\"$racine\">Accueil</a></li>\n";

// affiche une page dans le sommaire, et récurre si nécessaire
function affichePageSommaire($page, $urlCourante, $niveau, $categories)
{
  $urlCourante .= $page["url"];
  
  $selectionne = false;
  $categorieParente = false;

  if ($niveau <= count($categories))
  {
    $categorieDuNiveau = $categories[$niveau - 1];
    if (substr($page["url"], -strlen($categorieDuNiveau)) === $categorieDuNiveau)
    {
      $categorieParente = true;
      if ($niveau === count($categories))
      {
        $selectionne = true;
      }
    }
  }

  echo "<li class=\"menu-item-$niveau";
  if ($selectionne)
  { 
    echo " menu-selected";
  }
  echo "\"><a href=\"$urlCourante\">" . $page["nom"] . "</a></li>\n";

  if ($categorieParente && (array_key_exists("pages", $page)))
  {
    $urlCourante .= "/";
    $niveau ++;
    foreach($page["pages"] as $sousPage)
    {
      affichePageSommaire($sousPage, $urlCourante, $niveau, $categories);
    }
  }
}

$structure = litStructureSite($racine);
foreach($structure as $page)
{
  affichePageSommaire($page, "", 1, $categories);
}
?>

        </ul>
      </div>
    </div>

    <div class="principal">

<?
// --- construction du sommaire de la page ---

// analyse une balise +xxx pour le sommaire
function analyseBalisePlus($ligne, &$niveau, &$lien, &$titre)
{
  $niveau = 0;
  while (substr($ligne, 0, 1) == "+")
  {
    $niveau ++;
    $ligne = substr($ligne, 1);
  }
  if ($niveau > 0)
  {
    $pos = strpos($ligne, '+');
    if ($pos === false)
    {
      $lien = $ligne;
      $titre = $lien;
    }
    else
    {
      $lien = substr($ligne, 0, $pos);
      $titre = substr($ligne, $pos + 1);
    }
    return true;
  }
  else
  {
    return false;
  }
}


// fait une première passe sur index.txt pour construire le sommaire
rewind($fichier);

// passe les deux premiere lignes
fgets($fichier);
fgets($fichier);

$sommaire = "<ul class=\"sommaire-0\">Sommaire :\n";

while ($ligne = fgets($fichier))
{
  $ligne = rtrim($ligne);
  if (analyseBalisePlus($ligne, $niveau, $lien, $titre))
  {
    // on utilise le lien dans la page qui sera cree plus bas
    $sommaire .= "<li class=\"sommaire-${niveau}\"><a href=\"#${lien}\">${titre}</a></li>\n";
  }
}
$sommaire .= "</ul>\n";


// --- affichage du contenu de la page ---

// relit le fichier pour tout afficher
rewind($fichier);

// passe les deux premiere lignes
fgets($fichier);
fgets($fichier);

while ($ligne = fgets($fichier))
{
  $ligne = rtrim($ligne);
  if ($ligne == "<?")
  {
    // traitement special des blocs de code PHP
    $code = "";
    while (($ligne = rtrim(fgets($fichier))) != "?>")
    {
      $code .= $ligne . "\n";
    }
    eval($code);
  }
  elseif ($ligne == "*sommaire")
  {
    // affichage du sommaire
    echo $sommaire;
  }
  else
  {
    // traitement special des sections de la page
    if (analyseBalisePlus($ligne, $niveau, $lien, $titre))
    {
      // on commence à h3
      $niveau += 2;
      // on cree un lien dans la page pour le sommaire
      echo "<a name=\"${lien}\"><h${niveau}>${titre}</h${niveau}></a>\n";
    }
    else
    {
      echo $ligne . "\n";
    }
  }
}
?>

      <p><i>Le contenu de ce site est, en tant qu'œuvre originale de l'esprit, protégé par le droit d'auteur.<br/>
          Pour tout commentaire, vous pouvez m'écrire à xavier.lamorlette@gmail.com.</i></p>
    </div>
  </body>
</html>
