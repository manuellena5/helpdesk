<?php
session_start();
include("connect.php");
include("funciones.php");
salir();


$arreglo['success'] = true;
$arreglo = _validarform_interno($con);


if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{


$arreglo['success']	= true;
//Enviado por el formulario de Interno.php
$quienenvia 		= $_SESSION["nrousuario"];
$asunto				= $_POST['titulo'];
$cuerpo 			= $_POST['mensaje'];
$observacion 		= $_POST['observacion'];
$drol 				= $_POST['drol']!="" ? $_POST['drol'] : 0;
$dusuario 			= $_POST['dusuario']!="" ? $_POST['dusuario'] : 0;
$nroarchivos		= $_POST['nroarchivos'];
$etiquetas 			= $_POST['etiquetas'];
$expediente 		= $_POST['expediente'];


//Esto es Para fil01mail
$emisor				= "Interno";
$fecha 				= date("y/m/d - H:i:s");
$adj 				= "N";
$tipo_ticket		= "I";
$estado				= "T";
$proceso			= "NUMINTERNO";

//Esto es para fil03mail
$eestado 			= "D";





if($_POST['secuetiquetas'] == ""){
	$secuetiquetas 		= "";
} else {
	$expo 				= substr($_POST['secuetiquetas'],0,-1);
	$secuetiquetas 		= explode(";",$expo);
}

		//obtenemos el numero de Ticket y el nombre

		$nro_ticket = obtener_nuevo_numero_ticket($con, $proceso);
		$nombre_ticket = formar_nombre_ticket($nro_ticket, $tipo_ticket);

		//Insertamos los datos en Fil01Mail
		$query = "INSERT INTO `fil01mail`
		(`emisor`, `asunto`, `cuerpo`, `fecha`, `tipo_ingreso`, `estado`, `nro_ticket`, `fecha_ticket`, `nombre_ticket`,`categoria`,`tipo_ticket`,`expediente`) 
		VALUES 
		('$emisor','$asunto','$cuerpo','$fecha','$tipo_ticket','$estado','$nro_ticket','$fecha','$nombre_ticket','$etiquetas','$tipo_ticket','$expediente')";
		$resultInsert = mysqli_query($con,$query);
		$id_mail = mysqli_insert_id($con);

		//Error en Fil01mail
		$text 	= "Error al insertar en fil01mail. Linea 62";
		$text2 	= "Error en insertar en interno O1.";
		resultInsert($con, $resultInsert, $text, $text2);
		$id_mail = mysqli_insert_id($con);

		//Insertamos los datos en Fil03Mail
		$query2 = "INSERT INTO `fil03mail`
		(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`, `rolasig`, `fechaasig`, `observacion`, `estado`, `tipo_ticket`) 
		VALUES 
		('$id_mail','$nro_ticket','$quienenvia','$dusuario','$drol','$fecha','$observacion','$eestado','$tipo_ticket')";
		$resultInsert2 = mysqli_query($con,$query2);

		//Error en Fil03mail
		$text03 	= "Error al insertar en fil03mail. Linea 76";
		$text032 	= "Error en insertar en interno 02";
		resultInsert($con, $resultInsert2, $text03, $text032);
		$id = getNextId($con);
		//Ingreso de todas las Categorias
		$tipo = "I";

		if($secuetiquetas != ""){
			for ($i=0; $i <count($secuetiquetas) ; $i++) { 
				$nro_categ = $secuetiquetas[$i];
				$quer = "INSERT INTO `fil03tieti`
				(`nro_ticket`, `tipo_ticket`, `nro_categ`) 
				VALUES 
				('$nro_ticket','$tipo','$nro_categ')";

				$resultInse = mysqli_query($con,$quer);
				$textsecu = "Error al insertar en fil03tieti. Linea 105";
				$textsecu2 = "Error al intentar guardar los datos.";

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
        		$lugar 		= 'adjuntos/';
        		$destino 	= $lugar.$name;
        		$tipo 		= "S";

				//Movemos y validamos que el archivo se haya cargado correctamente
				//El primer campo es el origen y el segundo el destino
				if(move_uploaded_file($_FILES["archivo".$i]['tmp_name'],$destino)) {	

				$query1 = "INSERT INTO `fil01adj`(`ruta`, `id_mail`,`nombre`,`tipo`,`nro_ticket`,`tipo_ticket`) VALUES ('$destino','$id_mail','$name','$tipo','$nro_ticket','$tipo_ticket')";
				
				$resultInsert3 = mysqli_query($con,$query1);

				$textadj 	= "Error al insertar el adjunto. Linea 139";
				$textadj2 	= "Error al tratar de guardar el archivo adjunto.";
				resultInsert($con, $resultInsert3, $textadj, $textadj2);

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

	$query="UPDATE `fil01mail` SET `adjunto`='$adj' WHERE `id_mail`='$id_mail'";
	$resultUpdate = mysqli_query($con,$query);

	$text01 	= "Error al actualizar el ticket fil01. Linea 167";
	$text012 	= "Error al intentar guardar los datos.";
	resultInsert($con, $resultUpdate, $text01, $text012);

	$query="UPDATE `fil03mail` SET `adjunto`='$adj' WHERE `id`='$id'";
	$resultUpdate = mysqli_query($con,$query);

if (!$resultUpdate) {

	error_log("Error al actualizar el ticket fil03 ." .
            mysqli_errno($con) . " " . mysqli_error($con));
	$arreglo["success"] = false;
	$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
	header('Content-type: application/json; charset=utf-8');
    echo json_encode($arreglo, JSON_FORCE_OBJECT); 
    exit;	


	}

	
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT); 
mysqli_close($con);

?>