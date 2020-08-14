<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); 
$arreglo 	= array();
$arreglo['success'] = true;
$arreglo['url'] = "parametrizacion/ABM_Nro_Mesa_Entrada.php?ver=si&men=Se ha modificado el N° de Mesa de Entrada.&donde=ABM Usuarios";
$numero = $_POST['numero'];

$editar = mysqli_query($con, "UPDATE `fil00num` SET `numero`='$numero' WHERE `proceso`='NUMMESAENT'");
$text 	= "Error al insertar un nuevo valor. Linea 10";
$text1 	= "Error al insertar un nuevo valor.";
resultInsert($con, $editar, $text, $text1);

echo json_encode($arreglo);
?>