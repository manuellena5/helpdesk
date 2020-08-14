<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$caracteres = 25;
$arreglo = array(); 

$query0 = "SELECT m3.id,
                  m3.nro_ticket,
                  if(m1.emisor='',f01ent.razon,m1.emisor)emisor,
                  m1.asunto,
                  m1.fecha,
                  m3.fechaasig,
                  m3.estado,
                  m3.`usuarioasig`,
                  m3.rolasig,
                  m1.categoria,
                  m1.tipo_ingreso,
                  m3.id_mail,
                  m3.quienresp,
                  m3.tipo_ticket
    FROM `fil03mail` m3
    inner join fil01mail m1 on m3.id_mail = m1.id_mail 
    inner join (SELECT max(fechaasig)fecha,nro_ticket,tipo_ticket
                    from fil03mail 
                    GROUP by nro_ticket,tipo_ticket)as tt on tt.fecha = m3.fechaasig and tt.nro_ticket = m3.nro_ticket and tt.tipo_ticket= m3.tipo_ticket
    left join fil01ent f01ent on f01ent.codigo = m1.cod_entidad
    ORDER BY m3.fechaasig DESC";

/*$query0 = "SELECT m3.id,
                  m3.nro_ticket,
                  if(m1.emisor='',f01ent.razon,m1.emisor)emisor,
                  m1.asunto,
                  m1.fecha,
                  m3.fechaasig,
                  m3.estado,
                  m3.`usuarioasig`,
                  m3.rolasig,
                  m1.categoria,
                  m1.tipo_ingreso,
                  m3.id_mail,
                  m3.quienresp,
                  m3.tipo_ticket
    FROM `fil03mail` m3
    inner join fil01mail m1 on m3.id_mail = m1.id_mail 
    left join fil01ent f01ent on f01ent.codigo = m1.cod_entidad
    ORDER BY m1.fecha DESC";

*/
if ($sql = mysqli_query($con,$query0)) {
	if( $sql->num_rows > 0 ){   
        $num = mysqli_num_rows($sql);
		while ($row= mysqli_fetch_array($sql)) {
			$arreglo["data"][] = $row;
		}
		$num = mysqli_num_rows($sql);
		$ticket = $arreglo["data"];
		//Acomoda el asunto y lo corta a 50 caracteres
		for ($i=0; $i < $num ; $i++) { 
            /*$arreglo["data"][$i]["asunto"]  = mb_convert_encoding($arreglo["data"][$i]["asunto"], 'UTF-8', 'UTF-8');
            $arreglo["data"][$i][3]         = mb_convert_encoding($arreglo["data"][$i][3], 'UTF-8', 'UTF-8');*/

			$cadena = $arreglo["data"][$i]["asunto"];
            $cadena2 = $arreglo["data"][$i]["emisor"];
			if (strlen($cadena) > 20){
				$arreglo["data"][$i][3] = substr($cadena, 0, $caracteres).'...';
			}else{
				$arreglo["data"][$i][3] = $cadena;
			}
            if (strlen($cadena2) > 20){
                $arreglo["data"][$i][2] = substr($cadena2, 0, $caracteres).'...';
            }else{
                $arreglo["data"][$i][2] = $cadena2;
            }
			$arreglo["data"][$i]["asunto"] =$cadena;
            $arreglo["data"][$i]["emisor"] =$cadena2;
			//Acomoda el formato de fecha
            $arreglo["data"][$i]["fecha_formato"] = date("Y/m/d", strtotime($arreglo["data"][$i]["fecha"]));
			$arreglo["data"][$i][14] = date("Y/m/d", strtotime($arreglo["data"][$i]["fecha"]));
            $arreglo["data"][$i][4] = date("d/m/y", strtotime($arreglo["data"][$i]["fecha"]));
            $arreglo["data"][$i][5] = date("d/m/y", strtotime($arreglo["data"][$i]["fechaasig"]));
			if ($ticket[$i]["usuarioasig"]!=null && $ticket[$i]["usuarioasig"]!=0) {  
                $nrousu=$ticket[$i]["usuarioasig"];
                $query2 = "SELECT usuario FROM fil01seg WHERE nrousuario = '$nrousu'";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"][$i]["usuarioasig"]=$row2['usuario'];
                        $arreglo["data"][$i][7]=$row2['usuario'];
                    }
                }
                $sql2->close();
            } 
            if ($ticket[$i]["rolasig"]!=null && $ticket[$i]["rolasig"]!=0) {
                $nrorol=$ticket[$i]["rolasig"];
                $query2 = "SELECT pardesc FROM fil00par WHERE parvalor = '$nrorol' and parcod='1' "; 
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"][$i]["rolasig"]=$row2['pardesc'];
                        $arreglo["data"][$i][8]=$row2['pardesc'];
                    }
                }
                $sql2->close();
            }elseif ($ticket[$i]["rolasig"]==0) {
                $arreglo["data"][$i]["rolasig"]="";
                $arreglo["data"][$i][8]="";
            }	
            if ($ticket[$i]["quienresp"] != null && $ticket[$i]["quienresp"] != 0) {        
                $nrousu=$ticket[$i]["quienresp"];
                $query2 = "SELECT usuario FROM fil01seg WHERE nrousuario = '$nrousu'";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"][$i]["quienresp"]=$row2['usuario'];
                        $arreglo["data"][$i][12]=$row2['usuario'];
                    }
                } 
                $sql2->close();
            }
            if ($ticket[$i]["categoria"]!=null && $ticket[$i]["categoria"]!=0) {  
                $etiquetaPpal=$ticket[$i]["categoria"];
                $query2 = "SELECT pardesc FROM fil00par WHERE parvalor = '$etiquetaPpal' and parcod='5' ";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"][$i]["categoria"]=$row2['pardesc'];
                        $arreglo["data"][$i][9]=$row2['pardesc'];
                    }
                }
                $sql2->close();
            }
            $estadoticket = $ticket[$i]["estado"];
            switch ($estadoticket) {
                case 'D':
                	$arreglo["data"][$i]["estado"] = "En proceso";
                	$arreglo["data"][$i][6] = "En proceso";
                	break;
                case 'C':
                	$arreglo["data"][$i]["estado"] = "En proceso";
                	$arreglo["data"][$i][6] = "En proceso";
                	break;
                case 'R':
                	$arreglo["data"][$i]["estado"] = "En espera";
                	$arreglo["data"][$i][6] = "En espera";
                	$arreglo["data"][$i]["usuarioasig"] = $arreglo["data"][$i]["quienresp"];
                	$arreglo["data"][$i][7] = $arreglo["data"][$i]["quienresp"];
                	break;
                case 'F':
                	$arreglo["data"][$i]["estado"] = "Finalizado";
                	$arreglo["data"][$i][6] = "Finalizado";
                	$arreglo["data"][$i]["usuarioasig"] = $arreglo["data"][$i]["quienresp"];
                	$arreglo["data"][$i][7] = $arreglo["data"][$i]["quienresp"];
                	break;
                case '':
                	$arreglo["data"][$i]["estado"] = "En espera";
                	$arreglo["data"][$i][6] = "En espera";
                	break;
                }
/*IF(m3.estado='D','En proceso','') m3.estado
                if(m3.estado='C','En proceso',if(m3.estado='R','En espera',if(m3.estado='F','Finalizado','En espera')))*/
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
echo errorjson(json_last_error());
}
mysqli_close($con);
?> 