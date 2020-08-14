<?php 
session_start();
include("connect.php");
include("funciones.php");
salir(); ?>

<h3>Nuevo Contacto</h3>
<form  method="POST" accept-charset="utf-8" id="formulario">
	<div class="container">
	  <div class="row">
  	  <div class="col-6">
  	    <b>Nombre</b>
  	    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
  	  </div>
  	  <div class="col-6">
  	    <b>Email*</b>
  	    <input type="text" name="email" class="form-control" placeholder="Email" required>
  	  </div>
      <br>
      <div class="col-4">
        <b>Titulo</b>
        <input type="text" name="titulo" class="form-control" placeholder="Titulo" required>
      </div>
      <div class="col-4">
        <b>Empresa</b>
        <input type="text" name="empresa" class="form-control" placeholder="Empresa" required>
      </div>
      <div class="col-4">
        <b>Profesion</b>
        <input type="text" name="profesion" class="form-control" placeholder="Profesion" required>
      </div>
      <div class="col-sm">
        <b>Telefono Fijo</b><br>
        <div class="input-group">
          <div class="input-group-text">0</div><input type="text" name="telef_fijo_cod_area" id="telef_fijo_cod_area" class="form-control" placeholder="341" required maxlength="4" style="width: 15%;">
          <div class="input-group-text">-</div><input type="text" name="telef_fijo_tel" id="telef_fijo_tel" class="form-control" placeholder="xxxxxxx" required maxlength="7" style="width: 25%;">
        </div>
      </div>
  	  <div class="col-sm">
        <div class="form-group">
  	    <b>Telefono Movil</b><br>
        <div class="input-group">
    	    <div class="input-group-text">0</div><input type="text" name="telef_movil_cod_area" id="telef_movil_cod_area" class="form-control" placeholder="341" required maxlength="4" style="width: 15%;">
          <div class="input-group-text">-15</div><input type="text" name="telef_movil_tel" id="telef_movil_tel" class="form-control" placeholder="xxxxxxx" required maxlength="7" style="width: 25%;">
        </div>
  	  </div>
  	</div>
    
  </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="enviarmail();">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('contactos.php?ver=no&men=&donde=');">Volver</button>
<br>
<script>
  var formulario = document.querySelector("#formulario");
  formulario.telef_fijo_cod_area.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_fijo_tel.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_movil_cod_area.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
    formulario.telef_movil_tel.addEventListener('keypress', function(e){
    if(!soloNumero(e)){
      e.preventDefault();
    }
  });
  function soloNumero(e){
    var key = e.charCode;
    return key >= 48 && key <= 57;
  }
function enviarmail(){  
  $.ajax({
    url: '_contacto.php',
    type: 'POST',
    dataType: 'json',
    data: $("#formulario").serialize(),
  })
  .done(function(respuesta) {
    if (respuesta.success) { 
      cargarPagina(respuesta.url);
    }else{
      alert(respuesta.error.mensaje);
    }
  })
  .fail(function(respuesta) {
    alert("Algo ha fallado.");
  })
}
</script>