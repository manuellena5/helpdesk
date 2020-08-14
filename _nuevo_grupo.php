<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
$arreglo = array();


$nombre = $_POST['nombre'];
$arreglo = validar_grupo_contacto();


if ( (is_array($arreglo)) && ((count($arreglo))>0)){
	$arreglo["success"] 	= false;
	$mensajeerror = "";
	foreach ($arreglo as $error) {
		$mensajeerror .= $error . "\n";
		
     } 
     $arreglo["error"]=  array('mensaje' => $mensajeerror);


}else{
	//buscamos nombre de grupos repetidos.
	$seleeciona = mysqli_query($con, "SELECT nombre FROM fil01lib WHERE nombre ='$nombre'");
	$num 		= $seleeciona->num_rows;
	if($num == 0){
		$arreglo['success'] = true;
		//agregamos el nombre del grupo
		$sql 			= mysqli_query($con, "INSERT INTO `fil01lib` (`nombre`) VALUES ('$nombre')");
		$text 			= "Error al insertar un nuevo valor. Linea 26.";
		$text1 			= "Se produjo un error al insertar un nuevo valor.";
		resultInsert($con, $sql, $text, $text1);
		$id = mysqli_insert_id($con);

		//buscamos los codigos de los mail
		for ($i=1; $i <=$_POST['numero'] ; $i++) {
		$mail = $_POST['mail'.$i];

		$buscar = mysqli_query($con, "SELECT codigo FROM fil01lib WHERE mail='$mail'");
		$re= mysqli_fetch_array($buscar);
		$tex 			= "Error al insertar un nuevo valor. Linea 36.";
		$tex1 			= "Se produjo un error al seleccionar un nuevo valor.";
		resultInsert($con, $buscar, $tex, $tex1);

		//Insertamos los codigos de los mail con el codigo del grupo
		$insertar = mysqli_query($con, "INSERT INTO `fil03lib`(`cod_grupo`, `cod_mail`) VALUES ('$id','".$re['codigo']."')");
		$texint 			= "Error al insertar un nuevo valor. Linea 43.";
		$texint1 			= "Se produjo un error al Insertar un nuevo valor.";
		resultInsert($con, $insertar, $texint, $texint1);
		}
		$arreglo['url'] = "contactos.php?ver=si&men=Se creo con exitos el Grupo: ".$nombre.".&donde=Contactos";
	} else {
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "El Grupo ya Existe."
		);
	}
}
mysqli_close($con);
echo json_encode($arreglo);
?>