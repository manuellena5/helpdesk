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
	$codigo 			= $_POST['codigo'];
	$razon 				= mb_strtoupper($_POST['razon']);
	$domicilio 			= mb_strtoupper($_POST['domicilio']);
	$localidad 			= mb_strtoupper($_POST['localidad']);
	$cuit 				= $_POST['cuit'];
	$telefono 			= $_POST['telefono'];
	$mail 				= $_POST['mail'];


	$query = "SELECT * FROM `fil01ent` WHERE `codigo`='$codigo'";
	$resultmaxnum = mysqli_query($con,$query);
	if( $resultmaxnum->num_rows == 1 ){
		$arreglo['url'] = "parametrizacion/ABM_Entidades.php?ver=si&men=Se edito con Exito.&donde=ABM Entidades";
		$query2 ="UPDATE `fil01ent` SET 
		`razon`='$razon',`domicilio`='$domicilio',`localidad`='$localidad',`cuit`='$cuit',`telefono`='$telefono',`mail_contacto`='$mail' WHERE
		 `codigo`='$codigo'";
		$resultinsert = mysqli_query($con,$query2);
		$text 	= "Error al insertar un nuevo valor. Linea 21";
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