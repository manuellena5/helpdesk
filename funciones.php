<?php 		
error_reporting(E_ALL); // Error engine - always ON!

ini_set('ignore_repeated_errors', false); // always ON

ini_set('display_errors', false); // Error display - OFF in production env or real server

ini_set('log_errors', 1); // Error logging

ini_set('error_log', 'php-error.log'); // Logging file

ini_set('log_errors_max_len', 1024); // Logging file size

ini_set('default_charset', 'UTF-8');



include("connect.php");
if(isset($_POST["action"]) && $_POST["action"] != "") {
	// Se pasa una acción
	$arreglo = array();
	$arreglo["success"] = false;
	switch($_POST["action"]) { // ¿Qué acción?
		case "1":
		$cant = fnoleidos($con,$_POST['nrousuario'],$_POST['rol']);
		$cant2 = observado($con,$_POST['nrousuario'],$_POST['rol']);
		echo  $cant;
		break;
	case "2":
		$arreglo["success"] = true;
		$arreglo["ingresopormail"] = contar_estados($con,"nulos");
		$arreglo["papelera"] = contar_estados($con,"E");
		$arreglo["sinaccion"]=contar_ticket($con,$_POST['nrousuario'],$_POST['rol'],"accion");
		$arreglo["enespera"]=contar_ticket($con,$_POST['nrousuario'],$_POST['rol'],"espera");
		$arreglo["finalizados"]=contar_ticket($con,$_POST['nrousuario'],$_POST['rol'],"fin");
		$arreglo["spam"]=contar_ticket($con,$_POST['nrousuario'],$_POST['rol'],"spam");
		$arreglo["seguimiento"]=contar_ticket($con,$_POST['nrousuario'],$_POST['rol'],"seguimiento");
		$arreglo["cantticketsnoleidos"] = fnoleidos($con,$_POST['nrousuario'],$_POST['rol']);
		$arreglo["cantticketsobservados"] = observado($con,$_POST['nrousuario'],$_POST['rol']);
		echo json_encode($arreglo); 
		break;  
	case "numero_mesa_entrada":
		$arreglo = llamar_numero_mesa_entrada();
		echo json_encode($arreglo);
		break;  
	}
}


if(isset($_POST["pagina"])){
	session_start();
	include("connect.php");
	$arreglo = array();
	$pagina = $_POST["pagina"];
	$php = strpos($pagina, ".php");
	$rest = substr($pagina, 0,$php);
	$sql = mysqli_query($con, "SELECT * FROM `fil02seg` WHERE `usuario`='".$_SESSION['nrousuario']."' AND `proceso`='$rest'");
	if ($sql->num_rows == 0) {
		$arreglo["success"] = true;
	} else {
		$arreglo["success"] = false;
	}
	echo json_encode($arreglo);
}

function obtener_nuevo_numero_ticket($con, $proceso){
	$con = $con;
	$query = "SELECT numero from fil00num WHERE proceso='$proceso'";
	$sql = mysqli_query($con,$query);
	$respuesta = mysqli_fetch_array($sql);
	if ($respuesta) {
		$numticket = $respuesta['numero'];
		$nuevonro = $numticket + 1;
		$query = "UPDATE `fil00num` SET `numero`='$nuevonro' WHERE proceso='$proceso'";
		$resultupdate = mysqli_query($con,$query);
		if (!$resultupdate) {
			error_log("error update de numeroticket fil00num ." .
			    mysqli_errno($con) . " " . mysqli_error($con));
			exit;
		}else{
			$nuevonro = str_pad($nuevonro, 8, '0',STR_PAD_LEFT);
			return $nuevonro;
		}	
	}else{
		error_log("Hubo un error al obtener el ultimo nro de ticket");
		exit;
	}
}
function nuevonumeroarchivo($con){
	$query ="SELECT max(id_file)id_file FROM fil01adj";
	$sql 		= mysqli_query($con,$query);
	$resultado  = mysqli_fetch_array($sql);
	if ($resultado) {
		$numfile 	= $resultado['id_file'];
		$numero 	= str_pad($numfile + 1, 11, '0',STR_PAD_LEFT);
		return $numero;
	}else{
		error_log("Hubo un error al obtener el ultimo nro de ticket");
	}
}
function formar_nombre_ticket($numero ,$tipo_ticket){
	if($tipo_ticket == "I"){
		$fecha = date("Ymd");
		$nombreticket = "[INTERNO#".$fecha."".$numero."]";
	} else {
		$fecha = date("Ymd");
		$nombreticket = "[TICKET#".$fecha."".$numero."]";
	}
	return $nombreticket;
}
function convertircadenas($inp) { 
	if(is_array($inp)){
		return array_map(__METHOD__, $inp); 
	}
	if(!empty($inp) && is_string($inp)) { 
		return str_replace(array('\\', "\0", "\n", "\r", "'","´", '"', "\x1a", "#"), array('\\\\', '\\0', '\\n', '\\r', "\\'",'\\´', '\\"', '\\Z',' '), $inp); 
	} 
	return $inp; 
} 


