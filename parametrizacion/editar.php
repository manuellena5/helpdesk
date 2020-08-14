<?php
session_start();
include("../funciones.php");
salir();
$parcod   = $_GET["parcod"];

switch ($parcod) {
	case '1':
    $parvalor = $_GET["parvalor"];
		abmrol($parcod , $parvalor);
	break;
	case '4':
    $parvalor = $_GET["parvalor"];
		abmhora($parcod , $parvalor);
	break;
	case '5':
    $parvalor = $_GET["parvalor"];
    abmetiqueta($parcod , $parvalor); 
  break;
  case 'usuario':
    $nrousuario = $_GET['nrousuario'];
    abmusuario($nrousuario); 
  break;
  case '7':
    $codigo = $_GET["codigo"];
    abmentidades($codigo); 
  break;
  case '8':
    $parvalor = $_GET["parvalor"];
    abmexpedientes($parcod , $parvalor); 
  break;
  
}

//Funcion de rol
function abmrol($parcod , $parvalor){
  include("../connect.php");
  $query  = "SELECT parvalor,pardesc FROM fil00par WHERE parcod= '$parcod' and parvalor='$parvalor'";
  $res    = mysqli_query($con,$query);
  if($re = mysqli_fetch_array($res)){ ?>
    <h3>Modificar roles</h3>
    <form class="form-inline" id="formulario">
      <input type="text" name="parvalor" value="<?php echo $parvalor; ?>" hidden>
      <input type="text" name="parcod" value="<?php echo $parcod; ?>" hidden>
      <div class="form-group mb-2">
        <label for="staticEmail2" >Ingrese una descripcion</label>
      </div>
      <div class="form-group mx-sm-3 mb-2">
        <label for="pardesc" class="sr-only">Descripcion</label>
        <input type="text" name="pardesc" value="<?php echo $re['pardesc'] ?>" class="form-control campo_mayu" id="pardesc">
      </div>
    </form>
    <button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('rol','modificacion','_actualizar.php');">Confirmar</button>
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Roles.php?ver=no&men=&donde=');">Volver</button>
    <script>
      

    </script>
  <?php } 
};

