<?php	
session_start();
header('Content-Type: text/html; charset=UTF-8');
include("../connect.php");
include("../funciones.php");
salir();

// El servidor debe ser una cadena de conexión completa, como se muestra en el siguiente ejemplo
$login = 'cie@cie.gov.ar';
$password = 'Tlfaluni73';
$srv = '{ftp.colegioing.com/notls}INBOX.spam';;
$conn = imap_open($srv, $login, $password,OP_READONLY);
$uids   = imap_search($conn, 'ALL');
foreach ($uids as $number) {
		if(imap_uid($conn, $number) == $_GET['uid']){
			imap_mail_move($conn, $number, 'INBOX');
			imap_delete($conn, $number);
			$delete = mysqli_query($con, "DELETE FROM `fil01spam` WHERE `uid`='".$_GET['uid']."'"); 
			$mensajeusuario="No se pudo borrar correctamente";
			$mensajeconsola="No se pudo borrar correctamente";
			resultInsert($con,$resultUpdate,$mensajeusuario,$mensajeconsola);
			break;
		} 
	}
imap_expunge($conn);
imap_close($conn);
mysqli_close($con);
?>
<script>javascript:cargarPagina('mesaentrada/spam.php?ver=si&men=El mail se movio a la bandeja de Entrada.&donde=Spam');</script>