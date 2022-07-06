<?php 
session_start();

if($_SERVER['REQUEST_METHOD'] != 'GET') : // si la methode est différente de get
  $now = time(); // la variable now = maintenant
  if ($now > $_SESSION['expiration'] OR !$_SESSION['user']): // si maintenant est plus tard que l'expiration OU que l'user n'est pas setté, exécuter le code suivant
    unset($_SESSION['user']); //supprimer user du tableau $_SESSION
    unset($_SESSION['token']); // supprimer le token 
    unset($_SESSION['expiration']); // suprimer l'expiration
    $response['response'] = "session timed out a/or access denied"; // message d'erreur
    $response['code'] = 403;
    echo json_encode($response); //encoder la réponse en json
    die(); //arrêter l'exécution du reste du code (même chose qu'un exit)
  else:
    $json = file_get_contents('php://input'); // on récupère le json dans l'en-tête http
    $arrayPOST = json_decode($json, true); // on le décode en json
    if($arrayPOST['token'] != $_SESSION['token']) : // si le token qu'on renseigne = token dans $_SESSION, alors exécuter le code ci-dessous
      $response['response'] = "wrong token"; // message d'erreur
      $response['code'] = 401;
      echo json_encode($response); //encoder la réponse en json
      die;
    endif;
  endif;
  $_SESSION['expiration'] = time() +1 *60; // on renouvelle l'expiration pour une minute si aucune expiration n'a été faite après les if
endif;

?>