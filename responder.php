<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
$id_mail = $_GET['id_mail'];
$sql = mysqli_query($con, "SELECT * FROM `fil01mail` where id_mail='$id_mail'");
$re = mysqli_fetch_array($sql);

//URL para saber de donde se hace la accion.
$url = $_GET['url'];
$men = "El mail se envió correctamente.";
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
?>
 
<!-- Botones de Acciones -->
<div style="text-align:right">
  <button type='button' value='Send' id='envioform' class='btn btn-primary' onclick="funcionclick();">Enviar</button>
  <button type='button' value='Send' id='btn-envioform2' class='btn btn-primary' onclick="cargarPagina('<?php echo $dire2; ?>');">Volver</button>
</div>

<!-- Formulario para enviar la Respuesta -->
<form method='post' id='formregistro' name="formregistro" enctype="multipart/form-data">

  <div class='form-group'>
    <?php if($url == "accion"){ 
    ?>
     
      <input type='text' name='idticket' id="idticket" value="<?php echo $_GET['id']; ?>" hidden='hidden'>
    <?php } ?>
    <input type='text' name='quienresp' id="quienresp" value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
    <input type='text' name='nombre_ticket' id='nombre_ticket' value="<?php echo $re['nombre_ticket']; ?>" hidden='hidden'>
    <input type='text' name='numail' id="numail" value="<?php echo $re['id_mail']; ?>" hidden='hidden'> 
    <input type='text' name='tipo' id='tipo' value="<?php echo $re['tipo_ingreso']; ?>" hidden='hidden'>
    <input type='text' class='form-control' id='nrousuario' name='nrousuario' value="<?php echo $_SESSION["nrousuario"]; ?>" hidden='hidden'>
    <input type='text' class='form-control' id='nro_ticket' name='nro_ticket' value="<?php echo $re['nro_ticket']; ?>" hidden='hidden'>          
    <input type='text' class='form-control' id='rol' name='rol' value="<?php echo $_SESSION['rol']; ?>" hidden='hidden'>
    <input type='text' class='form-control' id='firma' name='firma' value="<?php echo $_SESSION['firma']; ?>" hidden='hidden'>
    
    <?php if($re['nombre_ticket'] == ""){ ?>
      <label for='nombreticket_asunto'>Asunto*</label><br>
      <input type='text' class='form-control' id='nombreticket_asunto' name='nombreticket_asunto' value="<?php echo $re['asunto']; ?>">
    <?php } else{ ?>
      <input type='text' class='form-control' id='nombreticket_asunto' name='nombreticket_asunto' value="<?php echo $re['nombre_ticket'].''.$re['asunto']; ?>" hidden='hidden'> 
    <?php } ?>
        
    <label for='cuerpo'>Respuesta*</label><br>
    <textarea class='form-control' id='cuerpo' name='cuerpo' ></textarea>
  
    <br>
    <div class="container">  
      <div class="row">
        <div class="col-1">
          <input class="form-check-input" type="checkbox" name="para" id="para" value="0" >
          <label class="form-check-label">Para:</label>
        </div>
        <div class="col-10">
          <input type='email' class='form-control' name='receptor' id='receptor' value="<?php if($re['emisor'] != 'Interno'){echo $re['emisor'];} ?>" placeholder="Para" readonly>
        </div>
        <div class="col-1">
          <div class="vOculto" id="para2">
            <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  
              <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('receptor');"><i class="fas fa-clipboard-list fa-2x"></i></button>
            </span>
          </div>
        </div>
      </div>
      <br>
      <div class="row">         
        <div class="col-1">
          <input class="form-check-input" type="checkbox" name="inpCC" id="inpCC" value="0" >
          <label class="form-check-label">CC:</label>
        </div>
          <div class="col-10">
            <div class="vOculto" id="oculCC">
              <input type='email' class='form-control' name='CC[]' id='CC' value="<?php echo $re['cc']; ?>" placeholder="CC" >
            </div>
          </div>
          <div class="col-1">
            <div class="vOculto" id="oculCC2">
              <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  
                <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('cc');"><i class="fas fa-clipboard-list fa-2x"></i></button>
              </span> 
            </div>
          </div> 
      </div>   
      <br>
      <div class="row">
        <div class="col-1">           
          <input class="form-check-input" type="checkbox" name="inpCCO" id="inpCCO" value="0">   
          <label class="form-check-label" for="defaultCheck1">CCO:</label>
        </div>
        <div class="col-10">
          <div class="vOculto" id="oculCCO">
            <input type='email' class='form-control' name='CCO' id='CCO' placeholder="CCO">
          </div>
        </div>
        <div class="col-1">
          <div class="vOculto" id="oculCCO2">
            <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Agenda'>  
              <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong' onclick="agenda('cco');"><i class="fas fa-clipboard-list fa-2x"></i></button>
            </span>
          </div>
        </div>
      </div>    
    </div>
    </div>

    <?php if($re['nombre_ticket'] == ""){ ?>
      <input type='text' name='tipo_ticket' id="tipo_ticket" value="E" hidden='hidden'>
    <div class="container">
      <div class="row">
          <div class="col-5"> 
            
            <select class='form-control' id='etiquetas' name='etiquetas'>
                            <option value="0" disabled selected>Seleccione una etiqueta*</option>

            </select>
          
          </div>
        
          <div id="divsecuetiquetas" class="vOculto" name="divsecuetiquetas"> 
                          
                          <select class="custom-select" multiple id="secuetiquetas" name="secuetiquetas">
                              <option value="0" disabled >Seleccione una etiqueta</option>
                          </select>
                       
          </div>
      
      </div>
    </div><br>
    <?php }else{

      $id =$_GET['id_mail'];
      $query0 = "SELECT tipo_ticket
FROM fil03mail
WHERE  `id_mail` =  '$id' limit 1";
      $sql = mysqli_query($con,$query0);
      $resultado = mysqli_fetch_array($sql);
      ?>
       <input type='text' name='tipo_ticket' id="tipo_ticket" value="<?php echo $resultado['tipo_ticket']; ?>" hidden='hidden'>
    <?php } ?>
    <div class="container">
      <div class="row">
        <div class="col-6"> 
          <label>Archivos adjuntos (Para seleccion multiple -> tecla CTRL)</label><br>
          <input type='file' multiple='multiple' class='form-control-file' id='adjunto' name='archivo[]'>
        </div>
        <div class="col-6"> 
          <?php if($re['nombre_ticket'] == ""){ ?>
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
      <?php }
      $ss = mysqli_query($con, "SELECT `nombre`,`id_file` FROM `fil01adj` WHERE `nro_ticket`='".$re['nro_ticket']."' order by nombre asc");
      if($ss->num_rows > 0){ ?>
      <div class="col-sm">  
          <label>Agregar ADJ:</label><br>
          <select class='form-control' id='adj' name='adj' multiple>
            <option value="0" disabled selected>Seleccione Adj.</option>
            <?php 
            $s = mysqli_query($con, "SELECT `nombre`,`id_file` FROM `fil01adj` WHERE `nro_ticket`='".$re['nro_ticket']."' order by nombre asc");
            while ($r = mysqli_fetch_array($s)) {
              echo "<option value='".$r['id_file']."'>".$r['nombre']."</option>";

            }
            ?>
            </select>
      <div class="col-sm">           
          <input class="form-check-input" type="checkbox" name="todos" id="todos" value="0">   
          <label class="form-check-label" for="defaultCheck1">Todos los ADJ</label>
      </div>
        </div>
      <?php } ?>
      </div>
    </div>
    
      <small class='form-text text-muted'>Los campos marcados con * son obligatorios</small>
      <div class="vOculto" id="cargador"></div>
  </div>
