<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
	
$arreglo = array();
$arreglo['success'] = true;
$arreglo = _validarform_respondermail($con);
 

if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{


$arreglo['success']	= true;
	/*** Captura la firma ***/
	$fir = mysqli_query($con, "SELECT `firma` FROM `fil01seg` WHERE `nrousuario`='".$_POST['nrousuario']."'");
	$firre = mysqli_fetch_array($fir);
	$firma 	= $firre['firma'];

	$arreglo = array();
	$arreglo["success"] = true;

	/*** VALORES DEL FORMULARIO ***/
	$nroarchivos = $_POST['nroarchivos'];
	$id_mail 	= $_POST['numail'];
	$rol 		= $_POST['rol'];
	$asunto		= $_POST['nombreticket'];
	//$firma		= $_POST['firma'];

	$quienresp   = $_POST['quienresp'];
	$quienderi   = $_POST['nrousuario'];
	//$receptor	 = $_POST['receptor'];
	$idticket	 = $_POST['idticket'];
	$todos 		 = $_POST['todos'];
	


	/**VALIDACIONES**/

	if (responder($_POST['cuerpo'])) {
		$respuesta	 = $_POST['cuerpo'];
		//$respuesta 	 = str_replace("<br>","\n",$respuesta);
	}

	if ($_POST['nombre_ticket'] == "") {
		$etiquetas 			= $_POST['etiquetas'];
		funcionetiqueta($etiquetas);

	}

	if ($_POST['secuetiquetas'] == "") {
		$secuetiquetas 	= "";

	}else{
		$expo 			= substr($_POST['secuetiquetas'],0,-1);
		$secuetiquetas 	= explode(";",$expo);
	}

	if ($_POST['adj'] == "") {
		$adjj 	= "";

	}else{
		$expo 			= substr($_POST['adj'],0,-1);
		$adjj 			= explode(";",$expo);
	}

	
	/*Es el tipo en adjuntos*/
	if($_POST['tipo'] == "I"){
		$tipo = "I";
	} else {
		$tipo = "E";
	}

	$emisor 	 = "cie@cie.gov.ar";
	$cuerpo 	 = $respuesta."<br> >";//falta cuerpo entero
	
	$derivado	= "T";
	$estado		= "R";
	$adj 		= "N";
	
	$fecha_ticket = date("y/m/d - H:i:s");
	
	


	$query0 = "SELECT * FROM fil01mail WHERE id_mail='$id_mail'";
		
	if ($sql = mysqli_query($con,$query0)) {

		if ($sql->num_rows > 0) {
			$result = mysqli_fetch_array($sql);
			$cuerpo2 	= $result['cuerpo'];
		}

	}else{

		error_log("Error al consultar el cuerpo del mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "error al enviar el mail" );
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($arreglo, JSON_FORCE_OBJECT); 
    	exit;

	}
	$sql->close();
	
	
	//Validar si el ticket esta generado o no
if(strpos($asunto, '[TICKET#') === false and strpos($asunto, '[INTERNO#') === false){
	
	//No esta generado
	//Crea el ticket y se lo asigna a si mismo (Se lo deriva, estado=D)
	
	$proceso = "numticket";
	$nro_ticket = obtener_nuevo_numero_ticket($con, $proceso);	
	$nombre_ticket = formar_nombre_ticket($nro_ticket, "E");
	$expediente = $_POST['expediente'];
	
	$query = "UPDATE fil01mail SET estado='$derivado', nro_ticket='$nro_ticket', fecha_ticket='$fecha_ticket', nombre_ticket='$nombre_ticket',categoria='$etiquetas',tipo_ticket='$tipo',expediente='$expediente' WHERE id_mail='$id_mail'";

	$resultUpdate = mysqli_query($con,$query);
	$text = "Error al actualizar en fil01mail. Linea 67";
	$text2 = "Error al enviar el mail.";
	resultInsert($con, $resultUpdate, $text, $text2);

	$asunto = $nombre_ticket.$asunto;
	

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

	$insadj = mysqli_query($con , "UPDATE `fil01adj` SET `nro_ticket`='$nro_ticket',`tipo_ticket`='E' WHERE `id_mail`='$id_mail'");
	$texadj = "Error al actualizar en fil01adj. Linea 104";
	$texadj2 =	"Error al actualizar los datos";
	resultInsert($con, $insadj, $texadj, $texadj2);
	

	$query2 = "INSERT INTO `fil03mail`
		(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`, `fechaasig`,`estado`,`tipo_ticket`) 
		VALUES 
		('$id_mail','$nro_ticket','$quienderi','$quienderi','$fecha_ticket','D','E')";
	$resultInsert2 = mysqli_query($con,$query2);
	$text03 = "Error al insertar en fil03mail. Linea 107";
	$text032 = "Error al enviar el mail.";

	resultInsert($con, $resultInsert2, $text03, $text032);
	$nro_mesaent 	= 0;
	$tipo_docum 	= 0;
	$ing_env 		= "";
}else{
	
		$tipo = $_POST['tipo_ticket'];
		
		$nro_ticket  = $_POST['nro_ticket'];

		$query0 = "SELECT if(nro_mesaent is null,0,nro_mesaent)nro_mesaent,if(tipo_docum is null,0,tipo_docum)tipo_docum ,if(ing_env is null,'',ing_env)ing_env  
					FROM  fil03mail 
					WHERE nro_ticket = '$nro_ticket ' and tipo_ticket ='$tipo'
					order by fechaasig ASC
					limit 1";


		if ($sql = mysqli_query($con,$query0)) {

			if ($sql->num_rows > 0) {
				$result = mysqli_fetch_array($sql);
				//$tipo_ticket 	= $result['tipo_ticket'];
				$nro_mesaent 	= $result['nro_mesaent'];
				$tipo_docum 	= $result['tipo_docum'];
				$ing_env 		= $result['ing_env'];
			} 

		}else{

		error_log("Error al consultar el tipo_ticket del mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "error al enviar el mail" );
		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($arreglo, JSON_FORCE_OBJECT); 
    	exit;

		}
		$query0 		= "UPDATE fil03mail set leido = 1 where id='$idticket'";
		$resultUpdate 	= mysqli_query($con,$query0);
		$msjconsola 	= "Error al actualizar en fil03mail como leido. Linea 70";
		$msjusuario 	= "Error al intentar guardar los datos.";
		resultInsert($con,$resultUpdate,$msjconsola,$msjusuario);
				
		$sql->close();

}
		
		$cuerpo = $cuerpo.$cuerpo2."<br>";

		if ($_POST['inpCC'] == 1){
			$CC = $_POST['CC'];
			$longitud = mb_strlen($CC);
			$caracter = substr($CC,$longitud-1);
			if ($caracter == ';') {
				$CC = substr($CC,0,-1);
			}
			$respuesta = $respuesta."<br><b>CC:</b> ".$CC;
			$destinatariosCC = explode(";",$_POST['CC']);
			
		} 

		if ($_POST['inpCCO'] == 1){
			$CCO = $_POST['CCO'];
			$longitud = mb_strlen($CCO);
			$caracter = substr($CCO,$longitud-1);
			if ($caracter == ';') {
				$CCO = substr($CCO,0,-1);
				
			}
			$respuesta = $respuesta."<br><b>CCO:</b> ".$CCO;
			$destinatariosCCO = explode(";",$_POST['CCO']);
			
		} 

		if($_POST['mail']){
			$mmail = $_POST['mail'];
			$longitud = mb_strlen($mmail);
			$caracter = substr($mmail,$longitud-1);
			if ($caracter == ';') {
				$mmail = substr($mmail,0,-1);
			}
			$respuesta = $respuesta."<br><b>Para:</b> ".$mmail;
			$mmaail = explode(";",$mmail);
		}

		/**********************ENVIO DEL MAIL************************/
		
		include("_enviodemail.php");
	
		/**********************ENVIO DEL MAIL************************/


		$respuesta = convertircadenas($respuesta);
			//Aumento 30 seg para que no quede la misma fecha entre los dos registros de 
			$date= date('y-m-d H:i:s'); 
			$newDate = strtotime ( '+2 second' , strtotime ($date) ) ; 
			$newDate = date ( 'y-m-d H:i:s' , $newDate); 

			$query = "INSERT INTO `fil03mail`
						(`id_mail`,`nro_ticket`,`usuarioasig`,`fechaasig`,`quienresp`, `respuesta`, `estado`,`adjunto`,`tipo_ticket`,`nro_mesaent`,`tipo_docum`,`ing_env`) 
						VALUES 
						('$id_mail','$nro_ticket','$quienderi','$newDate','$quienresp','$respuesta','$estado','$adj','$tipo','$nro_mesaent','$tipo_docum','$ing_env')";
			$resultInsert = mysqli_query($con, $query);


			if (!$resultInsert) {
		
	        error_log("Error al insertar el ticket." .
	            mysqli_errno($con) . " " . mysqli_error($con));
	        $arreglo["success"] = false;
			$arreglo["error"]=array('mensaje' => "Error al tratar de enviar el mail" );
			header('Content-type: application/json; charset=utf-8');
	    	echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    	exit;	
			
			}


			/*
		 if(!$mail->Send()) {
	
			error_log("Problema al enviar el mail. " . $mail->ErrorInfo);
		    $arreglo["success"] = false;
			$arreglo["error"]=array('mensaje' => "Error al tratar de enviar el mail" );
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