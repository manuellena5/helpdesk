<?php
session_start();
include ("connect.php");
include("funciones.php");
salir();

//URL para saber de donde se hace la accion.
$url = $_GET['url'];
$men = "El mail se derivo correctamente.";
if($url == "mail"){
  $dire = "mesaentrada/ingresopormail.php?ver=si&men=".$men."&donde=Ingreso por Mail";
  $dire2  = "mesaentrada/ingresopormail.php?ver=no&men=&donde=";
}
if($url == "accion"){
  $dire   = "seguimientotickets/sinaccion.php?ver=si&men=".$men."&donde=Tickets sin accion";
  $dire2  = "seguimientotickets/sinaccion.php?ver=no&men=&donde=";
} 
if($url == "espera"){
  $dire = "seguimientotickets/enespera.php?ver=si&men=".$men."&donde=Tickets en espera";
  $dire2  = "seguimientotickets/enespera.php?ver=no&men=&donde=";
}
if($url == "seguimiento"){
  $dire = "seguimientotickets/seguimiento.php?ver=si&men=".$men."&donde=Tickets en derivados";
  $dire2  = "seguimientotickets/seguimiento.php?ver=no&men=&donde=";
}
if (isset($_GET["id"])) {
  $idticket = $_GET["id"];  
}
?>

<!-- Botones de Acciones -->
<div style="text-align:right">
  <button type="button" class="btn btn-danger" id="derivar" onclick="funcionclick();">Derivar</button>
  <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
</div>

