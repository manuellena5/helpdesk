<?php 
session_start();
include("../funciones.php");
salir(); 
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<div class="table-responsive-xl">
	<table id="table_notificaciones_generadas" class="table table-hover" style="width:100%;">
		<h4>Notificaciones Generadas</h4><br>
        <thead>
            <tr>
                <th>Nro</th>
                <th>Titulo</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Nro</th>
                <th>Titulo</th>
                <th>Accion</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- Modal -->
<?php include("../modal.php"); ?>

<script type="text/javascript" >
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
$(document).ready(function() {
	listar();
});
 
function listar(){
    //Para luego capturar error de carga de la tabla
    $.fn.dataTable.ext.errMode = 'none';
	var table_notificaciones_generadas = $('#table_notificaciones_generadas').DataTable( {
		"ajax":"notificaciones/_listargeneradas.php",
		"language": {
      		url:'DataTables/es-ar.json'},
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver'><button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>"
		}]
	});
	obtener_data_editar("#table_notificaciones_generadas tbody", table_notificaciones_generadas);
}
  
$('#table_notificaciones_generadas').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_notificaciones_generadas){
	$(tbody).on("click", "button.ver", function(){
		var data = table_notificaciones_generadas.row( $(this).parents("tr") ).data();
    	ver2(data); 
      //guardar el ver 
	});
}
function ver2(data){
  var output = "<h4>" + data['titulo'] + "</h4>";
  var adjunto = "";
      output += "<div class='container'>";
      output +=   "<div class='row'>"; 
      output +=     "<div class='col-12'><hr />";
      output +=       data['mensaje'];
      output +=     "<hr /></div>";
      output +=     "<br>";  
      for (var i = 1; i <= data["cant"]; i++) {
      output +=     "<div class='col-6'>";
      output +=       "<b>Asignado a:</b> "+data["notif"][i]["nombre"];
      output +=       "<br><b>Leido:</b> "+data["notif"][i]["estado"];
      output +=     "<br></div>";
      }
      output +=    "</div>";
      output +=   "</div>";
  document.getElementById("cuerpo").innerHTML = output;
}
</script>
