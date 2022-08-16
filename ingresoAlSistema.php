<?php


$usuario = $_POST['usuario'];
$clave =$_POST['clave'];
$nombreArchivo = "contador.txt";
$contenido = trim(file_get_contents($nombreArchivo));
$visitas = intval($contenido);

session_start();
if (!isset($_SESSION['identificativo'])) { //No hay sesión iniciada asociada al requerimiento http 
    
    if (!autenticacion($usuario,$clave)) {
        header('location: ./formLogin.html'); 
        exit();
    }

    
    $_SESSION['identificativo'] = session_create_id();
    $_SESSION['usuario']=$usuario;
    $_SESSION['clave']=$clave;
    $visitas++;

    file_put_contents($nombreArchivo, $visitas);

}



echo "<h1>Acceso correcto</h1>";
    
echo "<p>Los parametros de inicio de sesion son los siguientes:</p>";

echo "<div class='datosinicio'><h2>Informacion de sesion</h2><span>Identificativo de sesion:</span> " . $_SESSION['identificativo'] . "<br />";
echo "<br /><span>Usuario ingresado:</span> " . $usuario . "<br />";   
echo "<br /><span>Contador de sesion: </span>" . $visitas . "<br /></div>";  
       
echo "<p><button id='continuar'>Continuar al Dashboard </button><p>";
       

function autenticacion($usuario, $clave) {

        $dbname="bqttuuyuglnwseuamusl";
        $host= "bqttuuyuglnwseuamusl-mysql.services.clever-cloud.com";
        $user ="udsm00rlr3hd3bgc";
        $password = "E08KdPPfSrIBOGHASgmO";

        $respuesta_estado = "";


        $clave= hash("sha256", $clave);
        //alert($clave);
        
    
        try {
            $dsn = "mysql:host=$host;dbname=$dbname";
            $dbh = new PDO($dsn, $user, $password); /*Database Handle*/
            $respuesta_estado = $respuesta_estado . "\nconexion exitosa";
        }
        catch (PDOException $e) {
            $respuesta_estado = $respuesta_estado . "\n" . $e->getMessage();
        }


         //consulta al servidor de los valores 
                    
        $sql ="SELECT * FROM usuarios";
        $stmt = $dbh->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
                    
         
        $usuarios=[];
        While($fila = $stmt->fetch()) {
                    $objUsuario = new stdClass();
                    $objUsuario->usuario=$fila['user'];
                    $objUsuario->clave=$fila['password'];
                    array_push($usuarios,$objUsuario);
        }

        $dbh = null;


        if($objUsuario->usuario == $usuario && $objUsuario->clave == $clave)
        {
            return true;
        }else
        {
            return false;
        }
                    
}

function incrementaContador(){

    static $contador = 0;

    $contador++;
    
    echo $contador;

    return $contador;
}
   

?>

<!DOCTYPE html>

<html lang="es">
<head>

    <meta charset="utf-8" />
   
    <script src="../jquery.js" type="text/javascript"></script>
       
    <title> Ingreso al sistema </title>

    <style>

        html, body {
            width: 100%;
            height: 100%;            
            align-items:center;
            background-color:lavender;
            font-family: sans-serif;

        }

        .datosinicio{
            width: 80%;
            height: auto;
            margin-left:5%;
            background-color:#2874A6;
            border:solid;
            border-color: #5DADE2;
            padding:20px;
            box-sizing:border-box;
            color: antiquewhite;

        }

        span{
            font-size: 1em;
            font-weight: bold;
        }

        /*button {
            margin-left:20%;
            font-size:24px;
            padding:10px;
        }*/

        button{
            font-size: 24px;
            margin-left: 30%;
            padding: 10px;
            cursor: pointer;
            border-radius: 15px;
            background-color: lightgrey;
            box-shadow: 0px 2px 0px black;
            border-width: thin;
            color: black;
            height: 10%;
            width: 30%;
        }
        button:hover{
            background-color: lightblue;
        }
        button:active{
            transform: scale(0.98);
        }

    </style>


</head>
<body>

<script>
    $("#continuar").click(function() {
        location.href="./index.php";
    });

</script>
</body>

</html>


