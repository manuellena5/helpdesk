<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$arreglo        = array();
$arreglo['success'] = true;

$id_mail 		= $_POST['numail'];
$etiquetas 		= $_POST['etiquetas'];

$sql = mysqli_query($con, "UPDATE `fil01mail` SET `estado`='A',`categoria`='$etiquetas' WHERE `id_mail`='$id_mail'");
$error 		= "Error en fil01mail. Linea 12";
$error2 	= "Error 01.";
resultInsert($con,$sql,$error,$error2);

echo json_encode($arreglo);
mysqli_close($con);
?>