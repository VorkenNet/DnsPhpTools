ls VarNamed | cat | cut -c5- | xargs -n1 php updaterTTL.php | tee UpdateZone.log
