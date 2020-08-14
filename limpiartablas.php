<?php 
include("connect.php");


$sql= "TRUNCATE TABLE `fil01adj`;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 1 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 1 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "ALTER TABLE `fil01adj` auto_increment = 1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 2 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 2 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "TRUNCATE TABLE `fil03tieti`;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 3 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 3 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "ALTER TABLE `fil03tieti` auto_increment = 1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 4 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 4 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "TRUNCATE TABLE `fil03mail`;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 5 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 5 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "ALTER TABLE `fil03mail` auto_increment = 1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 6 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 6 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "SET FOREIGN_KEY_CHECKS=0;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 7 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 7 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }


$sql= "TRUNCATE TABLE `fil01mail`;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 8 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 8 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "SET FOREIGN_KEY_CHECKS=1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 9 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 9 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "ALTER TABLE `fil01mail` auto_increment = 1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 10 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 10 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "UPDATE `fil00num` SET `numero` = '0' WHERE `fil00num`.`id` = 1;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 11 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 11 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "UPDATE `fil00num` SET `numero` = '0' WHERE `fil00num`.`id` = 2;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 12 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 12 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }

$sql= "UPDATE `fil00num` SET `numero` = '0' WHERE `fil00num`.`id` = 3;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 13 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 13 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }


$sql= "TRUNCATE TABLE `fil01spam`;";
$resultUpdate = mysqli_query($con, $sql);

		     if (!$resultUpdate) {
		     	error_log("Error en la consulta 14 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	echo ("Error en la consulta 14 ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }


echo "Se limpiaron las tablas con exito";
mysqli_close($con);
 ?>