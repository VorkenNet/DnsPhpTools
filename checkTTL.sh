ls VarNamed | cat | cut -c5- | xargs -n1 php testTTL.php  $1 
