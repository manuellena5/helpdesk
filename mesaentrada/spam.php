<?php
session_start();
include("../connect.php");
include("../funciones.php");
salir();
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<div  class="table-responsive-xl"> 
  <table id="table_de_spam" class="table" style="width:100%">
    <h4>Spam de Mail</h4><br>
    <button type='button' class='btn btn-primary' onclick="recargar();" >Recargar Spam</button>
    <thead>
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Emisor</th>
        <th scope="col">Asunto</th>
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
        <th scope="col">Accion</th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>
<div class="vOculto" id="cargador"></div>
<script>
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 

$(document).ready(function() {
  var accion = "2";
  cronmenu(nrousuario,rol,accion);
  listar();
});

function listar(){
  //Para luego capturar error de carga de la tabla
  $.fn.dataTable.ext.errMode = 'none';
  var table_de_spam = $('#table_de_spam').DataTable( {     
    "ajax": "mesaentrada/_listarspam.php",
    "aaSorting": [],
    "language": {
      url:'DataTables/es-ar.json'},
    "columnDefs": [ {
      "targets": -1,
      "data": null,
      "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver'><button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Sacar de Spam'><button type='button' class='sacar btn btn-primary'><i class='fa fa-retweet'></i></button></span>"
    }],   
  });
  //table_de_spam.order( [ 0, 'desc' ] );
  obtener_data_editar("#table_de_spam tbody", table_de_spam);
  global_tables(table_de_spam);
}

var obtener_data_editar = function(tbody, table_de_spam){
  // Este para Restaurar
  $(tbody).on("click", "button.sacar", function(){
    var data = table_de_spam.row( $(this).parents("tr") ).data();
    cargarPagina("mesaentrada/_sacarspam.php?uid="+data["uid"]);
  });

  // Este para Ver
  $(tbody).on("click", "button.ver", function(){
    var data = table_de_spam.row( $(this).parents("tr") ).data();
    verMensaje(data);
  });

}

function verMensaje(data){
  var output = "<h4>" + data['asunto'] + "</h4>";

      output += "<div class='container'>";
      output +=   "<div class='row'>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>Fecha:</b>" + data['fecha'];
      output +=     "</div>";
      output +=     "<div class='col-sm'>";
      output +=       "<b>De:</b>"+ data['emisor']; 
      output +=     "</div><br>";
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
    document.getElementById("piemodal").innerHTML = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>";
  document.getElementById("cuerpo").innerHTML = output;
}

function recargar(){
  $("#cargador").html('<div class="loading"><img src="images/cargador.gif" alt="loading" /><br/>Un momento, por favor...</div>');
  $("#cargador").removeClass("vOculto").addClass("vVisible");
  $.ajax({
      url: "mesaentrada/_borrarspam.php",      // Url to which the request is send
      contentType: false,         // The content type used when sending data to the server.
      cache: false,               // To unable request pages to be cached
      processData:false,         // To send DOMDocument or non processed data file it is set to false
    })
  .done(function() {
    cargarPagina("mesaentrada/spam.php?ver=no&men=&donde=");
    
  })
  .always(function() {
    $("#cargador").removeClass("vVisible").addClass("vOculto");   
  });
}
</script>