function decodificar_nombre($nombre){

	if (mb_detect_encoding($nombre, 'UTF-8', true)) {
							
							$nombre = iconv('UTF-8','UTF-8',$nombre);
							
							

	}else if(mb_detect_encoding($nombre, 'ISO-8859-1', true)){
							
							$nombre = iconv('ISO-8859-1','UTF-8',$nombre);
							
							
							

	}else {

							ini_set('mbstring.substitute_character', "none"); 
				  			$nombre= mb_convert_encoding($nombre, 'UTF-8', 'UTF-8'); 
				  			

	}	
	return $nombre;  



}



function zero_fill ($valor, $long=11){
/*
* zero_fill
 *
* Rellena con ceros a la izquierda
*
* @param $valor valor a rellenar
* @param $long longitud total del valor
* @return valor rellenado
*/
	return str_pad($valor, $long, '0', STR_PAD_LEFT);
}
function  getNextId($c){
	return mysqli_insert_id($c);
}

function campos($usuario, $pass, $pass2, $nombre, $grupo, $roles, $dia, $hora, $firma){
	$arreglo['success'] = true;
	if($usuario == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en el usuario."
		);
	} 
	if($pass == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en las contraseñas."
		);
	} elseif($pass2 == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en las contraseñas."
		);
	} elseif($pass <> $pass2){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en las contraseñas."
		);
	} elseif(strlen($pass) > "15"){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en las contraseñas."
		);
	} 
	if($nombre == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en el Nombre."
		);
	} 
	if($grupo == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en Grupos."
		);
	} 
	if($roles == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en el Rol."
		);
	} 
	if($dia == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en el Dia."
		);
	} 
	if($hora == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en la Hora."
		);
	} 
	if($firma == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Error en la Firma."
		);
	}
	return $arreglo;
}
function errorSubidaArchivo($codigo) { 	
	switch ($codigo) { 
		case UPLOAD_ERR_INI_SIZE: 
			$mensaje = "El fichero subido excede la directiva upload_max_filesize de php.ini"; 
			break; 
		case UPLOAD_ERR_FORM_SIZE: 
			$mensaje = "El fichero subido excede la directiva MAX_FILE_SIZE especificada en el formulario HTML"; 
			break; 
		case UPLOAD_ERR_PARTIAL: 
			$mensaje = "El fichero fue sólo parcialmente subido"; 
			break; 
		case UPLOAD_ERR_NO_FILE: 
			$mensaje = "No se subió ningún fichero"; 
			break; 
		case UPLOAD_ERR_NO_TMP_DIR: 
			$mensaje = "Falta la carpeta temporal"; 
			break; 
		case UPLOAD_ERR_CANT_WRITE: 
			$mensaje = "No se pudo escribir el fichero en el disco"; 
			break; 
		case UPLOAD_ERR_EXTENSION: 
			$mensaje = "Una extensión de PHP detuvo la subida de ficheros"; 
			break; 
		default: 
			$mensaje = "No hay error, fichero subido con éxito."; 
			break; 
	} 
	error_log($mensaje);
	return $mensaje;
} 
function errorjson($error,$valor) { 	
	switch($error) {
        case "0": //JSON_ERROR_NONE
            $mensaje = ' - Sin errores';
        break;
        case "1": //JSON_ERROR_DEPTH
            $mensaje = ' - Excedido tamaño máximo de la pila';
        break;
        case "2": //JSON_ERROR_STATE_MISMATCH
            $mensaje = ' - Desbordamiento de buffer o los modos no coinciden';
        break;
        case "3": //JSON_ERROR_CTRL_CHAR
            $mensaje = ' - Encontrado carácter de control no esperado';
        break;
        case "4": //JSON_ERROR_SYNTAX
            $mensaje = ' - Error de sintaxis, JSON mal formado';
        break;
        case "5": //JSON_ERROR_UTF8
            $mensaje = ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta';
        break;
        case "6": //JSON_ERROR_RECURSION
            $mensaje = ' - Una o más referencias recursivas en el valor a codificar';
        break;
        case "7": //JSON_ERROR_INF_OR_NAN
            $mensaje = ' - Uno o más valores NAN o INF en el valor a codificar';
        break;
         case "8": //JSON_ERROR_UNSUPPORTED_TYPE
            $mensaje = ' - Se proporcionó un valor de un tipo que no se puede codificar';
        break;
         case "9": //JSON_ERROR_INVALID_PROPERTY_NAME
            $mensaje = ' - Se dio un nombre de una propiedad que no puede ser codificada';
        break;
        case "10": //JSON_ERROR_UTF16
            $mensaje = ' - Caracteres UTF-16 malformados, posiblemente codificados de forma incorrecta';
        break;
        default:
            $mensaje = ' - Error desconocido';
        break;
    } 
	error_log($mensaje);
	return $mensaje;
}
function decodificarcadena($cadena){
	if (mb_detect_encoding($cadena, 'UTF-8', true)) {
		$cadena = iconv('UTF-8','UTF-8',$cadena);
	}else if(mb_detect_encoding($cadena, 'ISO-8859-1', true)){
		$cadena = iconv('ISO-8859-1','UTF-8',$cadena);
	}else {
		ini_set('mbstring.substitute_character', "none"); 
		$cadena= mb_convert_encoding($e1, 'UTF-8', 'UTF-8'); 
	}	
	return $cadena;
}
function responder($cuerpo){
	$arreglo['success'] = true;
	if($cuerpo == null || $cuerpo == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Por favor rellene el campo de Respuesta."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
		exit;
	} else{
		return true;}
}
function derivar($drol , $dusuario){
	$arreglo = array();
	$arreglo['success'] = true;
	if($drol == "" and $dusuario == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Se necesita Derivar a un Rol/Usuario."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
		exit;
	} elseif($drol != "" && $dusuario != ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Seleccione solo un Rol/Usuario."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT); 
		exit;
	} elseif ($drol == "" && $dusuario != "") {
		$arreglo['drol'] = "0";
		$arreglo['dusuario'] = $dusuario;
	} elseif ($drol != "" && $dusuario == ""){
		$arreglo['dusuario'] = "0";
		$arreglo['drol'] = $drol;
	}
	return $arreglo;
}
function ccuerpo($titulo, $mensaje){
	$arreglo['success'] = true;
	if($titulo == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Por favor rellene el campo de titulo."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT);
		exit;
	} elseif($mensaje == ""){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Por favor rellene el campo de mensaje."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT);
		exit;
	}else{ 
		return $arreglo;
	}
}
function resultInsert($con, $resultado, $mensaje_consola, $mensaje_usuario){
	$arreglo = array();
	$arreglo["success"] = true;
	if (!$resultado) {
		error_log($mensaje_consola .
			mysqli_errno($con) . " " . mysqli_error($con));
		$arreglo["success"] = false;
		$arreglo["error"]=array('mensaje' => $mensaje_usuario );
		//header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT);
		exit;
	}else{
		return $arreglo;		
	}			
}
function funcionetiqueta($etiqueta){
	$arreglo["success"] = true;
	if($etiqueta == 0){
		$arreglo['success'] = false;
		$arreglo['error'] = array(
			'mensaje' => "Por favor Seleccione una Etiqueta."
		);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($arreglo, JSON_FORCE_OBJECT);
		exit;
	}else{
		return true;
	}
}




