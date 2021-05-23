<?
function read_structure($root_page) {
    $directory = opendir($root_page);
    while ($filename = readdir($directory)) {
        $file_path = $root_page . $filename;
        if (is_a_page($file_path)) {
            $page = build_page_structure($file_path, ".");
            $structure[$filename] = $page;
        }
    }
    closedir($directory);
    usort($structure, "compare_pages_names");
    return $structure;
}

function build_page_structure($file_path,
        $parent_directory) {
    $page["url"] = $file_path;
    $current_directory = $parent_directory . "/" . $file_path;
    read_name_and_descriptions($current_directory . "/index.txt", $page);
    build_children_structure($current_directory, $page);
    return $page;
}

function read_name_and_descriptions($file_path,
        &$page) {
    $file = fopen($file_path, "r");
    if (! $file) {
        return NULL;
    }
    while (! feof($file)) {
        $line = fgets($file);
        $line = rtrim($line);
        if (! array_key_exists("name", $page)) {
            $page["name"] = $line;
        } elseif (! array_key_exists("short_description", $page)) {
            $page["short_description"] = $line;
        } elseif (! array_key_exists("long_description", $page)) {
            $page["long_description"] = $line;
        }
    }
    fclose($file);
}

function build_children_structure($directory_path,
        &$page) {
    $directory = opendir($directory_path);
    while ($filename = readdir($directory)) {
        if (is_a_page($directory_path . "/" . $filename)) {
            $child_page = build_page_structure($filename, $directory_path);
            $page["pages"][$filename] = $child_page;
        }
    }
    closedir($directory);
    if (array_key_exists("pages", $page)) {
        usort($page["pages"], "compare_pages_names");
    }
}

function is_a_page($filename) {
    if (basename($filename) === "." || basename($filename) === "..") {
        return false;
    }
    if (! is_dir($filename)) {
        return false;
    }
    if (! file_exists("${filename}/index.php")) {
        return false;
    }
    if (! file_exists("${filename}/index.txt")) {
        return false;
    }
    return true;
}

function compare_pages_names($a, $b) {
    return strcmp($a["name"], $b["name"]);
}

function skip_description_lines($file) {
    fgets($file);
    fgets($file);
    fgets($file);
}

?>
