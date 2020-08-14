<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$nro_ticket         = $_GET['nro'];
$idticket           = $_GET['idticket'];
$tipo_ticket        = $_GET['tipo_ticket'];
$nrousuario         = $_SESSION["nrousuario"];
$grupo              = $_SESSION['grupo'];
$rol                = $_SESSION['rol'];
$nombre_pagina      = isset($_REQUEST['nombre_pagina']) ? $_REQUEST['nombre_pagina']:"";
$arreglo            = array();

//error_log("nombre_pagina".$_GET['nombre_tabla']);

$query ="SELECT f01.id_mail,f01.nombre_ticket,f01.adjunto,f01.emisor,f01.destinatario,f01.asunto,f01.cuerpo,f01.fecha,f01.estado estadomail,f03.`quienderi`,f03.`usuarioasig`,f03.`rolasig`,f03.`fechaasig`,f03.`observacion`,f03.`quienresp`,f03.`respuesta`,f03.`estado` estadoticket,f03.nro_ticket,if(f03.adjunto is null,'N',f03.adjunto) adjticket,f03.leido,f01.cc,f01.cco,f03.`nro_mesaent`,f01.cod_entidad,f01ent.razon,f03.ing_env
FROM `fil03mail` f03           
inner join fil01mail f01 on f01.id_mail = f03.id_mail 
LEFT join fil01ent f01ent on f01ent.codigo = f01.cod_entidad
WHERE (f03.nro_ticket='$nro_ticket') and (f03.tipo_ticket = '$tipo_ticket') 
ORDER BY f03.fechaasig DESC";

if ($sql = mysqli_query($con,$query)) {    
    if( $sql->num_rows > 0 ){
        $arreglo["success"] = true;
        
        $arreglo["data"]["ticket"] = array();
        $numfilas = $sql->num_rows;
         $arreglo["cant_ticket"] = $numfilas;
        while( $row = mysqli_fetch_assoc($sql) ) {
            $arreglo["data"]["ticket"][] = $row;
        }
        $num = mysqli_num_rows($sql);

        $my = mysqli_query($con, "SELECT * FROM fil01adj WHERE nro_ticket='$nro_ticket' and tipo_ticket = '$tipo_ticket'");
                $cant = mysqli_num_rows($my);
                $arreglo["data"]["cantarchivos"] = $cant;
                $arreglo["data"]["archivo"] = array();
        for ($j=1; $j <= $cant; $j++) { 
            
                    $res = mysqli_fetch_array($my);
                    $arreglo["data"]["archivo"][$j]["nombre"] = substr($res['nombre'],12); 
                    $arreglo["data"]["archivo"][$j]["ruta"] = $res['ruta']; 
                    //$arreglo["data"]["ticket"]["cant"]=$j;
        }

        
        for ($i=0; $i < $num ; $i++) { 
            $arreglo["data"]["ticket"][$i]["fecha"] = date("d-m-Y H:i:s", strtotime($arreglo["data"]["ticket"][$i]["fecha"]));
            $arreglo["data"]["ticket"][$i]["fechaasig"] = date("d-m-Y H:i:s", strtotime($arreglo["data"]["ticket"][$i]["fechaasig"]));
            $arreglo["data"]["ticket"][$i]["cuerpo"]  = mb_convert_encoding($arreglo["data"]["ticket"][$i]["cuerpo"], 'UTF-8', 'UTF-8');
            $arreglo["data"]["ticket"][$i][6]         = mb_convert_encoding($arreglo["data"]["ticket"][$i][6], 'UTF-8', 'UTF-8');
            /*if($arreglo["data"]["ticket"][$i]["adjunto"] == "S" or $arreglo["data"]["ticket"][$i]["adjticket"] == "S"){
                $id_mail = $arreglo["data"]["ticket"][$i]["id_mail"];
                
            }*/
            
        //}
        //$sql->close();
        $ticket = $arreglo["data"]["ticket"]; 
        //for ($i=0; $i < $numfilas; $i++) {     
            if ($ticket[$i]["usuarioasig"]!=null && $ticket[$i]["usuarioasig"]!=0) {       
                $nrousu=$ticket[$i]["usuarioasig"];
                $query2 = "SELECT usuario FROM fil01seg WHERE nrousuario = '$nrousu'";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                            $arreglo["data"]["ticket"][$i]["usuarioasig"]=$row2['usuario'];
                    }
                }
                $sql2->close();
            }
            if ($ticket[$i]["quienderi"] != null && $ticket[$i]["quienderi"] != 0) { 
                $nrousu=$ticket[$i]["quienderi"];
                $query2 = "SELECT usuario FROM fil01seg WHERE nrousuario = '$nrousu'";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"]["ticket"][$i]["quienderi"]=$row2['usuario'];
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
                        $arreglo["data"]["ticket"][$i]["rolasig"]=$row2['pardesc'];
                    }
                }
                $sql2->close();
            }
            if ($ticket[$i]["quienresp"]!=null && $ticket[$i]["quienresp"]!=0) {    
                $nrousu=$ticket[$i]["quienresp"];
                $query2 = "SELECT usuario FROM fil01seg WHERE nrousuario = '$nrousu'";
                if ($sql2 = mysqli_query($con,$query2)) {
                    if ($sql2->num_rows >0) {
                        $row2 =  mysqli_fetch_array($sql2);
                        $arreglo["data"]["ticket"][$i]["quienresp"]=$row2['usuario'];
                    }
                }
                $sql2->close();
            }

            
        }

        

        

        if($idticket != 0){
            $query0 = "SELECT leido,id_mail from fil03mail where id = '$idticket'";
             if ($sql2 = mysqli_query($con,$query0)) {
                $resultado = mysqli_fetch_array($sql2);
            }
            
            if (($resultado["leido"] == 3) || ($resultado["leido"] == 2)) {
                $query1         = "UPDATE fil03mail set leido = 3 where id='$idticket'";
            }else{
                $query1         = "UPDATE fil03mail set leido = 1 where id='$idticket' ";  
            }
            $sql2->close();
            
             $resultUpdate   = mysqli_query($con,$query1);
            $msjconsola     = "Error al actualizar en fil03mail como leido. Linea 135";
            $msjusuario     = "Error al intentar mostrar los datos.";
            resultInsert($con,$resultUpdate,$msjconsola,$msjusuario);

        }   

        
        
    }else{
        $arreglo["success"] = false;
        $arreglo["data"] = array(
            'mensaje' => 'No se encontró ningún resultado.'
        );
    }
}else{
    $arreglo["success"] = false;
    $arreglo["data"] = array(
        'mensaje' => $con->error
    );
}
header('Content-type: application/json; charset=utf-8');
echo json_encode($arreglo, JSON_FORCE_OBJECT); 
mysqli_close($con);
?>