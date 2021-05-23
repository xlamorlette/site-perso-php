<?
function tab() {
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

function mini_tab() {
    echo "&nbsp;&nbsp;&nbsp;";
}

function extension($filename) {
    $pos = strrpos($filename, ".");
    if ($pos <= 0) {
        return "";
    }
    else {
        return substr($filename, $pos + 1);
    }
}

function name_without_extension($filename) {
    $pos = strrpos($filename, ".");
    if ($pos <= 0) {
        return "";
    } else {
        return substr($filename, 0, $pos);
    }
}

?>
