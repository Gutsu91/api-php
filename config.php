<?php
  require_once 'function.php';
  define('DB_USER', 'root');
  define('DB_PASS', 'root');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'ingrwf10_php');

  define('MODE', 'dev'); // either dev or prod

  $connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if($connect ->connect_error) :
      die('Connection failed: ' . $connect ->connect_error);
  else : 
    $connect->set_charset('utf8');
  endif;
?>