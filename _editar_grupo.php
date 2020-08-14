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
	$seleeciona = mysqli_query($con, "SELECT * FROM fil01lib WHERE codigo ='".$_POST['codigo']."'");
	$num 		= $seleeciona->num_rows;
	if($num == 1){
		$arreglo['success'] = true;
		
		//eliminamos todos los registros de ese grupo
		$eliminar = mysqli_query($con, "DELETE FROM `fil03lib` WHERE `cod_grupo`='".$_POST['codigo']."'");
		$elim 			= "Error al insertar un nuevo valor. Linea 24.";
		$elim1 			= "Se produjo un error al eliminar los valores.";
		resultInsert($con, $eliminar, $elim, $elim1);

		//editamos nombre
		$editar = mysqli_query($con, "UPDATE `fil01lib` SET `nombre`='$nombre' WHERE `codigo`='".$_POST['codigo']."'");
		$edit 			= "Error al insertar un nuevo valor. Linea 30.";
		$edit1 			= "Se produjo un error al editar los valores.";
		resultInsert($con, $editar, $edit, $edit1);

		//agregamos de nuevo los mail del grupo
		for ($i=0; $i <=$_POST['numero'] ; $i++) {
			if($_POST['mail'.$i] != null){
				$mail = $_POST['mail'.$i];

				$buscar = mysqli_query($con, "SELECT codigo FROM fil01lib WHERE mail='$mail'");
				$re= mysqli_fetch_array($buscar);
				$tex 			= "Error al insertar un nuevo valor. Linea 36.";
				$tex1 			= "Se produjo un error al seleccionar un nuevo valor.";
				resultInsert($con, $buscar, $tex, $tex1);

				//Insertamos los codigos de los mail con el codigo del grupo
				$insertar = mysqli_query($con, "INSERT INTO `fil03lib`(`cod_grupo`, `cod_mail`) VALUES ('".$_POST['codigo']."','".$re['codigo']."')");
				$texint 			= "Error al insertar un nuevo valor. Linea 43.";
				$texint1 			= "Se produjo un error al Insertar un nuevo valor.";
				resultInsert($con, $insertar, $texint, $texint1);
			}
		}
		$arreglo['url'] = "contactos.php?ver=si&men=Se edito con exito el Grupo: ".$nombre.".&donde=Contactos";
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