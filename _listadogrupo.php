<?php 
session_start();
include("connect.php");
include("funciones.php");
salir();
$query= "SELECT `codigo`,`nombre`,`mail` FROM `fil01lib` WHERE `mail` IS NOT null";

if ($sql = mysqli_query($con,$query)) {

	if( $sql->num_rows > 0 ){
		 
		 $arreglo["data"]= array();

		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]= $re;
		}

	$sql->close();
	
	}else{
		
        $arreglo["data"] = array(
        'mensaje' => 'No se encontró ningún resultado.'
        );

	}
	
}else{
	
    $arreglo["data"] = array(
        'mensaje' => $con->error
        );
}

 header('Content-type: application/json; charset=utf-8');
 echo json_encode($arreglo); 

    mysqli_close($con);
 ?>