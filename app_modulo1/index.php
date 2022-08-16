<?php
//PROTECCION DE SESION

Session_start();

if(!isset($_SESSION['identificativo'])) {

    header('location:../index.php');
    exit();

}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>
			Eje30Sesion
		</title>
		<link rel="stylesheet" type="text/css" href="./Estilos.css">
	</head>
	<body>
		<!--<div class="main" id="main">-->
			<div class="encabezado-pie" id="encabezado-pie">
				<h2>Jugadores</h2>
				<label>Orden:</label><br />
				<input type="text" name="orden" id="orden" readonly>
				<button id="cargar" class="cargar">Cargar datos</button>
				<button id="vaciar">Vaciar datos</button>
				<button id="alta">Alta jugador</button>
			</div>
			<div class="th" id="th">
				<div class="columna" id="nom">
					Nombre<br />
					<input type="text" name="filtroNom" id="filtroNom">
				</div>
				<div class="columna" id="pos">
					Posición<br />
					<input type="text" name="filtroPos" id="filtroPos">
				</div>
				<div class="columna" id="fec">
					Fecha de nacimiento<br />
					<input type="text" name="filtroFecha" id="filtroFec">
				</div>
				<div class="columna" id="dor">
					Dorsal<br />
					<input type="text" name="filtroDorsal" id="filtroDor">
				</div>
				<div class="columnaBoton" id="pdf">
					PDF<br />
				</div>
				<div class="columnaBoton" id="modi">
					Modis<br />
				</div>
				<div class="columnaBajas" id="baja">
					Bajas<br />
				</div> 			
			</div>
			<div class="clear"></div>
			<div class="contenedor">
				<table>
					<tbody id="tbody">
						
					</tbody>
				</table>
			</div>
			<div class="encabezado-pie" id="encabezado-pie">
				<div id="registros">
					
				</div>
				<h3>Pie de página</h3>
			</div>
		<!--</div>-->
		<div id="modalAlta">
			<div class="encabezadoModal">
				Dar alta jugador
				<button class="cerrar" id="cerrarAlta">X</button>
			</div>
			<form enctype="multipart/form-data" id="formularioAlta" method="post">
				<div class="columnaModal">
					<label>Nombre:</label>
					<input type="text" name="nombreAlta" id="nombreAlta" required><br /><br />
					<label>Posición:</label>
					<select id="posicionAlta" name="posicionAlta"required>
						
					</select><br /><br />
					<input type="file" name="PDFAlta" id="PDFAlta">
				</div>
				<div class="columnaModal">
					<label>Fecha de nacimiento:</label>
					<input type="date" id="fechaAlta" name="fechaAlta" required><br /><br />
					<label>Dorsal:</label>
					<input type="number" id="dorsalAlta" name="dorsalAlta" min="1" max="99" required><br /><br />
				</div>
				<div class="clear"></div>
				<div class="botonEnv">
					<button id="enviarAlta">Enviar</button>
				</div>	
			</form>
		</div>
		<div id="modalModi">
			<div class="encabezadoModal">
				Modificar jugador
				<button class="cerrar" id="cerrarModi">X</button>
			</div>
			<form enctype="multipart/form-data" id="formularioModi" method="post">
				<div class="columnaModal">
					<label>Nombre:</label>
					<input type="text" name="nombreModi" id="nombreModi" required><br /><br />
					<label>Posición:</label>
					<select id="posicionModi" name="posicionModi"required>
						
					</select><br /><br />
					<input type="file" name="PDFModi" id="PDFModi">
					<input type="number" name="dorsalOrig" id="dorsalOrig" readonly hidden>
				</div>
				<div class="columnaModal">
					<label>Fecha de nacimiento:</label>
					<input type="date" id="fechaModi" name="fechaModi" required><br /><br />
					<label>Dorsal:</label>
					<input type="number" id="dorsalModi" name="dorsalModi" min="1" max="99" required><br /><br />
				</div>
				<div class="clear"></div>
				<div class="botonEnv">
					<button id="enviarModi">Enviar</button>
				</div>	
			</form>
		</div>
		<div id="modalRespuesta">
			<div class="encabezadoModal">
				Respuesta servidor
				<button class="cerrar" id="cerrarResp">X</button>
			</div>
			<div id="contRespuesta">
				<h2>Respuesta del servidor:</h2>
			</div>
		</div>
	</body>
	<script src="../../jquery.js" type="text/javascript"></script>
	<script src="./posiciones.json" type="text/javascript"></script>
	<script type="text/javascript">
		var opcion;
		var pos= JSON.parse(textoPosiciones);
		var dorsal= document.getElementById("dorsalAlta");
		var selectPosAlta= document.getElementById("posicionAlta");
		var selectPosModi= document.getElementById("posicionModi");
		var bodyTabla=document.getElementById("tbody");
		var formAlta = document.getElementById("formularioAlta");
        var formModi = document.getElementById("formularioModi");

		function limpiarForm(){
            document.getElementById("nombreAlta").value = "";
            document.getElementById("fechaAlta").value = "";
            document.getElementById("dorsalAlta").value = "";
            document.getElementById("nombreModi").value = "";
            document.getElementById("fechaModi").value = "";
            document.getElementById("dorsalModi").value = "";
        }

        $(document).ready(function () {
            creaOpciones();
            document.getElementById("modalAlta").className="modalApagado";
			document.getElementById("modalModi").className="modalApagado";
			document.getElementById("modalRespuesta").className="modalApagado";
            
            //FUNCIONES HEADER

            $("#cargar").click(function () {
                cargaTabla();                
            });

            $("#vaciar").click(function () {
                $("#tbody").empty(); 
            });
        });       
        
        function cargaTabla(){
			$("#tbody").empty();
			$("#tbody").html("<p>Esperando respuesta...</p>");
			var objAjax= $.ajax({
				type:"GET",
				url: "./respuesta.php",
				data:{
					orden:$("#orden").val(),
					filtroNom:$("#filtroNom").val(),
					filtroPos:$("#filtroPos").val(),
					filtroFec:$("#filtroFec").val(),
					filtroDor:$("#filtroDor").val()
				},
				success: function(respuestaDelServer,estado){
					//alert(respuestaDelServer);
					$("#tbody").empty();
					objJson=JSON.parse(respuestaDelServer);
					objJson.jugadores.forEach(element =>{
						var fila	= document.createElement("tr");
						var celda= document.createElement("td");
						celda.setAttribute("campo-dato","nombre");
						celda.innerHTML=element.nombre;
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","posicion");
						celda.innerHTML=element.posicion;
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","fecha");
						celda.innerHTML=element.fecha;
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","dorsal");
						celda.innerHTML=element.dorsal;
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","pdf");
						celda.innerHTML="<button class='btColumnaPDF'>PDF</button>";	
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","modi");
						celda.innerHTML="<button class='btColumnaModi'>Modi</button>";
						fila.appendChild(celda);
						celda= document.createElement("td");
						celda.setAttribute("campo-dato","baja");
						celda.innerHTML="<button class='btColumnaBorrar'>Borrar</button>";
						fila.appendChild(celda);
						$("#tbody").append(fila);
					});
					$("#registros").html("N° de registros: " + objJson.jugadores.length);
				}
			});
		}

        function modi() {
            var confirma = confirm("¿Esta seguro de modificar los datos del jugador?");
            if (confirma) {
                var objAjax = $.ajax({
                    type: 'post',
                    method: 'post',
                    enctype: 'multipart/form-data',
                    url: "./modi.php",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: new FormData($("#formularioModi")[0]),
                    success: function (respuestaDelServer){
                    	//alert(respuestaDelServer);
                        var objDato = JSON.parse(respuestaDelServer);
                        alert(objDato.respuesta_estado);                        
                        //limpiarForm();
                        document.getElementById("modalModi").className="modalApagado";
                        MostrarRespModal();
                        document.getElementById("contRespuesta").innerHTML=objDato.respuesta_estado;
                        cargaTabla();
                    }
                });
            }  
        }

        function alta(){
            	var data= new FormData($("#formularioAlta")[0]);               
                var objAjax = $.ajax({
                    type: "post",
                    method: "post",
                    enctype: "multipart/form-data",
                    url: "./alta.php",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: data,
                    success:function(respuestaDelServer){
                    	alert(respuestaDelServer);
                        var objJSON = JSON.parse(respuestaDelServer);                        
                        limpiarForm();
                        document.getElementById("modalAlta").className="modalApagado";
                        MostrarRespModal();
                        document.getElementById("contRespuesta").empty();
                        document.getElementById("contRespuesta").innerHTML=objDato.respuesta_estado;
                        cargaTabla();
                    }
                });
        }

        function baja(dor) {
            var confirma = confirm("¿Esta seguro que desea eliminar al jugador?");
            if (confirma) {
                var objAjax = $.ajax({
                    type: "GET",
                    url: "./baja.php",
                    data: { dorsal: dor },

                    success: function (respuestaDelServer, estado) {
                        //alert(respuestaDelServer);
                        var objJson = JSON.parse(respuestaDelServer);
                        document.getElementById("contRespuesta").empty();
                       	MostrarRespModal();
                       	document.getElementById("contRespuesta").innerHTML=objJson.respuesta_estado;
                        cargaTabla();
                    }
                });
            }
        }

        function CargarPDF(dor){
            var request = $.ajax({
                type: "GET",
                url: "./traerPDF.php",
                data: {dorsal: dor},
                success: function (respuestaDelServer, estado) {
                    //alert(respuestaDelServer);
                    var objetoDato = JSON.parse(respuestaDelServer);
                    //console.log(objetoDato);
                    MostrarRespModal();
                    //alert(objetoDato.respuesta_estado);
                    document.getElementById("contRespuesta").innerHTML="<iframe id='iframePDF' width='100%' height='100%' src='data:application/pdf;base64,"+objetoDato.documentoPDF+"'></iframe>";
                    
                }//cierra funcion asignada al success
            });//cierra ajax
        }

        
        //COMPLETAR CAMPOS

       $("#nom").click(function(){
			$("#orden").val("nombre");
			cargaTabla();
		});

		$("#pos").click(function(){
			$("#orden").val("posicion");
			cargaTabla();
		});

		$("#fec").click(function(){
			$("#orden").val("fecha");
			cargaTabla();
		});

		$("#dor").click(function(){
			$("#orden").val("dorsal");
			cargaTabla();
		});

        //ENVIO DATOS

        $("enviarModi").click(function() {
            modi();
        });

        $("enviarAlta").click(function() {
            alta();
        });

        $("#alta").click(function () {
            MostrarAlta();
        });    

        $("#cerrarAlta").click(function () {
            document.getElementById("modalAlta").className="modalApagado";
            limpiarForm();
        });

        $("#cerrarModi").click(function () {
            document.getElementById("modalModi").className="modalApagado";
            limpiarForm();
        });

        $("#cerrarResp").click(function () {
            document.getElementById("modalRespuesta").className="modalApagado"; 
        });


        //TEMPORAL
        
         function creaOpciones() {
            pos.posiciones.forEach(function(argValor,argIndice){
				var opcion=document.createElement("option");
				opcion.setAttribute("value",argValor.pos);
				opcion.innerHTML=argValor.desc;
				selectPosAlta.appendChild(opcion);
				//selectPosModi.appendChild(opcion);
			});
			pos.posiciones.forEach(function(argValor,argIndice){
				var opcion=document.createElement("option");
				opcion.setAttribute("value",argValor.pos);
				opcion.innerHTML=argValor.desc;
				selectPosModi.appendChild(opcion);
			});
        }

        function limpiarForm(){
            document.getElementById("nombreAlta").value = "";
            document.getElementById("fechaAlta").value = "";
            document.getElementById("posicionAlta").value = "";
            document.getElementById("dorsalAlta").value = "";
            //document.getElementById("PDFAlta").value = "";
            document.getElementById("nombreModi").value = "";
            document.getElementById("fechaModi").value = "";
            document.getElementById("posicionModi").value = "";
            document.getElementById("dorsalModi").value = "";
            //document.getElementById("PDFModi").value = "";
        }

        // Event Listener para botones de Modi, PDF y Delete
        if (window.addEventListener) {
            document.addEventListener('click', function (e) {
              if (e.target.getAttribute("class") != null){
                if (e.target.getAttribute("class").indexOf("btColumnaModi") === 0) {
                    CargarVentanaModi(e.target.parentElement.parentElement);
                    MostrarModi();
                    //MostrarRespModal();
                   
                }
                if (e.target.getAttribute("class").indexOf("btColumnaPDF") === 0) {
                    CargarPDF(e.target.parentElement.parentElement.querySelector('[campo-dato^="dorsal"]').innerHTML);
                    //MostrarRespModal();
                }
                if (e.target.getAttribute("class").indexOf("btColumnaBorrar") === 0) {
                    baja(e.target.parentElement.parentElement.querySelector('[campo-dato^="dorsal"]').innerHTML);
                    //MostrarRespModal();
                }
              }
            });
        }

        
        function CargarVentanaModi(dor){
            document.querySelector("#nombreModi").value = dor.querySelector('[campo-dato^="nombre"]').innerHTML;
            var pos=dor.querySelector('[campo-dato^="posicion"').innerHTML;
            if(pos=="Arquero"){
            	document.querySelector("#posicionModi").value="PT";
            }
            if(pos=="Defensor Central"){
            	document.querySelector("#posicionModi").value="CD";
            }
            if(pos=="Lateral Derecho"){
            	document.querySelector("#posicionModi").value="LD";
            }
            if(pos=="Lateral Izquierdo"){
            	document.querySelector("#posicionModi").value="LI";
            }
            if(pos=="Mediocampista"){
            	document.querySelector("#posicionModi").value="MC";
            }
            if(pos=="Delantero"){
            	document.querySelector("#posicionModi").value="DC";
            }
            //document.querySelector("#posicionModi").value = dor.querySelector('[campo-dato^="posicion"]').innerHTML;
            document.querySelector("#fechaModi").value = dor.querySelector('[campo-dato^="fecha"]').innerHTML;
            document.querySelector("#dorsalModi").value = dor.querySelector('[campo-dato^="dorsal"]').innerHTML;
            document.querySelector("#dorsalOrig").value = dor.querySelector('[campo-dato^="dorsal"]').innerHTML;
        }

        function MostrarRespModal(){
            document.getElementById("modalRespuesta").className="modalPrendido";
        }

        function MostrarModi(){
           document.getElementById("modalModi").className="modalPrendido";
        }

        function MostrarAlta(){
            document.getElementById("modalAlta").className="modalPrendido";
        }
	</script>
</html>