function fnoleidos($con,$nrousuario,$rol){
	$cantidad = 0;
	$query="SELECT COUNT(*) cantidad 
				FROM `fil03mail` m3 
				inner join fil01mail m1 on m3.id_mail = m1.id_mail 
				inner join (SELECT max(fechaasig)fecha, nro_ticket,estado 
							from fil03mail 
							GROUP by nro_ticket,tipo_ticket)as tt on tt.nro_ticket = m3.nro_ticket and m3.fechaasig = tt.fecha 
							WHERE (m3.`usuarioasig`='$nrousuario' or m3.`rolasig` = '$rol') and (m3.estado='C' or m3.estado='D') and (m3.leido=0)";
	if ($sql = mysqli_query($con,$query)) {
		if( $sql->num_rows > 0 ){
			$resultado = mysqli_fetch_array($sql);
			$cantidad  = $resultado['cantidad'];
		}
	}	
	$sql->close();
	return $cantidad;
}



function observado($con,$nrousuario,$rol){

	$cantidad = 0;
	$query="SELECT COUNT(*) cantidad 
				FROM `fil03mail` m3 
				inner join fil01mail m1 on m3.id_mail = m1.id_mail 
				inner join (SELECT max(fechaasig)fecha, nro_ticket,estado 
							from fil03mail 
							GROUP by nro_ticket,tipo_ticket)as tt on tt.nro_ticket = m3.nro_ticket and m3.fechaasig = tt.fecha 
							WHERE (m3.`usuarioasig`='$nrousuario' or m3.`rolasig` = '$rol') and (m3.estado='R' or m3.estado is null or m3.estado='C' or m3.estado='D') and (m3.leido=2)";
	if ($sql = mysqli_query($con,$query)) {
		if( $sql->num_rows > 0 ){
			$resultado = mysqli_fetch_array($sql);
			$cantidad  = $resultado['cantidad'];
		}
	}	
	$sql->close();
	return $cantidad;
}



