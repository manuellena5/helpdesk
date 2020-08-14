<?php
try {
	$con = mysqli_connect("localhost","manuel","m37774145","sistticket") or die("Error en la conexion");

mysqli_set_charset($con,"utf8mb4");

/* Comprueba la conexión */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    error_log("Fallo en la conexion------- %s\n", mysqli_connect_error());
    exit();
}


/* Cierra la conexión */

} catch (Exception $e) {
	var_dump($e);
}

?>