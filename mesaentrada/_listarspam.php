<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres = 25;

$query="SELECT fecha,emisor,asunto,uid,cuerpo,cc,destinatario FROM fil01spam ORDER BY fecha DESC";

if ($sql = mysqli_query($con,$query)) {
$num = mysqli_num_rows($sql);
    if (mysqli_num_rows($sql) === 0) {
		$arreglo= array("data"=>array("-"=>"no hay datos"));
	}else{
		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]	= $re;
		}
		for ($i=0; $i < $num ; $i++) { 
			$arreglo["data"][$i]["cuerpo"]	= mb_convert_encoding($arreglo["data"][$i]["cuerpo"], 'UTF-8', 'UTF-8');
			$arreglo["data"][$i][5] 		= mb_convert_encoding($arreglo["data"][$i][5], 'UTF-8', 'UTF-8');
			$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 25){
				$arreglo["data"][$i][2] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][2] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] = $cadena;
			
			$arreglo["data"][$i]["0"] = date("d-m-y", strtotime($arreglo["data"][$i]["0"]));
			$arreglo["data"][$i]["fecha"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fecha"]));
		}
	}
}else{
	$mensajeerror = $con->error;
	error_log($con->error);
    $arreglo["data"] = array("data"=>array("-"=>$mensajeerror));
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo);	
mysqli_close($con);
?>