function contar_estados($con,$estado){
	$cantidad = 0;
	if ($estado == "nulos") {
		$query="SELECT ifnull(SUM(f1.nulos),'0') nulos
					FROM fil01mail f
					INNER join (SELECT id_mail,COUNT(*) nulos
					            from fil01mail
					            WHERE estado is null
					            GROUP by id_mail) as f1 on f1.id_mail = f.id_mail";
	}elseif($estado== "E"){
		$query="SELECT ifnull(SUM(f1.nulos),0) nulos
					FROM fil01mail f
					INNER join (SELECT id_mail,COUNT(*) nulos
					            from fil01mail
					            WHERE estado = 'E'
					            GROUP by id_mail) as f1 on f1.id_mail = f.id_mail";
	}
	if ($sql = mysqli_query($con,$query)) {
		if( $sql->num_rows > 0 ){
			$resultado = mysqli_fetch_array($sql);
			$cantidad  = $resultado['nulos'];
		}
	}else{
		resultInsert($con,$sql,"Error en menu","error al consultar para el menu, linea 478");
	}
	$sql->close();	
	return $cantidad;
}
function contar_ticket($con,$usuario,$rol,$accion){
	$cantidad = 0;
	if ($accion == "accion") {
		$query="SELECT COUNT(*) cantidad
						FROM `fil03mail` m3
						inner join fil01mail m1 on m3.id_mail = m1.id_mail 
						inner join (SELECT max(fechaasig)fecha,nro_ticket,tipo_ticket
                    			from fil03mail 
                    			GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = m3.fechaasig and tt.nro_ticket = m3.nro_ticket and tt.tipo_ticket= m3.tipo_ticket           
						WHERE (m3.`usuarioasig`='$usuario' or m3.`rolasig` = '$rol') and (m3.estado='C' or m3.estado='D')";
	}
	if($accion == "espera"){
		$query="SELECT COUNT(*) cantidad
						FROM `fil03mail` m3
						inner join fil01mail m1 on m3.id_mail = m1.id_mail 
						inner join (SELECT max(fechaasig)fecha,nro_ticket,tipo_ticket
                    			from fil03mail 
                    			GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = m3.fechaasig and tt.nro_ticket = m3.nro_ticket and tt.tipo_ticket= m3.tipo_ticket          
						WHERE (m3.`usuarioasig`='$usuario' or m3.`rolasig` = '$rol') and (m3.estado='R' or m3.estado is null)";
	}
	if($accion == "fin"){
		$query="SELECT COUNT(*) cantidad
						FROM `fil03mail` m3
						inner join fil01mail m1 on m3.id_mail = m1.id_mail 
						WHERE  m3.`estado`='F' and m3.`quienresp`='$usuario'";
	}
	if($accion == "spam"){
		$query="SELECT COUNT(*) cantidad
						FROM `fil01spam` m3
						inner join fil01spam m1 on m3.uid = m1.uid ";
	}
	if($accion == "seguimiento"){
		$query="SELECT COUNT(*) cantidad
						FROM `fil03mail` 
						WHERE `leido`='4' and `quienresp`='$usuario'";
	}
	if ($sql = mysqli_query($con,$query)) {
		if( $sql->num_rows > 0 ){
			$resultado = mysqli_fetch_array($sql);
			$cantidad  = $resultado['cantidad'];
		}
	}else{
		resultInsert($con,$sql,"Error en menu","error al consultar para el menu, linea 478");
	}
	$sql->close();
	return $cantidad;
}




function salir(){
	
	if(!(isset($_SESSION["usuario"]))){
	    echo "<script>location.href=\"index.php\";</script>";
	    exit;
	}
}


function llamar_numero_mesa_entrada(){
	include("connect.php");
	$arreglo = array();
	$num = mysqli_query($con, "SELECT numero FROM fil00num WHERE proceso='NUMMESAENT'");
    $mosnum = mysqli_fetch_array($num);
    $arreglo["NUMMESAENT"] = $mosnum['numero'] +1;
    return $arreglo;
}





function _validarform_ingresosvarios(){

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                //-----------------------------------------------------
                // Variables
                //-----------------------------------------------------
                $errores = array();
                $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
                $entidades = isset($_POST['entidades']) ? $_POST['entidades'] : null;
                $dusuario = isset($_POST['dusuario']) ? $_POST['dusuario'] : null;
                $drol = isset($_POST['drol']) ? $_POST['drol'] : null;
                $email = isset($_POST['email']) ? $_POST['email'] : "";
                $etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : null;
                //-----------------------------------------------------
                // Validaciones
                //-----------------------------------------------------

                if (!validar_requerido($titulo)) {
                    $errores["titulo"] = 'El campo titulo es obligatorio.';
                }
                if ((!validar_requerido($entidades)) || ($entidades == 0)) {
                    $errores["entidades"] = 'Debe seleccionar una entidades';
                }

                if ((!validar_requerido($dusuario)) && (!validar_requerido($drol))) {
                    $errores["seleccion"] = 'Debe seleccionar un rol o usuario a derivar';
                }

                if ($email != ""){
					
						
						$longitud = mb_strlen($email);
						$caracter = substr($email,$longitud-1);
						if ($caracter == ';') {
							$email = substr($email,0,-1);
						}
						$email = explode(";",$email);
						for ($i=0; $i<count($email) ; $i++) { 
						//error_log($mmaail[$i]);
							if (!validar_email($email[$i])) {
								$errores["mail".$i] = 'Debe ingresar un e-mail valido'."\n".'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).';
							}
						}
				}	
                if ((!validar_requerido($etiquetas)) || ($etiquetas == 0)) {
                    $errores["etiquetas"] = 'Debe seleccionar una etiqueta';
                }
                return $errores;
                
            }
		}

		function _validarform_ingresosvarios_v2(){

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                
                //-----------------------------------------------------
                // Variables
                //-----------------------------------------------------
                $errores = array();
                $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
                $entidades = isset($_POST['entidades']) ? $_POST['entidades'] : null;
                $etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : null;
                //-----------------------------------------------------
                // Validaciones
                //-----------------------------------------------------

                if (!validar_requerido($titulo)) {
                    $errores["titulo"] = 'El campo titulo es obligatorio.';
                }
                if ((!validar_requerido($entidades)) || ($entidades == 0)) {
                    $errores["entidades"] = 'Debe seleccionar una entidades';
                }
	
                if ((!validar_requerido($etiquetas)) || ($etiquetas == 0)) {
                    $errores["etiquetas"] = 'Debe seleccionar una etiqueta';
                }
                return $errores;
                
            }
		}



            function _validarform_contacto($nombre,$email){
            	$arreglo["success"] = true;
				if($nombre == ""){
					$arreglo['success'] = false;
					$arreglo['error'] = array(
						'mensaje' => "Por favor agregue un Nombre"
					);
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($arreglo, JSON_FORCE_OBJECT);
					exit;
				}
				if(!validar_email($email)){
					$arreglo['success'] = false;
					$arreglo['error'] = array(
						'mensaje' => "Mail no Valido"
					);
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($arreglo, JSON_FORCE_OBJECT);
					exit;
				}
				return $arreglo;
            }


            function _validarform_enviarmail(){

            	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            	 //-----------------------------------------------------
                // Variables
                //-----------------------------------------------------
                $errores = array();
                $asunto = isset($_POST['asunto']) ? $_POST['asunto'] : null;
                $mensaje = isset($_POST['cuerpo']) ? $_POST['cuerpo'] : null;
                $quienresp = isset($_POST['quienresp']) ? $_POST['quienresp'] : null;
                $etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : null;
                $email = isset($_POST['mail']) ? $_POST['mail'] : "";
                /*$CC = isset($_POST['CC']) ? $_POST['CC'] : null;
                $CCO = isset($_POST['CCO']) ? $_POST['CCO'] : null;
*/
                //-----------------------------------------------------
                // Validaciones
                //-----------------------------------------------------
                // Nombre
                if (!validar_requerido($asunto)) {
                    $errores["asunto"] = 'El campo asunto es obligatorio.';
                }
                 if (!validar_requerido($mensaje)) {
                    $errores["mensaje"] = 'El campo mensaje es obligatorio.';
                }
                if (!validar_requerido($quienresp)) {
                    $errores["quienresp"] = 'Hubo un error al enviar el mail';
                }
                if (!validar_requerido($etiquetas) || $etiquetas == "0") {
                    $errores["etiquetas"] = 'Debe seleccionar una etiqueta';
                }
                if (!validar_requerido($email)) {
                    $errores["email"] = 'El campo mail es obligatorio.';
                }else{
                 
					
						
						$longitud = mb_strlen($email);
						$caracter = substr($email,$longitud-1);
						if ($caracter == ';') {
							$email = substr($email,0,-1);
						}
						$email = explode(";",$email);
						for ($i=0; $i<count($email) ; $i++) { 
						//error_log($mmaail[$i]);
							if (!validar_email($email[$i])) {
								$errores["mail".$i] = 'Debe ingresar un e-mail valido'."\n".'Para multiples direcciones, separe las mismas mediante ";" (Punto y coma).';
							}
						}
				}	
                 /*
               if ($CC != "")  {
                	
	                if (!validar_email($CC)) {
	                    $errores["CC"] = 'El campo de Email en CC tiene un formato no válido.';
	                }
                }
                if ($CCO != "")  {
                	
	                if (!validar_email($CCO)) {
	                    $errores["CCO"] = 'El campo de Email en CCO tiene un formato no válido.';
	                }
                }
*/
                return $errores;

            }

            }





            //-----------------------------------------------------
                // Funciones Para Validar
                //-----------------------------------------------------

                /**
                 * Método que valida si un texto no esta vacío
                 * @param {string} - Texto a validar
                 * @return {boolean}
                 */
                function validar_requerido(string $texto)//: bool
                {
                    return !(trim($texto) == '');
                }

          
                /**
                 * Método que valida si el texto tiene un formato válido de E-Mail
                 * @param {string} - Email
                 * @return {bool}
                 */
                function validar_email(string $texto)//loca: bool
                {
                    return (filter_var($texto, FILTER_VALIDATE_EMAIL) === FALSE) ? False : True;
                }

