<?
function build_page_table_of_contents($file) {
    rewind($file);
    skip_description_lines($file);

    $table_of_contents = "<p class=\"sommaire-0\">Sommaire :</p>\n";
    $table_of_contents .= "<ul>\n";
    while ($line = fgets($file)) {
        $line = rtrim($line);
        if (analyse_page_structure_tag($line, $level, $link, $title)) {
            $table_of_contents .= "<li class=\"sommaire-${level}\"><a href=\"#${link}\">${title}</a></li>\n";
        }
    }
    $table_of_contents .= "</ul>\n";
    return $table_of_contents;
}

function analyse_page_structure_tag($line,
        &$level,
        &$link,
        &$title) {
    $level = 0;
    while (substr($line, 0, 1) == "+") {
        $level ++;
        $line = substr($line, 1);
    }
    if ($level > 0) {
        $pos = strpos($line, '+');
        if ($pos === false) {
            $link = $line;
            $title = $link;
        } else {
            $link = substr($line, 0, $pos);
            $title = substr($line, $pos + 1);
        }
        $link = replace_characters_for_link($link);
        return true;
    } else {
        return false;
    }
}

function replace_characters_for_link($link) {
    $link = str_replace(" ", "_", $link);
    $link = str_replace("'", "_", $link);
    $link = str_replace("<code>", "_", $link);
    $link = str_replace("</code>", "_", $link);
    return $link;
}
?>
