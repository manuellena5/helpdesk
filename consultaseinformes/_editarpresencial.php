<?php
try {
session_start();
include("../connect.php");
require_once("../funciones.php");
salir();
$arreglo 				= array();
$arreglo["success"] 	= true;
$errores = _validarform_ingresosvarios_v2();

if (is_array($errores) && (count($errores)>0)) {
	$arreglo["success"] 	= false;
	foreach ($errores as $error) {
		$arreglo["error"]=array('mensaje' => $error);
        error_log ("Error en _editarpresencial.php " . $error);
     } 
}else{
$arreglo['url'] 		= "consultaseinformes/consultapresencial.php?ver=si&men=Se Edito con Exitos.&donde=Consulta Presencial";
$id_mail 				= $_POST['id_mail'];
$nro_ticket 			= $_POST['nro_ticket'];
$tipo_ticket 			= $_POST['tipo_ticket'];
$asunto 				= convertircadenas($_POST['titulo']);
$cuerpo 				= convertircadenas($_POST['mensaje']);
$entidades 				= $_POST['entidades'];
$documento 				= $_POST['documento'];
$etiquetas 				= $_POST['etiquetas'];
funcionetiqueta($etiquetas);

if($_POST['secuetiquetas'] == ""){
	$secuetiquetas 	= "";
}else{
	$expo 			= substr($_POST['secuetiquetas'],0,-1);
	$secuetiquetas 	= explode(";",$expo);
}

/*Mail Original*/
$resultInsert2 = mysqli_query($con, "UPDATE `fil01mail` SET 
	`asunto`='$asunto',`cuerpo`='$cuerpo',`categoria`='$etiquetas',`cod_entidad`='$entidades' 
	WHERE nro_ticket='$nro_ticket' and id_mail='$id_mail'");
if (!$resultInsert2) {
		error_log("Error al insertar en fil01mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
}

/*Editar en Fil03Mail todos*/
$f03edit = mysqli_query($con, "UPDATE `fil03mail` SET `tipo_docum`='$documento' WHERE `nro_ticket`='$nro_ticket' AND `tipo_ticket`='$tipo_ticket'");
if (!$f03edit) {
		error_log("Error al insertar en fil03mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
}


//Ingresamos las etiquetas multiples
if($secuetiquetas != ""){
	$ssql = mysqli_query($con, "DELETE FROM `fil03tieti` WHERE `nro_ticket`='$nro_ticket' and `tipo_ticket`='$tipo_ticket'");
	for ($i=0; $i <count($secuetiquetas) ; $i++) {
			$nro_categ = $secuetiquetas[$i];
			$quer = "INSERT INTO `fil03tieti`
			(`nro_ticket`, `tipo_ticket`, `nro_categ`) 
			VALUES 
			('$nro_ticket','$tipo_ticket','$nro_categ')";
			$resultInse 	= mysqli_query($con,$quer);
			$textsecu 		= "Error al insertar en fil03tieti. Linea 104";
			$textsecu2 		= "Error al intentar guardar los datos.";
			resultInsert($con, $resultInse, $textsecu, $textsecu2);
	}
}

//f03 -> $adj,$observacion,$documento

$query = "UPDATE `fil03mail` SET 
 `usuarioasig`='$dusuario',`rolasig`='$drol',`observacion`='$observacion',`tipo_docum`='$documento'
  WHERE id='$id'";
$resultInsert = mysqli_query($con,$query);
$text03 	= "Error al insertar en fil03mail. Linea 108";
$text032	= "Error al intentar guardar los datos.";
resultInsert($con, $resultInsert, $text03, $text032);
}//fin del else del error por campo no ingresado

}catch(RuntimeException $ex2){
	$arreglo["error"]=array('mensaje' => "Ha ocurrido un error inesperado" );
	error_log("Error 1: ".$ex2);
}catch (Exception $e) {
	error_log("Error 2: ".$e);

	$arreglo["error"]=array('mensaje' => "Ha ocurrido un error inesperado" );
	
}finally{
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
		mysqli_close($con);
}
?>