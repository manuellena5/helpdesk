<?php 
session_start();
include("../connect.php");
include("../funciones.php");
salir(); 
$sql = mysqli_query($con, "SELECT `numero` FROM `fil00num` WHERE `proceso`='NUMMESAENT'");
$re = mysqli_fetch_array($sql);
$ver = $_GET['ver'];
if($ver == "si"){?>
  <div id="jGrowl-container1" class="bottom-left"></div>
<?php } ?>

<h3>Numero de Mesa de Entrada</h3>
<form class="form-inline" id="formulario">
  <div class="form-group mx-sm-3 mb-2">
    <input type="text" name="numero"  class="form-control campo_mayu" id="numero" value="<?php echo $re['numero']; ?>" placeholder="N°">
  </div>
</form>

<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('mesa','alta','_alta_mesa.php');">Confirmar</button>
<script>
$('#jGrowl-container1').jGrowl("<?php echo $_GET['men']; ?>", {
  header: '<?php echo $_GET['donde']; ?>',
  theme:  'manilla',
  glue: 'before'
});
</script>