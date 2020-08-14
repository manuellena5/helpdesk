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
  <table id="table_papelera_de_mail" class="table" style="width:100%">
    <h4>Papelera de Mail</h4><br>
    <thead>
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Emisor</th>
        <th scope="col">Asunto</th>
        <th scope="col">Adjunto</th>
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
        <th scope="col">Adjunto</th>
        <th scope="col">Accion</th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>

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
  var table_papelera_de_mail = $('#table_papelera_de_mail').DataTable( {     
    "ajax": "mesaentrada/_listapapelera.php",
    "language": {
      url:'DataTables/es-ar.json'},
      "aaSorting": [],
    "columnDefs": [ {
      "targets": -1,
      "data": null,
      "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'><button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>    <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Restablecer'><button type='button' class='restaurar btn btn-primary'><i class='fa fa-retweet'></i></button></span>"
    }],   
  });
  //table_papelera_de_mail.order( [ 0, 'desc' ] );
  obtener_data_editar("#table_papelera_de_mail tbody", table_papelera_de_mail);
  global_tables(table_papelera_de_mail);
  //setInterval( function () {table_papelera_de_mail.ajax.reload();cronmenu(nrousuario,rol,accion);}, 90000 );
}

var obtener_data_editar = function(tbody, table_papelera_de_mail){
  // Este para Restaurar
  $(tbody).on("click", "button.restaurar", function(){
    var data = table_papelera_de_mail.row( $(this).parents("tr") ).data();
    cargarPagina("mesaentrada/_restaurar.php?id_mail="+data["id_mail"]);
  });

  // Este para Ver
  $(tbody).on("click", "button.ver", function(){
    var data = table_papelera_de_mail.row( $(this).parents("tr") ).data();
    verMensaje(data);
  });

}

function verMensaje(data){
  //console.log(data);
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
</script>