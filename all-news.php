<?php
require_once 'config.php';
require_once 'headers.php';

$req_all_news = "SELECT * FROM `news`";
$result = $connect->query($req_all_news);
echo $connect->error;

while($row = $result->fetch_assoc()):
  $allNews['data'][] = $row;
endwhile;

//$allNews['code'] = 200;
//$allNews['reponse'] = "All news";

echo json_encode($allNews);


?>