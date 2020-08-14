<?php
session_start();
include("../connect.php"); 
include("../funciones.php");
salir();
?>
<h3>Busqueda Avanzada</h3>
<form action="" id="buscarid" method="post" accept-charset="utf-8">
	<input type="text" name="buscar" id="buscar" placeholder="Busqueda">
</form>
<button name="buscar" id="buscar" onclick="buscar();">Buscar</button>
<div id="tabl" class="table-responsive-xl vOculto"><br>
	<table id="table_busqueda_avanzada" class=" table table-hover">
    <thead>
      <tr>
        <th>Nro_Ticket</th>
        <th>Emisor</th>
        <th>Asunto</th>
        <th>Fecha</th>
        <th>Accion</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Nro_Ticket</th>
        <th>Emisor</th>
        <th>Asunto</th>
        <th>Fecha</th>
        <th>Accion</th>
      </tr>
    </tfoot>
  </table>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>



<script>
var nrousuario  = '<?php echo $_SESSION["nrousuario"];?>';
var rol         = '<?php echo $_SESSION["rol"];?>'; 
var table       = "";
 
$(document).ready(function() { 
	cronmenu(nrousuario,rol,"2");
});

function buscar(){
	var campobuscar = $("#buscar").val();

	if(campobuscar != ""){
        
        if (table_busqueda_avanzada != null && table_busqueda_avanzada!= "") {
          $("#table_busqueda_avanzada").dataTable().fnDestroy();
        }
		    listar(campobuscar);
		    $("#tabl").removeClass("vOculto").addClass("vVisible");
	     
  } else {
		alert("El campo buscar no esta completo.");
	}

}

function listar(dat){
  //Para luego capturar error de carga de la tabla
	table_busqueda_avanzada = $('#table_busqueda_avanzada').DataTable( {
		"ajax":  "busquedaAvanzada/_buscar.php?buscar="+dat,
		"language": {
      		url:'DataTables/es-ar.json'
      	},
        "aaSorting": [],
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>"
    }],
    retrieve: true,
	});
	obtener_data_editar("#table_busqueda_avanzada tbody", table_busqueda_avanzada);
  $.fn.dataTable.ext.errMode = 'none';
}

$('#table_busqueda_avanzada').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log('Ocurrió un error al cargar la tabla: ', message);
})
		
var obtener_data_editar = function(tbody, table_busqueda_avanzada){
	// Este para Ver
	$(tbody).on("click", "button.ver", function(){
		var data = table_busqueda_avanzada.row( $(this).parents("tr") ).data();
		verhistorial(data['nro_ticket'],data["id"],data["tipo_ticket"],table_busqueda_avanzada);
    //nro,idticket,tipo_ticket,nombre_tabla
	});
}

</script>