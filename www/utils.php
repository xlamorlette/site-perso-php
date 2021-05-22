<?
function tab() {
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

function mini_tab() {
    echo "&nbsp;&nbsp;&nbsp;";
}

function extension($nom_fichier) {
    $pos = strrpos($nom_fichier, ".");
    if ($pos <= 0) {
        return "";
    }
    else {
        return substr($nom_fichier, $pos+1);
    }
}

function nomSansExtension($nom_fichier) {
    $pos = strrpos($nom_fichier, ".");
    if ($pos <= 0) {
        return "";
    } else {
        return substr($nom_fichier, 0, $pos);
    }
}

// construit la structure page pour la structure du site en lisant le fichier index.txt
function construitStructurePage($nomPage, $repertoireCourant) {
    $page["url"] = $nomPage;
    $fichier = fopen($repertoireCourant . "/" . $nomPage . "/index.txt", "r");
    if (! $fichier) {
        return NULL;
    }
    while (! feof($fichier)) {
        $ligne = fgets($fichier);
        $ligne = rtrim($ligne);
        if (! array_key_exists("nom", $page)) {
            $page["nom"] = $ligne;
        } elseif (! array_key_exists("descriptif", $page)) {
            $page["descriptif"] = $ligne;
        }
    }
    fclose($fichier);

    $repertoireCourant .= "/" . $nomPage;
    $directory = opendir($repertoireCourant);
    while ($file_name = readdir($directory)) {
        if (estPage($repertoireCourant . "/" . $file_name)) {
            $sousPage = construitStructurePage($file_name, $repertoireCourant);
            $page["pages"][$file_name] = $sousPage;
        }
    }
    closedir($directory);

    if (array_key_exists("pages", $page)) {
        usort($page["pages"], "compareNomsPages");
    }

    return $page;
}

function litStructureSite($root) {
    return read_site_structure($root);
}

# TODO: deprecated name
function read_site_structure($root) {
    $directory = opendir($root);
    while ($file_name = readdir($directory)) {
        $file_path = $root . $file_name;
        if (estPage($file_path)) {
            $page = construitStructurePage($file_path, ".");
            $structure[$file_name] = $page;
        }
    }
    closedir($directory);
    usort($structure, "compareNomsPages");
    return $structure;
}

// determine si le fichier est un directory, contenant index.php et index.txt
function estPage($file_name) {
    if (basename($file_name) === "." || basename($file_name) === "..") {
        return false;
    }
    if (! is_dir($file_name)) {
        return false;
    }
    if (! file_exists("${file_name}/index.php")) {
        return false;
    }
    if (! file_exists("${file_name}/index.txt")) {
        return false;
    }
    return true;
}

// fonction de comparaison pour le tri des pages selon leur nom
function compareNomsPages($a, $b) {
    return strcmp($a["nom"], $b["nom"]);
}

# TODO: deprecated name
function affichePage($page, $current_url) {
    display_page_and_sub_pages_links($page, $current_url);
}

function display_page_and_sub_pages_links($page, $current_url) {
    $current_url .= $page["url"];
    if (array_key_exists("pages", $page)) {
        echo "<li>- " . page_link_with_description($page, $current_url) . " :\n<ul>\n";
        $current_url .= "/";
        foreach ($page["pages"] as $sousPage) {
            affichePage($sousPage, $current_url);
        }
        echo "</ul></li>\n";
    } else {
        echo "<li>- " . page_link_with_description($page, $current_url) . ".</li>\n";
    }
}

function page_link_with_description($page, $current_url) {
    $link = "<a href=\"" . $current_url . "\">" . $page["nom"] . "</a>";
    if (! empty($page["descriptif"])) {
        $link .= " : " . $page["descriptif"];
    }
    return $link;
}
?>
