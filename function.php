<?php 
  function myPrint_r($value) { // on appellera la fonction avec le parametre $value
    if(MODE =="dev"): // la fonction myPrint_r ne s'effectuera QUE si la constante MODE on estset sur dev. C'est une constante, donc pas besoin de quote.  Quand on sera en prod, on pourra mettre le switch sur prod et myPrint_r ne tournera nulle part, même si on oublie d'en retirer une
    echo '<pre>'; // on met une balise pre pour que print_r s'affcihe sous forme d'array dans le navigateur
        print_r($value);
      echo '</pre>';
    endif;
  }
?>