</form>
<hr>

<!-- Cuerpo del mensaje -->
<?php if($re){ ?>
  <div class="container">
    <div class="row">
      <div class="col-sm">
        <b>Asunto:</b> <?php echo $re['nombre_ticket'].$re['asunto']; ?> 
      </div>
      <div class="col-12 table-responsive">
        <hr />
        <?php echo $re['cuerpo']; ?>
        <hr />
      </div>
    </div>
  </div>
<?php } ?>

<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario">
      <div class="modal-body" id="cuerpo">
        <?php include("agenda.php"); ?>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" id="seleccion" class="close" data-dismiss="modal" aria-label="Close">Seleccion</button>
      </div>
    </div>
  </div>
</div>
  
<script>

var valor;

$(document).ready(function() {

  $('#cuerpo').Editor();

      $('#cuerpo').Editor('setText', ['']);

      $('#btn-enviar').click(function(e){
        e.preventDefault();
        $('#cuerpo').text($('#cuerpo').Editor('getText'));
        $('#frm-test').submit();        
      });

  if($("#nombre_ticket").val() == ""){
    cargar_select(0);
    limpiarselect("secuetiquetas");
  }

  var table ="";
  var tipo ="";
  $.fn.dataTable.ext.errMode = 'none';

  //Funcion Para
  $("#para").on("click",function() {
    var para = document.getElementById('para2');

    if(para.className == 'vVisible'){   
      $("#para2").removeClass("vVisible").addClass("vOculto");
      document.getElementById("receptor").readOnly = true;
      //document.getElementById('inpCC').value = 0;
    } else {
      $("#para2").removeClass("vOculto").addClass("vVisible");
      document.getElementById("receptor").readOnly = false;
      //document.getElementById('inpCC').value = 1;
    }
  });

  //Funcion para que aparezca el CC
  $("#inpCC").on("click",function() {
    var oculCC = document.getElementById('oculCC');

    if(oculCC.className == 'vVisible'){   
      $("#oculCC").removeClass("vVisible").addClass("vOculto");
      $("#oculCC2").removeClass("vVisible").addClass("vOculto");
      document.getElementById('inpCC').value = 0;
    } else {
      $("#oculCC").removeClass("vOculto").addClass("vVisible");
      $("#oculCC2").removeClass("vOculto").addClass("vVisible");
      document.getElementById('inpCC').value = 1;
    }
  });

  //Funcion para que aparezca el CCO
  $("#inpCCO").on("click",function() {
    var oculCCO = document.getElementById('oculCCO');

    if(oculCCO.className == 'vVisible'){
      $("#oculCCO").removeClass("vVisible").addClass("vOculto");
      $("#oculCCO2").removeClass("vVisible").addClass("vOculto");
      document.getElementById('inpCCO').value = 0;
    } else {
      $("#oculCCO").removeClass("vOculto").addClass("vVisible");
      $("#oculCCO2").removeClass("vOculto").addClass("vVisible");
      document.getElementById('inpCCO').value = 1;
    }
  });

  //Funcion para los adjuntos
  $("#todos").on("click",function() {
    var todos = document.getElementById('todos');

    if(todos.value == 1){
      document.getElementById('todos').value = 0;
      document.getElementById("adj").disabled = false;
    } else {
      document.getElementById('todos').value = 1;
      document.getElementById("adj").disabled = true;
    }
  });


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
  document.getElementById("envioform").disabled = true;
	if (validacionform_responderticket()) {
  
    var contadorvalor = "";
    $("#secuetiquetas :selected").each(function(){
      if($(this).val() != "" &&  $(this).val() > 0){
        contadorvalor += $(this).val()+";";
      }
    });

    var contadoradj = "";
    $("#adj :selected").each(function(){
      if($(this).val() != "" &&  $(this).val() > 0){
        contadoradj += $(this).val()+";";
      }
    });
        
    var inputFileImage  = document.getElementById("adjunto");
    var file            = inputFileImage.files[0];
    var i               = 0;
    var cuerpo          = document.getElementById("editor").innerHTML;
    var numail          = document.getElementById("numail").value;
    var rol             = document.getElementById("rol").value;
    var nombreticket_asunto    = document.getElementById("nombreticket_asunto").value;
    var quienresp       = document.getElementById("quienresp").value;
    var nrousuario      = document.getElementById("nrousuario").value;
    var receptor        = document.getElementById("receptor").value;
    var nro_ticket      = document.getElementById("nro_ticket").value;
    var CC              = document.getElementById("CC").value;
    var CCO             = document.getElementById("CCO").value;
    var inpCC           = document.getElementById("inpCC").value;
    var inpCCO          = document.getElementById("inpCCO").value;
    var firma           = document.getElementById("firma").value;
    var tipo_ingreso    = document.getElementById("tipo").value;
    
    

    if(document.getElementById("idticket") != null){
      var idticket      = document.getElementById("idticket").value;
    } else {
      var idticket      = "";
    }

    if(document.getElementById("nombre_ticket") != null){
      var nombreti      = document.getElementById("nombre_ticket").value;
    } else {
      var nombreti      = "";
    }

    if(document.getElementById("etiquetas") != null){
      var etiquetas     = document.getElementById("etiquetas").value;
    } else {
      var etiquetas     = "";
    }

    if(document.getElementById("tipo_ticket") != null){
      var tipo_ticket     = document.getElementById("tipo_ticket").value;
    } else {
      var tipo_ticket     = "E";
    }

    if(document.getElementById("expediente") != null){
      var expediente     = document.getElementById("expediente").value;
    } else {
      var expediente     = "0";
    }

    if(document.getElementById("todos") != null){
      if(document.getElementById("todos").value == "1"){
        var todos     = document.getElementById("todos").value;
        contadoradj   = "";
      }else{
        var todos     = "0";
      }
    }
 
    var data = new FormData();
      data.append('nroarchivos',i);
      data.append('cuerpo',cuerpo);
      data.append('numail',numail);
      data.append('rol',rol);
      data.append('nombreticket',nombreticket_asunto);
      data.append('quienresp',quienresp);
      data.append('nrousuario',nrousuario);
      data.append('mail',receptor);
      data.append('nro_ticket',nro_ticket);
      data.append('CC',CC);
      data.append('CCO',CCO);
      data.append('inpCC',inpCC);
      data.append('inpCCO',inpCCO);
      data.append('firma',firma);
      data.append('secuetiquetas',contadorvalor);
      data.append('etiquetas',etiquetas);
      data.append('tipo',tipo_ingreso);
      data.append('nombre_ticket',nombreti);
      data.append('idticket',idticket);
      data.append('tipo_ticket',tipo_ticket);
      data.append('expediente',expediente);
      data.append('adj',contadoradj);
      data.append('todos',todos);

    jQuery.each($('#adjunto')[0].files, function(i, file) {
      data.append('archivo'+i, file);
      i = i + 1 ;
      data.append('nroarchivos',i);
    });
    $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
    $("#cargador").removeClass("vOculto").addClass("vVisible");
        
    $.ajax({
      url: "_respondermail.php",      // Url to which the request is send
      type: "POST",               // Type of request to be send, called as method
      data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
       processData:false,         // To send DOMDocument or non processed data file it is set to false
    })
      .done(function(respuesta){
      if(respuesta.success){
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        
        cronmenu(nrousuario,rol,"2");
        cargarPagina('<?php echo $dire; ?>');
      } else {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        alert(respuesta.error.mensaje);
      }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
        
        console.log("Algo ha fallado: " +  textStatus);
        document.getElementById("cuerpo").innerHTML ="<h4>Error</h4>";
      })
      .always(function() {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
          document.getElementById("envioform").disabled = false;
      });
      }else{
        document.getElementById("envioform").disabled = false;

      }
}

  
  function agenda(variable){
    tipo = variable;
    table = $('#tablaAgenda').DataTable({
      destroy: true,
      'ajax': '_listadoagenda.php',
      "language": {
        url:'DataTables/es-ar.json'},
      'columnDefs': [
        {
          'targets': 0,
          'checkboxes': {
            'selectRow': true
          } 
         }
      ],
      'select': {
        'style': 'multi'
      },
      'order': [[1, 'asc']]
   });
  };

  $('#seleccion').on('click', function(e){
    var mensaje         = "";
    var separador       = "";
    var rows_selected   = table.column(0).checkboxes.selected();
    var emails          = "";
    if(rows_selected.length > 1){
      separador         = ";"; 
    }

    //Iterar sobre todas las casillas de verificación seleccionadas
    $.each(rows_selected, function(index, rowId){
      //Crea un elemento oculto
      mensaje += rowId + separador;
    });

    if(tipo=='cc'){
      var CC            = document.getElementById("CC").value;
      if(CC != ""){
        CC = CC + ";";
      }
      emails = CC+ mensaje;
      emails = eliminar_puntocoma(emails);
      document.getElementById("CC").value= emails;
    } else if(tipo=='cco'){
      var CCO           = document.getElementById("CCO").value;
      if (CCO != "") {
        CCO = CCO + ";";
      }
       emails = CCO+ mensaje;
      emails = eliminar_puntocoma(emails);
      document.getElementById("CCO").value=emails;
    } else if(tipo=='receptor'){
      var receptor           = document.getElementById("receptor").value;
      if (receptor != "") {
        receptor = receptor + ";";
      }
       emails = receptor+ mensaje;
      emails = eliminar_puntocoma(emails);
      document.getElementById("receptor").value=emails;
    }
   });

  $('#tablaAgenda').on('error.dt', function(e, settings, techNote, message) {
    alert("Ocurrió un error al cargar la tabla");
    console.log('Ocurrió un error al cargar la tabla: ', message);
  });
</script>