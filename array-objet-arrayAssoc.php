<?php 
$personnel[] = [ //ceci est un array associatif
  ['nom'] => "charlier",
  ['prenom'] => "pierre"
];

// si on veut créer un objet en php il faut créer notre classe constructive qui va construire l'objet
// MAIS si on nous retourne un objet Jason, alors c'est bien un objet PHP (pour autant qu'on ait bien fait json_encode)

echo $personnel[0]['nom'] //affichera l'entrée nom du tableau associatif

?>
<script>
  let personnel = [ // ceci est un objet javascript
    {
      "nom":"charlier",
      "prenom":"pierre"
    }
  ]
console.log(personne[0].nom) // on va chercher la propriété nom dans l'entrée 0 de l'objet personnel
</script>