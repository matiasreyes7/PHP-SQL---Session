<?php
Session_start();

if(!isset($_SESSION['identificativo'])) {

    header('location:./formLogin.html');
    exit();

}
?>

<!DOCTYPE html>

<html lang="es">
<head>
        <meta charset="utf-8" />
        
        <title>Dashboard</title>
        <script src="../jquery.js" type="text/javascript"></script>   
        <link rel="stylesheet" type="text/css" href="estilo.css">

    <style>
        *{
            font-size:24px;
        }
        html, body {
            width: 100%;
            height: 100%;            
            align-items:center;
            background-color:lavender;   
            text-align:center;
            font-family: sans-serif;            

        }

        button{
            margin: 2%;
            box-sizing: border-box;
            padding: 20px;
            cursor: pointer;
            border-radius: 15px;
            background-color: lightgrey;
            box-shadow: 0px 2px 0px black;
            border-width: thin;
            color: black;
            height: 15%;
            width: 20%;
        }
        button:hover{
            background-color: lightblue;
        }
        button:active{
            transform: scale(0.98);
        }


        h1{
            font-size:1.8em;
            margin:2%; 
        }
     

    </style>

</head>
<body>
    <h1>Dashboard</h1>
    <button id="app1"> Aplicacion </button>

    <button id="destruir"> Cerrar Sesion </button>

<script>

    var num_sesion = 0;

    $(document).ready(function () {

        $("#destruir").click(function() {
            location.href="./destruirSesion.php";
        });

        $("#app1").click(function () {
            location.href = "./app_modulo1/index.php";
        });


    });     


</script>

</body>
</html>