<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$id 					= $_POST['id'];
$quienderi 				= $_POST['user'];
$observacion 			= $_POST['observacion'];
$fechaasig 				= date("y/m/d - H:i:s");
$leido 					= 2;
$arreglo 				= array();
$arreglo["success"] 	= true;


$query = "SELECT * FROM fil03mail WHERE id='$id'";
if ($sql = mysqli_query($con, $query)) {
	if ($sql->num_rows > 0) {
		$res = mysqli_fetch_array($sql);

		$id_mail 		= $res['id_mail'];
		$nro_ticket 	= $res['nro_ticket'];
		$estado			= $res['estado'];
		$adjunto		= $res['adjunto'];
		$tipo_ticket	= $res['tipo_ticket'];
		$nro_mesaent 	= $res['nro_mesaent'];
		$tipo_docum 	= $res['tipo_docum'];
		$ing_env 		= $res['ing_env'];
		$respuesta      = $res['respuesta'];

		if ($res['usuarioasig'] == NULL) {
			$usuarioasig = 0;
		}else{
			$usuarioasig	= $res['usuarioasig'];
		}
		
		if ($res['rolasig'] == NULL) {
			$rolasig = 0;
		}else{
			$rolasig		= $res['rolasig'];
		}

		if ($res['quienresp'] == NULL) {
			$quienresp = 0;
		}else{
			$quienresp		= $res['quienresp'];
		}

		$query2 = "INSERT INTO `fil03mail`
		(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`, `rolasig`, `fechaasig`, `observacion`, `quienresp`, `respuesta`, `estado`, `adjunto`, `tipo_ticket`, `leido`,`tipo_docum`,`ing_env`,`nro_mesaent`) 
		VALUES 
		('$id_mail','$nro_ticket','$quienderi','$usuarioasig','$rolasig','$fechaasig','$observacion','$quienresp','$respuesta','$estado','$adjunto','$tipo_ticket','$leido','$tipo_docum','$ing_env','$nro_mesaent')";
		$resultInsert = mysqli_query($con,$query2);
		$text 	= "Error al insertar en fil03mail. Linea 22";
		$text1 	= "Error al observar el ticket.";
		resultInsert($con, $resultInsert, $text, $text1);
	}else{
		error_log("La consulta no trajo resultados fil03mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => "error al finalizar el ticket " );
		header('Content-type: application/json; charset=utf-8');
	    echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	    exit;
	}
}else{
	error_log("Error en consulta en fil03mail " .
	            		mysqli_errno($con) . " " . mysqli_error($con));
	$arreglo["success"] = false;
	$arreglo["error"]=array('mensaje' => "error al finalizar el ticket " );
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	exit;
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT);
mysqli_close($con);  

?>