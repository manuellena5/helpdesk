<?php
session_start();
include("../connect.php"); 
include("../funciones.php");
salir();
$arreglo 	= array();
$buscar	 	= $_GET['buscar'];

$query= "SELECT fil01.`nro_ticket`,if(fil01.`emisor`='',f01ent.razon,fil01.emisor)emisor,fil01.`asunto`,fil01.`fecha`,fil03.`id`, fil03.`tipo_ticket`,fil01.`cuerpo`,fil01.cod_entidad 
		FROM  `fil01mail` fil01
		INNER JOIN `fil03mail` fil03 ON fil03.`id_mail` = fil01.`id_mail`
		LEFT JOIN fil01ent f01ent on f01ent.codigo = fil01.cod_entidad 
		WHERE fil01.`cuerpo` LIKE '%".$buscar."%' OR fil01.`asunto` LIKE '%".$buscar."%' OR fil03.`observacion` LIKE '%".$buscar."%' OR fil03.`respuesta` LIKE '%".$buscar."%'
		GROUP BY fil01.`nro_ticket`,fil03.tipo_ticket
		ORDER BY fil01.`fecha` DESC";



if ($sql = mysqli_query($con,$query)) {
	if( $sql->num_rows > 0 ){ 
		
		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]= $re;
		}

		$num = mysqli_num_rows($sql);
		for ($i=0; $i < $num ; $i++) { 
			$arreglo["data"][$i]["cuerpo"]	= mb_convert_encoding($arreglo["data"][$i]["cuerpo"], 'UTF-8', 'UTF-8');
			$arreglo["data"][$i][6] 		= mb_convert_encoding($arreglo["data"][$i][6], 'UTF-8', 'UTF-8');
			if(($arreglo["data"][$i]["cod_entidad"] != 0) && ($arreglo["data"][$i]["cod_entidad"] != null) && ($arreglo["data"][$i]["cod_entidad"] != "")){
					$arreglo["data"][$i]["emisor"] = $arreglo["data"][$i]["razon"];
					$arreglo["data"][$i][1] = $arreglo["data"][$i]["razon"];
				}
			$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 50){
				$arreglo["data"][$i][2] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][2] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] =$cadena;
			$arreglo["data"][$i]["3"] = date("d-m-y", strtotime($arreglo["data"][$i]["3"]));
			$arreglo["data"][$i]["fecha"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fecha"]));
		}
	}else{
		$arreglo["data"] = array(
        	'mensaje' => 'No se encontró ningún resultado.'
        );
	}
}else{
	$arreglo["data"] = array(
        'mensaje' => $con->error
    );
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo);
mysqli_close($con); 
?>