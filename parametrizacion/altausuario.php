<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); ?>

<h3>Alta de Usuario</h3>

<form  method="POST" accept-charset="utf-8" id="formulario">
	<div class="container">
	  <div class="row">
  	    <div class="col-sm">
  	      <b>Usuario</b>
  	      <input type="text" name="usuario" id="usuario" class="form-control campo_mayu" placeholder="Nuevo Usuario" required>
  	    </div>
  	    <div class="col-sm">
  	      <b>Contraseña</b>
  	      <input type="password" name="pass" id="pass" class="form-control" placeholder="Nueva Contraseña" required>
  	    </div>
        <div class="col-sm">
          <b>Rep. Contraseña</b><br>
          <input type="password" name="pass2" id="pass2" class="form-control" placeholder="Repetir Contraseña" required>
        </div>
  	    <div class="col-sm">
  	      <b>Nombre</b>
  	      <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Usuario" required>
  	    </div>
        <input type="text" name="parcod" id="parcod" class="form-control" value="usu" hidden>
  	  </div>
	<br>
	  <div class="row">
	    <div class="col-sm">
	      <b>Grupo</b>
			  <div class='form-group'>
          <select class='form-control' id='grupo' name='grupo'>
            <option value="">Seleccione Grupo</option>
              <?php 
              $grupo = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='2'");
              while ($gru= mysqli_fetch_array($grupo)) { ?>
                <option value='<?php echo $gru['parvalor']; ?>'><?php echo $gru['pardesc']; ?></option>
              <?php } ?>
          </select>
        </div>
	    </div>
	    <div class="col-sm">
	      <b>Roles</b>
			  <div class='form-group'>
          <select class='form-control' id='roles' name='roles'>
            <option value="">Seleccione Roles</option>
              <?php 
              $roles = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='1'");
              while ($rol= mysqli_fetch_array($roles)) { ?>
                <option value='<?php echo $rol['parvalor']; ?>'><?php echo $rol['pardesc']; ?></option>
              <?php } ?>
          </select>
        </div>
	    </div>
	    <div class="col-sm">
	      <b>Franja Dias</b>
			  <div class='form-group'>
          <select class='form-control' id='dia' name='dia'>
            <option value="">Selecciones Dias</option>
              <?php 
              $dias = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='3'");
              while ($dia= mysqli_fetch_array($dias)) {
              $mod = substr($dia['pardesc'], 10); ?>
                <option value='<?php echo $dia['parvalor']; ?>'><?php echo $mod; ?></option>
              <?php } ?>
          </select>
        </div>
	    </div>
	    <div class="col-sm">
	      <b>Franja Horas</b>
			  <div class='form-group'>
          <select class='form-control' id='hora' name='hora'>
            <option value="">Seleccione Hora</option>
              <?php 
              $Hora = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='4'");
              while ($Ho= mysqli_fetch_array($Hora)) { ?>
                <option value='<?php echo $Ho['parvalor']; ?>'><?php echo $Ho['pardesc']; ?></option>
              <?php } ?>
          </select>
        </div>
	    </div>
	  </div>
    <div class="row">
       <div class="col-10">
          <b>Firma</b>
          <textarea class="form-control" id="firma" name="firma" style="height: 50%;"></textarea>
       </div>
    </div>
  </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('usuario','alta','_altausuario.php');">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Usuarios.php?ver=no&men=&donde=');">Volver</button>
<br>
<script>





</script>