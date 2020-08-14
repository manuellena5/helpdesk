<?php 
session_start();
include("../funciones.php");
salir();
if (isset($_GET['ver'])) {
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php }
} ?> 

<input type="text" id="nombre_pagina" name="nombre_pagina" hidden value="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="table-responsive-xl">
	<table id="table_seguimiento" class="table table-hover" style="width: 100%;">
		<h4>Tickets Derivados</h4><br>
    <thead>
      <tr>
        <th>Nro_Ticket</th>
        <th>Asunto</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Accion</th>
      </tr>
    </thead>
    <tfoot>
      <tr>
        <th>Nro_Ticket</th>
        <th>Asunto</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Accion</th>
      </tr>
    </tfoot>
  </table>
</div>


<?php include("../modal.php");  ?>






<script type="text/javascript" >
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});	  
var nrousuario  = '<?php echo $_SESSION["nrousuario"];?>';
var rol         = '<?php echo $_SESSION["rol"];?>'; 
var table       = "";
 
$(document).ready(function() { 
   
  cronmenu(nrousuario,rol,"2");
	listar();




});

function listar(){
  //Para luego capturar error de carga de la tabla

	table_seguimiento = $('#table_seguimiento').DataTable( {
		"ajax": "seguimientotickets/_seguimiento.php",
		"language": {
      url:'DataTables/es-ar.json'},
		"columnDefs": [ {
			"targets": -1,
			"data": null,
			"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>   <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Hacer observacion'>  <button type='button' class='observacion btn btn-primary'><i class='fas fa-angle-double-right'></i></button></span>      <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Re-Activar Ticket'><button type='button' class='derivar btn btn-primary'><i class='fas fa-level-up-alt' aria-hidden='true'></i></button></span>"
    }],
    "aaSorting": [],
    rowCallback:function(row,data){         
      if(data["leido"] == 4){
        $($(row)).addClass("bg-secondary"); 
      }
      if(data["leido"] == 2){
         $($(row)).addClass("bg-danger");
      }
      if(data["leido"] == 0){
        $($(row)).addClass("bg-primary"); 
      }
    },
    retrieve: true,
	});
	obtener_data_editar("#table_seguimiento tbody", table_seguimiento);
  global_tables(table_seguimiento);
  
  $.fn.dataTable.ext.errMode = 'none';
}

$('#table_seguimiento').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log('Ocurrió un error al cargar la tabla: ', message);
})
		
var obtener_data_editar = function(tbody, table_seguimiento){
  $(tbody).on("click", "button.observacion", function(){
    var data = table_seguimiento.row( $(this).parents("tr") ).data();
    if (data["estado"]!="F") {
      cargarPagina("consultaseinformes/observarticket.php?id="+data["id"]+"&url=seguimiento");
    }else{
      alert("No puede realizar esta accion (Ticket finalizado) ");
    }
  });

	$(tbody).on("click", "button.derivar", function(){
		var data = table_seguimiento.row( $(this).parents("tr") ).data();
    if (data["estado"]=="F") {
		  cargarPagina("derivar.php?id_mail="+data["id_mail"]+"&url=seguimiento&id="+data["id"]);
    } else {
      alert("No puede realizar esta accion (Ticket no finalizado) ");
    }
	});

	// Este para Ver
	$(tbody).on("click", "button.ver", function(){
		var data = table_seguimiento.row( $(this).parents("tr") ).data();
		verhistorial(data["nro_ticket"],data["id"],data["tipo_ticket"],table_seguimiento);
	});
}






</script>	