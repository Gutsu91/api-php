<?php 
header("Access-Control-Allow-Origin:*"); // par défaut AJAX est limité à son propre domaine, hors on en a besoin pour utiliser une API, donc on l'autorise
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE"); //on autorise les méthodes get, post, put et delete
header("Content-Type:application/json"); // on retourne les résultats en mode json
?>