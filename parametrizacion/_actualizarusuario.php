<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$nrousuario = $_POST['nrousuario'];
$usuario 	= $_POST['usuario'];
$pass 		= $_POST['pass'];
$pass2 		= $_POST['pass2'];
$nombre 	= $_POST['nombre'];
$grupo 		= $_POST['grupo'];
$roles 		= $_POST['roles'];
$dia 		= $_POST['dia'];
$hora 		= $_POST['hora'];
$firma 		= preg_replace("/\n/","<br>",$_POST['firma']);
$arreglo 	= array();
$arreglo 	= _validarform_alta($con);


if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		
		$mensajeerror .= $error . "\n";
		
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{



	$query = "SELECT usuario from fil01seg where usuario = '$usuario'";
	$resultmaxnum = mysqli_query($con,$query);
		if( $resultmaxnum->num_rows == 1 ){
			$arreglo["success"] 	= true;
			$arreglo['url'] = "parametrizacion/ABM_Usuarios.php?ver=si&men=Se edito con Exito.&donde=ABM Usuario";
			$usuario = mb_strtoupper($usuario);
			$query2 ="UPDATE `fil01seg` SET 
				`usuario`='$usuario',`nombre`='$nombre',`password`='$pass',`grupo`='$grupo',`rol`='$roles',`franjadia`='$dia',`franjahora`='$hora', `firma`='$firma'  
				WHERE nrousuario='$nrousuario'";
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