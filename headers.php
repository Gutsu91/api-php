<?php 
// par défaut, AJAX limite les settings et ce n'est pas compatible pour faire de l'API et du json, du coup on set des en-têtes HTTP
header("Access-Control-Allow-Origin:*"); //On rend notre API accessible à des scripts qui viennent d'autres domaines que le notre
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); //on autorise le GET, POST, PUT et DELETE uniquement (sauf que les restirctions n'ont pas l'air de fonctionner)
header("Content-Type:application/json"); // on indique que  les résultats doivent être retourné en json
?>