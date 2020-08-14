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
  <div style="text-align:right">
		<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/altahora.php');">Alta de Franja Horaria</button>
	</div>
  <table id="table_abm_franja_horaria" class="table table-hover">
    <h4>ABM Franja Horas</h4><br>
    <thead>
      <tr>
        <th scope="col">Franja Horaria</th>
        <th scope="col">Acciones</th>
      </tr>
    </thead>
                  
    <tbody> 
    </tbody>
            
    <tfoot>
      <tr>
        <th scope="col">Franja Horaria</th>
        <th scope="col">Acciones</th>
      </tr>
    </tfoot>
  </table>
</div>

<script>
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
$(document).ready(function (){
  listar();
});
function listar(){
  var table_abm_franja_horaria = $('#table_abm_franja_horaria').DataTable( {
    "ajax": "parametrizacion/_listar.php?parcod=4",
    "language": {
      url:'DataTables/es-ar.json'},
    "columnDefs": [ {
      "targets": -1,
      "data": null,
      "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'><button type='button' class='editar btn btn-primary'><i class='fas fa-pen-square'></i></button></span>"
    }],  
  });
  obtener_data_editar("#table_abm_franja_horaria tbody", table_abm_franja_horaria);
}

$('#table_abm_franja_horaria').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_abm_franja_horaria){

  // Este para Editar
  $(tbody).on("click", "button.editar", function(){
    var data = table_abm_franja_horaria.row( $(this).parents("tr") ).data();
    cargarPagina("parametrizacion/editar.php?parcod=4&parvalor="+data["parvalor"]);
  });
}
</script>