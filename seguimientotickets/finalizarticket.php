<?php
session_start();
include ("../connect.php");
include("../funciones.php");
salir();
$id       = $_GET['id'];

//URL para saber de donde se hace la accion.
$url      = $_GET['url'];
$men      = "El mail se finalizo con exito.";
if($url == "accion"){
  $dire   = "seguimientotickets/sinaccion.php?ver=si&men=".$men."&donde=Tickets sin accion";
  $dire2  = "seguimientotickets/sinaccion.php?ver=no&men=&donde=";
} 
if($url == "espera"){
  $dire   = "seguimientotickets/enespera.php?ver=si&men=".$men."&donde=Tickets en espera";
  $dire2  = "seguimientotickets/enespera.php?ver=no&men=&donde=";
}

if ($id!="") {
  $query ="SELECT f01.nombre_ticket
      FROM `fil03mail` f03
      inner join fil01mail f01 on f01.id_mail = f03.id_mail
      WHERE f03.id = '$id' ";
  if ($sql = mysqli_query($con,$query)) {
    if ($sql->num_rows>0) {
      $re = mysqli_fetch_array($sql);
    } else { ?>
      <h3>Ocurrio un Error</h3>
      <div align='left'>
        <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
      </div>
    <?php }
  } else { ?>
    <h3>Ocurrio un Error</h3>
    <div align='left'>
      <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
    </div>
  <?php }
}else{ ?>
  <h3>Ocurrio un Error</h3>
  <div align='left'>
    <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
  </div>
<?php } ?>
<form id="finalizarfor" method="POST" accept-charset="utf-8" >
  <h4>Finalizar Ticket</h4>
  <input type='text' name='id' value="<?php echo $_GET['id']; ?>" hidden='hidden'>
  <input type='text' name='user' value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
  <div class='form-group'>
    <h5><?php echo $re['nombre_ticket']; ?></h5>
    <label for="exampleFormControlTextarea1">Observacion*:</label>
    <textarea class="form-control" id="observacion" name="observacion"  value="" rows="3" required></textarea>
  </div> 
  <div align='left'>
    <button type='button' value='Send' id='envioform' class='btn btn-primary' onclick="finalizar();">Finalizar</button>
    <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
  </div>
  <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small>
  <div class="vOculto" id="cargador"></div>
</form>

<script>
function finalizar(){
  var obser = $('#exampleFormControlTextarea1').val();
  if(obser == ""){
    alert("Campo observacion Obligatorio");
  } else {
    $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
    $("#cargador").removeClass("vOculto").addClass("vVisible");
    $.ajax({  
      url: 'seguimientotickets/_finalizar.php',                       
      type: 'POST',                            
      data: $("#finalizarfor").serialize(),
    })
    .done(function(respuesta) {
      if (respuesta.success) {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        cargarPagina('<?php echo $dire; ?>'); 
      }else{
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
      $("#cargador").removeClass("vVisible").addClass("vOculto"); 
    });  
  }                          
}
</script>