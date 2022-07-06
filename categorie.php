<?php
require_once 'config.php';
require_once 'headers.php';
//require_once 'verif-token.php';

/* Gestion du GET */
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

/* Gestion du DELETE */

if($_SERVER['REQUEST_METHOD'] == "DELETE"):
  if(isset($_GET['id_categorie'])) :
    $categorie['response'] = "vous avez bien spécifié l'id de la catégorie";
    $categorie['code'] = "OK";
    $req_cat_empty = sprintf("SELECT * FROM product WHERE id_categorie_prod = %d",
      $_GET['id_categorie']);
    $result = $connect->query($req_cat_empty);
    echo $connect->error;
    $categorie['nbhits'] = $result->num_rows;
      if ($categorie['nbhits']  > 0 ):
        $categorie['response'] = "Vous devez vider les produits de la catégorie avant de la supprimer";
        $categorie['code'] = "NOK";
      else:
        $categorie['response'] = "Suppression de la catégorie avec l'id " . $_GET['id_categorie'];
        $categorie['code'] = "OK";
        $req_cat = sprintf("DELETE FROM categorie WHERE id_categorie = %d",
          $_GET['id_categorie']);
        $connect->query($req_cat);
        echo $connect->error;
      endif;
  else:
    $categorie['response'] = "vous devez préciser l'id de la catégorie à supprimer";
    $categorie['code'] = "NOK";
  endif;
endif;

echo json_encode($categorie); // doit être la dernière ligne sinon il ne retournera pas de réponse
?>
