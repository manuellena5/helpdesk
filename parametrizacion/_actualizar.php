<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$arreglo 	= array();
$arreglo['success'] = true;
$parcod 	= $_POST["parcod"];
$parvalor 	= $_POST["parvalor"];
$men 		= "Se realizo la modificacion con exito.";

if($parcod == "4"){
$hora1 		= $_POST['dhora'];
$min1 		= $_POST['dminutos'];
$hora2 		= $_POST['hhora'];
$min2 		= $_POST['hminutos'];
$desde 		= $hora1.":".$min1;
$hasta 		= $hora2.":".$min2;
$horafin 	= $desde."-".$hasta;

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
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($pardesc);
	$arreglo['url'] = "parametrizacion/ABM_Roles.php?ver=si&men=".$men."&donde=ABM Roles";

} elseif($parcod == "5"){
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($pardesc);
	$arreglo['url'] = "parametrizacion/ABM_Categorias.php?ver=si&men=".$men."&donde=ABM Etiquetas";
} elseif($parcod == "7"){
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($pardesc);
	$arreglo['url'] = "parametrizacion/ABM_Entidades.php?ver=si&men=".$men."&donde=ABM Entidades";
} elseif($parcod == "8"){
	$pardesc = convertircadenas($_POST["pardesc"]);
	$pardesc = mb_strtoupper($pardesc);
	$arreglo['url'] = "parametrizacion/ABM_Expedientes.php?ver=si&men=".$men."&donde=ABM Entidades";
}

$query ="SELECT * FROM fil00par where parcod = '$parcod' and pardesc = '$pardesc'";
$sql = mysqli_query($con,$query);
if ($sql) {
	if (mysqli_num_rows($sql) == 0) {
		$query2= "UPDATE `fil00par` SET `pardesc`='$pardesc' WHERE parcod = '$parcod' and parvalor = '$parvalor'";
			$resultupdate = mysqli_query($con,$query2);
			$text 	= "Error al modificar un nuevo valor. Linea 48";
			$text1 	= "Se produjo un error al modificar un nuevo valor.";
			resultInsert($con, $resultupdate, $text, $text1);
	}/*else{
			/*error_log("No se pudo registrar por duplicado");
			$arreglo['success'] = false;
			$arreglo['error'] = array(
				'mensaje' => "Ya existe un registro con esa descripcion."
				);*/
		//}
}else{
	error_log("error al consultar si existe un duplicado." .
	    mysqli_errno($con) . " " . mysqli_error($con));
	$arreglo['success'] = false;
	$arreglo['error'] = array(
		'mensaje' => "Se produjo un error, vuelva a interntarlo."
	);
}
echo json_encode($arreglo);
?>