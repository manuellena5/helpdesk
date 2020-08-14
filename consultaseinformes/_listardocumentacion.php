<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();

$caracteres 	= 50;

if(isset($_GET['valor'])){
$tipo_ingreso =$_GET['valor'];


    $query= "SELECT if(f03.nro_mesaent=0,f03.nro_mesaent,f03.nro_mesaent)nro_mesaent,f01.fecha,ent.razon Remitente,fseg.usuario 'Recibido Por',
f03.ing_env 'Tipo Ingreso',f01.fecha
FROM `fil01mail` f01
inner join (
            SELECT MIN(fecha)fecha,id_mail,nro_ticket 
            FROM fil01mail
            where tipo_ingreso ='P'
            group by id_mail,nro_ticket
            )as tt on tt.id_mail = f01.id_mail and tt.fecha = f01.fecha
inner join fil03mail f03 on f03.nro_ticket = f01.nro_ticket
left join fil01seg fseg on fseg.nrousuario = f03.usuarioasig
left join fil01ent ent on ent.codigo = f01.cod_entidad 
WHERE f03.tipo_ticket = 'E' and f01.tipo_ingreso='P' and f03.ing_env = '$tipo_ingreso' 

group by f03.nro_ticket";
}

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

			
                $arreglo["data"][$i][5] = date("Y/m/d", strtotime($arreglo["data"][$i]["fecha"]));
                $arreglo["data"][$i][1] = date("d/m/Y", strtotime($arreglo["data"][$i]["fecha"]));


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