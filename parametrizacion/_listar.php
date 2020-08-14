<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$parcod = $_GET['parcod'];
$sql = mysqli_query($con, "SELECT `pardesc`,`parvalor` FROM `fil00par` WHERE `parcod`='$parcod'");
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