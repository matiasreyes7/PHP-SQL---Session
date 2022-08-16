
<?php
//PROTECCION DE SESION

Session_start();

if(!isset($_SESSION['identificativo'])) {

    header('location:./index.php');
    exit();

}
?>