function _validarform_interno(){

            	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            	 //-----------------------------------------------------
                // Variables
                //-----------------------------------------------------
                $errores = array();
                $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
                $mensaje = isset($_POST['mensaje']) ? $_POST['mensaje'] : null;
                $drol = isset($_POST['drol']) ? $_POST['drol'] : null;
                $dusuario = isset($_POST['dusuario']) ? $_POST['dusuario'] : null;
                $etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : null;

                //-----------------------------------------------------
                // Validaciones
                //-----------------------------------------------------
                // Nombre
                if (!validar_requerido($titulo)) {
                    $errores["titulo"] = 'El campo titulo es obligatorio.';
                }
                 if (!validar_requerido($mensaje)) {
                    $errores["mensaje"] = 'El campo mensaje es obligatorio.';
                }
                if (!validar_requerido($etiquetas) || $etiquetas=="0") {
                    $errores["etiquetas"] = 'Debe seleccionar una etiqueta';
                }
                if ((!validar_requerido($dusuario)) && (!validar_requerido($drol))) {
                    $errores["seleccion"] = 'Debe seleccionar un rol o usuario a derivar';
                }
                if (($dusuario != "") && ($drol != "")) {
                    $errores["seleccion2"] = 'Solo debe seleccionar un rol o usuario a derivar';
                }
                return $errores;

            }

            }

