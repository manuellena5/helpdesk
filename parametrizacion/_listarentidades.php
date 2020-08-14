<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$sql = mysqli_query($con, "SELECT `razon`,`domicilio`,`localidad`,`cuit`,`telefono`,`mail_contacto`,`codigo` FROM `fil01ent`");
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