<?PHP

#Update SoaSerial
function editSerialSoa($file,$progressivo,$ttl){
  //Non ancora implementeato il Rewrite completo del SOA
  #update Serial (YYmmddXX)
  $date=date("Ymd").$progressivo;
  $serial="sed -i -e '/IN SOA/ {' -e 'n; s/.*/\t".$date."/' -e '}' ".$file."";
  exec($serial);
  //$soa=parseSOA($file);
  //print_r($soa);
}

function editTTL($file, $ttl){
  #Update $TTL
  $searchTTL="sed -i -e '/\$TTL/ {' -e 's/.*/\$TTL ".$ttl."/' -e '}' ".$file."";
  exec($searchTTL);
  #update [time-to-refresh] 2n
  $timeToRefresh="sed -i -e '/IN SOA/ {' -e 'n;n; s/.*/\t".$ttl."/' -e '}' ".$file."";
  exec($timeToRefresh);
  #update [minimum-TTL] 5n
  $minimumTTL="sed -i -e '/IN SOA/ {' -e 'n;n;n;n;n; s/.*/\t".$ttl."/' -e '}' ".$file."";
  exec($minimumTTL);
  //parsa il SOA in Array (per ora solo per debug)
  //$soa=parseSOA($file);
  //print_r($soa);
}

function namedCheckzone ($zone,$file){
  exec("named-checkzone ".$zone." ".$file, $result);
  //Meglio sostituire con end($result)
  if ($result[1]=="OK") return true;
  else{
    echo "FAIL!";
    exec("rm ".$file);
    print_r($result);
    die();
  }
}

#Edit a fileZone
$zone=trim($argv[1]);
echo "\n".$zone."\t\t";
$file= "pri.".$zone;
$ipToChange=trim($argv[2]);;
$progressivo="01";
$ttl="900";

#search for IptoChange
exec("grep \"".$ipToChange."\" ".$file, $grepSearch);
if (count($grepSearch)){
    editTTL($file,$ttl);
    editSerialSoa($file,$progressivo, $ttl);
    if (namedCheckzone ($zone,$file)) echo "WellDone! ;) \n";
} else {
    exec("rm ".$file);
    echo "NothingToEdit?\n";
}
?>
