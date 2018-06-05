<?php 
$mydb = new mysqli('10.100.1.33', 'dev', 'devloop356', 'intra_gamp');
// $mydb = new mysqli('localhost', 'root', '', 'intra_gamp');
return $mydb;