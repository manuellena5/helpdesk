<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres = 50;

$query="SELECT `nro_ticket`,`asunto`,if(`tipo_ticket`='E','Ticket','Interno')`tipo`,`id_mail`,`adjunto`,`fecha`,`cuerpo`,`emisor`,`cc`,`tipo_ticket`
FROM `fil01mail`
WHERE `estado`='T' 
GROUP BY  `nro_ticket`,`tipo_ticket`";
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
			$arreglo["data"][$i][6] 		= mb_convert_encoding($arreglo["data"][$i][6], 'UTF-8', 'UTF-8');

			/*$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 50){
				$arreglo["data"][$i][1] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][1] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] = $cadena;*/
			$adjunto = $arreglo["data"][$i]["adjunto"];
			if ($adjunto == "") {
				$arreglo["data"][$i]["adjunto"] = "N";
				$arreglo["data"][$i][4] = "N";
			}
			$arreglo["data"][$i][5] = date("d-m-y", strtotime($arreglo["data"][$i][5]));
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