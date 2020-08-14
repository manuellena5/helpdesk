<?php
session_start();
include("connect.php");
include("funciones.php");
salir();

$arreglo = array();
$arreglo['success'] = true;
$arreglo = _validarform_enviarmail($con);
 

if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{


$arreglo['success']	= true;
$receptor 				= $_POST['mail'];
$asunto 				= convertircadenas($_POST['asunto']);
$cuerpo 				= $_POST['cuerpo'];
$quienenvia 			= $_POST['quienresp'];
$nroarchivos 			= $_POST['nroarchivos'];
$estado_enviado 		= "E";
$estado_ticket 			= "T";
$adj 					= "N";
$eemail 				= "cie@cie.gov.ar";
$fecha_ticket 			= date("y/m/d - H:i:s");
$tipo_ticket			= 'E';
$proceso				= 'numticket';
$nro_ticket     		= obtener_nuevo_numero_ticket($con,$proceso);
$nombre_ticket  		= formar_nombre_ticket($nro_ticket,$tipo_ticket);			
$etiquetas 				= $_POST['etiquetas'];
$firma					= isset($_POST['firma']) ? $_POST['firma'] : "";
$expediente 			= $_POST['expediente'];
$todos 					= "0";
$adjj					= "";




	if ($_POST['secuetiquetas'] == "") {
		$secuetiquetas 		= "";

	}else{
		$expo 				= substr($_POST['secuetiquetas'],0,-1);
		$secuetiquetas 		= explode(";",$expo);
	}

	// Reguistrar la respues.
		$query = "INSERT INTO `fil01mail`
			(`destinatario`, `emisor`, `asunto`, `cuerpo`, `fecha`, `adjunto`, `tipo_ingreso`, `estado`, `nro_ticket`, `fecha_ticket`, `nombre_ticket`,`categoria`,`tipo_ticket`,`expediente`) 
			VALUES 
			('$eemail','$receptor','$asunto','$cuerpo','$fecha_ticket','$adj','$estado_enviado','$estado_ticket','$nro_ticket','$fecha_ticket','$nombre_ticket','$etiquetas','$tipo_ticket','$expediente')";

		$resultInsert = mysqli_query($con,$query);

		$text 		= "Error al insertar en fil01mail. Linea 41.";
		$text2 		= "Error al enviar el mail.";
		resultInsert($con, $resultInsert, $text, $text2);
		
		$id_mail 		= mysqli_insert_id($con);

		//Ingreso de todas las Categorias

		if($secuetiquetas != ""){
			for ($i=0; $i <count($secuetiquetas) ; $i++) { 
				$nro_categ = $secuetiquetas[$i];
				$quer = "INSERT INTO `fil03tieti`
				(`nro_ticket`, `tipo_ticket`, `nro_categ`) 
				VALUES 
				('$nro_ticket','$tipo_ticket','$nro_categ')";

				$resultInse = mysqli_query($con,$quer);
				$textsecu = "Error al insertar en fil03tieti. Linea 105";
				$textsecu2 = "Error al intentar guardar los datos.";

				resultInsert($con, $resultInse, $textsecu, $textsecu2);
			}

		}

		$asunto = $nombre_ticket."".$asunto;
		
		if ($_POST['inpCC'] == 1){
		$CC = $_POST['CC'];
		$longitud = mb_strlen($CC);
		$caracter = substr($CC,$longitud-1);
		if ($caracter == ';') {
			$CC = substr($CC,0,-1);
		}
		$sqlcc = mysqli_query($con , "UPDATE `fil01mail` SET `cc`='$CC' WHERE `id_mail`='$id_mail'");
		$destinatariosCC = explode(";",$CC);
		
		} 

		if ($_POST['inpCCO'] == 1){
			$CCO = $_POST['CCO'];
			$longitud = mb_strlen($CCO);
			$caracter = substr($CCO,$longitud-1);
			if ($caracter == ';') {
				$CCO = substr($CCO,0,-1);
				
			}
			$sqlcco = mysqli_query($con , "UPDATE `fil01mail` SET `cco`='$CCO' WHERE `id_mail`='$id_mail'");
			$destinatariosCCO = explode(";",$CCO);
		} 

		if($_POST['mail']){
			$mmail = $_POST['mail'];
			$longitud = mb_strlen($mmail);
			$caracter = substr($mmail,$longitud-1);
			if ($caracter == ';') {
				$mmail = substr($mmail,0,-1);
			}
			$mmaail = explode(";",$mmail);
		}


		/**********************ENVIO DEL MAIL************************/
		
		include("_enviodemail.php");

		/**********************ENVIO DEL MAIL************************/




		$query = "INSERT INTO `fil03mail`
			(`id_mail`, `nro_ticket`, `usuarioasig`, `fechaasig`,`adjunto`,`tipo_ticket`) VALUES 
			($id_mail,'$nro_ticket','$quienenvia','$fecha_ticket','$adj','E')";

		$resultInsert = mysqli_query($con,$query);

		$text03 	= "Error al insertar el ticket. Linea 140";
		$text032 	= "Error al tratar de enviar el mail Insert 03mail.";
		resultInsert($con, $resultInsert, $text03, $text032);

		
		$query="UPDATE `fil01mail` SET `adjunto`='$adj' WHERE `id_mail`='$id_mail'";
		
		$resultUpdate = mysqli_query($con,$query);

		$text01 	= "Error al insertar el ticket. Linea 233";
		$text012 	= "Error al tratar de enviar el mail update 01mail.";
		resultInsert($con, $resultUpdate, $text01, $text012);


		/*
		if(!$mail->Send()) {
		
		error_log("Problema al enviar el mail. " . $mail->ErrorInfo);
	    $arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "Error al tratar de enviar el mail ". $mail->ErrorInfo);
		header('Content-type: application/json; charset=utf-8');
	    echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    exit;	

		} //fin if 

		*/

}
	header('Content-type: application/json; charset=utf-8');
    echo json_encode($arreglo, JSON_FORCE_OBJECT);
		 
		 mysqli_close($con);  	
	 
  ?>