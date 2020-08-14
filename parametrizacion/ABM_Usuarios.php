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
		<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/altausuario.php');">Alta de Usuario</button>
	</div>
	<table id="table_abm_usuarios" class="table table-hover nowrap" style="width:100%">
		<h4>ABM Usuario</h4><br>
        <thead>
            <tr>
                <th>Usuario</th>
				<th>Nombre</th>
				<th>Rol</th>
				<th>Franja Dia</th>
				<th>Franja Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Usuario</th>
				<th>Nombre</th>
				<th>Rol</th>
				<th>Franja Dia</th>
				<th>Franja Hora</th>
                <th>Acciones</th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formulario">
      <div class="modal-body" id="cuerpo">
        <div class="table-responsive-xl">
          <table id="tablapermisos" class="table display" style="width:100%">
            <h4>Permisos</h4>
            <br>
              <thead>
                  <tr>
                      <th></th>
                      <th>Procesos</th>
                       <th></th>
                  </tr>
              </thead>
              <tfoot>
                  <tr>
                      <th></th>
                      <th>Procesos</th>
                       <th></th>
                  </tr>
              </tfoot>
          </table>
        </div>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" id="seleccion" class="close" data-dismiss="modal" aria-label="Close">Seleccion</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" >
  var selected = new Array();
 var table_abm_usuarios;
 var tablapermisos;
 var usuario = "";
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
	 table_abm_usuarios = $('#table_abm_usuarios').DataTable( {
		"ajax":"parametrizacion/_listarusuarios.php",
		"language": {
      		url:'DataTables/es-ar.json'},
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": " <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'><button type='button' class='editar btn btn-primary'><i class='fas fa-pen-square'></i></button></span>  <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Permisos'><button type='button' class='permisos btn btn-primary' data-target='#exampleModalLong' data-toggle='modal'><i class='fas fa-arrow-alt-circle-right'></i></button></span> "
		}]
	});
	obtener_data_editar("#table_abm_usuarios tbody", table_abm_usuarios);
}
  
$('#table_abm_usuarios').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})
	
var obtener_data_editar = function(tbody, table_abm_usuarios){
	$(tbody).on("click", "button.editar", function(){
		var data = table_abm_usuarios.row( $(this).parents("tr") ).data();
    	cargarPagina("parametrizacion/editar.php?nrousuario="+data['nrousuario']+"&parcod=usuario"); 
	});
	$(tbody).on("click", "button.permisos", function(){
		var data = table_abm_usuarios.row( $(this).parents("tr") ).data();
    	permisos(data['nrousuario']);
	});
}


function permisos(numero){

	 tablapermisos = $('#tablapermisos').DataTable({
     'destroy' : true,
      'ajax': 'parametrizacion/_permisos.php?usu='+numero,
      "language": {
        url:'DataTables/es-ar.json'},
      'columnDefs': [
        {
          'targets': 2,
          'searchable': false,
          'orderable': false,
          'className': 'dt-body-center',
          'render': function (data, type, full, meta){
            if (data == "N") {
              return '<input type="checkbox" name="id[]" value="'+data+'" checked>';
            }else{
              return '<input type="checkbox" name="id[]" value="'+data+'">';   
            }
            

         }
         },
         {
          'targets':[0],
          'visible':false,
          'searchable':false
         }

      ],
    
      'select': {
        'style': 'multi'
      },
      'order': [[1, 'asc']],
      "paging": false
   });

  $('#seleccion').on('click', function(){
    var nombre = "";
    $("tr").find("input:checkbox:checked").each(function() {
      nombre = $(this).parent().parent().find("td:first").text();
      
      selected.push(nombre);
    });

    usuario = numero;
    var data = new FormData();
    data.append('usuario',usuario);
    data.append('selected',selected);
    //console.log(usuario);
    //console.log(selected);

    $.ajax({
          url: "parametrizacion/_permisos.php",   // Url to which the request is send.
          type: "POST",                           // Type of request to be send, called as method.
          data: data,                             // Data sent to server, a set of key/value pairs (i.e. form fields and values)
          contentType: false,                     // The content type used when sending data to the server.
          cache: false,                           // To unable request pages to be cached.
          processData:false,                      // To send DOMDocument or non processed data file it is set to false.
        })
        .done(function(respuesta) {
          //cargarPagina("parametrizacion/ABM_Usuarios.php?donde=ABM Usuarios&ver=S&men=Se modifico los permisos");
          alert("Los cambios realizados se veran reflejados en el proximo logueo del usuario");
          tablapermisos.ajax.reload(null, false);
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
          console.log("Algo ha fallado: " +  textStatus);
        })
  
  });    


};
$("#exampleModalLong").on('hidden.bs.modal', function () {
  cargarPagina("parametrizacion/ABM_Usuarios.php?donde=ABM Usuarios&ver=S&men=Se modifico los permisos");
});


</script>