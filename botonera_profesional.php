<?php
session_start();
include("funciones.php");
salir();
?>
<div class="container">
	<div class="row">
		<div id="estadosSitRyG" class="text-center col-6 col-md-4" >	
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("mesaentrada/ingresopormail.php?ver=no&men=&donde=");'>
				<h2>Mesa de entrada</h2>
				<div class="panel-headingBotonera" style="padding: 1px;">
					<i class="fas fa-sign-in-alt"></i>
				</div>
			</a>
		</div>
		<div id="estadosContables" class="text-center col-6 col-md-4">
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("seguimientotickets/sinaccion.php?ver=no&men=&donde=");'>
				<h2>Seguimiento de Tickets</h2>
				<div class="panel-headingBotonera">
					<img src="images/seguimtic.png" style="margin: 26px;width: 63px;">
				</div>
			</a>
		</div>
		<div id="notasDeCumplimiento" class="text-center col-6 col-md-4">
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("enviarmail.php");'>
				<h2>Enviar Mail</h2>
				<div class="panel-headingBotonera" >
					<img src="images/enviomail.png" style="margin: 26px;width: 77px;">
				</div>
			</a>
		</div>
	

		<div id="estadosSitRyG" class="text-center col-6 col-md-4" >		
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("interno.php");'>
				<h2>Generar Ticket Interno</h2>
				<div class="panel-headingBotonera">
					<img src="images/generatic.png" style="margin: 26px;width: 76px;">
				</div>
			</a>	
		</div>
		<div id="estadosContables" class="text-center col-6 col-md-4">	
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("busquedaAvanzada/busqueda_avanzada.php");'>
				<h2>Busqueda Avanzada</h2>
				<div class="panel-headingBotonera">
					<img src="images/busqueda.png" style="margin: 26px;width: 63px;">
				</div>
			</a>
		</div>
		<div id="notasDeCumplimiento" class="text-center col-6 col-md-4">
			<a class="panel panel-default panel-icon" href='javascript:cargarPagina("consultaseinformes/consultageneral.php?ver=no&men=&donde=");'>
				<h2>Consultas e Informes</h2>
				<div class="panel-headingBotonera">
					<img src="images/consulta.png" style="margin: 26px;width: 66px;">
				</div>
			</a>			
		</div>
	
	</div>
</div>