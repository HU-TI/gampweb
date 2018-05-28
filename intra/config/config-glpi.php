<?php
// Connecting, selecting database
$mysqliglpi = new mysqli('localhost', 'root', '', 'glpi');

// Check erros
if ( $mysqliglpi->connect_errno ) {
  echo $mysqliglpi->connect_errno, ' ', $mysqliglpi->connect_error;
}
// Change character set to utf8
mysqli_set_charset($mysqliglpi,"utf8");
?>