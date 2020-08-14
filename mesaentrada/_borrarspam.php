<?php	
session_start();
header('Content-Type: text/html; charset=UTF-8');
include("../connect.php");
include("../funciones.php");
salir();

//Coneccion a la base de datos
$sql = mysqli_query($con, "SELECT `uid` FROM `fil01spam`");
$re = mysqli_fetch_array($sql);

if(mysqli_num_rows($sql) >= 1){
	// El servidor debe ser una cadena de conexión completa, como se muestra en el siguiente ejemplo
	$login = 'cie@cie.gov.ar';
	$password = 'Tlfaluni73';
	$srv = '{ftp.colegioing.com/notls}INBOX.spam';;
	$conn = imap_open($srv, $login, $password,OP_READONLY);
	$uids   = imap_search($conn, 'ALL');
	foreach ($uids as $number) {
			if(imap_uid($conn, $number) == $re['uid']){
				imap_mail_move($conn, $number, 'TRASH');
				imap_delete($conn, $number);
				$delete = mysqli_query($con, "DELETE FROM `fil01spam` WHERE `uid`='".$re['uid']."'"); 
				$mensajeusuario="No se pudo borrar correctamente";
				$mensajeconsola="No se pudo borrar correctamente";
				resultInsert($con,$delete,$mensajeusuario,$mensajeconsola);
				break;
			} 
		}
	imap_expunge($conn);
	imap_close($conn);
}
mysqli_close($con);
include("../_readspam.php");
?>