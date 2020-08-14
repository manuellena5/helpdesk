<?php
session_start();
include("../connect.php"); 
include("../funciones.php");
salir();
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>
<input type="text" id="nombre_pagina" name="nombre_pagina" hidden value="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="table-responsive-xl">
	<h4>Consulta General</h4><br>
  <table border="0" cellspacing="5" cellpadding="5"> 
  	<tbody>
  		<tr>
            <td>Fecha minima:</td>
            <td><input type="text" id="fechamin" name="fechamin" placeholder="Desde:"></td>
        </tr>
        <tr>
            <td>Fecha maxima</td>
            <td><input type="text" id="fechamax" name="fechamax" placeholder="Hasta:"></td>
        </tr>
    </tbody>
  </table>
  <div class="table-responsive">
  <div id="areaImprimir">
  <table id="table_consulta_general" class="table table-hover nowrap" style="width:100%">
    <thead>
    	<tr>
    		    <th colspan="1">Accion    </th>
            <th colspan="1">Nro_Ticket</th> 
			      <th scope="col">Emisor</th>
            <th scope="col">Asunto</th> 
            <th colspan="1">Fecha</th> 
            <th colspan="1">UltFecha</th> 
            <th colspan="1">Estado</th> 
            <th colspan="1">Usuario</th> 
            <th colspan="1">Rol</th>
            <th colspan="1">Etiqueta Ppal</th>
            <th colspan="1">Tipo ingreso</th>
            <th colspan="1"></th>
            <th colspan="1"></th>
		    <th colspan="1"></th>
        </tr>
      	<tr>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th> 
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
          <th scope="col"></th>
      	</tr>
    </thead>      
    <tbody> 
    </tbody>
    <tfoot>
	      <tr>
	      	<th scope="col-2">Accion </th>
	        <th scope="col">Nro_Ticket</th>
	        <th scope="col">Emisor</th>
	        <th scope="col">Asunto</th>
	        <th scope="col">Fecha</th>
	        <th scope="col">UltFecha</th>
	        <th scope="col">Estado</th>
	        <th scope="col">Usuario</th>
	        <th scope="col">Rol</th>
	        <th scope="col">Etiqueta Ppal</th>
	        <th scope="col"></th>
        	<th scope="col"></th>
        	<th scope="col"></th>
          <th scope="col"></th>
	      </tr>
    </tfoot>
  </table>
</div>
</div>
</div>

<!-- Modal -->
<?php include("../modal.php"); ?>




<script>
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});

var table_consulta_general="";
var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
var rol = '<?php echo $_SESSION["rol"];?>'; 

    $("#fechamin").datepicker({ onSelect: function () { table_consulta_general.draw(); },dateFormat: 'dd/mm/y', changeMonth: true, changeYear: true });
    $("#fechamax").datepicker({ onSelect: function () { table_consulta_general.draw(); },dateFormat: 'dd/mm/y', changeMonth: true, changeYear: true });  


$(document).ready(function() {

   $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
         if (settings.nTable.id != "table_consulta_general") {return true;}
          var min = $('#fechamin').datepicker("getDate");
          var max = $('#fechamax').datepicker("getDate");
          var startDate = new Date(data[14]);
          if (min == null && max == null) { return true; }
          if (min == null && startDate <= max) { return true;}
          if(max == null && startDate >= min) {return true;}
          if (startDate <= max && startDate >= min) { return true; }
          return false;
      
      }
    );  


  cronmenu(nrousuario,rol,"2");
  listarr();

});
  $.fn.dataTable.ext.errMode = 'none';
  

    
 
  

function listarr(){	
  	table_consulta_general = $('#table_consulta_general').DataTable( {
      

    	"ajax": "consultaseinformes/_listadoconsultageneral.php",
    	"language": {
      		url:'DataTables/es-ar.json'},
          "aaSorting": [],
    	"columnDefs": [ {
      		"targets": 0,
      		"data": null,
      		"defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Ver historial'>  <button type='button' class='ver btn btn-primary' data-toggle='modal' data-target='#exampleModalLong'><i class='fa fa-eye' aria-hidden='true' ></i></button></span>          <span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='Hacer observacion'>  <button type='button' class='observacion btn btn-primary'><i class='fas fa-angle-double-right'></i></button></span>"},
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
        "searchable": false,
	       "visible": false
	    },
	    {
	      "targets": [ 13 ],
        
	       "visible": false
	    },
      {
        "targets": [ 14 ],
        
         "visible": false
      },
      /*{
        "targets": [ 4 ],
        "order": 'desc',
      }*/],

    	initComplete: function () {
            this.api().columns([2,3,6,7,8,9,10]).every( function () {
                var column = this;
                var select = $('<select><option value="">Seleccione ...</option></select>')
                .appendTo( $(column.header()).empty() )
                .on( 'change', function () {
                 	var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );
                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    });
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                });
            });
        }

        /*
dom: 'Bfrtip',
        buttons: [{
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL'
        }],
         "scrollX": true*/
  	});
//table_consulta_general.order( [ 4, 'desc' ] );
    // Event listener to the two range filtering inputs to redraw on input
   

	obtener_data_editar("#table_consulta_general tbody", table_consulta_general);
  global_tables(table_consulta_general);
	//setInterval( function () {table_consulta_general.ajax.reload(null, false);}, 90000 );
	//Para luego capturar error de carga de la tabla
   $('#fechamin, #fechamax').change(function () {
      table_consulta_general.draw();

  });

}


$('#table_consulta_general').on('error.dt', function(e, settings, techNote, message) {
   alert("Ocurrió un error al cargar la tabla");
   console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, table_consulta_general){
  	// Este para Ver

    $(tbody).on("click", "button.ver", function(){

      var data = table_consulta_general.row( $(this).parents("tr") ).data();
	    verhistorial(data['nro_ticket'],0,data["tipo_ticket"],table_consulta_general);
      //verhistorial2(data['nro_ticket'],data['id'],data["tipo_ticket"],table_consulta_general,0);
      
	}); 


      $(tbody).on("click", "button.observacion", function(){
              

        var data = table_consulta_general.row( $(this).parents("tr") ).data();
        if (data["estado"]=="Finalizado") {
          alert("No puede realizar esta accion (Ticket finalizado) ");
        }else{
        cargarPagina("consultaseinformes/observarticket.php?id="+data["id"]+"&url=consultageneral");
        }
      
  });


}



</script>