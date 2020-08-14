<?php
session_start();
include("../connect.php"); 
include("../funciones.php");
salir();
$nro_ticket = $_GET['nro_ticket'];
$tipo_ticket = $_GET['tipo_ticket'];

$query="SELECT f01.`id_mail`,f03.id,f01.nro_ticket,f03.tipo_ticket,f01.cuerpo,f01.asunto,f01.cod_entidad,ent.razon,f01.categoria,fcateg.pardesc,f03.nro_mesaent, 
    f01.fecha,f03.tipo_docum,fdocum.pardesc documento
    FROM `fil01mail` f01
    inner join fil03mail f03 on f01.id_mail = f03.id_mail
    left join fil01ent ent on ent.codigo = f01.cod_entidad 
    left join fil00par fcateg on (fcateg.parcod=5) and (fcateg.parvalor = f01.categoria) 
    left join fil00par fdocum on (fdocum.parcod=7) and (fdocum.parvalor = f03.tipo_docum) 
    WHERE f01.nro_ticket = '$nro_ticket' and f03.tipo_ticket ='$tipo_ticket'
    order by f01.fecha ASC
    limit 1";


$sql = mysqli_query($con ,$query);
$re = mysqli_fetch_array($sql);

/*"SELECT f03.`id`,f01.`asunto`,f03.`observacion`,f01.`emisor`,f01.`cuerpo`,f01.`cod_entidad`,if(f01.`cod_entidad` = '0','Seleccione Entidades',ent.`razon`)razon,f03.`tipo_docum`,if(f03.`tipo_docum` = '0','Seleccione Tipo Documento',fpar.`pardesc`)documento,f03.`usuarioasig`,if(f03.`usuarioasig` = '0','Seleccione Usuarios',fseg.`nombre`)usuario,f03.`rolasig`,IF(f03.`rolasig` = '0','Seleccione Rol',fparrol.`pardesc`)rol,fparcat.`parvalor`,fparcat.`pardesc`,f01.`nro_ticket`,f03.`tipo_ticket`
  FROM `fil01mail` f01 
  inner join fil03mail f03 on f03.`id_mail` = f01.`id_mail` 
  left join fil01ent ent on ent.`codigo` = f01.`cod_entidad` 
  left join fil00par fpar on (fpar.`parcod`=7) and (fpar.`parvalor` = f03.`tipo_docum`) 
  left join fil00par fparrol on (fparrol.`parcod`=1) and (fparrol.`parvalor` = f03.`rolasig`) 
  left join fil01seg fseg on f03.`usuarioasig` = fseg.`nrousuario`
  left join fil00par fparcat on (fparcat.`parcod`=5) and (fparcat.`parvalor` = f01.`categoria`)
  
  WHERE f01.`tipo_ingreso` ='P' and f03.`id` ='$id'"*/
?>
<div>
	<h4>Ingresos Varios editar</h4>
  <div id="jGrowl-container1" class="bottom-left vOculto"></div>
  <form  enctype='multipart/form-data' method='post' id='formregistro' name="formregistro">
  	<div class='form-group'>
     		<input type='text' name='id_mail' id="id_mail" value="<?php echo $re['id_mail']; ?>" hidden='hidden'>
        <input type='text' name='nro_ticket' id="nro_ticket" value="<?php echo $re['nro_ticket']; ?>" hidden='hidden'>
        <input type='text' name='tipo_ticket' id="tipo_ticket" value="<?php echo $re['tipo_ticket']; ?>" hidden='hidden'>
        <label>Titulo*:</label>
        <input type='test' class='form-control' id='titulo' name='titulo' value="<?php echo $re['asunto']; ?>"><br>
        <div class="container">
          <div class="row">
            <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='entidades' name='entidades'>
                <option value="<?php echo $re['cod_entidad']  ?>"><?php echo $re['razon'] ?></option>
                  <?php
                  $users = mysqli_query($con, "SELECT `codigo`,`razon` FROM `fil01ent` where razon!='".$re['razon']."'");
                  while($ruser = mysqli_fetch_array($users)){
                    echo "<option value='".$ruser['codigo']."'>".$ruser['razon']."</option>";
                  }
                  ?>
              </select>
            </div><br>
            <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='documento' name='documento'>
                <option value="<?php echo $re['tipo_docum']; ?>"><?php echo $re['documento']; ?></option>
                  <?php
                  $rols = mysqli_query($con, "SELECT `parvalor`,`pardesc` FROM `fil00par` WHERE `parcod`=7 and pardesc!='".$re['documento']."'");
                  while($rrol = mysqli_fetch_array($rols)){
                    echo "<option value='".$rrol['parvalor']."'>".$rrol['pardesc']."</option>";
                  }
                  ?>
              </select>
            </div>
          </div>
        </div><br>
        <label>Mensaje*:</label><br>
        <textarea class='form-control' id='mensaje' name='mensaje' ><?php echo $re['cuerpo'] ; ?></textarea><br>
        <br>
        <div class="container sinpaddingleftright">
          <div class="row">
            <div class="col-5"> 
              
               <select class='form-control' id='etiquetas' name='etiquetas'>
                
                <option value="<?php echo $re['categoria']; ?>"><?php echo $re['pardesc']; ?></option>
                <?php
                  $rol5s = mysqli_query($con, "SELECT `pardesc`,`parvalor` FROM fil00par WHERE `parcod`='5' AND `pardesc`!='".$re['pardesc']."'");
                  while($rro5l = mysqli_fetch_array($rol5s)){
                    echo "<option value='".$rro5l['parvalor']."'>".$rro5l['pardesc']."</option>";
                  }
                  ?>
                </select>

            </div>
            
            <div id="divsecuetiquetas" name="divsecuetiquetas"> 
              <select class="custom-select" multiple id="secuetiquetas" name="secuetiquetas">
               <option value="0" disabled >Seleccion multiple de etiqueta</option>
                <?php
                
                $secti = mysqli_query($con, "SELECT nro_categ FROM `fil03tieti` WHERE `nro_ticket`='".$re['nro_ticket']."' AND `tipo_ticket`='".$re['tipo_ticket']."'");
                $arreglo = array();
                while($sectire = mysqli_fetch_array($secti)){
                  $arreglo[] = $sectire;
                }              
                for ($i=0; $i < $secti->num_rows; $i++) { 
                  $vereti = mysqli_query($con, "SELECT `pardesc`,`parvalor` FROM fil00par WHERE `parcod`='5' AND `parvalor`='".$arreglo[$i]['nro_categ']."'");
                  $rever = mysqli_fetch_array($vereti);
                  echo "<option value='".$rever['parvalor']."'>".$rever['pardesc']."</option>";
                }
                  ?>
              </select>
                           
            </div>

          </div>
        </div>
        <br> 		
  	</div>
     <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small><br>
     <div class="vOculto" id="cargador"></div>
     <button type='button' onclick="funcionclick();"  value='submit' id='envioform' class='btn btn-primary'>Ingresar</button>
     <button type='button' onclick="cargarPagina('consultaseinformes/consultapresencial.php?ver=no&men=&donde=');"  value='submit' id='volver' class='btn btn-primary'>Volver</button>
  </form>
