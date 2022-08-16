<?php
    $objJugador = new stdClass();
    $objJugador->success = FALSE;
    // Connect to DB


    $conn = mysqli_connect("bqttuuyuglnwseuamusl-mysql.services.clever-cloud.com", "udsm00rlr3hd3bgc", "E08KdPPfSrIBOGHASgmO", "bqttuuyuglnwseuamusl");
    //print if not connected
    if (!$conn) $respuesta_estado = 'Error al contectar a la base de datos: ' . mysqli_connect_error();

    
    
    if(!isset($_GET['dorsal'])) $respuesta_estado = "No se ha enviado un dorsal válido";
    else 
    {
        $objJugador->dorsal = $_GET['dorsal'];

        $sql = "SELECT pdf FROM datos WHERE dorsal='" . $_GET['dorsal'] . "'";
        
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $objJugador->documentoPDF = base64_encode($row['pdf']);
            }
            if (!empty($objJugador->documentoPDF))
            {
                $objJugador->success = TRUE;
                $respuesta_estado = "Consulta exitosa!";
            } 
            else $respuesta_estado = "El dorsal enviado no posee archivo PDF";
        }
        else $respuesta_estado = "No se encuentra una persona con ese dorsal: " . $_GET['dorsal'];
    }
    mysqli_close($conn);

    $objJugador->respuesta_estado = $respuesta_estado;
    echo json_encode($objJugador,JSON_INVALID_UTF8_SUBSTITUTE); // envio objArticulos como JSON al front
?>