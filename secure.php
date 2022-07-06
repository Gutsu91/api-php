<? 

session_start(); // on est obligé de faire appel à session start parce pour pouvoir écrire ou LIRE dans session, on a besoin de cette ligne de code
if(!isset($_SESSION['user'])) : // si je n'ai ps d'utilisateur dans $_SESSION, alors, exécuter le code ci-dessous (on a acces à $_SESSION car c'st une variable superglobale et on a set userdans l'auttre page)
  header("location:auth.php"); // on redirige vers location
  exit; // on bloque le reste du code en attendant que la redirection se fasse
endif;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Secure</title>
</head>
<body>
  <h1>Secure</h1>
  <?php //le but ici est de faire un test. Si on est pas connecté et qu'one ssaie d'accéder à secure, on doit rediriger l'user sur login, s'il est connecté alors il peut accéder à secure?>
  <a href="auth.php?delog">Se déconnecter</a> <?php // on crée un lien avec un parametre d'url pour pouvoir exploiter ce parametre?>
</body>
</html>