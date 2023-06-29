<?php
/*======================================================
|   [Connect SQL]
======================================================*/   
$connection = new mysqli('localhost', 'root', '', 'u122207942_itp_tempo');
$_SESSION['connection'] = $connection;
if($connection->connect_error){
     // var_dump ($connection); die;
    echo '<center>Hay un problema en el servidor. Contacta con un administrador, gracias.</center>';
    die();
}

?>