<?php
/* script d'authentification*/
include 'config.php'; // on a besoin de se connecter à la db pour vérifier que les données sont bien conformes à ce qu'il ya dans useer

if (isset($_GET['delog'])) :
  session_start(); // on accede à session
  unset($_SESSION['user']); // on retire l'user de session
  unset($_SESSION['token']); //on retire le token de la session
  unset($_SESSION['expiration']);
  $response['message'] = "deconnection";
  $response['code'] = 200;
  $response['time'] = date('Y-m-d, H:i:s');
  echo json_encode($response);
  exit;
endif;

$json = file_get_contents('php://input'); // on récup les données dans ce qu'on enverra en JSON, plus dans $_POST; on travaille l'api pas le navugateur
$arrayPOST = json_decode($json, true);
$sql_auth = sprintf("SELECT * FROM `users` WHERE `login` = '%s' AND `password` = '%s'", // requêtes SQL pour s'assurer que login et mdp sont présents dans la db
$arrayPOST['login'],
$arrayPOST['password']
);
$result = $connect->query($sql_auth);
echo $connect->error;
if($result->num_rows > 0 ) : 
  $user = $result -> fetch_assoc();
  session_start(); 
  $_SESSION['user'] = $user['id_users']; 
  $_SESSION['token'] = md5($user['login'].time()); 
  $_SESSION['expiration'] = time() + 1 * 30; // le token sera valable deux minutes
  $response['expiration'] = date('Y-m-d,H:i:s', $_SESSION['expiration']);
  $response['response'] = "ok connecté";
  $response['token'] = $_SESSION['token'];
else : 
  $response['message'] = "erreur de log et/ou de mot de passe";
  $response['code'] = 403;
endif;

$response['code'] = (isset($response['code'])) ? $response['code'] : 200 ;
$response['time'] = date('Y-m-d, H:i:s');
echo json_encode($response);

?>