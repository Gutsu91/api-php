<?php
require_once 'config.php'; // on a besoin de config pour pouvoir se connecter à la db ET charger fonction
require_once 'headers.php'; // on a besoin d'inclure les headers pour faire de l'API (on aurait pu l'inclure dans config)
require_once 'verif-token.php';

/* Gestion des requêtes faites en GET sur la route personne */
if($_SERVER['REQUEST_METHOD'] == 'GET') : // Si la ligne de l'array de la supervariable $_SERVER est en GET (si la requête et en get quoi), alors :
  if(isset($_GET['id_personnes'])): // si on l'ID personne est dans l'URL,
    $req_allPeople = sprintf("SELECT * FROM `personnes` WHERE `id_personnes` = %d", // on fait une requête SQL dans la table personne, where l'id = (...)
    $_GET['id_personnes']); //placeholder pour %d
    $allPeople['response'] = 'One specific person'; // message de réponse pour une personne spécifique
  else:
    $req_allPeople = "SELECT * FROM `personnes` ORDER BY `nom`,`prenom` ASC "; // pas d'id alors on demande tout le monde
    $allPeople['response'] = "All people"; // message de réponse pour tout le monde ('response' est une valeur arbitraire). Ca serait plus propre de transformer response en array, d'y stocker le message, nb hits, le code et le time
  endif;

  $result = $connect->query($req_allPeople);  // on stoque les résultats de la query danbs l'objet connect
  echo $connect->error; // on écrit l'erreur de l'objet connect s'il y en a une

  $allPeople['code'] = 200; // on écrit un code de réponse arbirtraire dans le json
  $allPeople['time'] = date('Y-m-d,H:i:s'); // on défini le temps de la réponse
  $allPeople['nbhits'] = $result->num_rows; // on ajoute en ligne le nombre d'enregistrement que la requête renvoie
  while($row = $result->fetch_assoc()): // tant qu'il y a des résultats dans l'objet result alors on fait un fetch de ces résultats qu'on place dans $row
    $allPeople['data'][] = $row; //on place ces résultats dans l'array data pour avoir un objet avec un array dedans
  endwhile;
endif;
// $allPeople['N'] correspond à la syntaxe d'un array associatif. C'est un type d'array propre à PHP, qui s'approche d'un objet en JS mais qui n'en est pas vraiment un (il n'y a apas d'objet en PHP). On devra ensuite convertir cet objet associatif en objet json (voir la ligne avec json_encode)

/* gestion des requêtes faites en mode DELETE */
if($_SERVER['REQUEST_METHOD'] == 'DELETE') : // Si one st en méthode delete
  if(isset($_GET['id_personnes'])): // Si l'ID de la personne est présent dans l'url, alors exécute le block ci-dessous
    $req_delPeople = sprintf("DELETE FROM `personnes` WHERE id_personnes=%d", //on appelle delete sur une route ou l'ID est set donc on peut le récupérer
    $_GET['id_personnes']); // l'id est dans l'url donc on va le chercher dans la supervariable get. 
    $connect->query($req_delPeople); // on stock le résultat de la demande dans l'array associatif $csonnect
    echo $connect->error; // si message d'erreur on le stock dans l'objet connect et on l'écrit dans le client
    $allPeople['response'] = "Suppression du personnage avec l'id " . $_GET['id_personnes']; // message arbitraire de réponse
  else :  // s'il n'y a pas l'id de la personne dans la route, on donne un message d'erreur
    $allPeople['response'] = "Il manque l'id de la personne";
    $allPeople['code'] = '50';
  endif;
endif;

/* gestion des requêtes en post */
if($_SERVER['REQUEST_METHOD'] == "POST") :
  $json = file_get_contents('php://input'); // on récupère le json dans l'en-tête http
  $object = json_decode($json, true); // on le decode, ça génère un objet PHP
  $sql = sprintf("INSERT INTO `personnes`SET `nom`='%s', `prenom`='%s'",
    addslashes($object['nom']), // lire les propriétés de l'array associatif (et on les slash pour éviter les pbm)
    addslashes($object['prenom'])
    //$_POST['nom'], //ça correspond à l'ancienne methode ou on passerait les infos dans un formulaire en post, mtn on balance des objets json plutôt que de chipoter avec un formulaire
    //$_POST['prenom']
  );
  $connect->query($sql); // on stock les résultats de la requête SQL dans l'array associatif $connect
  echo $connect->error;
  $allPeople['new_id'] = $connect->insert_id; // quand on insert une entrée, on ne connait pas l'ID pusque c'est la DB qui va le créer, donc on met l'insert_id ici
  $allPeople['response'] = "Ajout d'une personne avec l'id " . $connect->insert_id; // et du coup on le récup ici
  
endif;

if($_SERVER['REQUEST_METHOD'] == "PUT") :
  $json = file_get_contents('php://input'); // on récupère le json dans l'ent-ête http
  $object = json_decode($json, true); // on le decode, ET on passe le paramètre 'true', pour récupérer les données au format ARRAY
  if(isset($object['nom']) AND isset($object['prenom'])) : //SI nom et prenom sont bien présent dans la requête, excécuter le code ci-dessous (modifier les données)
    $sql = sprintf("UPDATE `personnes`SET `nom`='%s', `prenom`='%s' WHERE id_personnes=%d",
    $object['nom'], // on a recup un array donc on utilise la syntaxe array, et pas la syntaxe objet (ci dessous en commentaire)
    //$ibject->nom,
    $object['prenom'], // on l'a pas fait ici mais on devrait faire des strip_tags et addslashes
    $_GET['id_personnes']
    );
    // on l'a pas fait non plus mais on devrait ajouter des controles pour voir si les champs obligatoire sont bien remplis avec ce qu'il devrait contenir.
    $connect->query($sql);
    echo $connect->error;
    $allPeople['response'] = "Edit d'une personne avec l'id " . $_GET['id_personnes']; // et du coup on le récup ici
  else : // SI nom et prenom, ne sont pas set, alors afficher ce message d'erreur
    $allPeople['response'] = 'il manque des données';
    $allPeople['code'] = '17';
    // pour bien faire il faudrait faire un if pour afficher le/les champs manquants
  endif;
endif;

echo json_encode($allPeople); // doit être la dernière ligne sinon il ne te retournera pas de réponse
?>
