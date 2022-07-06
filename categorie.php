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
if($_SERVER['REQUEST_METHOD'] == "DELETE") :
  if(isset($_GET['id_categorie'])) : // on fait une requête sur la table produit pour voir s'il a des produits qui ont cette catégorie
    $req_cat_empty = sprintf("SELECT * FROM product WHERE id_categorie_prod=%d",
    $_GET['id_categorie']);
    $categorie = $connect->query($req_cat_empty);
    echo $connect->error;
    $nbResult = $categorie->num_rows;
    if($nbResult == 0 ) :
      echo 'foo';
    else:
      $categorie['response'] = "Vous ne pouvez pas supprimer cette catégorie car elle contient encore des produits";
      $categoriet['code'] = "NOK";
    endif;
  else:
    $categorie['response'] = "Vous n'avez pas défini l'id de la catégorie à supprimer";
    $categorie['code'] = "NOK";
  endif;
endif;
/* post mortem : le delete ne fonctionne pas, j'ai du foirer dans mes checks */

echo json_encode($categorie); // doit être la dernière ligne sinon il ne retournera pas de réponse
?>
