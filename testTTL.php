<?PHP

require_once("include/functions.inc.php");
//print_r($argv);
#Edit a fileZone
$zone=trim($argv[2]);
$file= "VarNamed/pri.".$zone;

//$ipToChange="81.29.192.246";//Old Farm IP
$ipToChange=$argv[1];
#search for IptoChange
exec("grep \"".$ipToChange."\" ".$file, $grepSearch);
if (count($grepSearch)){
    $soa=parseSOA($file);
    echo "TTL\t".$soa["minimum-TTL"]."\t".$zone."\n";
}
?>
