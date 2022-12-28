<?php

function validate_session(){
    //  VERIFICAMOS QUE EL USUARIO ESTÉ LOGEADO
    if(empty($_SESSION['id']) || !isset($_SESSION['id']) || !$_SESSION){
        session_destroy();
        header('Location: ../index.php');
    }
}