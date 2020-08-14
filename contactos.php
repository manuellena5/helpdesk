<?php
session_start();
include("connect.php");
include("funciones.php");
salir();
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
  <div style="text-align:right">
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('nuevo_contacto.php');">Nuevo Contacto</button>
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('nuevo_grupo.php');">Nuevo Grupo</button>
  
<?php include("agenda.php"); ?>
</div>
<script>
  $('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
  $(document).ready(function() {
    //cargar_select(0);
    //limpiarselect("secuetiquetas");
    listar();
  });
  function listar(){
  //Para luego capturar error de carga de la tabla
  
   table_contactos = $('#tablaAgenda').DataTable( {
        "ajax":"_listadoagenda.php",
        "language": {
          url:'DataTables/es-ar.json'},
        "columnDefs": [ {
          "targets": 0,
          "data": null,
          "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'><button type='button' class='editar btn btn-primary'><i class='fas fa-edit'></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Borrar Mail'><button type='button' class='borrar btn btn-primary'><i class='fas fa-trash-alt'></i></button></span>"
        }],
        retrieve: true,    
  });
   table_contactos.order( [ 1, 'asc' ] );
  obtener_data_editar("#tablaAgenda tbody", table_contactos);
  $.fn.dataTable.ext.errMode = 'none';
}
  
$('#tablaAgenda').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_contactos){
  // Este para Editar
  $(tbody).on("click", "button.editar", function(){
    var data = table_contactos.row( $(this).parents("tr") ).data();
    if(data['mail'] == null){
      cargarPagina("editar_grupo.php?codigo="+data['codigo']);
    }else{
      cargarPagina("editar_contacto.php?mail="+data['mail']);
    }
  });

  // Este para Borrar
  $(tbody).on("click", "button.borrar", function(){
    var data = table_contactos.row( $(this).parents("tr") ).data();
    cargarPagina("_eliminarcontacto.php?codigo="+data["codigo"]+"&mail="+data['mail']);
  });

}
</script>