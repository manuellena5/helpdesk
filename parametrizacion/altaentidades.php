<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); ?>

<h3>Alta de Entidades</h3>
<form  method="POST" accept-charset="utf-8" id="formulario">
  <input type="text" name="parcod"  class="form-control" value="ent" hidden>
  <div class="container">
    <div class="row">
      <div class="col-6">
        <b>Cuit</b>
        <input type="text" name="cuit" id="cuit" class="form-control" maxlength="11" placeholder="Cuit" required>
      </div>
      <div class="col-6" style="top: 20px;padding-left: 0px;">
        <button type="button" class="btn btn-primary mb-2" onclick="buscar();">Buscar</button>
      </div>
    </div>
    <div id="for1" class="vOculto">
    <div class="row">
        <div class="col-4">
          <b>Razon Social</b>
          <input type="text" name="razon" id="razon" class="form-control campo_mayu" placeholder="Razon Social" required>
        </div>
        <div class="col-4">
          <b>Domicilio</b><br>
          <input type="text" name="domicilio" id="domicilio" class="form-control campo_mayu" placeholder="Domicilio" required>
        </div>
        <div class="col-4">
          <b>Localidad</b>
          <input type="text" name="localidad" id="localidad" class="form-control campo_mayu" placeholder="Localidad" required>
        </div>
    
      <div class="col-sm">
        <b>Telefono</b>
        <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Telefono" required>
      </div>
      <div class="col-sm">
        <b>Mail</b>
        <input type="text" name="mail" id="mail" class="form-control" placeholder="Mail" required>
      </div>
       <div class="col-sm">
        <b>Rubro</b>
        <input type="text" name="rubro" id="rubro" class="form-control" placeholder="Rubro" required>
      </div>
    </div>
  </div>
  </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('entidad','alta','_altaentidades.php');">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Entidades.php?ver=no&men=&donde=');">Volver</button>
<br>

<script>

formulario = document.querySelector('#formulario');
    formulario.cuit.addEventListener('keypress', function (e){
      if (!soloNumeros(e)){
        e.preventDefault();
      }
    })

    formulario.telefono.addEventListener('keypress', function (e){
      if (!soloNumeros(e)){
        e.preventDefault();
      }
    })

function buscar(){
var campocuit = $("#cuit").val();
$.ajax({
        url: 'afip/traer_comitente.php',
        type: 'POST',
        dataType: 'json',
        data: {campocuit: campocuit},
      })
      .done(function(respuesta) {   
       $("#for1").removeClass("vOculto").addClass("vVisible");        
        cargarformulario(respuesta);
      })
      .fail(function(jqXHR,textStatus,errorThrown) {
            $("#cargador").removeClass("vVisible").addClass("vOculto");
        console.log("error " + textStatus  + "  " + errorThrown + "  " + jqXHR.status);
      })
      .always(function() {
        $("#cargador").removeClass("vVisible").addClass("vOculto");
          
      });
}
   
      function cargarformulario(empseleccionada){
            
           
            $('#localidad').val(empseleccionada.localidad);
        if (empseleccionada.ws) {
             
             $('#razon').val(empseleccionada.razonSocial);
            $('#domicilio').val(empseleccionada.direccion);
            $('#provincia').val(empseleccionada.idProvincia);
            
        }else{
            
             $('#razon').val(empseleccionada.nombre_comitente);
            $('#domicilio').val(empseleccionada.domicilio_comitente);
            $('#provincia').val(empseleccionada.nombre);
        }
      


      }
</script>