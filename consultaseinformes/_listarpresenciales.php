<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$caracteres 	= 50;

$query= "SELECT f03.id,f01.`nro_ticket`,f01.`asunto`,f03.`estado`,ent.razon,fpar.pardesc,f03.ing_env,f01.fecha,f01.`cod_entidad`,f03.tipo_ticket,f01.`destinatario`,f01.`emisor`
    FROM `fil01mail` f01 
    inner join fil03mail f03 on f03.nro_ticket = f01.nro_ticket 
    inner join (SELECT max(fechaasig)fecha,nro_ticket,tipo_ticket 
                from fil03mail 
                GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = f03.fechaasig and tt.nro_ticket = f03.nro_ticket and tt.tipo_ticket= f03.tipo_ticket 
    left join fil01ent ent on ent.codigo = f01.cod_entidad 
    left join fil00par fpar on (fpar.parcod=7) and (fpar.parvalor = f03.tipo_docum) 
    WHERE f01.tipo_ingreso='P' and f03.tipo_ticket ='E'";

if ($sql = mysqli_query($con,$query)) {
	if( $sql->num_rows > 0 ){	 
		$arreglo["data"]= array();
		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]= $re;
		}
		$num = mysqli_num_rows($sql);
		$ticket = $arreglo["data"];
		//Acomoda el asunto y lo corta a 50 caracteres
		for ($i=0; $i < $num ; $i++) { 

			$estadoticket = $ticket[$i]["estado"];
            switch ($estadoticket) {
                case 'D':
                	$arreglo["data"][$i]["estado"] = "En proceso";
                	$arreglo["data"][$i][3] = "En proceso";
                	break;
                case 'C':
                	$arreglo["data"][$i]["estado"] = "En proceso";
                	$arreglo["data"][$i][3] = "En proceso";
                	break;
                case 'R':
                	$arreglo["data"][$i]["estado"] = "En espera";
                	$arreglo["data"][$i][3] = "En espera";
                	break;
                case 'F':
                	$arreglo["data"][$i]["estado"] = "Finalizado";
                	$arreglo["data"][$i][3] = "Finalizado";
                	break;
                case '':
                	$arreglo["data"][$i]["estado"] = "En espera";
                	$arreglo["data"][$i][3] = "En espera";
                	break;
                }
                $arreglo["data"][$i][7] = date("Y/m/d", strtotime($arreglo["data"][$i]["fecha"]));
                $ssql = mysqli_query($con, "SELECT f03.`nro_mesaent`
                        FROM `fil03mail` f03           
                        WHERE (f03.nro_ticket='".$arreglo["data"][$i]["nro_ticket"]."') and (f03.tipo_ticket = '".$arreglo["data"][$i]["tipo_ticket"]."') 
                        ORDER BY f03.fechaasig DESC");
                while ($rrow= mysqli_fetch_array($ssql)) {
                    $arreglo["dd"][$i][] = $rrow;
                }
                $nnum = mysqli_num_rows($ssql);
                
               for ($j=0; $j < $nnum; $j++) {   
                   $arreglo["data"][$i][12] = $arreglo["dd"][$i][$j][0].",".$arreglo["data"][$i][12];
                   $arreglo["data"][$i]['nro_mesaent'] = $arreglo["dd"][$i][$j][0].",".$arreglo["data"][$i]['nro_mesaent'];
                }

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