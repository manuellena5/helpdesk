<?php 
session_start();
include("../funciones.php");
salir(); 
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<div class="table-responsive-xl">
	<div style="text-align:right">
	<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/altaentidades.php');">Alta de Entidades</button>
	</div>
	<table id="table_abm_entidades" class="table table-hover" style="width:100%;">
		<h4>ABM Entidades</h4><br>
        <thead>
            <tr>
                <th>Razon</th>
                <th>Domicilio</th>
                <th>Localidad</th>
                <th>Cuit</th>
                <th>Telefono</th>
                <th>Mail</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Razon</th>
                <th>Domicilio</th>
                <th>Localidad</th>
                <th>Cuit</th>
                <th>Telefono</th>
                <th>Mail</th>
                <th>Acciones</th>
            </tr>
        </tfoot>
    </table>
</div>
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
	var table_abm_entidades = $('#table_abm_entidades').DataTable( {
		"ajax":"parametrizacion/_listarentidades.php",
		"language": {
      		url:'DataTables/es-ar.json'},
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": " <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'><button type='button' class='editar btn btn-primary'><i class='fas fa-pen-square'></i></button></span> "
		}]
	});
	obtener_data_editar("#table_abm_entidades tbody", table_abm_entidades);
}
  
$('#table_abm_entidades').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_abm_entidades){
	$(tbody).on("click", "button.editar", function(){
		var data = table_abm_entidades.row( $(this).parents("tr") ).data();
    	cargarPagina("parametrizacion/editar.php?codigo="+data['codigo']+"&parcod=7");   
	});
}
</script>
