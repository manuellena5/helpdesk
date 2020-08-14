<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$query="SELECT fseg.usuario,fseg.nombre,frol.pardesc descrol,SUBSTR(ffd.pardesc,11) descfranjadia,ffh.pardesc descfranjahora,fseg.nrousuario
	FROM `fil01seg` fseg 
	inner join ( 
				SELECT fp.parvalor,fp.pardesc 
				FROM fil00par fp 
				WHERE fp.parcod=1)as frol on frol.parvalor = fseg.rol 
	inner join ( 
				SELECT fp.parvalor,fp.pardesc 
				FROM fil00par fp 
				WHERE fp.parcod=3)as ffd on ffd.parvalor = fseg.franjadia 
	inner join ( 
				SELECT fp.parvalor,fp.pardesc 
				FROM fil00par fp 
				WHERE fp.parcod=4)as ffh on ffh.parvalor = fseg.franjahora";
$sql = mysqli_query($con, $query);
if ($sql) {
	if (mysqli_num_rows($sql) == 0) {
		$arreglo= array("data"=>array("-"=>"no hay datos"));
	}else{
		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]	= $re;
		}
	}
	echo json_encode($arreglo);
}else{
	error_log("error al obtener listado de usuarios ." .
			    mysqli_errno($con) . " " . mysqli_error($con));
	exit;
}
?>