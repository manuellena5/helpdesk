<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<div class="table-responsive-xl">
    <h4>Ingreso por Mail</h4><br>
  <table id="table_ingreso_por_mail" class="table table-hover" style="width: 100%;font-size: 80%;">
  
    <thead>
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Emisor</th>
        <th scope="col">Asunto</th>
        <th scope="col">Adj</th>
        <th scope="col">Accion</th>
      </tr>
    </thead>      
    <tbody> 
    </tbody>
    <tfoot>
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Emisor</th>
        <th scope="col">Asunto</th>
        <th scope="col">Adj</th>
        <th scope="col">Accion</th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>


<!-- modal 2 -->
<div class="modal fade bd-example-modal-lg" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle2"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       
        
        
        <div class="modal-body" id="cuerpo">
           <table id="unir" class="table table-hover" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">Nro Ticket</th>
                <th scope="col">Asunto</th>
                <th scope="col">Tipo Ticket</th>
                <th scope="col">Accion</th>
              </tr>
            </thead>      
            <tbody> 
            </tbody>
            <tfoot>
              <tr>
                <th scope="col">Nro Ticket</th>
                <th scope="col">Asunto</th>
                <th scope="col">Tipo Ticket</th>
                <th scope="col">Accion</th>
              </tr>
            </tfoot>
          </table>
        </div>
        
        <div class="modal-footer" id="piemodal">
          <?php if (!isset($agenda)) {
          ?>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <?php }else { ?>
          <button type="button" id="seleccion" class="close" data-dismiss="modal" aria-label="Close">Seleccion</button>
          <?php } ?>
        </div>
      </div>
    </div>
</div>





<script>
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 

$(document).ready(function() {
  cronmenu(nrousuario,rol,"2");
  listar();
});
var table_ingreso_por_mail;
function listar(){
  //Para luego capturar error de carga de la tabla
  
   table_ingreso_por_mail = $('#table_ingreso_por_mail').DataTable( {
    //"ordering": false,
    "aaSorting": [],
    "ajax": "mesaentrada/_lista.php",
    "language": {
      url:'DataTables/es-ar.json'},
      "iDisplayLength":     100,
    "columnDefs": [ {
      "targets": -1,
      "data": null,
      "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Responder'><button type='button' class='responder btn btn-primary'><i class='fas fa-edit'></i></button></span>      <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Derivar ticket'><button type='button' class='derivar btn btn-primary'><i class='fa fa-users' aria-hidden='true'></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Borrar'><button type='button' class='borrar btn btn-primary'><i class='fas fa-trash-alt'></i></button></span>    <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Unir ticket'><button type='button' class='unir btn btn-primary' data-toggle='modal' data-target='#exampleModalLong2'><i class='fas fa-file-medical'></i></button></span>    <span class='d-inline-block' tabindex='0' title='Archivar'><button type='button' class='archivar btn btn-primary'><i class='fas fa-folder-open'></i></button></span>"
    }], 

  });

  //table_ingreso_por_mail.order( [ 0, 'desc' ] );
  obtener_data_editar("#table_ingreso_por_mail tbody", table_ingreso_por_mail);
  global_tables(table_ingreso_por_mail);
  //setInterval( function () {table_ingreso_por_mail.ajax.reload();}, 90000 );
  $.fn.dataTable.ext.errMode = 'none';
}

$('#table_ingreso_por_mail').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log( 'Ocurrió un error al cargar la tabla: ', message);
})
var id;
var obtener_data_editar = function(tbody, table_ingreso_por_mail){

  // Este para responder
  $(tbody).on("click", "button.responder", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    cargarPagina("responder.php?id_mail="+data["id_mail"]+"&url=mail");
  });

  // Este para Derivar
  $(tbody).on("click", "button.derivar", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    cargarPagina("derivar.php?id_mail="+data["id_mail"]+"&url=mail");
  });

  // Este para Borrar
  $(tbody).on("click", "button.borrar", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    cargarPagina("mesaentrada/_borrar.php?id_mail="+data["id_mail"]);
  });

  // Este para Ver
  $(tbody).on("click", "button.ver", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    $('#exampleModalLong2').modal('hide');
    verMensaje(data);
  });

  // Este para unir
  $(tbody).on("click", "button.unir", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    unir(data);
    id = data['id_mail'];
  });

  // Este para archivar
  $(tbody).on("click", "button.archivar", function(){
    var data = table_ingreso_por_mail.row( $(this).parents("tr") ).data();
    cargarPagina("mesaentrada/archivar.php?id_mail="+data["id_mail"]);
    
  });

}

