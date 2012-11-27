<?php

include_once 'functions.php';

if (!isset($_REQUEST['d'])) { 
    exit;
} else { 
    $d = $_REQUEST['d'];
}

$d = explode("-", $d);

$sensor = h2s($d[0]);
$sid	= h2s($d[1]);
$sip	= h2s($d[2]);
$spt	= h2s($d[3]);
$dip	= h2s($d[4]);
$dpt	= h2s($d[5]);
$ts	= h2s($d[6]);
$usr	= h2s($d[7]);
$pwd	= h2s($d[8]);

$cmd = "cliscript.tcl -sensor '$sensor' -timestamp '$ts' -u '$usr' -pw '$pwd' -sid $sid -sip $sip -spt $spt -dip $dip -dpt $dpt";
exec("../.scripts/$cmd",$raw);

$fmtd = $debug = '';

foreach ($raw as $line) {

    $line = htmlspecialchars($line);
    $type = substr($line, 0,3);

    switch ($type) {
        case "DEB": $debug .= $line . "<br>"; $line = ''; break;
        case "HDR": $line = preg_replace('/^HDR:/', "<span class=txtext_hdr>$0</span>", $line); break;
        case "DST": $line = preg_replace('/^DST:/', "<span class=txtext_dst>$0</span>", $line); break;
        case "SRC": $line = preg_replace('/^SRC:/', "<span class=txtext_src>$0</span>", $line); break;
    }

    if (strlen($line) > 0) {
        $fmtd  .= $line . "<br>";
    }
}

$result = array("tx"  => "$fmtd",
                "dbg" => "$debug",
                "cmd" => "$cmd");

$theJSON = json_encode($result);
echo $theJSON;
?>
