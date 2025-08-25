<?php
include('config/conexionDB.php');

$conexiondb = mysqli_connect("localhost:3307", "javier", "MyN3wP4ssw0rd", "green_valley_db");
if(mysqli_connect_errno()){  
echo mysqli_connect_error();  
}
$result= mysqli_query($conexiondb, "SELECT * FROM green_valley_db.cliente"); 
while ($row = mysqli_fetch_array($result)) {

echo $row["rut"]." ".$row["nombre"];
$correo= $row["correo"];
echo '<br />';

}

mysqli_free_result($result);
mysqli_close($conexiondb);
echo $correo;
/*$conexiondb = new conexionDB($_SESSION['tipoBD'], $_SESSION['hostBD'], $_SESSION['usuarioBD'], $_SESSION['passwordBD'], $_SESSION['nombreBD'], $_SESSION['puertoBD']);
    $conexiondb->conectaDb();
    $query = "SELECT * FROM green_valley_db.cliente";
    $conexiondb->setQuery($query);
    $conexiondb->sacaDatos();
    $conexiondb->ejecuta();
    $row = $conexiondb->datos();
    if ($conexiondb->conDatos()) {
        foreach ($row as $campos) {
            print $campos;
        }
    }
    $conexiondb->cerrar();
       */     
?>