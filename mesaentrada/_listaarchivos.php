<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres = 25;

$query="SELECT f01.fecha,f01.emisor,f01.asunto,f01.adjunto,f00.pardesc,f01.id_mail
FROM fil01mail f01
left join fil00par f00 ON f00.parvalor = f01.categoria AND f00.parcod = '5'
WHERE f01.estado='A'";

if ($sql = mysqli_query($con,$query)) {
	("error 02");
$num = mysqli_num_rows($sql);
    if (mysqli_num_rows($sql) === 0) {
		$arreglo= array("data"=>array("-"=>"no hay datos"));
	}else{
		while ($re= mysqli_fetch_array($sql)) {
 			$arreglo["data"][]	= $re;	
		}
		for ($i=0; $i < $num ; $i++) { 
			$adjunto = $arreglo["data"][$i]["adjunto"];
			if ($adjunto == "") {
				$arreglo["data"][$i]["adjunto"] = "N";
				$arreglo["data"][$i][2] = "N";
			}
			$arreglo["data"][$i]["0"] = date("d-m-y", strtotime($arreglo["data"][$i]["0"]));
			$arreglo["data"][$i]["fecha"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fecha"]));
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