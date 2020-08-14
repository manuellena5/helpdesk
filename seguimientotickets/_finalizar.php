<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$id 					= $_POST['id'];
$quienresp 				= $_POST['user'];
$observacion 			= $_POST['observacion'];
$fechaasig 				= date("y/m/d - H:i:s");
$estado 				= "F";
$arreglo 				= array();
$arreglo["success"] 	= true;

if($observacion != ""){
	$query = "SELECT * FROM fil03mail WHERE id='$id'";
	if ($sql = mysqli_query($con, $query)) {
		if ($sql->num_rows > 0) {
			$res = mysqli_fetch_array($sql);

			$id_mail 		= $res['id_mail'];
			$nro_ticket 	= $res['nro_ticket'];
			$tipo_ticket 	= $res['tipo_ticket'];
			$query2 = "INSERT INTO `fil03mail`
			(`id_mail`, `nro_ticket`, `fechaasig`, `observacion`, `quienresp`, `estado`,`tipo_ticket`,`leido`) 
			VALUES
			('$id_mail','$nro_ticket','$fechaasig','$observacion','$quienresp','$estado','$tipo_ticket','4')";
			$resultInsert = mysqli_query($con,$query2);
			$text 	= "Error al insertar en fil03mail. Linea 22";
			$text1 	= "Error al finalizar el ticket.";
			resultInsert($con, $resultInsert, $text, $text1);
			/*$query3 = "UPDATE fil03mail set leido=1 WHERE nro_ticket = '$nro_ticket'";
			$resultUpdate = mysqli_query($con,$query3);
			$text03 	= "Error al actualizar estado leido fil03mail. Linea 30";
			$text031 	= "Error al finalizar el ticket.";
			resultInsert($con, $resultUpdate, $text03, $text031);*/
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
} else {
	$arreglo["success"] = false;
	$arreglo["error"]=array('mensaje' => "Debe completar el campo de Observacion." );
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($arreglo, JSON_FORCE_OBJECT); 
	exit;
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT);
mysqli_close($con);  
?>