function _validarform_alta($con){

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$errores = array();
        $parcod = isset($_POST['parcod']) ? $_POST['parcod'] : null;

        switch ($parcod) {
        	case '1':

        		$errores = _validacion_alta_rol_etiqueta($con);
        		return $errores;
        		break;
        	case '2':
        		_validacion_alta_grupo();
        		break;
        	case '3':
        		_validacion_alta_franjadia();
        		break;
        	case '4':
        		$errores = _validacion_alta_franjahora($con);
        		return $errores;
        		break;
        	case '5':
        		$errores = _validacion_alta_rol_etiqueta($con);
        		return $errores;
        		break;
        	case '6':
        		_validacion_alta_estadoticket();
        		break;
        	case '7':
        		_validacion_alta_tipodocumento();
        		break;
        	case '8':
        		_validacion_alta_rol_etiqueta();
        		break;
        	case 'usu':
        		$errores = _validacion_alta_usuario($con);
        		return $errores;
        		break;
        	case 'ent':
        		$errores = _validacion_alta_entidades($con);
        		return $errores;
        		break;
        	default:
        		$errores["error"]=array('mensaje' => 'Ocurrio un error inesperado');
        		error_log("error validacion form alta, linea 705");
        		return $errores;
        		break;
        }



	}

}


function _validacion_alta_rol_etiqueta($con){

	$arreglo = array();
	$descripcion = isset($_POST['pardesc']) ? $_POST['pardesc'] : null;
	$parcod = $_POST['parcod'];

	if (!validar_requerido($descripcion)) {
        
        $arreglo["rol"] = 'El campo descripcion es obligatorio.';

    }else{
    $query="SELECT * FROM `fil00par` where parcod = '$parcod' and pardesc = '$descripcion'";
    if ($sql = mysqli_query($con,$query)) {
    	if ($sql->num_rows > 0) {
    		if ($parcod==1) {
    			
    			$arreglo["rol"] = "El rol ya existe, ingrese otra descripcion";
    		}elseif ($parcod==5) {
    			$arreglo["etiqueta"] = "La etiqueta ya existe, ingrese otra descripcion";
    		}elseif($parcod==8){
    			$arreglo["expedientes"] = "El expediente ya existe, ingrese otra descripcion";
    		}
    		
    	}
    }else{
    	$arreglo["rol"] = "Ocurrio un error inesperado";
    	error_log("Error al validar el alta rol, linea 733");
    }

    }
    return $arreglo;
}

function _validacion_alta_franjahora($con){

	$arreglo = array();

	$dhora = isset($_POST['dhora']) ? $_POST['dhora'] : null;
	$dminutos = isset($_POST['dminutos']) ? $_POST['dminutos'] : null;
	$hhora = isset($_POST['hhora']) ? $_POST['hhora'] : null;
	$hminutos = isset($_POST['hminutos']) ? $_POST['hminutos'] : null;
	$parcod = $_POST['parcod'];

	if (!validar_requerido($dhora)) {
        
        $arreglo["dhora"] = 'El campo hora es obligatorio.';

    }
    if (!validar_requerido($dminutos)) {
        
        $arreglo["dminutos"] = 'El campo minutos es obligatorio.';

    }
    if (!validar_requerido($hhora)) {
        
        $arreglo["hhora"] = 'El campo hora es obligatorio.';

    }
    if (!validar_requerido($hminutos)) {
        
        $arreglo["hminutos"] = 'El campo minutos es obligatorio.';

    }
    if(($dhora == $hhora && $dminutos==$hminutos) || ($dhora == $hhora && $dminutos>=$hminutos) || ($dhora > $hhora) ) {
      
     $arreglo["hora"] = "HORA HASTA no puede ser menor o igual a HORA DESDE .";
      
    }else{
    
    $desde 		= $dhora.":".$dminutos;
	$hasta 		= $hhora.":".$hminutos;
	$horafin 	= $desde."-".$hasta;
    $descripcion = $horafin;
    $query="SELECT * FROM `fil00par` where parcod = '$parcod' and pardesc = '$descripcion'";
    if ($sql = mysqli_query($con,$query)) {
    	if ($sql->num_rows > 0) {
    		
    			
    		
    			$arreglo["hora"] = "El horario ya existe";
    		
    		
    	}
    }else{
    	$arreglo["rol"] = "Ocurrio un error inesperado";
    	error_log("Error al validar el alta rol, funciones.php linea 807");
    }
    }
    
    return $arreglo;
}


