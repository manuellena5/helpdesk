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
$usuario 	= strtoupper($_POST['usuario']);
$pass 		= $_POST['pass'];
$pass2 		= $_POST['pass2'];
$nombre 	= $_POST['nombre'];
$grupo 		= $_POST['grupo']!="" ? $_POST['grupo'] : 1;
//$grupo 		= $_POST['grupo'];
$roles 		= $_POST['roles'];
$dia 		= $_POST['dia'];
$hora 		= $_POST['hora'];
$firma		= $_POST['firma'];


//$arreglo = campos($usuario, $pass, $pass2, $nombre, $grupo, $roles, $dia, $hora, $firma);

	//if($arreglo['success']){
		
		$query = "SELECT usuario from fil01seg where usuario = '$usuario'";
		$resultmaxnum = mysqli_query($con,$query);
			if( $resultmaxnum->num_rows == 0 ){
				if($pass == $pass2){
					$query2 ="INSERT INTO `fil01seg`
					(`usuario`, `nombre`, `password`, `grupo`, `rol`, `franjadia`, `franjahora`,`firma`) 
					VALUES 
					('$usuario','$nombre','$pass','$grupo','$roles','$dia','$hora','$firma')";
					$resultinsert = mysqli_query($con,$query2);
					$text 	= "Error al insertar un nuevo valor. Linea 25";
					$text1 	= "Error al insertar un nuevo valor.";
					resultInsert($con, $resultinsert, $text, $text1);	

					$men = "Se ha dado de alta con exito.";
					$arreglo['url'] = "parametrizacion/ABM_Usuarios.php?ver=si&men=".$men."&donde=ABM Usuarios";


				} else {
					$arreglo['success'] = false;
			    	$arreglo['error'] = array(
			    		'mensaje' => "Error en las Contraseñas."
					);
				}
			} else {
				$arreglo['success'] = false;
			    $arreglo['error'] = array(
			    	'mensaje' => "Ya Existe"
				);	
			}
	//}
}
echo json_encode($arreglo);
?>