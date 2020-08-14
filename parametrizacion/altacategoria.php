<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); ?>

<h3>Alta de Etiquetas</h3>
<form class="form-inline" id="formulario">
  <input type="text" name="parcod" value="5" hidden>
  <div class="form-group mb-2">
    <label for="staticEmail2" >Ingrese una descripcion</label>
  </div>
  <div class="form-group mx-sm-3 mb-2">
    <input type="text" name="pardesc"  class="form-control campo_mayu" id="pardesc" placeholder="Descripcion">
  </div>
</form>

<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('etiquetas','alta','_alta.php');">Confirmar</button>
  <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Categorias.php?ver=no&men=&donde=');">Volver</button>
<script>

</script>