<!-- Formulario para enviar la Respuesta -->
<form id="formregistro" name="formregistro" method="post" accept-charset="utf-8" enctype="multipart/form-data">
  <input type='text' name='numail' id='numail' value="<?php echo $_GET['id_mail']; ?>" hidden='hidden'>
  <input type='text' name='user' id='user' value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
  <?php 
  $id_mail  = $_GET['id_mail'];
  $tipo     = mysqli_query($con, "SELECT tipo_ingreso,nombre_ticket,asunto FROM fil01mail WHERE id_mail='$id_mail'");
  $rutipo   = mysqli_fetch_array($tipo);
  ?>
  <input type='text' name='tipo' id='tipo' value="<?php echo $rutipo['tipo_ingreso']; ?>" hidden='hidden'>
  <input type='text' name='nombre_ticket' id='nombre_ticket' value="<?php echo $rutipo['nombre_ticket']; ?>" hidden='hidden'>
  <div class="container">
    <div id="row">
      <?php 
      $nombreticket="";
      if($rutipo['nombre_ticket']!=""){
        $nombreticket= ": ".$rutipo['nombre_ticket'];
      ?>
      <input type='text' name='idticket' id='idticket' value="<?php echo $idticket; ?>" hidden='hidden'>
      <?php } ?>
      <h5>Derivar<?php echo $nombreticket; ?></h5>
    </div>
    <div class="row">
      <?php if($rutipo['nombre_ticket'] == ""){ ?>
      <div class="col-12">
          <label for="">Asunto:</label>
          <input type='text' class='form-control' id='asunto' name='asunto' value="<?php echo $rutipo['asunto']; ?>">
      </div>
      <?php } else { ?>
          <input type='text' class='form-control' id='asunto' name='asunto' value="<?php echo $rutipo['asunto']; ?>" hidden='hidden'>
       <?php } ?> 
      <div class="col-sm">
        <label for="exampleFormControlTextarea1">Observacion:</label>
          <textarea class="form-control" id="observacion" name="observacion" value="" rows="3"></textarea><br>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm">
        <div class='form-group'>
          <select class='form-control' id='dusuario' name='dusuario'>
            <?php 
            $usu = mysqli_fetch_array(mysqli_query($con, "SELECT quienderi FROM `fil03mail` WHERE `id_mail`='".$_GET['id_mail']."' order by fechaasig desc limit 1"));
            //$reusu = mysqli_fetch_array($usu);
            if($usu['quienderi'] != null){
              $use = mysqli_fetch_array(mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg where nrousuario='".$usu['quienderi']."'"));
              //$ruse = mysqli_fetch_array($use);
              echo "<option value='".$use['nrousuario']."'>".$use['nombre']."</option>";
              $users = mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg WHERE nrousuario!='".$usu['quienderi']."' order by nombre asc");
              while($ruser = mysqli_fetch_array($users)){
                echo "<option value='".$ruser['nrousuario']."'>".$ruser['nombre']."</option>";
              }
            }else{?>
              <option value="">Seleccione Usuarios*</option>
              <?php
              $users = mysqli_query($con, "SELECT nombre,nrousuario FROM fil01seg order by nombre asc");
              while($ruser = mysqli_fetch_array($users)){
                echo "<option value='".$ruser['nrousuario']."'>".$ruser['nombre']."</option>";
              }
            }?>
            
             
          </select>
        </div>
      </div>
      <div class="col-sm">
        <div class='form-group'>
          <select class='form-control' id='drol' name='drol'>
            <option value="">Seleccione Rol</option>
              <?php
              $rols = mysqli_query($con, "SELECT pardesc,parvalor FROM fil00par WHERE parcod=1 order by pardesc asc");
              while($rrol = mysqli_fetch_array($rols)){
                echo "<option value='".$rrol['parvalor']."'>".$rrol['pardesc']."</option>";
              }
              ?>
          </select>
        </div>
      </div>
    </div>
  </div>
  <?php if($rutipo['nombre_ticket'] == ""){ ?>
  <div class="container">
    <div class="row">
      <div class="col-5"> 
        
         <select class='form-control' id='etiquetas' name='etiquetas'>
          
          <option value="0" disabled selected>Seleccione una etiqueta*</option>

          </select>

      </div>
      
      <div class="vOculto" id="divsecuetiquetas" name="divsecuetiquetas"> 
        <select class="custom-select" multiple id="secuetiquetas" name="secuetiquetas">
          <option value="0" disabled >Seleccion multiple de etiqueta</option>
        </select>
                     
      </div>

    </div>
  </div><br>
  <?php } ?>
  <div class="container">
      <div class="row">
        <div class="col-6"> 
          <label>Archivos adjuntos (Para seleccion multiple -> tecla CTRL)</label><br>
          <input type='file' multiple='multiple' class='form-control-file' id='adjunto' name='archivo[]'>
        </div>
        <div class="col-6"> 
          <?php if($rutipo['nombre_ticket'] == ""){ ?>
          <label>Agregar Expediente:</label><br>
          <select class='form-control' id='expediente' name='expediente'>
            <option value="0" disabled selected>Seleccione un expediente</option>
            <?php 
            $sqql = mysqli_query($con, "SELECT * FROM `fil00par` WHERE `parcod`=8");
            while ($res = mysqli_fetch_array($sqql)) {
              echo "<option value='".$res['parvalor']."'>".$res['pardesc']."</option>";

            }
            ?>
            </select>
        </div>
      <?php } ?>
      </div>
    </div>
  <?php
  if($rutipo['tipo_ingreso'] == "P"){ ?>
    <div class="container">
    <div class="row">
      <div class="col-4"> 
        <div class="rounded">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="ingreso" value="I">
            <label for="ingreso">Ingreso Documentación</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="envio" value="E">
            <label for="envio">Envio Documentación</label>
          </div>
        </div>
      </div>
      <div class="col-4"> 
        <div class="form-check form-inline" >
          <label class="form-check-label" for="defaultCheck1">
            Numero mesa de entrada: <div id='num' class='vVisible'></div>
          </label>
          <input class="form-check-input" type="checkbox" id="NUMMESAENT" name="NUMMESAENT" value="0">
        </div>
      </div>
      <div class="col-4">
        <div class="col-sm sinpaddingleftright">
              <select class='form-control' id='documento' name='documento'>
                <option value="">Seleccione Tipo Documento</option>
                  <?php
                  $rols = mysqli_query($con, "SELECT `parvalor`,`pardesc` FROM `fil00par` WHERE `parcod`=7");
                  while($rrol = mysqli_fetch_array($rols)){
                    echo "<option value='".$rrol['parvalor']."'>".$rrol['pardesc']."</option>";
                  }
                  ?>
              </select>
            </div>
      </div>
    </div>
  </div>
<?php } ?>
  <div class="vOculto" id="cargador"></div>
</form>

<!-- Cuerpo del mensaje -->
<?php
$query= "SELECT f01.fecha,f01.asunto,if(f01.emisor ='',ent.razon,f01.emisor)emisor,f01.cuerpo 
FROM `fil01mail` f01
left join fil01ent ent on ent.codigo = f01.cod_entidad
WHERE id_mail =".$_GET['id_mail']." ";
$sql = mysqli_query($con,$query);
$re = mysqli_fetch_array($sql);
if($re){
?>
<br>
<div class="container">
  <div class="row">
    <div class="col-sm">
      <b>Fecha:</b> <?php echo $re['fecha']; ?>
    </div>
    <div class="col-sm">
      <b>De:</b> <?php echo $re['emisor']; ?>
    </div>
    <div class="col-sm">
      <b>Asunto:</b> <?php echo $re['asunto']; ?>
    </div>
    <div class="col-12 table-responsive">
      <hr />
      <?php echo $re['cuerpo']; ?>
      <br>
    </div>
  </div>
</div>
<?php  } ?>

<script>

var ingreso = "";
var envio   = "";

$("#ingreso").on("click", function(){
  ingreso = document.getElementById("ingreso").value;
  envio   = "";
});

$("#envio").on("click", function(){
  envio   = document.getElementById("envio").value;
  ingreso = "";
});

    $("#NUMMESAENT").on("click", function(){

    if($("input[type='checkbox']").is(':checked') === true){
        mostrar_nro_mesaentrada(true);
        
      }else{
      
        mostrar_nro_mesaentrada(false);
      
      }     

  });

  
  var valor;
  var user  = document.getElementById("user").value;
  var rol   = '<?php echo $_SESSION["rol"];?>'; 

   $(document).ready(function() {
     if($("#nombre_ticket").val() == ""){
      cargar_select(0);
      limpiarselect("secuetiquetas");
    }
  });


    $(document).on('change', '#etiquetas', function(event) {

            var valorselect = $("#etiquetas option:selected").val();
            var valorselectsecuetiquetas = $("#secuetiquetas option:selected").val();

            cargar_select(valorselect);

            if (valorselectsecuetiquetas != 0) {

              limpiarselect("secuetiquetas");
              
            }else{
              valor = "si";
            }

            
            
            if (valorselect == 0) {
              valor = "no";
            }else{
              valor="si";
            }

            ver_elementoHTML(valor,"divsecuetiquetas");  
  });




  function funcionclick(){ 
  document.getElementById("derivar").disabled = true;    
    var contadorvalor = "";
    $("#secuetiquetas :selected").each(function(){
      if($(this).val() != "" &&  $(this).val() > 0){
        contadorvalor += $(this).val()+";";
      }
    });

    var inputFileImage      = document.getElementById("adjunto");
    var file                = inputFileImage.files[0];
    var i                   = 0;
    var numail              = document.getElementById("numail").value;
    
    var observacion         = document.getElementById("observacion").value;
    var dusuario            = document.getElementById("dusuario").value;
    var drol                = document.getElementById("drol").value;
    var tipo                = document.getElementById("tipo").value;
    var asunto              = document.getElementById("asunto").value;
    
    if(document.getElementById("NUMMESAENT") != null){
      var NUMMESAENT      = document.getElementById("NUMMESAENT").value;
    } else {
      var NUMMESAENT      = "";
    }
    //var NUMMESAENT          = document.getElementById("NUMMESAENT").value;

    if(document.getElementById("nombre_ticket") != null){
      var nombreticket      = document.getElementById("nombre_ticket").value;
    } else {
      var nombreticket      = "";
    }

    if(document.getElementById("idticket") != null){
      var idticket          = document.getElementById("idticket").value;
    } else {
      var idticket          = "";
    }

    if(document.getElementById("etiquetas") != null){
      var etiquetas         = document.getElementById("etiquetas").value;
    } else {
      var etiquetas         = "";
    }

    if(document.getElementById("drol") != null){
      var drol              = document.getElementById("drol").value;
    } else {
      var drol              = "";
    }

    if(document.getElementById("dusuario") != null){
      var dusuario          = document.getElementById("dusuario").value;
    } else {
      var dusuario          = "";
    }

    if(document.getElementById("documento") != null){
      var documento         = document.getElementById("documento").value;
    } else {
      var documento         = "0";
    }

    if(document.getElementById("expediente") != null){
      var expediente         = document.getElementById("expediente").value;
    } else {
      var expediente         = "0";
    }

    var data = new FormData();
      data.append('nroarchivos',i);
      data.append('numail',numail);
      data.append('user',user);
      data.append('observacion',observacion);
      data.append('dusuario',dusuario);
      data.append('drol',drol);
      data.append('secuetiquetas',contadorvalor);
      data.append('etiquetas',etiquetas);
      data.append('tipo',tipo);
      data.append('nombreticket',nombreticket);
      data.append('idticket',idticket);
      data.append('NUMMESAENT',NUMMESAENT);
      data.append('ingreso',ingreso);
      data.append('envio',envio);
      data.append('documento',documento);
      data.append('expediente',expediente);
      data.append('asunto',asunto);

    jQuery.each($('#adjunto')[0].files, function(i, file) {
      data.append('archivo'+i, file);
      i = i + 1 ;
      data.append('nroarchivos',i);
    });

    $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
    $("#cargador").removeClass("vOculto").addClass("vVisible");
        
    $.ajax({
      url: "_derivar.php",        // Url to which the request is send
      type: "POST",               // Type of request to be send, called as method
      data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
      processData:false,          // To send DOMDocument or non processed data file it is set to false
    })
      .done(function(respuesta) {
      if(respuesta.success){
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        
        cronmenu(nrousuario,rol,"2");
        cargarPagina('<?php echo $dire; ?>');
        
      } else {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        alert(respuesta.error.mensaje);
      }
      })
      .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
        $("#cargador").removeClass("vVisible").addClass("vOculto");

        console.log("Algo ha fallado: " +  textStatus);
        alert(respuesta.error.mensaje);
      })
      .always(function() {
        document.getElementById("derivar").disabled = false;
        $("#cargador").removeClass("vVisible").addClass("vOculto");
          
      });
  }
  </script>