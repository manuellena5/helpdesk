<?php
session_start();
include("../funciones.php");
salir();
?>
<h4>Alta de Nueva Franja Horaria</h4><br>
<form  id="formulario" method="POST" accept-charset="utf-8">
    <input type="text" name="parcod" value="4" hidden>
    <div class="container">
        <div class="row">
        	<b>Desde</b>
            <div class="col-sm">
                <div class='form-group'>
                    <select class='form-control' id='dhora' name='dhora'>
                        <option value="">Hora</option>
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
                        <option value="">Minuto</option>
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
                        <option value="">Hora</option>
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
                        <option value="">Minuto</option>
                        <?php 
                        for ($i=0; $i<60 ; $i++) { 
                            if($i<10){
                                $i = "0".$i;
                            }
                        ?>
                        <option value='<?php echo $i; ?>'><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
<button type="button" class="btn btn-primary mb-2" onclick="validar_form_parametrizacion('franjahora','alta','_alta.php');">Confirmar</button>
<button type="button" class="btn btn-primary mb-2" onclick="cargarPagina('parametrizacion/ABM_Franja_Horaria.php?ver=no&men=&donde=');" >Volver</button>

<script>

</script>