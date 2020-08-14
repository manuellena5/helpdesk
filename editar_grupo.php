<?php 
session_start();
include("connect.php");
include("funciones.php");
salir(); 

//Seleccionamos el nombre del grupo
$seleccionnom = mysqli_query($con, "SELECT nombre FROM fil01lib WHERE codigo = '".$_GET['codigo']."'");
$renom = mysqli_fetch_array($seleccionnom);
$nombre = $renom['nombre'];

//seleccionamos todos los mail de fil03lib
$contamosmail = mysqli_query($con, "SELECT f01.mail
FROM fil03lib f03
INNER JOIN fil01lib f01 ON f03.cod_mail = f01.codigo
WHERE f03.cod_grupo = '".$_GET['codigo']."'");
while ($re= mysqli_fetch_array($contamosmail)) {
	$arreglo["data"][]= $re;
}
$num = mysqli_num_rows($contamosmail);
$num = $num -1;
?>
<div style="text-align:right">
<button type="button" class="btn btn-primary mb-2" onclick="enviarmail();">Editar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('contactos.php?ver=no&men=&donde=');">Volver</button>
</div>
<h3>Editar Grupo</h3>
<form  method="POST" accept-charset="utf-8" id="formulario">
	<input type='text' name='codigo' id='codigo' value='<?php echo $_GET['codigo']; ?>' hidden>
	<div class="container">
	  <div class="row">
  	  <div class="col-6">
  	    <b>Nombre del Grupo</b>
  	    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre" value="<?php echo $nombre; ?>" required>

  	  </div>
  </div>
</form><br>
<div class="container">
	<div class="row">
		<div class="col-sm">
			<div id="mail">
				<?php
				for ($i=0; $i <=$num ; $i++) { 
					echo "<div id='i".$i."' class='col-sm' style='overflow: hidden;text-overflow: ellipsis;float:left;width: 30%;outline: green solid thin'>".$arreglo['data'][$i]['mail']."<input type='text' name='m".$i."' id='m".$i."' value='".$arreglo['data'][$i]['mail']."' hidden><button type='button' onclick='eliminiar();' class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
				}?>
				
			</div>
		</div>
	</div>
</div><br>
<div class="table-responsive-xl">
  <table id="tablagrupo" class="table display" style="width:100%">
      <thead>
        <tr>
            <th>Accion</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
            <th>Accion</th>
            <th>Nombre</th>
            <th>Email</th>
        </tr>
      </tfoot>
  </table>
</div>

<br>
<script>

	var num = <?php echo $num; ?>;
	var mail;
	$(document).ready(function() {
    listar();
  });
  function listar(){
  //Para luego capturar error de carga de la tabla
  
   grupo = $('#tablagrupo').DataTable( {
        "ajax":"_listadogrupo.php",
        "language": {
          url:'DataTables/es-ar.json'},
        "columnDefs": [ {
          "targets": 0,
          "data": null,
          "defaultContent": "<span class='d-inline-block' tabindex='0' data-toggle='tooltip' title='+'><button type='button' class='mas btn btn-primary'><i class='fas fa-plus-circle'></i></button></span>"
        }],
        retrieve: true,    
  });
  obtener_data_editar("#tablagrupo tbody", grupo);
  
  $.fn.dataTable.ext.errMode = 'none';
}
  
$('#tablagrupo').on('error.dt', function(e, settings, techNote, message) {
  alert("Ocurrió un error al cargar la tabla");
  console.log( 'Ocurrió un error al cargar la tabla: ', message);
})

var obtener_data_editar = function(tbody, grupo){
  // Este para mas
  $(tbody).on("click", "button.mas", function(){
    var data = grupo.row( $(this).parents("tr") ).data();
    num = num+1;
    mail = document.getElementById('mail').innerHTML;
    document.getElementById('mail').innerHTML= "<div id='i"+num+"' class='col-sm' style='overflow: hidden;text-overflow: ellipsis;float:left;width: 30%;outline: green solid thin'>"+data['mail']+"<input type='text' name='m"+num+"' id='m"+num+"' value='"+data['mail']+"' hidden><button type='button' onclick='eliminiar();' class='close' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>"+" "+mail;
  });


}
$("#mail").on('mouseover', 'div', function(){
  // obtenemos el id del elemento seleccionado
  id = $(this).attr("id");   
  });

function eliminiar(){
  elemento=document.getElementById(id);
  elemento.parentNode.removeChild(elemento);

}

function enviarmail () {
if(Validacionfrom_contactos()){

  var nombre   = document.getElementById("nombre").value;
  var codigo   = document.getElementById("codigo").value;

  var data = new FormData();
      data.append('nombre',nombre);
      data.append('codigo',codigo);

  var codigo2 = num;
  if(num !=0){
      for (var i = 0; i <= codigo2; i++) {
        if(document.getElementById("m"+i+"") != null){
          var mail   = document.getElementById("m"+i+"").value;
          data.append('mail'+i,mail);
          data.append('numero',i);
        } else {
          data.append('mail'+i,'');
        }   
    }

    $.ajax({
        url: "_editar_grupo.php",        // Url to which the request is send
        type: "POST",               // Type of request to be send, called as method
        dataType: 'json',
        data: data,                 // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false,         // The content type used when sending data to the server.
        cache: false,               // To unable request pages to be cached
        processData:false,          // To send DOMDocument or non processed data file it is set to false
    })
    .done(function(respuesta) {
      if(respuesta.success){
          cargarPagina(respuesta.url);

        } else {
          alert(respuesta.error.mensaje);
        }
      
    })
    .fail(function(respuesta,jqXHR, textStatus, errorThrown) {
          console.log("Algo ha fallado: " +  textStatus);
          alert(respuesta.error.mensaje);
    })
  } else{
    alert("Se debe agregar mail al grupo.");
  }
}
}
</script>