function verMensaje(data){
  
  ///console.log(data);
  var output = "<h4>" + data['asunto'] + "</h4>";
  var adjunto = "";
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>Fecha:</b>" + data['fecha'];
      output +=     "</div>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>De:</b>"+ data['emisor']; 
      output +=     "</div><br>";
  if (data['adjunto']=='N') {
    adjunto = 'No';
      output +=     "<div class='col-sm'>";
      output +=       "<b>Adjunto:</b>"+ adjunto; 
      output +=     "</div>"  + "<br>";
  }else if(data['adjunto']=='S'){
    adjunto = 'Si';
      output +=     "<div class='col-sm'>";
      output +=       "<b>Adjunto:</b>"+ adjunto; 
      output +=     "</div>"  + "<br>";
  }
  if(data['cc']){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>CC:</b>"+ data["cc"]; 
      output +=     "</div>";
      output +=   "</div>";
      output += "</div>";
  }   
  if(data['destinatario'] != "cie@cie.gov.ar"){
      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>Para:</b>"+ data["destinatario"]; 
      output +=     "</div>";
      output +=   "</div>";
      output += "</div>";
  }   
      output +=     "<div class='col-12 table-responsive'><hr />";
      output +=     data['cuerpo'];

      output +=     "<br>";
      output +=    "</div>  ";
      output +=   "</div>";
      output += "</div><br>";
  var nombreadj = "";
  var rutaadj = "";
  var salidaadjuntos = "";
  if(data['adjunto']=='S' && data["cant"] > 0){
     salidaadjuntos += "<div class='container'>";
     salidaadjuntos +="<div class='row'>";
    for (var i = 1; i <= data["cant"]; i++) {
      nombreadj  = data["archivo"][i]["nombre"];
      rutaadj    = data["archivo"][i]["ruta"];
       salidaadjuntos += "<div class='col-sm'>";
      salidaadjuntos += "<br><a href='"+rutaadj+"' target='_blank'><button type='button' class='btn btn-primary'>";
      salidaadjuntos += nombreadj+" <span class='badge badge-light'><i class='fa fa-download' aria-hidden='true'></i></span>";
      salidaadjuntos += "</button></a>";
      salidaadjuntos += "</div>";
     
    }
       salidaadjuntos += "</div>";
      salidaadjuntos +="</div>";
      document.getElementById("piemodal").innerHTML = salidaadjuntos;
  }else{
    document.getElementById("piemodal").innerHTML = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>";
  }
  document.getElementById("cuerpo").innerHTML = output;
 
}

function unir(db){
  document.getElementById("exampleModalLongTitle2").innerHTML = "Unir mail a un Ticket existente";
  listar_unir();
}

function listar_unir(){
  //Para luego capturar error de carga de la tabla
  
  var unir = $('#unir').DataTable( {
    "ajax": "mesaentrada/_unir.php",
    "language": {
      url:'DataTables/es-ar.json'},
    "columnDefs": [ {
      "targets": -1,
      "data": null,
      "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'><button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>    <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Asignar'><button type='button' class='Asignar btn btn-primary'><i class='fas fa-arrow-alt-circle-up' aria-hidden='true' ></i></button></span>"
    }],  
  }); 

  obtener_data("#unir tbody", unir);
  otro("#unir tbody", unir);
  $.fn.dataTable.ext.errMode = 'none';
}
var otro = function(tbody, unir){
    $(tbody).on("click", "button.Asignar", function(){
    var data = unir.row( $(this).parents("tr") ).data();
    var nro_ticket = data['nro_ticket'];
    var tipo_ticket = data['tipo_ticket'];
    //cargarPagina("mesaentrada/_asignar.php?&id="+id+"&nro_ticket="+nro_ticket+"&tipo_ingreso="+tipo_ingreso);
    var data2 = new FormData();
    data2.append('nro_ticket',nro_ticket);
    data2.append('tipo_ticket',tipo_ticket);
    data2.append('id',id);
    //console.log(usuario);
    //console.log(tipo_ticket);


    $.ajax({
          url: "mesaentrada/_asignar.php",   // Url to which the request is send.
          type: "POST",                           // Type of request to be send, called as method.
          data: data2,                             // Data sent to server, a set of key/value pairs (i.e. form fields and values)
          contentType: false,                     // The content type used when sending data to the server.
          cache: false,                           // To unable request pages to be cached.
          processData:false,                      // To send DOMDocument or non processed data file it is set to false.
          dataType: 'json',
        })
        .done(function(respuesta) {
          if(respuesta.success){
            cargarPagina("mesaentrada/ingresopormail.php?donde=Ingreso por Mail&ver=Si&men=Se asigno correctamente el mail.");
            //alert("listo");
            table_ingreso_por_mail.ajax.reload(null, false);
            //$('#exampleModalLong2').modal('hide');
          }else{
            alert(respuesta.error.mensaje);
          }
          $('#exampleModalLong2').modal('hide');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log("Algo ha fallado: " +  textStatus);
        })
  });
    $("#exampleModalLong2").on('hidden.bs.modal', function () {
  //cargarPagina("mesaentrada/ingresopormail.php?donde=Ingreso por Mail&ver=S&men=Se asigno correctamente el mail.");
});
}
var obtener_data = function(tbody, unir){
  // Este para Ver
    $(tbody).on("click", "button.ver", function(){
    var data = unir.row( $(this).parents("tr") ).data();
    $('#exampleModalLong2').modal('hide');
    verhistorial(data["nro_ticket"],data["id"],data["tipo_ticket"],unir);
  });
  
}

</script>