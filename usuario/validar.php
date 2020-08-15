<?php
session_start();
include("../connect.php");
$usuario=$_POST['username'];
$pw= $_POST['password'];
if ($usuario != '' && $pw != '') {

		//Comprobacion 
		// Valida si el usuario y la contraseña existen en la base de datos.
		$usuario = mb_strtoupper($usuario);
		
		$sql3 = mysqli_query($con, "SELECT usuario,password FROM fil01seg WHERE usuario = '$usuario'");
		$row3 = mysqli_fetch_array($sql3);
		if($sql3->num_rows == 0 ){
			error_log("::::Ingreso no valido:::: El usuario ".$usuario." no existe en la base de datos");
			echo "";
			$sql3->close();
			exit;
		}
		if($row3['password'] != $pw) {
			error_log("::::Ingreso no valido:::: La pass ".$pw." no existe en la base de datos");
			echo "";
			$sql3->close();
			exit;
		} else {
			//Si existe en la base de datos va a preguntar su tiene permisos por dia.
			$sql = mysqli_query($con, "SELECT * FROM fil01seg WHERE usuario = '$usuario'");
			$row = mysqli_fetch_array($sql);
			$franjadia = $row['franjadia'];
			$franjahora = $row['franjahora'];
					// funcion
			$va = 3; //Codigo de parcod para buscar los dias
			$sql1 = mysqli_query($con, "SELECT pardesc FROM fil00par WHERE parvalor = '$franjadia' and parcod = '$va'");
			$row1 = mysqli_fetch_array($sql1);
			$desc = $row1['pardesc'];
			$dia = date("N");
			$res = $dia - 1;
			$caracter = strlen($desc);
			$rang = $dia - $caracter;
			$rest = substr($desc, $res, $rang);
			if($rest == 1){
				//Si esta en el dia correcto de trabajo va a preguntar si la hs coincide con la de trabajo.
				$va = 4; //Codigo de parcod para buscar los horiarios
				$sql2 = mysqli_query($con, "SELECT pardesc FROM fil00par WHERE parvalor = '$franjahora' and parcod = '$va'");
				$row2 = mysqli_fetch_array($sql2);
				$desc2 = $row2['pardesc'];
				$hora = date("H:i"); //Formato de hora 00:00
				$hInicio = substr($desc2, 0, 5); //Agarro los primeros cuatro valores que serian la hora de inicio
				$hFin = substr($desc2, 6); //Agarro los ultimos cuatro valores que serian la hora de salida
				if($hora >= $hInicio && $hora <= $hFin){
					//Despues derivamos a Recepcion o a email segun su rol.

						$_SESSION["usuario"] = $row['usuario'];

						$fecha = date("y/m/d - H:i:s");

						if($row['ultimoing'] == ""){

							mysqli_query($con, "UPDATE fil01seg SET ultimoing='$fecha' WHERE usuario='".$_SESSION["usuario"]."'");
							$_SESSION['ultimoing']= $fecha;

							} else {

							$_SESSION['ultimoing']= $row['ultimoing'];
							mysqli_query($con, "UPDATE fil01seg SET ultimoing='$fecha' WHERE usuario='".$_SESSION["usuario"]."'");
							}
						$_SESSION["logeado"] 		= "SI";
						$_SESSION["nrousuario"] 	= $row['nrousuario'];
						$_SESSION["nombre"] 		= $row['nombre'];
						$_SESSION['grupo'] 			= $row['grupo'];
						$_SESSION['rol'] 			= $row['rol'];
						$_SESSION['firma'] 			= $row['firma'];
						echo "ok";
				} else {
					if($desc2 == "Ninguno"){
						error_log("::::Ingreso no valido:::: Usted esta de Vacaciones.");
						echo "vacaciones";
					} else {
						error_log("::::Ingreso no valido:::: No tiene acceso a la pagina. Tu horario de trabajo no coinciden.");
						echo "hora";
					}
				}//fin if de hora
		} else {
			error_log("::::Ingreso no valido:::: No tiene acceso a la pagina. Tus dias laborales no coinciden");
			echo "dia";
		}
		mysqli_close($con);
	}
		// fin
} else {
	error_log("::::Ingreso no valido:::: Debe completar los campos de usuario y password");
	echo "faltausuario";
}
?>