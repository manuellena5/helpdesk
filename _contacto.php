<?php 
session_start();
include("connect.php");
include("funciones.php");
salir();
$arreglo = array();
$arreglo['success'] = true;


$nombre 		= $_POST['nombre']; 
$email 			= $_POST['email']; 
$titulo 		= $_POST['titulo'];
$empresa 		= $_POST['empresa'];
$profesion 		= $_POST['profesion'];

if($_POST['telef_fijo_tel'] == ""){
	$telef_fijo 	= "";
} else{
	$telef_fijo 	= "0".$_POST['telef_fijo_cod_area']."-".$_POST['telef_fijo_tel'];
}
if($_POST['telef_movil_tel'] == ""){
	$telef_movil 	= "";
}else{
	$telef_movil 	= "0".$_POST['telef_movil_cod_area']."-15".$_POST['telef_movil_tel'];
}
$arreglo 		= _validarform_contacto($nombre,$email);
$re 			= mysqli_query($con, "SELECT * FROM `fil01lib` WHERE `mail`='".$_POST['email']."'");
$num 			= $re->num_rows;
if($num == 0){
	$sql 			= mysqli_query($con, "INSERT INTO `fil01lib`
		(`nombre`, `mail`, `telef_movil`, `telef_fijo`,`titulo`,`empresa`,`profesion`)
		VALUES 
		('$nombre','$email','$telef_movil','$telef_fijo','$titulo','$empresa','$profesion')");
	
	$arreglo['url'] = "contactos.php?ver=si&men=Se a dado de alta con exito.&donde=Contactos";
	$text 			= "Error al insertar un nuevo valor. Linea 12";
	$text1 			= "Se produjo un error al modificar un nuevo valor.";
	resultInsert($con, $sql, $text, $text1);
} else{
	$arreglo['success'] = false;
	$arreglo['error'] = array(
		'mensaje' => "El mail ya Existe."
	);
}

mysqli_close($con);
echo json_encode($arreglo);
?>