<?php
  
  define('DB_USER', 'root'); // on définit l'username de la db
  define('DB_PASS', 'root'); // on défini le mdp de la db
  define('DB_HOST', 'localhost'); // on défini l'url de la db
  define('DB_NAME', 'ingrwf10_php'); // on défini le nom de la db

  define('MODE', 'dev'); // on crée une constante pour indiquer qu'on est en mode dev
  require_once 'function.php'; // on requiert le fichier function.php, une seule fois. On aura besoin du 'mode dev' dans function, donc on appelle fonction après l'avoir set sur dev


  $connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME); // on effectue la connecion à mysql. $connect est un objet qui représente cette connection. Si on a plusieurs db on aura plusieurs objet de connexion différents (nomme les habillement)

  if($connect ->connect_error) : //gestion d'erreur: si erreur de connection. 
      die('Connection failed: ' . $connect ->connect_error); // kill de l'app et retour du message d'erreur
  else : 
    $connect->set_charset('utf8'); // si pas d'erreur, les interactions doivent se faire en utf8
  endif;
?>