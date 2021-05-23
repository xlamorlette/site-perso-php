<!DOCTYPE html>
<html lang="fr">
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="index,follow"/>
    <meta name="author" content="Xavier Lamorlette">

<?
$file = fopen("index.txt", "r");

echo "    <title>Xavier Lamorlette";
$title = chop(fgets($file));
if ($title != "") {
    echo " - $title";
}
echo "</title>\n";

echo "    <meta name=\"description\" content=\"Xavier Lamorlette";
$description = chop(fgets($file));
if ($description != "") {
    echo " - $description";
}
echo "\">\n";
?>

    <? print("<link rel=\"stylesheet\" href=\"${root}style.css\" type=\"text/css\">\n"); ?>
    <? print("<script src=\"${root}highlight.pack.js\"></script>\n"); ?>
    <script>hljs.initHighlightingOnLoad();</script>
    <? print("<link rel=\"stylesheet\" href=\"${root}highlight.css\" type=\"text/css\">\n"); ?>
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
require_once("build_structure.php");
require_once("table_of_contents.php");
require_once("display_structure.php");
require_once("page_structure.php");
?>

<div id="navigation">
    <div class="menu">
        <ul>
            <li class="menu-titre">Sommaire</li>
<? display_navigation_table_of_contents($root, $path_elements); ?>
        </ul>
    </div>
</div>

<div class="principal">

<?
$table_of_contents = build_page_table_of_contents($file);

rewind($file);
skip_description_lines($file);

function handle_PHP_bloc($file) {
    $code = "";
    while (($line = rtrim(fgets($file))) != "?>") {
        $code .= $line . "\n";
    }
    eval($code);
}

while ($line = fgets($file)) {
    $line = rtrim($line);
    if ($line == "<?") {
        handle_PHP_bloc($file);
    } elseif ($line == "*sommaire") {
        echo $table_of_contents;
    } else {
        if (analyse_page_structure_tag($line, $level, $lien, $title)) {
            $level += 2;
            echo "<a id=\"${lien}\"><h${level}>${title}</h${level}></a>\n";
        } else {
            echo $line . "\n";
        }
    }
}
?>

    <p><i>Le contenu de ce site est, en tant qu'œuvre originale de l'esprit, protégé par le droit d'auteur.<br/>
        Pour tout commentaire, vous pouvez m'écrire à xavier.lamorlette@gmail.com.</i></p>
</div>
</body>
</html>
