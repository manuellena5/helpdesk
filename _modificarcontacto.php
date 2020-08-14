<?php 
session_start();
include("connect.php");
include("funciones.php");
salir();
$arreglo = array();
$arreglo['success'] = true;

$mail 			= $_POST['mail'];
$nombre 		= $_POST['nombre']; 
$email 			= $_POST['email']; 
if(!validar_email($email)){
	$arreglo['success'] = false;
	$arreglo['error'] = array(
		'mensaje' => "Mail no Valido"
		);
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($arreglo, JSON_FORCE_OBJECT);
	exit;
}
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

$re 			= mysqli_query($con, "SELECT * FROM `fil01lib` WHERE `mail`='".$_POST['email']."'");
$num 			= $re->num_rows;
if($num == 1){
	$sql 			= mysqli_query($con, "UPDATE `fil01lib` SET `nombre`='$nombre',`mail`='$email',`telef_movil`='$telef_movil',`telef_fijo`='$telef_fijo' WHERE `mail`='$mail'");
	$arreglo['url'] = "contactos.php?ver=si&men=Se modifico el Contacto con Exito.&donde=Contactos";
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