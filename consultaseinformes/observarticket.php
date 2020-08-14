<?php
session_start();
include ("../connect.php");
include("../funciones.php");
salir();
$id       = $_GET['id'];
if($_GET['url'] == "consultageneral"){
  $dire2    = "consultaseinformes/consultageneral.php?ver=no&men=&donde=";
}
if($_GET['url'] == "seguimiento"){
  $dire2    = "seguimientotickets/seguimiento.php?ver=no&men=&donde=";
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
  
  <h4>Observar Ticket</h4>
  <input type='text' name='id' value="<?php echo $_GET['id']; ?>" hidden='hidden'>
  <input type='text' name='user' value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
  
  <div class='form-group'>
    <h5><?php echo $re['nombre_ticket']; ?></h5>
    <label for="exampleFormControlTextarea1">Observacion*:</label>
    <textarea class="form-control" id="idobservacion" name="observacion"  value="" rows="3" required></textarea>
  </div> 

  <div align='left'>
    <button type='button' value='Send' id='envioform' class='btn btn-primary' onclick="fobservar();">Observar</button>
    <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
  </div>

  <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small>
  

</form>




<script>
function fobservar(){
  var obser = $('#idobservacion').val();
  if(obser != ""){
   
    
    $.ajax({  
      url: 'consultaseinformes/_observar.php',                       
      type: 'POST',               
      dataType: 'json',             
      data: $("#finalizarfor").serialize(),
    })
    .done(function(respuesta) {
      
     
      if (respuesta.success) {
        cargarPagina("consultaseinformes/consultageneral.php?ver=si&men=Ticket observado con Exito&donde=Consulta General");
      }else{
        
        alert(respuesta.error.mensaje);
      }
    })
    .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
     
        console.log("Algo ha fallado: " +  textStatus);
        alert(respuesta.error.mensaje);
    })
    
  }else{

     alert("Campo observacion Obligatorio");
  }
                            
}
</script>