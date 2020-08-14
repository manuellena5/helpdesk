<?php 

// Este ejemplo muestra la configuración para usar cuando se envía a través de los servidores Gmail de Google.
	// SMTP necesita tiempos precisos, y la zona horaria de PHP DEBE establecerse
	// Esto debería hacerse en tu php.ini, pero así es como hacerlo si no tienes acceso a eso
	//date_default_timezone_set('Etc/UTC');

	require 'PHPMailer/PHPMailerAutoload.php';

	// Crear una nueva instancia de PHPMailer
	$mail = new PHPMailer;

	$mail->SMTPOptions = array( 'ssl' => array( 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true ) );

	// Dile a PHPMailer que use SMTP
	$mail->isSMTP();

	// Habilitar depuración SMTP
	// 0 = apagado (para uso de producción)
	// 1 = mensajes del cliente
	// 2 = mensajes de cliente y servidor
	//$mail->SMTPDebug = 2;

	// Pide una salida de depuración compatible con HTML
	$mail->Debugoutput = 'html';

	// Establecer el nombre de host del servidor de correo
	//$mail->Host = 'smtp.gyfsoft.com'; -- $mail->Host = 'smtp.gmail.com';
	$mail->Host = gethostbyname('ftp.colegioing.com');

	// si su red no es compatible con SMTP sobre IPv6
	// Establezca el número de puerto SMTP - 587 para TLS autenticado, a.k.a. RFC4409 envío SMTP
	$mail->Port = 587;

	// Configurar el sistema de encriptación para usar - ssl (en desuso) o tls
	//$mail->SMTPSecure = 'tls';

	// Si usar la autenticación SMTP
	$mail->SMTPAuth = true;

	// Nombre de usuario que se usará para la autenticación SMTP: use la dirección de correo electrónico completa para gmail
	//$mail->Username = "cierosario2164@gmail.com";
	$mail->Username = "cie@cie.gov.ar";

	// Contraseña para usar para la autenticación SMTP
	//$mail->Password = "ciesl2164";
	$mail->Password = "Tlfaluni73";

	// Establecer de quién será enviado el mensaje
	//$mail->setFrom('cierosario2164@gmail.com');
	$mail->setFrom('cie@cie.gov.ar','cie@cie.gov.ar');

	// Establecer una dirección de respuesta alternativa
	//$mail->addReplyTo('replyto@example.com', 'First Last');
	if (isset($_POST['inpCC']) && $_POST['inpCC'] == 1){
		
		for ($i=0; $i<count($destinatariosCC) ; $i++) { 
			//error_log("2".$destinatariosCC[$i]);
			$mail->addCC($destinatariosCC[$i]);
		}
	} 

	if (isset($_POST['inpCCO']) && $_POST['inpCCO'] == 1){
		
		for ($i=0; $i<count($destinatariosCCO) ; $i++) { 
			//error_log($destinatariosCCO[$i]);
			$mail->addBCC($destinatariosCCO[$i]);
			
		}
	} 

	// Establecer a quién se enviará el mensaje
	//$mail->addAddress($receptor, $receptor);
	if (isset($_POST['mail'])){
		
		for ($i=0; $i<count($mmaail) ; $i++) { 
			//error_log($mmaail[$i]);
			if (!validar_email($mmaail[$i])) {
				$mensaje = "Error en las direcciones de mail";
				error_log($mensaje);
				$arreglo["success"] = false;
				$arreglo["error"]=array('mensaje' => $mensaje);
				header('Content-type: application/json; charset=utf-8');
	    		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    		exit;
			}
			$mail->addAddress($mmaail[$i], $mmaail[$i]);
			
		}
	} 
	// Establecer la línea de asunto
	$mail->Subject = $asunto;

	// Lea el cuerpo de un mensaje HTML desde un archivo externo, convierta las imágenes de referencia a incrustadas,
	// convertir HTML en un cuerpo alternativo básico de texto plano
	$mail->IsHTML(true);
	
	$mail->msgHTML($cuerpo);
	
	$mail->Body = $cuerpo."<br>".$firma;
 
	// Reemplazar el cuerpo de texto plano por uno creado manualmente
	//$mail->AltBody = 'This is a plain-text message body';
	$mail->CharSet = 'UTF-8';


	if($adjj != ""){
		for ($i=0; $i <count($adjj) ; $i++) { 
			$id_file = $adjj[$i];
			$buscar = mysqli_query($con, "SELECT id_file,nombre FROM `fil01adj` WHERE id_file='$id_file'");
			$tt = mysqli_fetch_array($buscar);
			$mail->addAttachment('adjuntos/'.$tt["nombre"], $tt["nombre"]);

			$texdj = "Error al insertar en fil03tieti. Linea 105";
			$texdj2 = "Error al intentar guardar los datos.";
			resultInsert($con, $buscar, $texdj, $texdj2);
		}

	}
	if($todos == 1){
		$buscar = mysqli_query($con, "SELECT nombre FROM `fil01adj` WHERE `nro_ticket`='$nro_ticket' and `tipo_ticket`='$tipo'");
		//$tt = mysqli_fetch_array($buscar);
		while ($tt = mysqli_fetch_array($buscar)) {
			$arreglo["adj"][]= $tt;
		}
		$texdj 	= "Error al insertar el adjunto. Linea 190";
		$texdj2 	= "Error al tratar de enviar con adjunto.";
		resultInsert($con, $buscar, $texdj, $texdj2);
		//$num = mysqli_num_rows($sql);
		for ($i=0; $i <= $buscar->num_rows-1; $i++) { 
			//$adj = 'adjuntos/'.$arreglo["adj"][$i]["nombre"].",".$arreglo["adj"][$i]["nombre"];
			$mail->addAttachment('adjuntos/'.$arreglo["adj"][$i]["nombre"], $arreglo["adj"][$i]["nombre"]);
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
	        		$tipo		= "S";
	        		
					//Movemos y validamos que el archivo se haya cargado correctamente
					//El primer campo es el origen y el segundo el destino
					if(move_uploaded_file($_FILES["archivo".$i]['tmp_name'],$destino)) {	
						$destino2 = convertircadenas($destino);
						$name2 = convertircadenas($name);
					$query1 = "INSERT INTO `fil01adj`(`ruta`, `id_mail`,`nombre`,`tipo`,`nro_ticket`,`tipo_ticket`) VALUES ('$destino2','$id_mail','$name2','$tipo','$nro_ticket','$tipo_ticket')";
					
					$resultInsert3 = mysqli_query($con,$query1);

					$textadj 	= "Error al insertar el adjunto. Linea 190";
					$textadj2 	= "Error al tratar de enviar con adjunto.";
					resultInsert($con, $resultInsert3, $textadj, $textadj2);
						
					$mail->addAttachment($destino, $nombre);


					} else {
						
						error_log("Error al intentar guardar el archivo." .$error);	
						$arreglo["success"] = false;
						$arreglo["error"]=array('mensaje' => "Error al tratar de enviar con adjunto" );
						header('Content-type: application/json; charset=utf-8');
	    				echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    				exit;			
					
					}

				} //fin if de si existe el archivo

		}//FIN DEL FOR
	}//FIN DEL IF SI HAY ARCHIVOS ADJUNTOS

	if(!$mail->Send()) {
		
		error_log("Problema al enviar el mail. " . $mail->ErrorInfo);
	    $arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al tratar de enviar el mail1 ". $mail->ErrorInfo);
		header('Content-type: application/json; charset=utf-8');
	    echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    exit;	

		} //fin if 

 ?>