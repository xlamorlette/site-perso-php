<?
function display_structure($structure) {
    echo "<ul>\n";
    foreach ($structure as $page) {
        display_page_and_sub_pages_links($page, "");
    }
    echo "</ul>\n";
}

function display_page_and_sub_pages_links($page, $current_url) {
    $current_url .= $page["url"];
    if (array_key_exists("pages", $page)) {
        echo "<li>- " . page_link_with_description($page, $current_url) . " :\n<ul>\n";
        $current_url .= "/";
        foreach ($page["pages"] as $sousPage) {
            display_page_and_sub_pages_links($sousPage, $current_url);
        }
        echo "</ul></li>\n";
    } else {
        echo "<li>- " . page_link_with_description($page, $current_url) . ".</li>\n";
    }
}

function page_link_with_description($page, $current_url) {
    $link = "<a href=\"" . $current_url . "\">" . $page["name"] . "</a>";
    if (! empty($page["long_description"])) {
        $link .= " : " . $page["long_description"];
    }
    return $link;
}
?>
