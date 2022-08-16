<?php
	sleep(1);

	$orden="";
	$respuesta_estado="";		
	include "./conexionBase.php";

	$obj=new stdclass;
	$obj->orden=$_GET["orden"];
	$obj->filtroNom=$_GET["filtroNom"];
	$obj->filtroPos=$_GET["filtroPos"];
	$obj->filtroFec=$_GET["filtroFec"];
	$obj->filtroDor=$_GET["filtroDor"];
	try{
		$dsn= "mysql:host=$host;dbname=$dbname";
		$dbh= new PDO($dsn, $user, $password);
		$respuesta_estado=$respuesta_estado . "\nconexión exitosa";
	}
	catch(PDOException $e){
		$respuesta_estado=$respuesta_estado . "\n" . $e->getMessage();
	}
	//echo $respuesta_estado;

	$sql="SELECT * FROM datos WHERE nombre like CONCAT('%',:nombre,'%') and ";
	$sql= $sql . "posicion like CONCAT('%',:posicion,'%') and ";
	$sql= $sql . "fecha like CONCAT('%',:fecha,'%') and ";
	$sql= $sql . "dorsal like CONCAT('%',:dorsal,'%')";
	if (!empty($obj->orden)) $sql= $sql . " ORDER BY " . $obj->orden;

	$stmt= $dbh->prepare($sql);
	$stmt->bindParam(':nombre',$obj->filtroNom);
	$stmt->bindParam(':posicion',$obj->filtroPos);
	$stmt->bindParam(':fecha',$obj->filtroFec);
	$stmt->bindParam(':dorsal',$obj->filtroDor);
	$stmt->setFetchMode(PDO::FETCH_ASSOC);
	$stmt->execute();
	$jugadores=[];
	While($fila=$stmt->fetch()){
		$objJugador= new stdClass();
		$objJugador->nombre= $fila["nombre"];
		$objJugador->posicion= $fila["posicion"];
		$objJugador->fecha= $fila["fecha"];
		$objJugador->dorsal= $fila["dorsal"];
		array_push($jugadores,$objJugador);
	}
	$objJugadores= new stdClass();
	$objJugadores->jugadores= $jugadores;
	$objJugadores->cuenta= count($jugadores);
	$salidaJson= JSON_encode($objJugadores);
	$dbh= null;
	echo $salidaJson;
?>