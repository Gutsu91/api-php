<?php
/* script d'authentification*/
include 'config.php'; // on a besoin de se connecter à la db pour vérifier que les données sont bien conformes à ce qu'il ya dans useer

if (isset($_GET['delog'])) :
  session_start(); // on accede à session
  //session_destroy(); // on pourrait détruire toute la session, mais si on fait ça on bute l'entièreté des infos dans la session, pex un panier d'achat qui est contenu dans $_SESSION aussi
  unset($_SESSION['user']); // on retire l'user de session
  unset($_SESSION['token']); //on retire le token de la session
  header("location:auth.php"); // on redirige vers auth mais sans delog dans l'url
endif;

//tester si le formulaire a été soumis
if(isset($_POST['ident'])) : // tester si les données post viennent bien du formulaire de login
  $sql_auth = sprintf("SELECT * FROM `users` WHERE `login` = '%s' AND `password` = '%s'", // requêtes SQL pour s'assurer que login et mdp sont présents dans la db
  $_POST['login'],
  $_POST['password']
  );
  $result = $connect->query($sql_auth);
  echo $connect->error;
  echo $result->num_rows; //afficher le nombre d'enregistrement dans la table users qui correspondent à ce qu'on a tapé
  // si numrows > 0, login +mdp sont corrects, sinon ils ne sont pas bon, donc on peut faire un if:
  if($result->num_rows > 0 ) : 
    echo "<p>vous n'êtes connectés</p>";
    $user = $result -> fetch_assoc(); // va me chercher les données et met lets dans un array associatif
    session_start(); // on indique que le script a accès à la variable superglobale $_SESSION, sans ça le script ne peut pas
    $_SESSION['user'] = $user['id_users']; // on crée l'entrée user dans la variable globale. Attention ça doit corresponde au champ dans la table user
    $_SESSION['token'] = md5($user['login'].$user['id_user'].time()); // on crée un token, on dit qu'il est égal à $user ET id_users ET time MAIS crypté par md5. L'id_user parce que l'user ne le connait pas, et time pour que le token change à chaque connection. Pour bien faire faudrait aussi inclure un chifre random et multiplier time avec.
    // le token est particulièrement important dans le cadre d'une API, il est moins nécessaire dans php car on gère pas mal de chose en natif
    header("location:secure.php"); // on rediriger sur secure si la connection est bonne
    exit; // on met un exit parce que la redirection va prendre un peu de temps et on ne veut pas que le reste du code s'exécute
    myPrint_r($_SESSION); // on affiche l'id de la session (il n'y a que ça dans $_SESSION pour l'instant)
    // on crée une session qui reste active tant qu'on ne ferme pas le NAVIGATEUR (pas la fenêtre) OU qu'on ne kill pas la session dans un script
    // il y a moyen de faire des settings pour spécifier qu'après Xmin d'inactivité, la session se bute automatiquement
  else : 
    echo "<p>vous n'etes pas connectés</p>";
  endif;
endif;


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Qui suis-je ?</title>
</head>
<body>
  <form action="" method="post"> 
    <input type="text" name="login" placeholder="votre login">
    <input type="password" name="password" placeholder="Le mot de paaaaasse ?">
    <button>S'identifier</button>
    <input type="hidden" name="ident"> <?php //on s'assure que les données viennent bien du formulaire ?>
  </form>
</body>
</html>