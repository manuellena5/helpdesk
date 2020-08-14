<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
/*$buscamos = mysqli_query($con, "SELECT `numero` FROM `fil03notif` WHERE `quienlee`='".$_SESSION["nrousuario"]."'");
$res  = mysqli_fetch_array($sql);*/
$sql = mysqli_query($con, "SELECT fnotif.`numero`,fnotif.`titulo`,fusuario.`usuario` quiengenera,fnotif.`mensaje`
	FROM `fil01notif` fnotif
	inner join ( SELECT fp.`usuario`,fp.`nrousuario` FROM fil01seg fp )as fusuario on fusuario.`nrousuario` = fnotif.`quiengenera`
    WHERE fnotif.`quiengenera` = '".$_SESSION["nrousuario"]."'");
$num = mysqli_num_rows($sql);
if (mysqli_num_rows($sql) === 0) {
	$arreglo= array("data"=>array("-"=>"no hay datos"));
}else{
	while ($re= mysqli_fetch_array($sql)) {
		$arreglo["data"][]	= $re;
	}
	
	for ($i=0; $i < $num; $i++) { 
		$numero = $arreglo["data"][$i]["numero"];
	    $my = mysqli_query($con, "SELECT fusuario.`usuario` nombre,IF(fnoti.`estado`=0,'No','Si')estado
	FROM `fil01notif` fnotif
    inner join ( SELECT not03.`quienlee`,not03.`numero`,not03.`estado` FROM `fil03notif` not03 )AS fnoti on fnoti.`numero` = fnotif.`numero`
	inner join ( SELECT fp.`usuario`,fp.`nrousuario` FROM fil01seg fp )as fusuario on fusuario.`nrousuario` = fnoti.`quienlee`
WHERE fnotif.`numero` = '$numero'");
		$cant = mysqli_num_rows($my);
	    $arreglo["data"][$i]["notif"] = array();  
	        for ($j=1; $j <= $cant; $j++) { 
	            $res = mysqli_fetch_array($my);
	            $arreglo["data"][$i]["notif"][$j]["nombre"] = $res['nombre'];; 
	            $arreglo["data"][$i]["notif"][$j]["estado"] = $res['estado']; 
	            $arreglo["data"][$i]["cant"]=$j;
	        }
	}
}
echo json_encode($arreglo);	
?>