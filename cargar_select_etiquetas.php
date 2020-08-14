<?php
session_start(); 
include("connect.php");
include("funciones.php");
salir();

$valor = $_POST['valor'];
$arreglo = array();
$arreglo["success"] = true;
$arreglo["cantidad"] = 0;
$query = "SELECT parvalor,pardesc FROM fil00par WHERE parcod=5 and parvalor!= '$valor' ORDER BY pardesc asc";

if ($sql = mysqli_query($con,$query)) {
	if ($sql->num_rows > 0) {
		while ($res = mysqli_fetch_array($sql)) {
			$arreglo[] = $res;

		}
			$arreglo["cantidad"] = $sql->num_rows;
	}else{
		error_log("No trajo resultados fil00par");
		$arreglo["success"] = false;
	}
}else{
		error_log("Error al buscar en la base de fil00par" .
			mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
}


header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo);
exit;











 ?>