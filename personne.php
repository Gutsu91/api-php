<?php
require_once 'config.php';
require_once 'headers.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') : // gestion du GET
  if(isset($_GET['id_personnes'])):
    $req_allPeople = sprintf("SELECT * FROM `personnes` WHERE `id_personnes` = %d",
    $_GET['id_personnes']);
    $allPeople['response'] = 'One specific person';
  else:
    $req_allPeople = "SELECT * FROM `personnes` ORDER BY `nom`,`prenom` ASC ";
    $allPeople['response'] = "All people";
  endif;

  $result = $connect->query($req_allPeople);
  echo $connect->error;

  $allPeople['code'] = 200;
  $allPeople['time'] = date('Y-m-d,H:i:s');
  $allPeople['nbhits'] = $result->num_rows;
  while($row = $result->fetch_assoc()):
    $allPeople['data'][] = $row;
  endwhile;
endif;

if($_SERVER['REQUEST_METHOD'] == 'DELETE') : // gestion du delete
  $req_delPeople = sprintf("DELETE FROM `personnes` WHERE id_personnes=%d",
  $_GET['id_personnes']);
  $connect->query($req_delPeople);
  echo $connect->error;
  $allPeople['response'] = "Suppression du personnage avec l'id " . $_GET['id_personnes'];
endif;

if($_SERVER['REQUEST_METHOD'] == "POST") :
  $sql = sprintf("INSERT INTO `personnes`SET `nom`='%s', `prenom`='%s'",
  $_POST['nom'], $_POST['prenom']);
  $connect->query($sql);
  echo $connect->error;
  $allPeople['new_id'] = $connect->insert_id; // quand on insert une entrée, on ne connait pas l'ID pusque c'est la DB qui va le créer, donc on met l'insert_id ici
  $allPeople['response'] = "Ajout d'une personne avec l'id " . $connect->insert_id; // et du coup on le récup ici
  
endif;

echo json_encode($allPeople);

?>
