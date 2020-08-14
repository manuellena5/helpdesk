<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
//arreglo de Acceso, si todo sale bien en true.
$arreglo 			= array();
$arreglo["success"] = true;

/*** VALORES DEL FORMULARIO ***/
$nroarchivos 		= $_POST['nroarchivos'];
$ddrol 				= $_POST['drol'];
$ddusuario 			= $_POST['dusuario'];
$numail 			= $_POST['numail'];
$observacion 		= $_POST['observacion'];
$deriuser 			= $_POST['user'];
$envio 				= $_POST['envio'];
$ingreso 			= $_POST['ingreso'];
$NUMMESAENT 		= $_POST['NUMMESAENT'];
$documento 			= $_POST['documento'];
$expediente 		= $_POST['expediente'];
$asunto 			= $_POST['asunto'];

/**VALIDACIONES**/

$arreglo 			= derivar($ddrol , $ddusuario);
if($_POST['nombreticket'] == ""){
	$etiquetas 		= $_POST['etiquetas'];
	funcionetiqueta($etiquetas);
}

if($_POST['secuetiquetas'] == ""){
	$secuetiquetas 	= "";
}else{
	$expo 			= substr($_POST['secuetiquetas'],0,-1);
	$secuetiquetas 	= explode(";",$expo);
}


/** Variables pre determinadas Globales**/
$adj 				= "N"; 			//Usado en lineas 57,62,117
$proceso 			= "numticket"; 	//Usado en lineas 83
$tipo_ticket 		= "E"; 			//Usado en lineas 62,85,117
$estado 			= "D";			//Usado en lineas 57,62,117
$derivado 			= "T";			//Usado en lineas 87

