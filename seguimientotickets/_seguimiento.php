<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$caracteres 	= 50;
$nrousuario		= $_SESSION["nrousuario"];
$grupo			= $_SESSION['grupo'];
$rol 			= $_SESSION['rol'];

$query= "SELECT DISTINCT m3.nro_ticket,m1.asunto,m3.fechaasig,m3.estado,IF(fadj.id_file is not null,'S','N')adjunto,m3.id_mail,m1.cuerpo,m3.observacion,m3.tipo_ticket,f01ent.razon,m1.cod_entidad
FROM `fil03mail` m3
inner join fil01mail m1 on m3.id_mail = m1.id_mail 
inner join (SELECT MIN(fechaasig)fecha,nro_ticket,tipo_ticket
                    from fil03mail 
                    GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = m3.fechaasig and tt.nro_ticket = m3.nro_ticket and tt.tipo_ticket= m3.tipo_ticket
left join fil01adj fadj on fadj.nro_ticket = m3.nro_ticket and fadj.tipo_ticket = m3.tipo_ticket 
LEFT join fil01ent f01ent on f01ent.codigo = m1.cod_entidad
WHERE m3.`quienderi`='$nrousuario'
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
			$arreglo["data"][$i][6] 		= mb_convert_encoding($arreglo["data"][$i][5], 'UTF-8', 'UTF-8');
			$cadena = $arreglo["data"][$i]["asunto"];
			if (strlen($cadena) > 50){
				$arreglo["data"][$i][1] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][1] = $cadena;
			}
			$arreglo["data"][$i]["asunto"] =$cadena;
			$adjunto = $arreglo["data"][$i]["adjunto"];
			if ($adjunto == "") {
				$arreglo["data"][$i]["adjunto"] = "N";
				$arreglo["data"][$i][4] = "N";
			}
			$arreglo["data"][$i]["2"] = date("d-m-y", strtotime($arreglo["data"][$i]["2"]));
			$arreglo["data"][$i]["fechaasig"] = date("d-m-Y H:i:s", strtotime($arreglo["data"][$i]["fechaasig"]));
			$buscar = mysqli_query($con, "SELECT leido,nro_ticket,id from fil03mail WHERE nro_ticket='".$arreglo["data"][$i]["nro_ticket"]."' ORDER BY leido DESC LIMIT 1");
			$re2 = mysqli_fetch_array($buscar);
			$arreglo["data"][$i][11] = $re2['id'];
			$arreglo["data"][$i]['id'] = $re2['id'];
			$arreglo["data"][$i][12] = $re2['leido'];
			$arreglo["data"][$i]['leido'] = $re2['leido'];
			$otro = mysqli_query($con, "SELECT estado from fil03mail WHERE nro_ticket='".$arreglo["data"][$i]["nro_ticket"]."' and tipo_ticket='".$arreglo["data"][$i]["tipo_ticket"]."' ORDER BY fechaasig DESC LIMIT 1");
			$re3 = mysqli_fetch_array($otro);
			switch ($re3['estado']) {
                case 'D':
                	$arreglo["data"][$i][3] = "En proceso";
                	break;
                case 'C':
                	$arreglo["data"][$i][3] = "En proceso";
                	break;
                case 'R':
                	$arreglo["data"][$i][3] = "En espera";
                	break;
                case 'F':
                	$arreglo["data"][$i][3] = "Finalizado";
                	break;
                case '':
                	
                	$arreglo["data"][$i][3] = "En espera";
                	break;
                }
			$arreglo["data"][$i]["estado"] = $re3['estado'];
			
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