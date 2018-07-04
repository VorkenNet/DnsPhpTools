ls VarNamed | cat | cut -c5- | xargs -n1 php updaterZone.php | tee UpdateZone.log
