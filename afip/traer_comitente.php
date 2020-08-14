<?php
require("wsproducciona13.php");
		
		
		
		$persona = array();
		
		$persona["cuitvalido"] = false;
		$persona = getPersona($_POST['campocuit']);
		$persona["success"] = true;
		$persona["ws"] = true;
		$persona["cantidad"] = 1;
		
		/******DESPUES ELIMINAR LO DE CUIT VALIDO Y DESCOMENTAR EL GETPERSONA Y EL REQUIRE******/
		//$persona["cuitvalido"] = false;
		
		echo json_encode($persona);
?>