<?php
require_once 'config.php';
require_once 'headers.php';
require_once 'verif-token.php';


//$theNews = $result->fetch_all(MYSQLI_ASSOC);
// on masque car j'ai une vieille version de PHP qui ne connait pas le fetch_all sur mon MAMP actuel
if($_SERVER['REQUEST_METHOD'] == 'GET') :
  $sql = "SELECT * FROM `news` WHERE `id_news` = " . $_GET['id_news']; // on sélectionne en SQL uniquement l'article à afficher
$result = $connect->query($sql);
echo $connect -> error;
  if($result->num_rows > 0) :
    while($row = $result->fetch_assoc()):
      $theNews[] = $row;
      $theNews['response'] = "OK";
      $theNews['code'] = 200; // c'est hardcodé parce qu'on émule, voir le commentaire sur la gestion des erreurs (en ligne 23 si ça n'a pas changé)
      $theNews['data'] = $theNews[0];
      unset($theNews[0]);
    endwhile;
  else :
    $theNews['response'] = "missing content"; // on gère une 404 si l'ID n'existe pas
    $theNews['code'] = 42; // c'est pas une vraie erreur 404 car le script existe, c'est juste la news qui n'existe pas
  endif;
endif;

if($_SERVER['REQUEST_METHOD'] == 'DELETE') :
  $sql = sprintf("DELETE FROM `news` WHERE id_news=%d",
  $_GET['id_news']);
  $connect->query($sql);
  $connect->error;
  $theNews['response'] = "Suppression de la news avec l'id {$_GET['id_news']}";
endif;

// ton code est bon mais c'est pas la bonne route, on a répliqué dans all-news
/*
if($_SERVER['REQUEST_METHOD'] == 'POST') :
  $json file_get_contents('php://input');
  $object json_decode($json);
  echo $sql = sprintf("INSERT INTO `news` SET `titre`='%s', `contenu`='%s'",
    $object->titre,
    $object->contenu
  );
  $connect->query($sql);
  echo $connect->error;
  $theNews['new_id'] = $connect->insert_id;
  $theNews['response'] = "ajout d'une news avec l'id " .$connect->insert_id;
endif;*/

if($_SERVER['REQUEST_METHOD'] == 'PUT') :
  $json = file_get_contents('php://input');
  $object = json_decode($json);
  $sql = sprintf("UPDATE `news` SET `titre`='%s', `contenu`='%s' WHERE id_news=%d",
    strip_tags(addslashes($object->titre)),
    strip_tags(addslashes($object->contenu)),
    $_GET['id_news']
  );
  $connect->query($sql);
  echo $connect->error;
  $theNews['response'] = "edit de la news avec l'id " . $_GET['id_news'];
endif;

// myPrint_r($theNews); on masque car sinon ça va générer une erreur
//$nb_rows = $result->num_rows;

echo json_encode($theNews); // on encode la réponse en json
exit;

?>