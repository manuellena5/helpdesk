<?php
include("../connect.php");
include("../funciones.php");
session_start();
?>
<li><strong>Mesa de Entrada</strong>
	<ul>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("mesaentrada/ingresopormail.php?ver=no&men=&donde=");'>Ingresos por Mail</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idingpormail"></span></li>

		<li><a href='javascript:cargarPagina("mesaentrada/ingresosvarios.php");'>Ingresos Varios</a></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("mesaentrada/papelerademail.php?ver=no&men=&donde=");'>Papelera de Mail</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idpapelera"></span></li>
		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("mesaentrada/spam.php?ver=no&men=&donde=");'>Spam</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idspam"></span></li>
		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("mesaentrada/ingresoarchivo.php?ver=no&men=&donde=");'>Archivado</a>
		
	</ul>
</li>
<li><strong>Seguimiento Tickets
	<input type="text" id="idcantidad" name="idcantidad" value="0" hidden='hidden'>

	<input type="text" id="idcantidadobservados" name="idcantidadobservados" value="0" hidden='hidden'>

	<span id="yaleidos" class="vVisible badge badge-info badge-pill" ><i  id="ticketleidos" class="fas fa-bell-slash" ></i></span>

	<span id="noleidos" class="vVisible badge badge-danger badge-pill" ><i id="ticketnoleidos" class="fas fa-bell"></i></span>

	<audio id="xyz" src="tono/TONO.mp3" preload="auto" ></audio>

</strong>
	<ul>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("seguimientotickets/sinaccion.php?ver=no&men=&donde=");'>Tickets sin Accion</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idsinaccion"></span></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("seguimientotickets/enespera.php?ver=no&men=&donde=");'>Tickets en Espera</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idenespera"></span></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("seguimientotickets/finalizados.php");'>Tickets Finalizados</a>
		<span class="badge badge-primary badge-pill" style="margin-right: 15%;" id="idfinalizados"></span></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("seguimientotickets/seguimiento.php");'>Tickets Derivados</a>
		<span class="badge badge-warning badge-pill" style="margin-right: 15%;" id="idseguimiento"></span></li>
				
	</ul>
</li>

<li><strong><a  href='javascript:cargarPagina("enviarmail.php");'>Enviar Mail</a></strong></li>

<li><strong><a href='javascript:cargarPagina("interno.php");'>Generar Ticket Interno</a></strong></li>

<li><strong>Notificaciones</strong>
	<ul style="display: none;">
		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("notificaciones/generar_notificacion.php?ver=no&men=&donde=");'>Generar Notificación</a></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("notificaciones/notificaciones_recibidas.php?ver=no&men=&donde=");'>Notificaciones Recibidas</a></li>

		<li class="d-flex justify-content-between align-items-center"><a href='javascript:cargarPagina("notificaciones/notificaciones_generadas.php?ver=no&men=&donde=");'>Notificaciones Generadas</a></li>				
	</ul>
</li>

<li><strong><a href='javascript:cargarPagina("contactos.php?ver=no&men=&donde=");'>Contactos</a></strong></li>

<li><strong><a href='javascript:cargarPagina("busquedaAvanzada/busqueda_avanzada.php");'>Busqueda Avanzada</a></strong></li>

<li><strong>Consultas e Informes</strong>
	<ul style="display: none;">
		<li><a href='javascript:cargarPagina("consultaseinformes/consultageneral.php?ver=no&men=&donde=");'>Consulta general</a></li>
		<li><a href='javascript:cargarPagina("consultaseinformes/consultapresencial.php?ver=no&men=&donde=");'>Consulta Documentacion</a></li>
		<li><a href='javascript:cargarPagina("consultaseinformes/informedocumentacion.php?ver=no&men=&donde=");'>Informe Documentacion</a></li>
		<li><a href='javascript:cargarPagina("consultaseinformes/estadisticas.php");'>Estadisticas</a></li>
	</ul>

</li>

<li><strong>Parametrización</strong>
	<ul style="display: none;">

		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Franja_Horaria.php?ver=no&men=&donde=");'>ABM Franja Horaria</a></li>

		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Usuarios.php?ver=no&men=&donde=");'>ABM Usuarios</a></li>

		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Roles.php?ver=no&men=&donde=");'>ABM Roles</a></li>

		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Categorias.php?ver=no&men=&donde=");'>ABM Etiquetas</a></li>
		
		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Entidades.php?ver=no&men=&donde=");'>ABM Entidades</a></li>
		
		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Nro_Mesa_Entrada.php?ver=no&men=&donde=");'>ABM N° Mesa</a></li>	

		<li><a href='javascript:cargarPagina("parametrizacion/ABM_Expedientes.php?ver=no&men=&donde=");'>ABM Expedientes</a></li>	
	</ul>
</li>

<strong><a href="usuario/logout.php">SALIR</a></strong>

<script>
	var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
    var rol = '<?php echo $_SESSION["rol"];?>'; 
	$(document).ready(function() {
   
	   //accion = "1";
        //cron(nrousuario,rol,accion); // Lanzo cron la primera vez
        accion = "2";
     	cronmenu(nrousuario,rol,accion);
    });
     setInterval( function () {cronmenu(nrousuario,rol,"2");}, 120000 );
</script>