<?php 
session_start();
include("../funciones.php");
salir();
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<div class="table-responsive-xl">
	<table id="table_en_espera" class="table table-hover" style="width: 100%;">
		<h4>Tickets en espera</h4><br>
      <thead>
        <tr>
          <th>Nro_Ticket</th>
          <th>Emisor</th>
          <th>Asunto</th>
          <th>Fecha</th>
          <th>Adj</th>
          <th>Accion</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>Nro_ticket</th>
          <th>Emisor</th>
          <th>Asunto</th>
          <th>Fecha</th>
          <th>Adj</th>
          <th>Accion</th>
        </tr>
      </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include('../modal.php'); ?>

<script type="text/javascript" >
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
}); 	 
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 
var table_en_espera = "";
$(document).ready(function() {
  cronmenu(nrousuario,rol,"2");
	listar();
});

function listar(){
  //Para luego capturar error de carga de la tabla
  
	 table_en_espera = $('#table_en_espera').DataTable( {
				"ajax":"seguimientotickets/_listadoenespera.php",
				"language": {
      		url:'DataTables/es-ar.json'},
          "aaSorting": [],
				"columnDefs": [ {
				  "targets": -1,
				  "data": null,
				  "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Responder'><button type='button' class='responder btn btn-primary'><i class='fas fa-edit'></i></button></span>      <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Derivar ticket'><button type='button' class='derivar btn btn-primary'><i class='fa fa-users' aria-hidden='true'></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Finalizar ticket'><button type='button' class='borrar btn btn-primary'><i class='fas fa-file-archive'></i></button></span>"
				}],
        rowCallback:function(row,data){         
      if(data["leido"] == 2){
         $($(row)).addClass("bg-danger");
      }
    },
        retrieve: true,    
	});
  //table_en_espera.order( [ 3, 'desc' ] );
	obtener_data_editar("#table_en_espera tbody", table_en_espera);
  global_tables(table_en_espera);
  
  $.fn.dataTable.ext.errMode = 'none';
}
  
$('#table_en_espera').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_en_espera){
	$(tbody).on("click", "button.responder", function(){
		var data = table_en_espera.row( $(this).parents("tr") ).data();
    if (data["emisor"]!="") {
      cargarPagina("responder.php?id_mail="+data["id_mail"]+"&url=espera");
    }else{
      alert("No puede realizar esta Accion.");
    }   
	});

	$(tbody).on("click", "button.derivar", function(){
		var data = table_en_espera.row( $(this).parents("tr") ).data();
		cargarPagina("derivar.php?id_mail="+data["id_mail"]+"&url=espera&id="+data["id"]);
	});

	// Este para Borrar
	$(tbody).on("click", "button.borrar", function(){
		var data = table_en_espera.row( $(this).parents("tr") ).data();
		cargarPagina("seguimientotickets/finalizarticket.php?id="+data["id"]+"&url=espera");
	});

	// Este para Ver
	$(tbody).on("click", "button.ver", function(){
		var data = table_en_espera.row( $(this).parents("tr") ).data();
		verhistorial(data["nro_ticket"],data["id"],data["tipo_ticket"],table_en_espera);
	});
}


</script>
		