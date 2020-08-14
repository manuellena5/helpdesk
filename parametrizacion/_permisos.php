<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$arreglo = array();
$arreglo2 = array();
if(isset($_GET) && count($_GET)>0){

	$sql1 = mysqli_query($con, "SELECT proceso,nombre FROM `fil00seg`");
	
	while ($re1= mysqli_fetch_array($sql1)) {
		$arreglo["data"][]	= $re1;
	}

	$sql2 = mysqli_query($con, "SELECT proceso FROM `fil02seg` WHERE `usuario`='".$_GET['usu']."'");
	while ($re2= mysqli_fetch_array($sql2)) {
		$arreglo2["data2"][]	= $re2;
	}
	
	$numfilas2 = mysqli_num_rows($sql2);


	$numfilas1 	= mysqli_num_rows($sql1);


	
	for ($j=0; $j < $numfilas2; $j++) { 
		
		for ($i=0; $i < $numfilas1 ; $i++) { 
			
			if($arreglo2["data2"][$j]["proceso"] == $arreglo['data'][$i]["proceso"]){
				
				$arreglo['data'][$i]["acceso"] = "N";
				$arreglo['data'][$i][2] = "N";
			}
		}
	}
	

	
}

if(isset($_POST) && count($_POST)>0){
	$selected = $_POST['selected'];
	$delete = mysqli_query($con, "DELETE FROM `fil02seg` WHERE `usuario`='".$_POST['usuario']."'");
	if($selected != ""){
		$destinatarios = explode(",",$selected);
		for ($i=0; $i<count($destinatarios) ; $i++) { 
			$sq1 = mysqli_query($con, "SELECT `proceso` FROM `fil00seg` WHERE `nombre`='".$destinatarios[$i]."'");
			while ($r1= mysqli_fetch_array($sq1)) {
				$arreglo["data"][]	= $r1;
			}
		}
		for ($u=0; $u<count($destinatarios) ; $u++) { 
			$insert =  mysqli_query($con, "INSERT INTO `fil02seg`(`proceso`, `usuario`) VALUES ('".$arreglo["data"][$u]["proceso"]."','".$_POST['usuario']."')");
		}
	}
	$arreglo["success"] = true;
	mysqli_close($con);
}
echo json_encode($arreglo);
?>