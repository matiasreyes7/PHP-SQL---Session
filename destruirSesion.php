<?php
include("./protecSesion.php");

session_destroy();

header('location:./formLogin.html');


?>