function _validacion_alta_usuario($con){
	


            	 //-----------------------------------------------------
                // Variables
                //-----------------------------------------------------
                $errores = array();
               	$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
				$pass = isset($_POST['pass']) ? $_POST['pass'] : null;
				$pass2 = isset($_POST['pass2']) ? $_POST['pass2'] : null;
				$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
				//$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : null;
				$roles = isset($_POST['roles']) ? $_POST['roles'] : null;
				$dia = isset($_POST['dia']) ? $_POST['dia'] : null;
				$hora = isset($_POST['hora']) ? $_POST['hora'] : null;
				$firma = isset($_POST['firma']) ? $_POST['firma'] : null;
	

                //-----------------------------------------------------
                // Validaciones
                //-----------------------------------------------------

                if (!validar_requerido($usuario)) {
                    $errores["usuario"] = 'El campo usuario es obligatorio.';
                }
                if (!validar_requerido($pass)) {
                    $errores["pass"] = 'El campo de la contraseña es obligatorio.';
                }
               	if (!validar_requerido($pass2)) {
                    $errores["pass2"] = 'El campo de la contraseña es obligatorio.';
                }
                if ($pass != $pass2) {
                	$errores["pass3"] = 'Las contraseñas deben coincidir.';
                }
                if (!validar_requerido($nombre)) {
                    $errores["nombre"] = 'El campo nombre es obligatorio.';
                }
                /*
                if (!validar_requerido($usuario)) {
                    $errores["usuario"] = 'El campo usuario es obligatorio.';
                }*/
                if (!validar_requerido($roles)) {
                    $errores["roles"] = 'Debe seleccionar un rol.';
                }
                if (!validar_requerido($dia)) {
                    $errores["dia"] = 'Debe seleccionar un dia.';
                }
                if (!validar_requerido($hora)) {
                    $errores["hora"] = 'Debe seleccionar una hora.';
                }
                if (!validar_requerido($firma)) {
                    $errores["firma"] = 'Debe completar el campo firma.';
                }
                /*
                $query="SELECT usuario FROM `fil01seg` where usuario='$usuario'";
			    
			    if ($sql = mysqli_query($con,$query)) {
			    	if ($sql->num_rows == 1) {
			    		
			    			$errores["usuario2"] = "El usuario ya existe.";
			    		}
			    		
			    	
			    }else{
			    	$errores["rol"] = "Ocurrio un error inesperado";
			    	error_log("Error al validar el alta rol, linea 733");
			    }*/

			 
                return $errores;

		}

function _validacion_alta_entidades($con){


				$errores = array();
               	//$razon = isset($_POST['razon']) ? $_POST['razon'] : null;
				/*$domicilio = isset($_POST['domicilio']) ? $_POST['domicilio'] : null;
				$localidad = isset($_POST['localidad']) ? $_POST['localidad'] : null;
				$cuit = isset($_POST['cuit']) ? $_POST['cuit'] : null;
				$telefono = isset($_POST['telefono']) ? $_POST['telefono'] : null;
				$mail = isset($_POST['mail']) ? $_POST['mail'] : null;*/
				
				 /*if (!validar_requerido($razon)) {
                    $errores["razon"] = 'El campo razon es obligatorio.';
                }
                /*if (!validar_requerido($domicilio)) {
                    $errores["domicilio"] = 'El campo de domicilio es obligatorio.';
                }
               	if (!validar_requerido($localidad)) {
                    $errores["localidad"] = 'El campo de localidad es obligatorio.';
                }
                if (!validar_requerido($cuit)) {
                	$errores["cuit"] = 'El campo cuit es obligatorio.';
                }
                if (!validar_requerido($telefono)) {
                    $errores["telefono"] = 'El campo telefono es obligatorio.';
                }
                if (!validar_email($mail)) {
                    $errores["mail"] = 'El campo mail es obligatorio.';
                }*/

                return $errores;

}


function _validarform_respondermail($con){




				$errores = array();
               	$cuerpo = isset($_POST['cuerpo']) ? $_POST['cuerpo'] : null;
				//$receptor = isset($_POST['mail']) ? $_POST['mail'] : null;
				$nombreticket = isset($_POST['nombre_ticket']) ? $_POST['nombre_ticket'] : null;
				$etiquetas = isset($_POST['etiquetas']) ? $_POST['etiquetas'] : null;
				
				
				 if (!validar_requerido($cuerpo)) {
                    $errores["cuerpo"] = 'El campo cuerpo es obligatorio.';
                }
                /*if (!validar_email($receptor)) {
                    $errores["receptor"] = 'El mail del receptor no es un mail correcto.';
                }
                */
                if (!validar_requerido($nombreticket)) {
                	if (!validar_requerido($etiquetas) || $etiquetas == 0) {
                	$errores["etiquetas"] = 'El campo etiquetas es obligatorio.';
                	}
                   
                }
        

                return $errores;

}

function validar_grupo_contacto(){

	$errores = array();
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;

    if (!validar_requerido($nombre)) {
        $errores["nombre"] = 'El campo nombre es obligatorio.';
    }

	return $errores;
}

function eliminar_tildes($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    $cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}

