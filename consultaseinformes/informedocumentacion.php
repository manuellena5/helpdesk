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
	<h4>Informe Documentación</h4><br>

  <b>Seleccione tipo de Documento</b><br>
  <div class="rounded">
        <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="ingreso" value="I" checked>
        <label for="ingreso">Ingreso Documentación</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="envio" value="E">
        <label for="envio">Envio Documentación</label>
      </div>
    </div<br>
  <input type="button" name="mostrar" value="Buscar" onclick="mostrar();">
  <input type="button" name="imprimir" value="Imprimir" onclick="imprimir();">

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
  <table id="table_informe_documentacion" class="table table-hover nowrap " style="width:100%">
    <thead>
    	<tr>
          <th colspan="1">Nro.</th> 
			    <th colspan="1">Fecha</th>           
          <th colspan="1">Remitente</th> 
          <th colspan="1">Recibido Por</th> 
          <th colspan="1">Tipo</th>
          <th colspan="1"></th>
        </tr>
    </thead>      
    <tbody> 
    </tbody>
    <tfoot>
	      <tr>
          <th colspan="1">Nro.</th> 
          <th colspan="1">Fecha</th>           
          <th colspan="1">Remitente</th> 
          <th colspan="1">Recibido Por</th> 
          <th colspan="1">Tipo</th>
          <th colspan="1"></th>
	      </tr>
    </tfoot>
  </table>
</div>
</div>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>

<script>
var table_informe_documentacion="";
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 

$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});

function mostrar(){
  $("#table_informe_documentacion").dataTable().fnDestroy();
  if(document.getElementById("ingreso").checked){
    var valor = document.getElementById("ingreso").value;  
  }

  if(document.getElementById("envio").checked){
    var valor = document.getElementById("envio").value;
  }
  listar(valor);
}

$("#fechamin").datepicker({ onSelect: function () { table_informe_documentacion.draw(); },dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true });
$("#fechamax").datepicker({ onSelect: function () { table_informe_documentacion.draw(); },dateFormat: 'dd/mm/yy', changeMonth: true, changeYear: true }); 

$(document).ready(function() {
  $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
         if (settings.nTable.id != "table_informe_documentacion") {return true;}
          var min = $('#fechamin').datepicker("getDate");
          var max = $('#fechamax').datepicker("getDate");
          var startDate = new Date(data[5]);
          if (min == null && max == null) { return true; }
          if (min == null && startDate <= max) { return true;}
          if(max == null && startDate >= min) {return true;}
          if (startDate <= max && startDate >= min) { return true; }
          return false;
      }
    );  
  cronmenu(nrousuario,rol,"2");
});
 
function listar(x){	
  	table_informe_documentacion = $('#table_informe_documentacion').DataTable( {
    	"ajax": "consultaseinformes/_listardocumentacion.php?valor="+x,
    	"language": {
      		url:'DataTables/es-ar.json'},
    	"columnDefs": [    
      {
        "targets": [ 5 ],
        "searchable": true,
         "visible": false
      }],
      retrieve: true,
  	});
    table_informe_documentacion.order( [ 0, 'asc' ] );
    // Event listener to the two range filtering inputs to redraw on input
	obtener_data_editar("#table_informe_documentacion tbody", table_informe_documentacion);
  $.fn.dataTable.ext.errMode = 'none';
}

$('#table_informe_documentacion').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_informe_documentacion){
  	// Este para Ver
    $(tbody).on("click", "button.ver", function(){
      table_informe_documentacion.draw();
      ta = table_informe_documentacion.row( $(this).parents("tr") ).data();
	    verhistorial(data['nro_ticket'],data['id'],data["tipo_ticket"],table_informe_documentacion);  
	}); 
      $(tbody).on("click", "button.editar", function(){
        var data = table_informe_documentacion.row( $(this).parents("tr") ).data();
        if (data["estado"]=="Finalizado") {
          alert("No puede realizar esta accion (Ticket finalizado) ");
        }else{
        cargarPagina("consultaseinformes/editarpresencial.php?id="+data["id"]);
        } 
  });
}

function imprimir(){
  if(document.getElementById("ingreso").checked){
    var ing_env = document.getElementById("ingreso").value;  
  }
  if(document.getElementById("envio").checked){
    var ing_env = document.getElementById("envio").value;
  }
  if(!document.getElementById("fechamin").value){
    var desde  = "2019/01/01";
  } else{
    var desde  = convertDateFormat(document.getElementById("fechamin").value);
  }
  if(document.getElementById("fechamax").value == ""){
    var hasta  = "3019/01/01";
  } else {
    var hasta  = convertDateFormat(document.getElementById("fechamax").value);

  }

function convertDateFormat(string) {
  var info = string.split('/');
  return info[2] + '/' + info[1] + '/' + info[0];
}
 window.open("consultaseinformes/pdf.php?ing_env="+ing_env+"&desde="+desde+"&hasta="+hasta, '_blank');
}
</script>