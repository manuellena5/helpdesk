<meta charset="utf-8">
<header id="header">
<?php if (isset($_SESSION['usuario'])) {
include("connect.php");
include("funciones.php");?>
<div id="sidedrawer">
	<nav id="sidenav" class="mui--no-user-select">
		<div>
			<h3 class="mui--appbar-line-height">
				<a id="leermail" href="index.php"><img  src="images/fondo.png" width="100%"></a>
			</h3>
		</div>
		<ul class="menu" id="menuSide"></ul>
	</nav>
</div>
<?php }?>
<div class="mui-appbar">
	<div class="mui-container-fluid">
		<table width=100% cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<div class=encabezado>
						<div style="float: left;">
							<?php if (isset($_SESSION['usuario'])) {?>
								<a class="sidedrawer-toggle mui--visible-xs-inline-block mui--visible-sm-inline-block js-show-sidedrawer">☰</a>
								<a class="sidedrawer-toggle mui--hidden-xs mui--hidden-sm js-hide-sidedrawer">☰</a>
							<?php }?>
						</div>

						<div id="divLogoEncabezado">
							<table border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td><img src='images/cie-web.png' class="img-fluid" alt="Logo cie" /></td>
								</tr>
							</table>
						</div>

						<div  style="float: right; ">
							
							
							
    						<?php if (isset($_SESSION['usuario'])) {?>
    						
								<div class="datosusuario">
									
									<div class="row">
										<div class="col-xs-12">
											<strong> 
	      										<?php 
	      											$sq = mysqli_query($con, "SELECT nombre,ultimoing FROM fil01seg WHERE usuario = '".$_SESSION["usuario"]."'"); 
													$ro = mysqli_fetch_array($sq); 
													echo $ro['nombre'];
												?>
											</strong>
										</div>
									</div>
									<div class="row">
										<div id="ultimoAcceso" class="col-xs-12">
											<?php
												if (isset($_SESSION['usuario'])) {
													$ultimo = date("d-m-Y H:i:s", strtotime($_SESSION['ultimoing']));
													echo "<b>Ultimo ingreso: </b>".$ultimo;
												}
											?>
										</div>
									</div>
									<div class="row">
										<div id="cantnoleidos" class="col-xs-12"></div>
									</div>
									<div class="row">
										<div id="cantobservados" class="col-xs-12"></div>
									</div>

								</div>
							<?php } ?>    							
							
							
						</div>
					</div>
				</td>
			</tr>
		</table>
		<div class=botonera>
			<table width=100%>
				<tr valign=top>
					<td>
						<div id="mimenu" class="dropdown clearfix"></div>
					</td>
					<td>
						<table align=center width=100%>
							<tr>
								<td align=center><div id=titulo_pagina class=titulopagina></div></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<?php if (isset($_SESSION['usuario'])){
		?>
		<script>
	        menuLink();
		</script>
		<?php }
		?>
	</div>
</div>
</header>