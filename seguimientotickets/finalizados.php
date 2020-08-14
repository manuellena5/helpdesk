<?php 
session_start();
include("../funciones.php");
salir(); ?>
<div class="table-responsive-xl">
	<table id="table_finalizados" class="table table-hover" style="width: 100%;">
		<h4>Tickets finalizados</h4><br>
      <thead>
        <tr>
          <th>nro_ticket</th>
          <th>emisor</th>
          <th>asunto</th>
          <th>fecha</th>
          <th>accion</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>nro_ticket</th>
          <th>emisor</th>
          <th>asunto</th>
          <th>fecha</th>
          <th>accion</th>
        </tr>
      </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include('../modal.php'); ?>




<script type="text/javascript" >
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 
$(document).ready(function() {
	cronmenu(nrousuario,rol,"2");
  listar();
});

function listar(){
  $.fn.dataTable.ext.errMode = 'none';
	var table_finalizados = $('#table_finalizados').DataTable( {
			"ajax": "seguimientotickets/_listadofinalizados.php",
			"language": {
      	url:'DataTables/es-ar.json'},
        "aaSorting": [],
			"columnDefs": [ {
				"targets": -1,
				"data": null,
				"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>"
			}],
      retrieve: true,
	});
  //table_finalizados.order( [ 3, 'desc' ] );
	obtener_data_editar("#table_finalizados tbody", table_finalizados);
  global_tables(table_finalizados);
  //setInterval( function () {table_finalizados.ajax.reload(null, false);}, 60000 );
}

$('#table_finalizados').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log( 'Ocurrió un error al cargar la tabla: ', message);
})
		
var obtener_data_editar = function(tbody, table_finalizados){
	// Este para Ver
	$(tbody).on("click", "button.ver", function(){
		var data = table_finalizados.row( $(this).parents("tr") ).data();
    verhistorial(data['nro_ticket'],data['id'],data["tipo_ticket"],table_finalizados);
	});
}


</script>
		