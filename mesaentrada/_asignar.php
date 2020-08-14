<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$id 			= $_POST['id'];
$nro_ticket 	= $_POST['nro_ticket'];
$tipo_ticket 	= $_POST['tipo_ticket'];
$arreglo        = array();
$arreglo['success'] = true;
//echo $tipo_ticket;

//(0)buscamos todo lo del primer ticket
$buscar = mysqli_query($con, "SELECT * FROM `fil01mail` WHERE `id_mail`='$id'");
$re_bus = mysqli_fetch_array($buscar);
$error_bus 		= "Error en fil01mail. Linea 15";
$error_bus_2 	= "Error 00.";
resultInsert($con,$buscar,$error_bus,$error_bus_2);

//(1)Seleccionamos todo lo del ticket al cual vamos a asignar 
$ticket_seleccin = mysqli_query($con, "SELECT * FROM  `fil01mail` WHERE  `nro_ticket` ='$nro_ticket' AND  `tipo_ticket` ='$tipo_ticket' ORDER BY  `fecha_ticket` ASC LIMIT 1");
$re_seleccion = mysqli_fetch_array($ticket_seleccin);
$error_seleccion 	= "Error en fil01mail. Linea 12";
$error_seleccion_2 	= "Error 01.";
resultInsert($con,$ticket_seleccin,$error_seleccion,$error_seleccion_2);

//(2)buscamos el ultimo registro en fil03mail de ese ticket asignado
$ticket_buscar = mysqli_query($con, "SELECT * FROM `fil03mail` WHERE `nro_ticket`='$nro_ticket' and `tipo_ticket`='$tipo_ticket' ORDER BY fechaasig DESC LIMIT 1");
$re_buscar = mysqli_fetch_array($ticket_buscar);
$error_buscar 		= "Error en fil03mail. Linea 19";
$error_buscar_2 	= "Error 02.";
resultInsert($con,$ticket_buscar,$error_buscar,$error_buscar_2);

//(3)editamos en fil01mail el mail que estabamos por derivar
$fecha = date("y/m/d - H:i");
if($re_seleccion['cod_entidad'] == ""){
	$cod_entidad = 0;
} else {
	$cod_entidad = $re_seleccion['cod_entidad'];
}

$mail_editar = mysqli_query($con, "UPDATE `fil01mail` SET `estado`='T',`nro_ticket`='$nro_ticket',`fecha_ticket`='$fecha',`nombre_ticket`='".$re_seleccion['nombre_ticket']."',`categoria`='".$re_seleccion['categoria']."',`cod_entidad`='".$cod_entidad."',tipo_ticket='$tipo_ticket' WHERE `id_mail`='$id'");
$error_editar 		= "Error en fil01mail. Linea 27";
$error_editar_2 	= "Error 03.";
resultInsert($con,$mail_editar,$error_editar,$error_editar_2);

//(4)agregamos a fil03mail el nuevo registro
if($re_buscar['nro_mesaent'] == ""){
	$nro_mesaent = 0;
}else{
	$nro_mesaent = $re_buscar['nro_mesaent'];
}
if($re_buscar['tipo_docum'] == ""){
	$tipo_docum = 0;
}else{
	$tipo_docum = $re_buscar['tipo_docum'];
}
if($re_buscar['ing_env'] == ""){
	$ing_env = 0;
}else{
	$ing_env = $re_buscar['ing_env'];
}
/*
echo $id."<br>";
echo $nro_ticket."<br>";
echo $re_buscar['usuarioasig']."<br>";
echo $re_buscar['rolasig']."<br>";
echo $fecha."<br>";
echo $re_bus['adjunto']."<br>";
echo $re_buscar['tipo_ticket']."<br>";
echo $nro_mesaent."<br>";
echo $tipo_docum."<br>";
echo $ing_env."<br>";
echo $re_bus['cuerpo']."<br>";
*/
$observacion = "Mail unido a Ticket existente";
$mail_agregar = mysqli_query($con, "INSERT INTO `fil03mail`
	(`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`, `rolasig`, `fechaasig`, `estado`, `adjunto`, `tipo_ticket`, `leido`, `nro_mesaent`, `tipo_docum`, `ing_env`,`observacion`) 
	VALUES 
	('$id','$nro_ticket','0','".$re_buscar['usuarioasig']."','".$re_buscar['rolasig']."','$fecha','C','".$re_bus['adjunto']."','$tipo_ticket','0','$nro_mesaent','$tipo_docum','$ing_env','$observacion')");
$error_agregar 		= "Error en fil03mail. Linea 38";
$error_agregar_2 	= "Error 04.";
resultInsert($con,$mail_agregar,$error_agregar,$error_agregar_2);

echo json_encode($arreglo, JSON_FORCE_OBJECT);
mysqli_close($con);
?>
