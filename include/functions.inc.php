<?php
/* HowToUseSed
Use the -i option to save the changes in the file
sed -i 's/\(^.*myvar.*$\)/\/\/\1/' file.

Explanation:
(      # Start a capture group
^      # Matches the start of the line
.*     # Matches anything
myvar  # Matches the literal word
.*     # Matches anything
$      # Matches the end of the line
)      # End capture group
*/


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

#Update $TTL
function editTTL($file, $ttl){
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

function cleanSoaField($string){
    $string = trim ($string);
    if(strpos(";",$string,1)){
      $array=explode(";",$string);
      return $array[0];
    } else return $string;
}

function parseSOA($file){
  exec("grep -A 6 \"IN SOA\" ".$file,$soa);
  $soa=array_map("cleanSoaField", $soa);
  $label=array("SOA","serial-number","time-to-refresh","time-to-retry","time-to-expire","minimum-TTL","end");
  return array_combine ( $label , $soa );
}

function namedCheckzone ($zone,$file){
  exec("named-checkzone ".$zone." ".$file, $result);
  //Meglio sostituire con end($result)
  if (end($result)=="OK") return true;
  else{
    echo "FAIL!";
    //exec("rm ".$file);
    print_r($result);
    die();
  }
}

function editHostCName($zone,$line,$cname,$file,$ipToChange,$ipNew){
  //funziona sia con i Tab che con gli spazzi disomogenei
  $parts = preg_split('/[\s]+/', $line);
  //print_r($parts);
  if (!(($parts[2]=="TXT") || ($parts[2]=="NS"))){
    // per evitare il parsing di campi TXT e NS
    //Root Record
    /*if ($parts[0]==$zone.".") {
      $newEntry=$parts[0]."\tIN\tA\t".$ipNew;
    } else {
      $newEntry=$parts[0]."\tIN\tCNAME\t".$cname.".";
    }*/
    //Cercare escape Bash/PHP
    // escapeshellarg()
    // http://php.net/manual/en/function.escapeshellarg.php
    $newEntry=$parts[0]."\tIN\tA\t".$ipNew;
    $replace="sed -i -e 's/\(^".$line."\)/".$newEntry."/g' ".$file;
    //echo $replace."\n";
    exec($replace);
  }
}

?>
