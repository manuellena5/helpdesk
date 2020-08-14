<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$caracteres 	= 25;
$nrousuario		= $_SESSION["nrousuario"];
$grupo			= $_SESSION['grupo'];
$rol 			= $_SESSION['rol'];

$query= "SELECT DISTINCT m3.nro_ticket,m1.emisor,m1.asunto,m3.fechaasig,IF(fadj.id_file is not null,'S','N')adjunto,m3.id_mail,m1.cuerpo,m3.observacion,m3.estado,m3.id,m3.leido,m3.tipo_ticket,f01ent.razon,m1.cod_entidad,m1.destinatario
FROM `fil03mail` m3
inner join fil01mail m1 on m3.id_mail = m1.id_mail 
inner join (SELECT max(fechaasig)fecha,nro_ticket,tipo_ticket
                    from fil03mail 
                    GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = m3.fechaasig and tt.nro_ticket = m3.nro_ticket and tt.tipo_ticket= m3.tipo_ticket
left join fil01adj fadj on fadj.nro_ticket = m3.nro_ticket and fadj.tipo_ticket = m3.tipo_ticket 
LEFT join fil01ent f01ent on f01ent.codigo = m1.cod_entidad
WHERE (m3.`usuarioasig`='$nrousuario' or m3.`rolasig` = '$rol') and (m3.estado='C' or m3.estado='D')
ORDER BY m3.fechaasig DESC";


if ($sql = mysqli_query($con,$query)) {
	if( $sql->num_rows > 0 ){	 
		$arreglo["data"]= array();
		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]= $re;
		}
		$num = mysqli_num_rows($sql);

		for ($i=0; $i < $num ; $i++) { 	
			$arreglo["data"][$i]["cuerpo"]	= mb_convert_encoding($arreglo["data"][$i]["cuerpo"], 'UTF-8', 'UTF-8');
			$arreglo["data"][$i][6] 		= mb_convert_encoding($arreglo["data"][$i][6], 'UTF-8', 'UTF-8');

			if(($arreglo["data"][$i]["cod_entidad"] != 0) && ($arreglo["data"][$i]["cod_entidad"] != null) && ($arreglo["data"][$i]["cod_entidad"] != "")){
				$arreglo["data"][$i]["emisor"] = $arreglo["data"][$i]["razon"];
				$arreglo["data"][$i][1] = $arreglo["data"][$i]["razon"];
			}

			$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 25){
				$arreglo["data"][$i][2] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][2] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] =$cadena;
			$adjunto = $arreglo["data"][$i]["adjunto"];
			if ($adjunto == "") {
				$arreglo["data"][$i]["adjunto"] = "N";
				$arreglo["data"][$i][4] = "N";
			}
			$arreglo["data"][$i]["3"] = date("d-m-y", strtotime($arreglo["data"][$i]["3"]));
			$arreglo["data"][$i]["fechaasig"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fechaasig"]));
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
if(json_last_error() != 0){
echo errorjson(json_last_error(),$arreglo);
}

mysqli_close($con);
?>