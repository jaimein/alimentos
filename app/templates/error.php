<?php ob_start() ?>

<div class="row">

<h3> Ha habido un error </h3>


<?php $contenido = ob_get_clean() ?>

<!-- Cambiar si al final pasamos el usuario en $params-->
<!--  if (isset($params['mensaje'])):  -->
<?php
 if (isset($_SESSION['id'])) {
    include 'layout.php';
 } else {
    include 'layout_basico.php'; 
 }
 ?>