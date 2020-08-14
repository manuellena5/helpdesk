<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$id_mail = $_GET['id_mail'];
$query = "UPDATE fil01mail SET estado='E' WHERE id_mail=$id_mail";
$resultUpdate = mysqli_query($con, $query);
$mensajeusuario="No se pudo borrar correctamente";
$mensajeconsola="No se pudo borrar correctamente";
resultInsert($con,$resultUpdate,$mensajeusuario,$mensajeconsola);
mysqli_close($con); 
?>
<script>javascript:cargarPagina('mesaentrada/ingresopormail.php?ver=si&men=El mail se Borro con Exito.&donde=Ingreso por Mail');</script>