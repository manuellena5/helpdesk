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
$razon 				= $_POST['razon'];
$domicilio 			= $_POST['domicilio'];
$localidad 			= $_POST['localidad'];
$cuit 				= $_POST['cuit'];
$telefono 			= $_POST['telefono'];
$mail 				= $_POST['mail'];
$rubro 				= $_POST['rubro'];

	$men = "Se ha dado de alta con exito.";
	$arreglo['url'] = "parametrizacion/ABM_Entidades.php?ver=si&men=".$men."&donde=ABM Entidades";
	$query = "SELECT * FROM `fil01ent` WHERE `razon`='$razon' and cuit='$cuit' and rubro='$rubro'";
	$resultmaxnum = mysqli_query($con,$query);
		if( $resultmaxnum->num_rows == 0 ){
				$query2 ="INSERT INTO `fil01ent`
				(`razon`, `domicilio`, `localidad`, `cuit`, `telefono`, `mail_contacto`,`rubro`) 
				VALUES 
				('$razon','$domicilio','$localidad','$cuit','$telefono','$mail','$rubro')";
				$resultinsert = mysqli_query($con,$query2);
				$text 	= "Error al insertar un nuevo valor. Linea 25";
				$text1 	= "Error al insertar un nuevo valor.";
				resultInsert($con, $resultinsert, $text, $text1);				
		} else {
			$arreglo['success'] = false;
		    $arreglo['error'] = array(
		    	'mensaje' => "Ya Existe"
			);	
		}
}
echo json_encode($arreglo);
?>