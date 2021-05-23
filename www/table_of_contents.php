<?
function display_navigation_table_of_contents($root,
        $current_page_path_elements) {
    echo "<li class=\"menu-item-0";
    if ($current_page_path_elements === NULL) {
        echo " menu-selected";
    }
    echo "\"><a href=\"$root\">Accueil</a></li>\n";
    $site_structure = read_structure($root);
    foreach ($site_structure as $page) {
        display_navigation_table_of_contents_for_page($page, "", 1, $current_page_path_elements);
    }
}

function display_navigation_table_of_contents_for_page($page,
        $parent_url,
        $level,
        $current_page_path_elements) {
    $page_url = $parent_url . $page["url"];

    $is_selected = false;
    $is_parent_category = false;

    if ($level <= count($current_page_path_elements)) {
        $same_level_page = $current_page_path_elements[$level - 1];
        if (substr($page["url"], -strlen($same_level_page)) === $same_level_page) {
            $is_parent_category = true;
            if ($level === count($current_page_path_elements)) {
                $is_selected = true;
            }
        }
    }

    echo "<li class=\"menu-item-$level";
    if ($is_selected) {
        echo " menu-selected";
    }
    echo "\"><a href=\"$page_url\">" . $page["name"] . "</a></li>\n";

    if ($is_parent_category && (array_key_exists("pages", $page))) {
        $page_url .= "/";
        $level ++;
        foreach ($page["pages"] as $sousPage) {
            display_navigation_table_of_contents_for_page($sousPage, $page_url, $level, $current_page_path_elements);
        }
    }
}

?>
