<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$id_mail = $_GET['id_mail'];

$aas = mysqli_query($con, "UPDATE fil01mail SET estado=NULL WHERE id_mail=$id_mail");
mysqli_close($con); 
?>
<script>javascript:cargarPagina('mesaentrada/papelerademail.php?ver=si&men=El mail se Restaudo con Exito.&donde=Papelera de Mail');</script>