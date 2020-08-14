<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$arreglo 	= array();
$arreglo['success'] = true;
$arreglo = _validarform_alta($con);


if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{


$arreglo['success'] = true;
$parcod=$_POST["parcod"];

if($parcod == "4"){
$hora1 		= $_POST['dhora'];
$min1 		= $_POST['dminutos'];
$hora2 		= $_POST['hhora'];
$min2 		= $_POST['hminutos'];
$desde 		= $hora1.":".$min1;
$hasta 		= $hora2.":".$min2;
$horafin 	= $desde."-".$hasta;
$men		= "Se ha dado de alta con exito.";

if($desde < $hasta){
	$pardesc = convertircadenas($horafin);
	$arreglo['url'] = "parametrizacion/ABM_Franja_Horaria.php?ver=si&men=".$men."&donde=ABM Franja Horas";
} else {
	$arreglo['success'] = false;
	$arreglo['error'] = array(
		'mensaje' => "Error, la Hora desde ".$desde." - hasta ".$hasta
	);
}

} elseif($parcod == "1"){
	$men		= "Se ha dado de alta con exito.";
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($_POST["pardesc"]);
	$arreglo['url'] = "parametrizacion/ABM_Roles.php?ver=si&men=".$men."&donde=ABM Roles";

} elseif($parcod == "5"){

	$men		= "Se ha dado de alta con exito.";
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($_POST["pardesc"]);
	$arreglo['url'] = "parametrizacion/ABM_Categorias.php?ver=si&men=".$men."&donde=ABM Etiquetas";
} elseif($parcod == "7"){
	$men		= "Se ha dado de alta con exito.";
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($_POST["pardesc"]);
	$arreglo['url'] = "parametrizacion/ABM_Entidades.php?ver=si&men=".$men."&donde=ABM Entidades";
} elseif($parcod == "8"){
	$men		= "Se ha dado de alta con exito.";
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($_POST["pardesc"]);
	$arreglo['url'] = "parametrizacion/ABM_Expedientes.php?ver=si&men=".$men."&donde=ABM Entidades";
}

$query = "SELECT pardesc from fil00par where parcod = '$parcod' and pardesc='$pardesc'";
$resultmaxnum = mysqli_query($con,$query);
if($resultmaxnum = mysqli_query($con,$query)){
	if( $resultmaxnum->num_rows == 0){
		$que = mysqli_query($con,"SELECT MAX(parvalor)parvalor from fil00par where parcod = '$parcod'");
		if($que = mysqli_query($con,"SELECT MAX(parvalor)parvalor from fil00par where parcod = '$parcod'")){
			$result = mysqli_fetch_array($que);
			$maxnum = $result['parvalor'];
			$parvalor = $maxnum + 1;
			$query2 ="INSERT INTO `fil00par`(`parcod`, `parvalor`, `pardesc`) VALUES ('$parcod','$parvalor','$pardesc')";
			$resultinsert = mysqli_query($con,$query2);
			$text 	= "Error al insertar un nuevo valor. Linea 47";
			$text1 	= "Se produjo un error al modificar un nuevo valor.";
			resultInsert($con, $resultinsert, $text, $text1);
		} else {
			error_log("Error SQL02: ." .
	    	mysqli_errno($con) . " " . mysqli_error($con));
			$arreglo['success'] = false;
			$arreglo['error'] = array(
				'mensaje' => "Error en SQL02."
			);
		}
	} else {
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Ya existe, vuelva a intentar."
		);
	}
} else {
	error_log("Error SQL01: ." .
	mysqli_errno($con) . " " . mysqli_error($con));
	$arreglo['success'] = false;
	$arreglo['error'] = array(
		'mensaje' => "Error en SQL01."
	);
}
}
echo json_encode($arreglo);
?>