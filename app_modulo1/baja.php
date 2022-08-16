<?php

    //PROTECCION DE SESION

    Session_start();

    if(!isset($_SESSION['identificativo'])) {

        header('location:../index.php');
        exit();

    }
    
    $dbname= "bqttuuyuglnwseuamusl";
    $host= "bqttuuyuglnwseuamusl-mysql.services.clever-cloud.com";
    $user= "udsm00rlr3hd3bgc";
    $password= "E08KdPPfSrIBOGHASgmO";

    $respuesta_estado = "";

    $objJugadores = new stdClass();
    $objJugadores->success = FALSE;
    // Connect to DB
    $conn = mysqli_connect("bqttuuyuglnwseuamusl-mysql.services.clever-cloud.com", "udsm00rlr3hd3bgc", "E08KdPPfSrIBOGHASgmO", "bqttuuyuglnwseuamusl");
    //print if not connected
    if (!$conn) $respuesta_estado = 'Error al contectar a la base de datos: ' . mysqli_connect_error();
    
    
    if(!isset($_GET['dorsal'])) $respuesta_estado = "No se ha enviado un número de dorsal válido";
    else 
    {
        $objJugadores->dorsal = $_GET['dorsal'];

        $sql = "DELETE FROM `datos` WHERE `dorsal`='" . $_GET['dorsal'] . "'";
        $result = $conn->query($sql);
        
        if ($result === TRUE) 
        {
            $respuesta_estado = "Jugador eliminado exitosamente!";
            $objJugadores->success = TRUE;
        } 
        else 
        {
            $respuesta_estado = "Error al eliminar al jugador: " . $objJugadores->dorsal = $_GET['dorsal'];
        }
    }
    mysqli_close($conn);
    $objJugadores->respuesta_estado = $respuesta_estado;
    echo json_encode($objJugadores,JSON_INVALID_UTF8_SUBSTITUTE); // envio objJugadores como JSON al front


?>