<script>
$(document).ready(function() {
    cargar_select(0);
    
});

$(document).on('change', '#etiquetas', function(event) {

            var valorselect = $("#etiquetas option:selected").val();
            var valorselectsecuetiquetas = $("#secuetiquetas option:selected").val();

            cargar_select(valorselect);

            if ((valorselect == 0) && (valorselectsecuetiquetas == 0)) {

              valor = "no";
              

            }else if((valorselect == 0) && (valorselectsecuetiquetas != 0)){
               
              valor = "no";
              
            
            }else if((valorselect != 0) && (valorselectsecuetiquetas == 0)){

              valor = "si";
              

             }

            //ver_elementoHTML(valor,"divsecuetiquetas");  
  });

function funcionclick(){
var contadorvalor = "";
    $("#secuetiquetas :selected").each(function(){
      if($(this).val() != ""){
        contadorvalor += $(this).val()+";";
      }
    });

  if (validacionform_ingresosvarios()) {
  var id_mail           = document.getElementById("id_mail").value;
  var nro_ticket        = document.getElementById("nro_ticket").value;
  var tipo_ticket       = document.getElementById("tipo_ticket").value;
  var titulo            = document.getElementById("titulo").value;
  var mensaje           = document.getElementById("mensaje").value;

  if(document.getElementById("etiquetas") != null){
      var etiquetas         = document.getElementById("etiquetas").value;
    } else {
      var etiquetas         = "";
    }
  if(document.getElementById("entidades") != ""){
      var entidades         = document.getElementById("entidades").value;
    } else {
      var entidades         = "0";
    }
  if(document.getElementById("documento") != ""){
      var documento         = document.getElementById("documento").value;
    } else {
      var documento         = "0";
    }

  var data = new FormData();
    data.append('id_mail',id_mail);    
    data.append('nro_ticket',nro_ticket);
    data.append('tipo_ticket',tipo_ticket);
    data.append('titulo',titulo);
    data.append('mensaje',mensaje);
    data.append('entidades',entidades);
    data.append('documento',documento);
    data.append('etiquetas',etiquetas);
    data.append('secuetiquetas',contadorvalor);

  $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
  $("#cargador").removeClass("vOculto").addClass("vVisible");

  $.ajax({
    url: "consultaseinformes/_editarpresencial.php",  // Url to which the request is send
    type: "POST",                    // Type of request to be send, called as method
    data: data,                      // Data sent to server, a set of key/value pairs (i.e. form fields and values)
    contentType: false,              // The content type used when sending data to the server.
    cache: false,                    // To unable request pages to be cached
    processData:false,               // To send DOMDocument or non processed data file it is set to false
  })
  .done(function(respuesta) {
    if (respuesta.success) {
      cargarPagina(respuesta.url);
    }else{
      $("#cargador").removeClass("vVisible").addClass("vOculto");
      console.log(respuesta.error.mensaje);
      window.alert(respuesta.error.mensaje);
    }
  })
  .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
    $("#cargador").removeClass("vVisible").addClass("vOculto");
    console.log("Algo ha fallado: " +  textStatus);
    alert(respuesta.error.mensaje);
  })  
  .always(function() {
    $("#cargador").removeClass("vVisible").addClass("vOculto");   
  });
  }
}
</script>