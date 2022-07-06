<?php
require_once 'config.php';
require_once 'headers.php';
//require_once 'verif-token.php';

if($_SERVER['REQUEST_METHOD'] == 'GET') :
  if(isset($_GET['id_categorie'])):
    $req_cat = sprintf("SELECT * FROM categorie LEFT JOIN product ON categorie.id_categorie = product.id_categorie_prod WHERE id_categorie = %d",
    $_GET['id_categorie']);
    $categorie['response'] = 'One specific category';
    else :
      $req_cat = "SELECT * FROM `categorie` ORDER BY `id_categorie` ASC";
      $categorie['response'] = "All categories";
    endif;
  $result = $connect->query($req_cat);
  echo $connect->error;
  $categorie['code'] = "OK";
  $categorie['time'] = date('Y-m-d,H:i:s');
  $categorie['nbhits'] = $result->num_rows;
  while($row = $result->fetch_assoc()):
    $categorie['data'][] = $row;
  endwhile;
endif;

echo json_encode($categorie);

?>