// Funcion de la Hora
function abmhora($parcod , $parvalor){
  include("../connect.php");
  $query  = "SELECT parvalor,pardesc FROM fil00par WHERE parcod= '$parcod' and parvalor='$parvalor'";
  $res    = mysqli_query($con,$query);
  $re     = mysqli_fetch_array($res);
  $des    = $re['pardesc'];
  $hora1  = substr($des, 0, 2);
  $hora2  = substr($des, 6, -3);
  $min1   = substr($des, 3, -6);
  $min2   = substr($des, 9); ?>
  <h4>Modificar Franja Horaria</h4><br>
  <form  method="POST" accept-charset="utf-8" id="formulario">
    <input type="text" name="parvalor" value="<?php echo $parvalor; ?>" hidden>
    <input type="text" name="parcod" value="<?php echo $parcod; ?>" hidden>
    <div class="container">
      <div class="row">
      	<b>Desde</b>
        <div class="col-sm">
          <div class='form-group'>
            <select class='form-control' id='dhora' name='dhora'>
              <option value="<?php echo $hora1; ?>"><?php echo $hora1; ?></option>
                <?php 
                for ($i=0; $i<24 ; $i++) { 
                  if($i<10){
                    $i = "0".$i;
                  } ?>
                <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <b>:</b>
        <div class="col-sm">
          <div class='form-group'>
            <select class='form-control' id='dminutos' name='dminutos'>
              <option value="<?php echo $min1; ?>"><?php echo $min1; ?></option>
                <?php 
                for ($i=0; $i<60 ; $i++) { 
                  if($i<10){
                    $i = "0".$i;
                  } ?>
                <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <b>Hasta</b>
        <div class="col-sm">
          <div class='form-group'>
            <select class='form-control' id='hhora' name='hhora'>
              <option value="<?php echo $hora2; ?>"><?php echo $hora2; ?></option>
                <?php 
                for ($i=0; $i<24 ; $i++) { 
                  if($i<10){
                    $i = "0".$i;
                  } ?>
                <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                <?php } ?>
            </select>
          </div>
        </div>
        <b>:</b>
        <div class="col-sm">
          <div class='form-group'>
            <select class='form-control' id='hminutos' name='hminutos'>
              <option value="<?php echo $min2; ?>"><?php echo $min2; ?></option>
                <?php 
                for ($i=0; $i<60 ; $i++) { 
                  if($i<10){
                    $i = "0".$i;
                  } ?>
                  <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                  <?php } ?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </form>
<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('franjahora','modificacion','_actualizar.php');">Confirmar</button>
  <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Franja_Horaria.php?ver=no&men=&donde=');" >Volver</button>
<?php
};

//Funcion de Categoria
function abmetiqueta($parcod , $parvalor){
  include("../connect.php");
  $query = "SELECT parvalor,pardesc FROM fil00par WHERE parcod= '$parcod' and parvalor='$parvalor'";
  $res = mysqli_query($con,$query);
  if($re = mysqli_fetch_array($res)){ ?>
    <h3>Modificar Etiquetas</h3>
    <form class="form-inline" id="formulario">
      <input type="text" name="parvalor" value="<?php echo $parvalor; ?>" hidden>
      <input type="text" name="parcod" value="<?php echo $parcod; ?>" hidden>
      <div class="form-group mb-2">
        <label for="staticEmail2" >Ingrese una descripcion</label>
      </div>
      <div class="form-group mx-sm-3 mb-2">
        <label for="pardesc" class="sr-only">Descripcion</label>
        <input type="text" name="pardesc" value="<?php echo $re['pardesc'] ?>" class="form-control campo_mayu" id="pardesc">
      </div>
    </form>
    <button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('etiquetas','modificacion','_actualizar.php');">Confirmar</button>
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Categorias.php?ver=no&men=&donde=');">Volver</button>
    
    <?php } 
};

//Funcion de usuario
function abmusuario($nrousuario){
  include("../connect.php");
  $query="SELECT fseg.usuario,fseg.nombre,fgrupo.pardesc descgrupo,frol.pardesc descrol,SUBSTR(ffd.pardesc,11) descfranjadia,ffh.pardesc descfranjahora,fseg.nrousuario,fseg.grupo,fseg.rol,fseg.franjadia,fseg.franjahora,fseg.password,fseg.firma
        FROM `fil01seg` fseg 
        inner join ( 
                SELECT fp.parvalor,fp.pardesc 
                FROM fil00par fp 
                WHERE fp.parcod=1)as frol on frol.parvalor = fseg.rol 
        inner join ( 
                SELECT fp.parvalor,fp.pardesc 
                FROM fil00par fp 
                WHERE fp.parcod=3)as ffd on ffd.parvalor = fseg.franjadia 
        inner join ( 
                SELECT fp.parvalor,fp.pardesc 
                FROM fil00par fp 
                WHERE fp.parcod=4)as ffh on ffh.parvalor = fseg.franjahora 
        inner join ( 
                SELECT fp.parvalor,fp.pardesc 
                FROM fil00par fp 
                WHERE fp.parcod=2)as fgrupo on fgrupo.parvalor = fseg.grupo 
        where fseg.nrousuario = '$nrousuario'";
  $res = mysqli_query($con,$query);
  if($re = mysqli_fetch_array($res)){ 
    $firma = preg_replace("/<br>/","\n",$re['firma']);?>
    <h3>Modificar Usuario</h3>
    <form  method="POST" accept-charset="utf-8" id="formulario">
      <input type="text" id="nrousuario" name="nrousuario" value="<?php echo $nrousuario; ?>" hidden>
       <input type="text" name="parcod" id="parcod" class="form-control" value="usu" hidden>
      <div class="container">
        <div class="row">
          <div class="col-sm">
            <b>Usuario</b><br>
            <input type="text" class="form-control campousuario" id="usuario" name="usuario" value="<?php echo $re['usuario']; ?>" >
          </div>
          <div class="col-sm">
            <b>Contraseña</b><br>
            <input type="password" class="form-control" id="pass" name="pass" value="<?php echo $re['password']; ?>">
          </div>
          <div class="col-sm">
            <b>Rep. Contraseña</b><br>
            <input type="password" class="form-control" id="pass2" name="pass2" value="<?php echo $re['password']; ?>">
          </div>
          <div class="col-sm">
            <b>Nombre</b><br>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $re['nombre']; ?>">
          </div>
        </div>
      <br>
        <div class="row">
          <div class="col-sm">
            <b>Grupo</b>
            <div class='form-group'>
              <select class='form-control' id='grupo' name='grupo'>
                <option value="<?php echo $re['grupo']; ?>"><?php echo $re['descgrupo']; ?></option>
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
                <option value="<?php echo $re['rol'];?>"><?php echo $re['descrol']; ?></option>
                  <?php 
                  $roles = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='1'");
                  while ($rol= mysqli_fetch_array($roles)) { ?>
                    <option value="<?php echo $rol['parvalor']; ?>"><?php echo $rol['pardesc']; ?></option>
                  <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-sm">
            <b>Franja Dias</b>
            <div class='form-group'>
              <select class='form-control' id='dia' name='dia'>
                <option value="<?php echo $re['franjadia']; ?>"><?php echo $re['descfranjadia']; ?></option>
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
                <option value="<?php echo $re['franjahora']; ?>"><?php echo $re['descfranjahora']; ?></option>
                  <?php 
                  $Hora = mysqli_query($con,"SELECT pardesc,parvalor from fil00par where parcod ='4'");
                  while ($Ho= mysqli_fetch_array($Hora)){ ?>
                    <option value='<?php echo $Ho['parvalor']; ?>'><?php echo $Ho['pardesc']; ?></option>
                  <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
           <div class="col-10">
              <b>Firma</b>
              <textarea class="form-control" id="firma" id="firma" name="firma" style="height: 50%;"><?php echo $firma; ?></textarea>
           </div>
        </div>
      </div>
    </form>
    <button type="submit" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('usuario','modificacion','_actualizarusuario.php');">Confirmar</button>
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Usuarios.php?ver=no&men=&donde=');">Volver</button>
    
    <?php } 
};
//Funcion de Entidades
function abmentidades($codigo){
  include("../connect.php");
  $query = "SELECT * FROM `fil01ent` WHERE `codigo`='$codigo'";
  $res = mysqli_query($con,$query);
  if($re = mysqli_fetch_array($res)){ ?>
    <h3>Modificacion de Entidades</h3>
<form  method="POST" accept-charset="utf-8" id="formulario">
   <input type="text" name="codigo" value="<?php echo $re['codigo']; ?>" hidden>
    <input type="text" name="parcod"  class="form-control" value="ent" hidden>
  <div class="container">
    <div class="row">
        <div class="col-sm">
          <b>Razon Social</b>
          <input type="text" name="razon" id="razon" class="form-control campo_mayu" value="<?php echo $re['razon']; ?>">
        </div>
        <div class="col-sm">
          <b>Domicilio</b><br>
          <input type="text" name="domicilio" id="domicilio" class="form-control campo_mayu" value="<?php echo $re['domicilio']; ?>">
        </div>
        <div class="col-sm">
          <b>Localidad</b>
          <input type="text" name="localidad" id="localidad" class="form-control campo_mayu" value="<?php echo $re['localidad']; ?>">
        </div>
      </div>
  <br>
    <div class="row">
      <div class="col-sm">
        <b>Cuit</b>
        <input type="text" name="cuit" id="cuit" class="form-control" value="<?php echo $re['cuit']; ?>">
      </div>
      <div class="col-sm">
        <b>Telefono</b>
        <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo $re['telefono']; ?>">
      </div>
      <div class="col-sm">
        <b>Mail</b>
        <input type="text" name="mail" id="mail" class="form-control" value="<?php echo $re['mail_contacto']; ?>">
      </div>
    </div>
  </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('entidad','modificacion','_actualizarentidades.php');">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Entidades.php?ver=no&men=&donde=');">Volver</button>
    <?php } 
};
//Funcion de Expedientes
function abmexpedientes($parcod , $parvalor){
  include("../connect.php");
  $query = "SELECT parvalor,pardesc FROM fil00par WHERE parcod= '$parcod' and parvalor='$parvalor'";
  $res = mysqli_query($con,$query);
  if($re = mysqli_fetch_array($res)){ ?>
    <h3>Modificar Expedientes</h3>
    <form class="form-inline" id="formulario">
      <input type="text" name="parvalor" value="<?php echo $parvalor; ?>" hidden>
      <input type="text" name="parcod" value="<?php echo $parcod; ?>" hidden>
      <div class="form-group mb-2">
        <label for="staticEmail2" >Ingrese una descripcion</label>
      </div>
      <div class="form-group mx-sm-3 mb-2">
        <label for="pardesc" class="sr-only">Descripcion</label>
        <input type="text" name="pardesc" value="<?php echo $re['pardesc'] ?>" class="form-control campo_mayu" id="pardesc">
      </div>
    </form>
    <button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('expedientes','modificacion','_actualizar.php');">Confirmar</button>
    <button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Expedientes.php?ver=no&men=&donde=');">Volver</button>
    
    <?php } 
};
?>