if($arreglo['success']){
	$drol 			= $arreglo['drol'];
	$dusuario		= $arreglo['dusuario'];
	$query = "SELECT * FROM fil01mail WHERE id_mail='$numail'";
		
	if($sql = mysqli_query($con, $query)){
		if ($sql->num_rows > 0) {

			$ra = mysqli_fetch_array($sql);
			$ticketnum = $ra['nro_ticket'];
			$ticketfecha = date("y/m/d - H:i:s");
			if($ra['estado'] == "T"){

				/* Si esta Generado el Ticket reguistra todo */ 
				if ($ra["tipo_ingreso"] == "I") {
					$query2 = "INSERT INTO `fil03mail`
					(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`,`rolasig`, `fechaasig`,`observacion`,`estado`,`tipo_ticket`,`adjunto`) 
					VALUES 
					($numail,$ticketnum,'$deriuser','$dusuario','$drol','$ticketfecha','$observacion','$estado','I','$adj')";
				}else{
					$secc = mysqli_query($con, "SELECT ing_env, nro_mesaent,tipo_docum FROM fil03mail where id='".$_POST['idticket']."'");
					$re5 = mysqli_fetch_array($secc);
					if($ingreso == ""){
						$ing_env = $re5['ing_env'];
					} else {
						$ing_env = $ingreso;
					}
					if($envio == ""){
						$ing_env = $re5['ing_env'];
					} else {
						$ing_env = $envio;
					}
					if($NUMMESAENT == "0"){
						$NUMMESAENT = $re5['nro_mesaent'];
					}else {
						$NUMMESAENT = obtener_nuevo_numero_ticket($con,"NUMMESAENT");
					}
					if($documento == "0"){
						$documento = $re5['tipo_docum'];
					}
					$query2 = "INSERT INTO `fil03mail`
					(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`,`rolasig`, `fechaasig`,`observacion`,`estado`,`tipo_ticket`,`adjunto`,`ing_env`,`nro_mesaent`,`tipo_docum`) 
					VALUES 
					($numail,$ticketnum,'$deriuser','$dusuario','$drol','$ticketfecha','$observacion','$estado','$tipo_ticket','$adj','$ing_env','$NUMMESAENT','$documento')";
				}
				$resultInsert 	= mysqli_query($con, $query2);
				$id = getNextId($con);
				$text 			= "Error al insertar en fil03mail. Linea 54 y 59";
				$text2 			= "Error al intentar guardar los datos.";
				resultInsert($con, $resultInsert, $text, $text2);
				$sigticket 		= $ra['nro_ticket'];
				$idticket 		= $_POST["idticket"];
				$query0 		= "UPDATE fil03mail set leido = 1 where id='$idticket'";
				$resultUpdate 	= mysqli_query($con,$query0);
				$msjconsola 	= "Error al actualizar en fil03mail como leido. Linea 70";
				$msjusuario 	= "Error al intentar guardar los datos.";
				resultInsert($con,$resultUpdate,$msjconsola,$msjusuario);
				
			} else {
				/*NO ESTA GENERADO EL TICKET, VIENE DE MESA DE ENTRADA
				SON MAILS EXTERNOS QUE LLEGAN POR PRIMERA VEZ*/
				/*GENERAMOS EL TICKET NUEVO */
				/*ACTUALIZAMOS FIL01*/
				/*INSERTAMOS FIL03*/
				/*ACTUALIZAMOS FIL01ADJ*/

				//Ingreso de todas las Categorias
				if($_POST['tipo'] == "I"){
					$tipo = "I";
				} else {
					$tipo = "E";
				}

				$sigticket = obtener_nuevo_numero_ticket($con,$proceso);
				$fechaticket = date("y/m/d - H:i");
				$nombreticket = formar_nombre_ticket($sigticket,$tipo_ticket);
				$query3 = "UPDATE fil01mail SET 
				estado='$derivado', nro_ticket='$sigticket', fecha_ticket='$fechaticket', nombre_ticket='$nombreticket', categoria='$etiquetas',tipo_ticket='$tipo',expediente='$expediente',asunto='$asunto'
				WHERE 
				id_mail=$numail";
				$resultUpdate 	= mysqli_query($con, $query3);
				$textupdate 	= "Error al actualizar en fil01mail. Linea 86";
				$textupdate2 	= "Error al intentar guardar los datos.";
				resultInsert($con, $resultUpdate, $textupdate, $textupdate2);

				
				$insadj = mysqli_query($con , "UPDATE `fil01adj` SET `nro_ticket`='$sigticket',`tipo_ticket`='$tipo' WHERE `id_mail`='$numail'");
				$texadj = "Error al actualizar en fil01adj. Linea 104";
				$texadj2 =	"Error al actualizar los datos";
				resultInsert($con, $insadj, $texadj, $texadj2);
				if($secuetiquetas != ""){
					for ($i=0; $i <count($secuetiquetas) ; $i++) { 
						$nro_categ = $secuetiquetas[$i];
						$quer = "INSERT INTO `fil03tieti`
						(`nro_ticket`, `tipo_ticket`, `nro_categ`) 
						VALUES 
						('$sigticket','$tipo','$nro_categ')";
						$resultInse 	= mysqli_query($con,$quer);
						$textsecu 		= "Error al insertar en fil03tieti. Linea 104";
						$textsecu2 		= "Error al intentar guardar los datos.";
						resultInsert($con, $resultInse, $textsecu, $textsecu2);
					}
				}
				$query4 = "INSERT INTO `fil03mail`
				(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`,`rolasig`, `fechaasig`,`observacion`,`estado`,`tipo_ticket`,`adjunto`) 
				VALUES 
				($numail,$sigticket,'$deriuser','$dusuario','$drol','$fechaticket','$observacion','$estado','$tipo_ticket','$adj')";
				$resultInsert2 	= mysqli_query($con,$query4);
				$id = getNextId($con);
				$text03 		= "Error al insertar en fil03mail. Linea 114";
				$text032 		= "Error al intentar guardar los datos.";
				resultInsert($con, $resultInsert2, $text03, $text032);
				
			}	

			/*GUARDAR LOS ADJUNTOS*/
			if ($nroarchivos > 0) {
				for ($i=0; $i < $nroarchivos; $i++) {	
					if($_FILES['archivo'.$i]['error'] !== UPLOAD_ERR_OK){ 
						$mensaje = errorSubidaArchivo(($_FILES['archivo'.$i]['error']));
						error_log("Error en la subida del archivo. Linea 127" .
				            		$mensaje);
						$arreglo["success"] = false;
						$arreglo["error"]=array('mensaje' => $mensaje );
						header('Content-type: application/json; charset=utf-8');
			    		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
			    		exit;
					} elseif($_FILES["archivo".$i]["name"]){
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
							$query1 = "INSERT INTO `fil01adj`
							(`ruta`, `id_mail`,`nombre`,`tipo`,`nro_ticket`,`nro_mesaent`,`tipo_ticket`) 
							VALUES 
							('$destino','$numail','$name','$tipo','$sigticket','$NUMMESAENT','$tipo_ticket')";
							$resultInsert3 	= mysqli_query($con,$query1);
							$textadj 		= "Error al insertar el adjunto. Linea 150";
							$textadj2 		= "Error al tratar de guardar el archivo adjunto.";
							resultInsert($con, $resultInsert3, $textadj, $textadj2);
						} else {	
							error_log("Error al intentar guardar el archivo. Linea 150" .$error);	
							$arreglo["success"] = false;
							$arreglo["error"]=array('mensaje' => "Error al tratar de guardar el archivo adjunto." );
							header('Content-type: application/json; charset=utf-8');
			    			echo json_encode($arreglo, JSON_FORCE_OBJECT); 
			    			exit;			
						}

					} //fin if de si existe el archivo

				}//FIN DEL FOR
				$query4 		= "UPDATE `fil03mail` SET `adjunto`='$adj' WHERE `id`='$id'";
				$resultUpdate 	= mysqli_query($con,$query4);
				$textfil 		= "Error al actualizar fil03mail con adjunto. Linea 170";
				$textfil2 		= "Error al guardar los datos";
				resultInsert($con, $resultUpdate, $textfil, $textfil2);
			}//FIN DEL IF SI HAY ARCHIVOS ADJUNTOS*/
		} else {
			error_log("No trajo resultados la consulta fil01mail ." .
		         mysqli_errno($con) . " " . mysqli_error($con));
			$arreglo["success"] = false;
			$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
		}
	} else {
		error_log("Error al realizar consulta fil01mail ." .
		     mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al intentar guardar los datos" );
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	}
} //Fin del Success

header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT); 
mysqli_close($con);
?>
