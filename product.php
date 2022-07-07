<?php 
require_once 'config.php';
require_once 'headers.php';
//require_once 'verif-token.php';

/* Gestion du GET */
if($_SERVER['REQUEST_METHOD'] == 'GET') :
  if(isset($_GET['id_produit'])):
    //$req_product = sprintf("SELECT product.* FROM product LEFT JOIN categorie ON product.id_categorie_prod = categorie.id_categorie WHERE id_produit=%d", // on a créé une vue donc on l'exploite
    $req_product = sprintf("SELECT * FROM product_lf2_categorie WHERE id_produit=%d",
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

/* Gestion du POST */
if($_SERVER['REQUEST_METHOD'] == "POST") :
    // on doit récupérer les valeurs AVANT les tests isset(), sinon on peut pas vérifier qu'elles sont présentes...
   $json = file_get_contents('php://input');
    $object = json_decode($json, true);
  if(isset($object['nom']) AND isset($object['id_categorie_prod']) AND isset($object['prix'])):
    $req_product = sprintf("INSERT INTO `product` SET `nom_produit`='%s', `id_categorie_prod`='%s', `prix` ='%s'",
      strip_tags(addslashes($object['nom'])),
      strip_tags(addslashes($object['id_categorie_prod'])),
      strip_tags(addslashes($object['prix']))
      );
    $connect->query($req_product);
    echo $connect->error;
    $product['new_id'] = $connect->insert_id;
    $product['code'] = "OK";
    $product['response'] = "ajout d'un produit " . $object['nom'] . "avec l'id " .$connect->insert_id;
  else:
    $product['response'] = "Vous devez spécifier le nom du produit, l'id de sa catégorie et son prix";
    $product['code'] = "NOK";
  endif;
endif;

echo json_encode($product); // doit être la dernière ligne sinon il ne te retournera pas de réponse?>