function cargararr($pnombre_proceso/*,$pnro_usulogueado,$pnro_ticket,$ptipo_ticket/*,$pid_mail*/){
	
	//switch($pnombre_proceso){ 

		/*case ($pnombre_proceso = "Ingreso x Mail" AND $pnro_usulogueado="0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil01mail de id_mail, fecha, emisor, asunto, adjunto PARA estado=null
		break;
		case ($pnombre_proceso = "Papelera" AND $pnro_usulogueado="0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"): 
			//Select en fil01mail de id_mail, fecha, emisor, asunto, adjunto PARA estado=”E”
		break;*/
		//case "Archivados":
		if($pnombre_proceso == "Archivados"){
			include("connect.php");
			$sqlas = mysqli_query($con, "SELECT `destinatario`, `emisor`, `cc`, `asunto`, `cuerpo`, `fecha`, `adjunto` 
										FROM `fil01mail` 
										WHERE `nro_ticket`='00000026' and `tipo_ticket`='E'");
			while ($reas= mysqli_fetch_array($sqlas)) {
	 			$arrelo["data"][]	= $reas;	
			}
			//echo $arrelo;
			return $arrelo;
		//break;		
		/*case ($pnombre_proceso = "Ticket sin Acción" AND $pnro_usulogueado>"0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil03mail.nro_ticket, fil01mail.fecha, fil01mail.emisor, fil01mail.asunto (sustraer [TICKET….] o [INTERNO…] si lo tiene), fil01mail.adjunto  
		//PARA fil03mail.usuarioasig = pnro_usulogueado AND max(fil03mail.id_mail) AND (estado=”D” OR estado=”C”) AND (min(fil01mail.id_mail) DE fil01mail.tipo_ticket=fil03mail.tipo_ticket AND fil01mail.nro_ticket=fil03mail.nro_ticket AND)
		break;
		case ($pnombre_proceso = "Ticket en Espera" AND $pnro_usulogueado>"0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil03mail.nro_ticket, fil01mail.fecha, fil01mail.emisor, fil01mail.asunto (sustraer [TICKET….] o [INTERNO…] si lo tiene), fil01mail.adjunto  
		//PARA fil03mail.usuarioasig = pnro_usulogueado AND max(fil03mail.id_mail) AND estado=”R” AND (min(fil01mail.id_mail) DE fil01mail.tipo_ticket=fil03mail.tipo_ticket AND fil01mail.nro_ticket=fil03mail.nro_ticket)
		break;
		case ($pnombre_proceso = "Ticket Finalizado" AND $pnro_usulogueado>"0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil03mail.nro_ticket, fil01mail.fecha, fil01mail.emisor, fil01mail.asunto (sustraer [TICKET….] o [INTERNO…] si lo tiene), fil01mail.adjunto  
		//PARA fil03mail.usuarioasig = pnro_usulogueado AND max(fil03mail.id_mail) AND estado=”F” AND (min(fil01mail.id_mail) DE fil01mail.tipo_ticket=fil03mail.tipo_ticket AND fil01mail.nro_ticket=fil03mail.nro_ticket)
		break;
		case ($pnombre_proceso = "Ticket Derivados" AND $pnro_usulogueado>"0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil03mail.nro_ticket, fil01mail.fecha, fil01mail.asunto (sustraer [TICKET….] o [INTERNO…] si lo tiene), fil03mail.usuarioasig,  fil03mail.estado (en proceso, en espera….)
		//PARA fil03mail.quienderi = pnro_usulogueado AND max(fil03mail.id_mail para obtener el estado) AND (min(fil01mail.id_mail) DE fil01mail.tipo_ticket=fil03mail.tipo_ticket AND fil01mail.nro_ticket=fil03mail.nro_ticket)
		break;
		case ($pnombre_proceso = "Botón Ver Historial de Ing.xMail" AND $pnro_usulogueado="0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail>"0"):
			//Select en fil01mail PARA fil01mail.id_mail = pid_mail y fil01adj PARA fil01adj.id_mail = pid_mail
		break;
		case ($pnombre_proceso = "Botón Ver Historial resto Tablas" AND $pnro_usulogueado="0" AND $pnro_ticket>"0" AND ($ptipo_ticket="E" OR $ptipo_ticket="I") AND $pid_mail="0"):
			//Select en fil03mail (completo) PARA fil03mail.nro_ticket = pnro_ticket AND fil03mail.tipo_ticket = ptipo_ticket 
			//y fil01mail (completo) PARA fil01mail.nro_ticket = pnro_ticket AND fil01mail.tipo_ticket = ptipo_ticket 
			//y fil01adj PARA fil01adj.nro_ticket = pnro_ticket AND fil01adj.tipo_ticket = ptipo_ticket
		break;
		case ($pnombre_proceso = "Botón UNIR de Ing.xMail" AND $pnro_usulogueado="0" AND $pnro_ticket="0" AND $ptipo_ticket="" AND $pid_mail="0"):
			//Select en fil01mail.nro_ticket, fil01mail.asunto, fil01mail.tipo_ticket PARA fil01mail.nro_ticket > 0
		break;*/
		/*return $sql;*/
		/*default:
        		$errores='Ocurrio un error inesperado';
        		
        		return $errores;
        		break;*/
	}
}
?>