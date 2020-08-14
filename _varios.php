<?php
	
session_start();
include("../connect.php");
require_once("../funciones.php");
salir();
$arreglo = array();
$arreglo['success'] = true;
$arreglo = _validarform_ingresosvarios($con);

if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{


$arreglo['success']	= true;
$nroarchivos 			= $_POST['nroarchivos'];
$adj 					= "N";
$quienderi 				= $_POST['quienenvia'];//nrousuario de sesion
$destinatario			= "gyfseguros@gyfsoft.com";
$asunto 				= convertircadenas($_POST['titulo']);
$observacion 			= convertircadenas($_POST['observacion']);
$cuerpo 				= convertircadenas($_POST['mensaje']);
$tipo_ticket 			= "E";
$entidades 				= $_POST['entidades'];
$documento 				= $_POST['documento'];
$check 					= $_POST['check'];
$etiquetas 				= $_POST['etiquetas'];
$drol 					= $_POST['drol']!="" ? $_POST['drol'] : 0;
$dusuario 				= $_POST['dusuario']!="" ? $_POST['dusuario'] : 0;

if($_POST['secuetiquetas'] == ""){
	$secuetiquetas 	= "";
}else{
	$expo 			= substr($_POST['secuetiquetas'],0,-1);
	$secuetiquetas 	= explode(";",$expo);
}




$emisor = $_POST['email']!="" ? $_POST['email'] : "";


if (isset($_POST['NUMMESAENT']) && ($_POST['NUMMESAENT']) == "1"){
 $proceso = "NUMMESAENT";
 $NUMMESAENT = obtener_nuevo_numero_ticket($con,$proceso);
} else {
	$NUMMESAENT = 0;
}
$arreglo["data"] = $NUMMESAENT;
//Cargamos todo en fil01mail

//Se genera el Ticket
$proceso = "numticket";
$nro_ticket = obtener_nuevo_numero_ticket($con,$proceso); //nuevo ya generado

$fechaticket = date("y/m/d - H:i:s");
$nombreticket = formar_nombre_ticket($nro_ticket,$tipo_ticket);
$estado = "T";
$tipo_ingreso = "E";

/*PREGUNTAR SI TRAE EMAIL O NO*/
/*CAMBIAR ESTADO A D */

$resultInsert2 = mysqli_query($con, "INSERT INTO `fil01mail`
	(`emisor`,`destinatario`, `asunto`, `cuerpo`, `fecha`, `adjunto`, `tipo_ingreso`, `estado`, `nro_ticket`, `fecha_ticket`, `nombre_ticket`,`cod_entidad`,`categoria`) 
	VALUES 
	('$emisor','$destinatario','$asunto','$cuerpo','$fechaticket','N','P','$estado','$nro_ticket','$fechaticket','$nombreticket','$entidades','$etiquetas')");
if (!$resultInsert2) {
		error_log("Error al insertar en fil01mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
}

$id_mail = getNextId($con); 

//Ingresamos las etiquetas multiples
if($secuetiquetas != ""){
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





if ($nroarchivos > 0) {
	for ($i=0; $i < $nroarchivos; $i++) {
		if ($_FILES['archivo'.$i]['error'] !== UPLOAD_ERR_OK) { 
			$mensaje = errorSubidaArchivo(($_FILES['archivo'.$i]['error']));
			error_log("Error en la subida del archivo " .
	            		$mensaje);
			$arreglo["success"] = false;
			$arreglo["error"]=array('mensaje' => $mensaje );
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
    		exit;
		}elseif($_FILES["archivo".$i]["name"]) {
				$adj  		= "S";
				$nombre 	= $_FILES["archivo".$i]['name'];
        		$nombre 	= mb_strtolower($nombre, 'UTF-8');
        		$error 		= $_FILES["archivo".$i]['error'];
        		$numero		= nuevonumeroarchivo($con);
        		$name 		= $numero.'-'.$nombre;
        		$lugar 		= '../adjuntos/';
        		$destino 	= $lugar.$name;
        		$tipo 		= "S";

				//Movemos y validamos que el archivo se haya cargado correctamente
				//El primer campo es el origen y el segundo el destino
				if(move_uploaded_file($_FILES["archivo".$i]['tmp_name'],$destino)) {	
					$query1 = "INSERT INTO `fil01adj`
					(`ruta`, `id_mail`,`nombre`,`tipo`,`nro_ticket`,`tipo_ticket`,`nro_mesaent`) 
					VALUES 
					('$destino','$id_mail','$name','$tipo','$nro_ticket','$tipo_ingreso','$NUMMESAENT')";
					$resultInsert3 = mysqli_query($con,$query1);
					$text 	= "Error al insertar el adjunto. Linea 91";
					$text2 	= "Error al tratar de guardar el archivo adjunto";
					resultInsert($con, $resultInsert3, $text, $text2);
				} else {
					error_log("Error al intentar guardar el archivo." .$error);	
					$arreglo["success"] = false;
					$arreglo["error"]=array('mensaje' => "Error al tratar de guardar el archivo adjunto" );
					header('Content-type: application/json; charset=utf-8');
    				echo json_encode($arreglo, JSON_FORCE_OBJECT); 
    				exit;			
				}
			} //fin if de si existe el archivo
	}//FIN DEL FOR
}//FIN DEL IF SI HAY ARCHIVOS ADJUNTOS

$query = "INSERT INTO `fil03mail`
		(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`,`rolasig`, `fechaasig`,`estado`, `observacion`,`adjunto`,`tipo_ticket`,`nro_mesaent`,`ing_env`,`tipo_docum`) VALUES 
		($id_mail,'$nro_ticket','$quienderi','$dusuario','$drol','$fechaticket','D','$observacion','$adj','$tipo_ticket','$NUMMESAENT','$check','$documento')";
$resultInsert = mysqli_query($con,$query);
$text03 	= "Error al insertar en fil03mail. Linea 108";
$text032	= "Error al intentar guardar los datos.";
resultInsert($con, $resultInsert, $text03, $text032);

$query="UPDATE `fil01mail` SET `adjunto`='$adj' WHERE `id_mail`='$id_mail'";
$resultUpdate = mysqli_query($con,$query);
$text01		= "Error al actualizar el ticket. Linea 116";
$text012	= "Error al intentar guardar los datos.";
resultInsert($con, $resultUpdate, $text01, $text012);




}//fin del else del error por campo no ingresado



		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
		mysqli_close($con);

?>