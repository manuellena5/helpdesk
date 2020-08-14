<?php

session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres = 50;

$query="SELECT fecha,emisor,asunto,adjunto,id_mail,cuerpo,cc,destinatario FROM fil01mail where estado is null
ORDER BY fecha DESC";

if ($sql = mysqli_query($con,$query)) {
	("error 02");
$num = mysqli_num_rows($sql);
    if (mysqli_num_rows($sql) === 0) {
		$arreglo= array("data"=>array("-"=>"no hay datos"));
	}else{
		while ($re= mysqli_fetch_array($sql)) {
			try{
 				$arreglo["data"][]	= $re;
			}catch(Exception $e){
			 	$arreglo["data"][]	= $re['id_mail'];
			 	error_log($e);
			}	
		}
		for ($i=0; $i < $num ; $i++) { 
			$arreglo["data"][$i]["cuerpo"]	= mb_convert_encoding($arreglo["data"][$i]["cuerpo"], 'UTF-8', 'UTF-8');
			$arreglo["data"][$i][5] 		= mb_convert_encoding($arreglo["data"][$i][5], 'UTF-8', 'UTF-8');
			/*if (mb_detect_encoding($arreglo["data"][$i]["cuerpo"], 'UTF-8', true)) {
				$arreglo["data"][$i]["cuerpo"] = "";
			}*/
			//$arreglo["data"][$i]["cuerpo"] = convertircadenas($arreglo["data"][$i]["cuerpo"]);
			//$arreglo["data"][$i][5] = convertircadenas($arreglo["data"][$i]["cuerpo"]);
			//convertircadenas();
			//error_log($arreglo["data"][$i]["cuerpo"]);
			$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 50){
				$arreglo["data"][$i][2] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][2] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] = $cadena;

			$cadena2 = $arreglo["data"][$i]["emisor"];
			if (strlen($cadena2) > 25){
				$arreglo["data"][$i][1] = substr($cadena2, 0, 25).'...';
			}else{
				$arreglo["data"][$i][1] = $cadena2;
			}
			$arreglo["data"][$i]["emisor"] = $cadena2;


			$adjunto = $arreglo["data"][$i]["adjunto"];
			if ($adjunto == "") {
				$arreglo["data"][$i]["adjunto"] = "N";
				$arreglo["data"][$i][3] = "N";
			}
			$arreglo["data"][$i]["0"] = date("d-m-y H:i:s", strtotime($arreglo["data"][$i]["0"]));
			$arreglo["data"][$i]["fecha"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fecha"]));
			if($arreglo["data"][$i]["adjunto"] == "S"){
	            $id_mail = $arreglo["data"][$i]["id_mail"];
	            $my = mysqli_query($con, "SELECT * FROM fil01adj WHERE id_mail='$id_mail'");
	            $cant = mysqli_num_rows($my);
	            $arreglo["data"][$i]["archivo"] = array();  
	            for ($j=1; $j <= $cant; $j++) { 
	                $res = mysqli_fetch_array($my);
	                $arreglo["data"][$i]["archivo"][$j]["nombre"] = substr($res['nombre'],12); 
	                $arreglo["data"][$i]["archivo"][$j]["ruta"] = $res['ruta']; 
	                $arreglo["data"][$i]["cant"]=$j;

	            }

	                
	        }
		}
	}
}else{
	error_log("error 03");
	$mensajeerror = $con->error;
	error_log($con->error);
    $arreglo["data"] = array("data"=>array("-"=>$mensajeerror));
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo);
if(json_last_error() != 0){
echo errorjson(json_last_error());
}

mysqli_close($con);
?>