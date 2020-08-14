<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres 	= 50;
$nrousuario		= $_SESSION["nrousuario"];
$grupo			= $_SESSION['grupo'];
$rol			= $_SESSION['rol'];
$arreglo 		= array(); 

$sql = mysqli_query($con,"SELECT DISTINCT
	m3.nro_ticket,m1.emisor,m1.asunto,m3.fechaasig,IF(fadj.id_file is not null,'S','N')adjunto,m3.id,m1.cuerpo,m3.observacion,m3.tipo_ticket,f01ent.razon,m1.cod_entidad
FROM `fil03mail` m3
inner join fil01mail m1 on m3.id_mail = m1.id_mail 
left join fil01adj fadj on fadj.nro_ticket = m3.nro_ticket and fadj.tipo_ticket = m3.tipo_ticket 
LEFT join fil01ent f01ent on f01ent.codigo = m1.cod_entidad
WHERE  m3.`estado`='F' and m3.`quienresp`='$nrousuario'
ORDER BY m3.fechaasig DESC");

if (mysqli_num_rows($sql) === 0) {
	$arreglo= array("data"=>array("-"=>"no hay datos"));
}else{
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
		$adjunto = $arreglo["data"][$i]["adjunto"];
		if ($adjunto == "") {
			$arreglo["data"][$i]["adjunto"] = "N";
			$arreglo["data"][$i][4] = "N";
		}
		$arreglo["data"][$i]["3"] = date("d-m-y", strtotime($arreglo["data"][$i]["3"]));
		$arreglo["data"][$i]["fechaasig"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fechaasig"]));
	}
}
echo json_encode($arreglo);	
?>