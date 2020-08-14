<?php 
session_start();
include("connect.php");
include("funciones.php");
salir();

//$query= "SELECT mail,nombre,mail,telef_fijo,telef_movil,codigo FROM `fil01lib`";

$query0 = "SET SESSION group_concat_max_len = 10000 ";
mysqli_query($con,$query0);

$query=" SELECT IF(tt2.concat is null,flib.mail,tt2.concat)mail,flib.nombre,IF(tt2.concat is null,flib.mail,null)mail,flib.telef_fijo,flib.telef_movil,titulo,empresa,profesion,flib.codigo
FROM fil01lib flib
left join (
            SELECT tt.nombre,GROUP_CONCAT(f01.mail SEPARATOR ';') concat,tt.codigo
            FROM `fil01lib` f01
            inner join (SELECT f03.cod_mail,f03.cod_grupo,f01.nombre,f01.codigo
                       from fil01lib f01
                       inner join fil03lib f03 on f03.cod_grupo = f01.codigo
                        ) as tt on tt.cod_mail = f01.codigo
            group by tt.cod_grupo
			) as tt2 on tt2.codigo = flib.codigo";

if ($sql = mysqli_query($con,$query)) {

	if( $sql->num_rows > 0 ){
		 
		 $arreglo["data"]= array();

		while ($re= mysqli_fetch_array($sql)) {
			$arreglo["data"][]= $re;
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