<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
/*$buscamos = mysqli_query($con, "SELECT `numero` FROM `fil03notif` WHERE `quienlee`='".$_SESSION["nrousuario"]."'");
$res  = mysqli_fetch_array($sql);*/
$sql = mysqli_query($con, "SELECT fnotif.`numero`,fnotif.`titulo`,fusuario.`usuario` quiengenera,fnotif.`mensaje`
	FROM `fil01notif` fnotif
    inner join ( SELECT not03.`quienlee`,not03.`numero` FROM `fil03notif` not03 )AS fnoti on fnoti.`quienlee` = '".$_SESSION["nrousuario"]."' and fnoti.`numero` = fnotif.`numero`
	inner join ( SELECT fp.`usuario`,fp.`nrousuario` FROM fil01seg fp )as fusuario on fusuario.`nrousuario` = fnotif.`quiengenera`");
$num = mysqli_num_rows($sql);
if (mysqli_num_rows($sql) === 0) {
	$arreglo= array("data"=>array("-"=>"no hay datos"));
}else{
	while ($re= mysqli_fetch_array($sql)) {
		$arreglo["data"][]	= $re;
	}
}
echo json_encode($arreglo);	
?>