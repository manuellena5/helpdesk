<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
//arreglo de Acceso, si todo sale bien en true.
$arreglo 			= array();
$arreglo["success"] = true;

/*** VALORES DEL FORMULARIO ***/
$usuario 		= $_SESSION["nrousuario"];
$cuerpo 		= $_POST['cuerpo'];
$titulo 		= $_POST['titulo'];
$dusuario 		= $_POST['dusuario'];
$drol 			= $_POST['drol'];

/**VALIDACIONES**/
$arreglo 			= derivar($drol , $dusuario);

if($arreglo['success']){
	$sql = mysqli_query($con, "INSERT INTO `fil01notif`
		(`quiengenera`, `titulo`, `mensaje`) 
		VALUES 
		('$usuario','$titulo','$cuerpo')");
	$id = getNextId($con);
	$text 			= "Error al insertar en fil01notif. Linea 21";
	$text2 			= "Error 01 al intentar guardar los datos.";
	resultInsert($con, $sql, $text, $text2);
	if($dusuario == 0){
		//inser de un Rol
		$busca = mysqli_query($con, "SELECT `nrousuario` FROM `fil01seg` WHERE `rol`='$drol'");
		//$num 		= $busca->num_rows;
		while ($re= mysqli_fetch_array($busca)) {
			$inser = mysqli_query($con, "INSERT INTO `fil03notif`
			(`numero`, `quienlee`, `estado`) 
			VALUES 
			('$id','".$re['nrousuario']."','0')");
		}
	}else{
		//Inser de un usuario
		$inser = mysqli_query($con, "INSERT INTO `fil03notif`
			(`numero`, `quienlee`, `estado`) 
			VALUES 
			('$id','$dusuario','0')");
	}
	$te 			= "Error al insertar en fil03notif.";
	$te2 			= "Error 02 al intentar guardar los datos";
	resultInsert($con, $inser, $te, $te2);
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT); 
mysqli_close($con);
?>