<?PHP

require_once("include/functions.inc.php");
#Edit a fileZone
$zone=trim($argv[1]);
echo "\n".$zone."\t\t";
$file= "VarNamed/pri.".$zone;

$ipToChange="81.29.197.90";//Old Farm IP
$ipNew="18.197.162.224";//AWS istance
$cname="web11.nextmove.it";

$progressivo="01";
$ttl="500";

#search for IptoChange
exec("grep \"".$ipToChange."\" ".$file, $grepSearch);
if (count($grepSearch)){
  foreach ($grepSearch as $line){
     editHostCName($zone,$line,$cname,$file,$ipToChange,$ipNew);
  }
  editTTL($file,$ttl);
  editSerialSoa($file,$progressivo, $ttl);
  if (namedCheckzone ($zone,$file)) echo "WellDone! ;) \n";
} else {
  exec("rm ".$file);
  echo "NothingToEdit?\n";
}
?>
