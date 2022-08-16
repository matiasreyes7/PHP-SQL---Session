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

    $nombre = ($_POST["nombreAlta"]);
    $posicion = ($_POST["posicionAlta"]);
    $fecha = ($_POST["fechaAlta"]);
    $dorsal = ($_POST["dorsalAlta"]);
    
    $respuesta_estado = "";
    $e = "";

    $objJugador= new stdClass();
    $objJugador->success=FALSE;
    try{
        $dsn = "mysql:host=$host;dbname=$dbname";
        $dbh = new PDO($dsn, $user, $password);
        $respuesta_estado = $respuesta_estado . "Conexion con base de datos exitosa.";
    } catch (PDOException $e){
        $respuesta_estado = $respuesta_estado . "\n" . $e->getMessage();
    }
    if(empty($_FILES["PDFAlta"]["name"])) {
        $sql = "INSERT INTO datos (nombre, posicion, fecha, dorsal, pdf) 
            VALUES (:nombre, :posicion, :fecha, :dorsal, '')";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":posicion", $posicion);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":dorsal", $dorsal);
        $respuesta_estado = $respuesta_estado . "<br/><br/>Alta realizada correctamente.<br /><br />Datos ingresados:<br />Nombre: " . $nombre . " <br />Posicion: " . $posicion . "<br />Fecha de nacimiento: " . $posicion . "<br />Dorsal: " . $dorsal . "<br />Sin pdf adjunto.";
        $objJugador->success=TRUE;
    }
    else{
        $pdf = base64_encode(file_get_contents($_FILES["PDFAlta"]["tmp_name"]));
        
        $sql = "INSERT INTO datos (nombre, posicion, fecha, dorsal, pdf) 
            VALUES (:nombre, :posicion, :fecha, :dorsal, :pdf)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":posicion", $posicion);
        $stmt->bindParam(":fecha", $fecha);
        $stmt->bindParam(":dorsal", $dorsal);
        $stmt->bindParam(":pdf", $pdf);
        $respuesta_estado = $respuesta_estado . "<br/><br/>Operacion exitosa.<br /><br />Datos ingresados:<br />Nombre: " . $nombre . " <br />Posicion: " . $posicion . "<br />Fecha de nacimiento: " . $fecha . "<br />Dorsal: " . $dorsal . "<br />Con pdf adjunto.";
        $objJugador->success=TRUE;
    }
    
    try{
        $stmt->execute();
    } catch (PDOException $e){
        if (strstr( $e->getMessage(), 'Duplicate entry' )) $respuesta_estado = "Entrada duplicada<br/>Por favor ingrese otro código";
    }
    

    $dbh = null;
    $objJugador->respuesta_estado=$respuesta_estado;
    //echo json_encode($respuesta_estado);
    echo json_encode($objJugador,JSON_INVALID_UTF8_SUBSTITUTE);

?>