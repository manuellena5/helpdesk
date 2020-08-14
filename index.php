<?php
	session_start();
	//require_once("funciones.php");
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta charset="utf-8">
		<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title id="idtitulopagina">Cie Tickets</title>
		<link rel="icon" href="images/logo_new.png" type="image/x-icon">
		<!-- CSS -->
		<?php include("usuario/css.php"); ?>
		<!-- Fin CSS -->
		<!-- JS -->
		<?php include("usuario/js.php"); ?>
		<!-- Fin JS -->
	</head>

	<body style="margin: 0px 0px 0px 0px;padding: 0px 0px 0px 0px;">
		<div id="nuevoComitenteDialog"></div>
		<?php include ('encabezado.php'); ?>
		<div id=content-wrapper align=center>
			<div align=left class="principal mui-container-fluid">
				<div >
					<div id=formPrincipal class="mui-col-sm-10 mui-col-sm-offset-1"></div>
				</div>
			</div>
		</div>
		<div class="p-3 mb-2 bg-primary text-white vOculto" id="error" align="center"></div>
		<?php if (isset($_SESSION['usuario'])){ ?>
		<script>
			var nrousuario = '<?php echo $_SESSION["nrousuario"];?>';
    		var rol = '<?php echo $_SESSION["rol"];?>'; 
			paginaPrincipal();
			cronmenu(nrousuario,rol,"2");
		</script>
		
		<?php } else { ?>
		<script>
		$.get("usuario/login_cie.php",function(data){
		    $("#formPrincipal").append(data);
		}); 
		</script>
		<?php } ?>
	</body>
</html>