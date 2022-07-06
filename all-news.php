<?php
require_once 'config.php';
require_once 'headers.php';
require_once 'verif-token.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') :
  $req_all_news = "SELECT * FROM `news`";
  $result = $connect->query($req_all_news);
  echo $connect->error;

  while($row = $result->fetch_assoc()):
    $allNews['data'][] = $row;
  endwhile;
endif;

//$allNews['code'] = 200;
//$allNews['reponse'] = "All news";

if($_SERVER['REQUEST_METHOD'] == 'POST') :
  $json = file_get_contents('php://input');
  $object = json_decode($json);
  $sql = sprintf("INSERT INTO `news` SET `titre`='%s', `contenu`='%s'",
    strip_tags(addslashes($object->titre)),
    strip_tags(addslashes($object->contenu)) // on strip les tags html et on demande à PHP d'echapper les quotes dans le contenu
  );
  $connect->query($sql);
  echo $connect->error;
  $allNews['new_id'] = $connect->insert_id;
  $allNews['response'] = "ajout d'une news avec l'id " .$connect->insert_id;
endif;

echo json_encode($allNews);


?>