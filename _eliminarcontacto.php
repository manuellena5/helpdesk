<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
if($_GET['mail'] == "null"){
	$eliminar = mysqli_query($con, "DELETE FROM `fil03lib` WHERE `cod_grupo`='".$_GET['codigo']."'");
}
$sql = mysqli_query($con, "DELETE FROM `fil01lib` WHERE `codigo`='".$_GET['codigo']."'");
$text 	= "Error al insertar un nuevo valor. Linea 12";
$text1 	= "Se produjo un error al modificar un nuevo valor.";
resultInsert($con, $sql, $text, $text1);
mysqli_close($con);
?>
<script>javascript:cargarPagina('contactos.php?ver=si&men=El mail se Borro con Exito.&donde=Contactos');</script>