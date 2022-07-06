<?php 
require_once 'config.php';
require_once 'headers.php';
//require_once 'verif-token.php';

/* Gestion du GET */
if($_SERVER['REQUEST_METHOD'] == 'GET') :
  if(isset($_GET['id_produit'])):
    $req_product = sprintf("SELECT * FROM product LEFT JOIN categorie ON product.id_categorie_prod = categorie.id_categorie WHERE id_produit=%d",
    $_GET['id_produit']);
    $product['response'] = "One specific product";
  else:
    $req_product = "SELECT * FROM `product`";
    $product['response'] = "All products";
  endif;
  $result = $connect->query($req_product);
  echo $connect->error;
  $product['code'] = "ok";
  $product['time'] = date('Y-m-d,H:i:s');
  $product['nbhits'] = $result->num_rows;
  while($row = $result->fetch_assoc()):
    $product['data'][] = $row;
  endwhile;
endif;

/* Gestion du DELETE */
if($_SERVER['REQUEST_METHOD'] == "DELETE") : 
  if(isset($_GET['id_produit'])) :
    $req_product = sprintf("DELETE FROM product WHERE id_produit=%d",
    $_GET['id_produit']);
    $connect->query($req_product);
    echo $connect->error;
    $product['response'] = "Suppression du produit avec l'id " . $_GET['id_produit'];
    $product['code'] = "OK";
  else:
    $product['response'] = "vous n'avez pas défini l'id du produit à supprimer";
    $product['code'] = "NOK";
  endif;
endif;

echo json_encode($product); // doit être la dernière ligne sinon il ne te retournera pas de réponse?>