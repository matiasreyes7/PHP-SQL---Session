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
	$respuesta_estado="";

	var_dump($_POST);
	$nombre= $_POST['nombreModi'];
	$posicion= $_POST['posicionModi'];
	$fecha = $_POST['fechaModi'];
	$dorsal = $_POST['dorsalModi'];
	$dorsalOrig = $_POST['dorsalOrig'];

	try{
		$dsn= "mysql:host=$host;dbname=$dbname";
		$dbh= new PDO($dsn, $user, $password);
		$respuesta_estado=$respuesta_estado . "\nConexión exitosa";
	}catch(PDOException $e){
		$respuesta_estado=$respuesta_estado . "\n" . $e->getMessage();
	}
	$sql="UPDATE datos SET nombre=:nombre, posicion=:posicion, fecha=:fecha, dorsal=:dorsal, pdf='' WHERE dorsal:=dorsalOrig";

	try {
		$stmt = $dbh->prepare($sql);
		$respuesta_estado = $respuesta_estado . "\nPreparacion exitosa!";
	}catch (PDOException $e) {
		$respuesta_estado = $respuesta_estado . "\n" . $e->getMessage();
	}
	$stmt->bindParam(':nombre', $nombre);
	$stmt->bindParam(':posicion', $posicion);
	$stmt->bindParam(':fecha', $fecha);
	$stmt->bindParam(':dorsal', $dorsal);
	$stmt->bindParam(':dorsalOrig', $dorsalOrig);

	try{
		$stmt->execute();
		$respuesta_estado=$respuesta_estado . "\nEjecución exitosa!";
	}catch(PDOException $e){
		$respuesta_estado=$respuesta_estado . "\n" . $e->getMessage();
	}

	$respuesta_estado= $respuesta_estado . "\n Modificacion de datos:";
	$respuesta_estado= $respuesta_estado . "\nNombre:" . $nombre;
	$respuesta_estado= $respuesta_estado . "\nPosicion:" . $posicion;
	$respuesta_estado= $respuesta_estado . "\nFecha de nacimiento:" . $fecha;
	$respuesta_estado= $respuesta_estado . "\nDorsal:" . $dorsal;

	if(!isset($_FILES['PDFModi'])){
		$respuesta_estado=$respuesta_estado . "No se inicializo global \$_FILES";
	}
	else{
		if(empty($_FILES['PDFModi']['name'])){
			$respuesta_estado=$respuesta_estado . "<br />No ha sido seleccionado ningun archivo para enviar!";
		}
		else{
			$respuesta_estado=$respuesta_estado . "Trae documento pdf asociado al jugador numero " . $dorsal;
			$contenidoPdf= file_get_contents($_FILES['PDFModi']['tmp_name']);
			$sql="UPDATE datos SET pdf=:contenidoPdf WHERE dorsal=:dorsalOrig";
			try {
				$stmt = $dbh->prepare($sql);
				$respuesta_estado = $respuesta_estado . "\nPreparacion exitosa!";
			}catch (PDOException $e) {
				$respuesta_estado = $respuesta_estado . "\n" . $e->getMessage();
			}
			$stmt->bindParam(':contenidoPdf', $contenidoPdf);
			$stmt->bindParam(':dorsalOrig', $dorsalOrig);
			try{
				$stmt->execute();
				$respuesta_estado=$respuesta_estado . "\nEjecución exitosa!";
			}catch(PDOException $e){
				$respuesta_estado=$respuesta_estado . "\n" . $e->getMessage();
			}
		}
	}
	$dbh=null;
	echo $respuesta_estado;
?>