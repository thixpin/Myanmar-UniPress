<?php

function currentPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
     $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"];
    } else {
     $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
    }
    return $pageURL;
}

$font = (isset($_GET['font']) && ($_GET['font'] != '')) ? ($_GET['font']) : 'pyidaungsu';

switch ($font) {
    case 'myanmar3':
        $have_bold=false;
        $font_family="Myanmar3";
        break;
    case 'mon3':
        $have_bold=false;
        $font_family = "MON3 Anonta 1";
        break;
    case 'padauk':
        $have_bold=false;
        $font_family="Padauk";
        break;
    case 'notosan':
        $have_bold=true;
        $font_family="Notosan Myanmar";
    case 'pyidaungsu':
    default:
        $font = 'pyidaungsu-1.8';
        $have_bold=true;
        $font_family="Pyidaungsu";
        break;
}

$current_url=substr(currentPageURL(),0,-9);

if($have_bold){
    $css = "@font-face {
    font-family:'MyanmarFont';
    src:local('".$font_family."'), 
    url('".$current_url.$font."_Regular.woff') format('woff'), 
    url('".$current_url.$font."_Regular.ttf') format('ttf');
}
@font-face {
    font-family:'MyanmarFont';
    src:local('".$font_family."'), 
    url('".$current_url.$font."_Bold.woff') format('woff'), 
    url('".$current_url.$font."_Bold.ttf') format('ttf');
    font-weight:bold;
}";
} else {
    $css = "@font-face {
    font-family:'MyanmarFont';
    src:local('".$font_family."'), 
    url('".$current_url.$font.".woff') format('woff'), 
    url('".$current_url.$font.".ttf') format('ttf');
}";
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/css");
//add cache for css
$seconds_to_cache = 86400; //24 hour
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
header("Content-type: text/css");
echo $css;
?>

.myan_mar_Font{
    font-family: MyanmarFont !important;
}