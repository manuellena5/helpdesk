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
	<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/altacategoria.php');">Alta de Etiquetas</button>
	</div>
	<table id="table_abm_categorias" class="table table-hover nowrap" style="width:100%">
		<h4>ABM Etiquetas</h4><br>
        <thead>
            <tr>
                <th>Descripcion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Descripcion</th>
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
	var table_abm_categorias = $('#table_abm_categorias').DataTable( {
		"ajax":"parametrizacion/_listar.php?parcod=5",
		"language": {
      		url:'DataTables/es-ar.json'},
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": " <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'><button type='button' class='editar btn btn-primary'><i class='fas fa-pen-square'></i></button></span> "
		}]
	});
	obtener_data_editar("#table_abm_categorias tbody", table_abm_categorias);
}
  
$('#table_abm_categorias').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_abm_categorias){
	$(tbody).on("click", "button.editar", function(){
		var data = table_abm_categorias.row( $(this).parents("tr") ).data();
    	cargarPagina("parametrizacion/editar.php?parvalor="+data['parvalor']+"&parcod=5");   
	});
}
</script>
