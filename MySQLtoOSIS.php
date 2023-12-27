<?php
use Medoo\Medoo;

set_time_limit(0);

include_once 'GenOSIS.php';

$int_min = intval($argv[1]??1);
$int_max = intval($argv[2]??75);

$int_min = intval($argv[1]??1);
$int_max = intval($argv[2]??1);

$go = new GenOSIS('F:/OSISBible/src/');

foreach (range($int_min, $int_max) as $bkNum) {
    echo progress_bar($bkNum-$int_min, $int_max-$int_min+1, "Processing Book:" . $bkNum .' - ' . BIB_ALL_BKS[ $bkNum ]['osisID']);
    $go->generateBook($bkNum);
    echo progress_bar($bkNum-$int_min+1, $int_max-$int_min+1, "Completed Book:" . $bkNum .' - ' . BIB_ALL_BKS[ $bkNum ]['osisID']);
}

function progress_bar($done, $total, $info="", $width=50) {
    return $info . "\r\n";
    $perc = round(($done * 100) / $total);
    $bar = round(($width * $perc) / 100);
    return sprintf("%s%%[%s>%s]%s\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width-$bar), $info);
}