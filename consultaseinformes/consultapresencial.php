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
	<h4>Consulta documentacion</h4><br>
  <table border="0" cellspacing="5" cellpadding="5"> 
    <tbody>
      <tr>
            <td>Fecha minima:</td>
            <td><input type="text" id="fechamin" name="fechamin" placeholder="Desde"></td>
        </tr>
        <tr>
            <td>Fecha maxima</td>
            <td><input type="text" id="fechamax" name="fechamax" placeholder="Hasta"></td>
        </tr>
    </tbody>
  </table>
  <div class="table-responsive ">
  <table id="table_consula_presencial" class="table table-hover nowrap " style="width:100%">
    <thead>
    	<tr>
    		  <th colspan="1">Accion</th>
          <th colspan="1">Nro_Ticket</th> 
			    <th colspan="1">Asunto</th>           
          <th colspan="1">Estado</th> 
          <th colspan="1">Entidad</th> 
          <th colspan="1">Documento</th>
          <th colspan="1">Tipo Ingreso</th>
          <th colspan="1"></th>
          <th colspan="1"></th>
          <th colspan="1"></th>
          <th colspan="1"></th>
          <th colspan="1"></th>
          <th colspan="1">Nro. Mesa entrada</th>
        </tr>
    </thead>      
    <tbody> 
    </tbody>
    <tfoot>
	      <tr>
          <th colspan="1">Accion</th>
          <th colspan="1">Nro_Ticket</th> 
           <th colspan="1">Asunto</th>    
            <th colspan="1">Estado</th> 
            <th colspan="1">Entidad</th> 
            <th colspan="1">Documento</th>
            <th colspan="1">Tipo Ingreso</th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1"></th>
            <th colspan="1">Nro. Mesa entrada</th>
	      </tr>
    </tfoot>
  </table>
</div>
</div>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>

<script>
var table_consula_presencial="";
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 

$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});

$("#fechamin").datepicker({ onSelect: function () { table_consula_presencial.draw(); },dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true });
$("#fechamax").datepicker({ onSelect: function () { table_consula_presencial.draw(); },dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true }); 

$(document).ready(function() {

  $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
         if (settings.nTable.id != "table_consula_presencial") {return true;}
          var min = $('#fechamin').datepicker("getDate");
          var max = $('#fechamax').datepicker("getDate");
          var startDate = new Date(data[7]);
          if (min == null && max == null) { return true; }
          if (min == null && startDate <= max) { return true;}
          if(max == null && startDate >= min) {return true;}
          if (startDate <= max && startDate >= min) { return true; }
          return false;
      
      }
    );  

  cronmenu(nrousuario,rol,"2");
  listar();

});

function listar(){	
  	table_consula_presencial = $('#table_consula_presencial').DataTable( {
    	"ajax": "consultaseinformes/_listarpresenciales.php",
    	"language": {
      		url:'DataTables/es-ar.json'},
    	"columnDefs": [ {
      		"targets": 0,
      		"data": null,
      		"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>          <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Editar'>  <button type='button' class='editar btn btn-primary'><i class='fas fa-edit'></i></button></span>"},    
      {
        "targets": [ 7 ],
        "searchable": true,
         "visible": false
      },
      {
        "targets": [ 8 ],
        "searchable": false,
         "visible": false
      },
      {
        "targets": [ 9 ],
        "searchable": false,
         "visible": false
      },
      {
        "targets": [ 10 ],
        "searchable": false,
         "visible": false
      },
      {
        "targets": [ 11 ],
        "searchable": false,
         "visible": false
      },
      {
        "targets": [ 12 ],
        "searchable": true,
         "visible": true
      }],
      retrieve: true,
  	});
    // Event listener to the two range filtering inputs to redraw on input
	obtener_data_editar("#table_consula_presencial tbody", table_consula_presencial);
  $.fn.dataTable.ext.errMode = 'none';
}

$('#table_consula_presencial').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_consula_presencial){
  	// Este para Ver
  $(tbody).on("click", "button.ver", function(){
      table_consula_presencial.draw();
      ta = table_consula_presencial.row( $(this).parents("tr") ).data();
	    verhistorial(ta['nro_ticket'],ta['id'],ta["tipo_ticket"],table_consula_presencial);
	}); 
  $(tbody).on("click", "button.editar", function(){
        var data = table_consula_presencial.row( $(this).parents("tr") ).data();
        if (data["estado"]=="Finalizado") {
          alert("No puede realizar esta accion (Ticket finalizado) ");
        }else{
        cargarPagina("consultaseinformes/editarpresencial.php?nro_ticket="+data["nro_ticket"]+"&tipo_ticket="+data['tipo_ticket']);
        }
  });
}
</script>