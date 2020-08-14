<?php
header('Content-Type: text/html; charset=UTF-8');
include("connect.php");
salir();

// El servidor debe ser una cadena de conexión completa, como se muestra en el siguiente ejemplo
$server = '{ftp.colegioing.com/notls}INBOX.Spam';
$login = 'cie@cie.gov.ar';
$password = 'Tlfaluni73';

$connection = imap_open($server, $login, $password);

$emails = imap_search($connection, 'ALL');

if($emails) {	   
	$salida = '';
	$chequeo = imap_mailboxmsginfo($connection);
   	$CantMsjs = $chequeo->Nmsgs;
   	rsort($emails);

   	foreach ($emails as $email_number) {
		
		/**** INICIALIZACIONES  ****/
		$cc = "";

   		/*** CABECERA    ***/

		$overview = imap_fetch_overview($connection,$email_number,0);
		$mailHeader = imap_headerinfo($connection, $email_number);
        $from = $mailHeader->from;

        $uid = imap_uid($connection, $email_number);
        

        foreach ($from as $id => $object) {
	        $overview[$email_number]['from'] = $object->personal;
	        $emisor = $object->mailbox . "@" . $object->host;  /**  EMISOR   **/
        }
        
        $to = $mailHeader->to;

        foreach ($to as $id => $object) {
	        $overview[$email_number]['to'] = $object->personal;
	        $receptor = $object->mailbox . "@" . $object->host; /**  RECEPTOR   **/
        }

        $fecha 		= new DateTime($overview[0]->date);
		$fecha 		= $fecha->format("Y-m-d H:i:s");   /**  FECHA   **/

        $asuntodecode = mb_decode_mimeheader($overview[0]->subject);
        $overview[0]->subject = $asuntodecode;
        
        $asunto = $overview[0]->subject; /**  ASUNTO   **/
        
        $asunto = str_replace("_", " ", $asunto);




        /**** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ****/

		// El número en el constructor es el número del mensaje
		$emailMessage = new EmailMessage($connection, $email_number);
		//var_dump($emailMessage);
		// Se establece en verdadero para obtener las partes del mensaje (o no se establece en falso, el valor predeterminado es verdadero)
		$emailMessage->getAttachments = false;
		$emailMessage->fetch();
		
		

				/*Sino inserta solo los datos del mail*/
			    $asunto = convertircadenas($asunto);
			    
			    
			    $query3 = "INSERT INTO `fil01spam`
			       (`uid`,`destinatario`, `emisor`, `asunto`,`fecha`) 
			       VALUES 
			       ($uid,'$receptor','$emisor','$asunto','$fecha')";
			    
			    $resultInsert3 = mysqli_query($con, $query3);
			        if (!$resultInsert3) {
						error_log("Error al insertar en fil01spam 03-- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
				    	exit;
					}
			    $id_mail = mysqli_insert_id($con);
				
		
		if(array_key_exists("cc", $mailHeader)){
			$cc = "";
			$cant = count($mailHeader->cc);
			$separador = "";
			if ($cant > 1) {
				$separador = ";";
			}
			for ($i=0; $i <$cant ; $i++) { 
				$cc .= $mailHeader->cc[$i]->mailbox."@".$mailHeader->cc[$i]->host.$separador;
			}


			$longitud = mb_strlen($cc);
			$caracter = substr($cc,$longitud-1);
			if ($caracter == ';') {
				$cc = substr($cc,0,-1);

			}



			
			$query4 = "UPDATE `fil01spam` SET `cc`='$cc' WHERE `uid`='$uid'";
		     $resultUpdate4 = mysqli_query($con,$query4);

		     if (!$resultUpdate4) {
		     	error_log("Error al actualizar fil01spam los CC -- ." .
	            		mysqli_errno($con) . " " . mysqli_error($con));
		     	exit;
		     }
		     

		} 
		

		// Emparejar imágenes en línea en contenido html
		preg_match_all('/src="cid:(.*)"/Uims', $emailMessage->bodyHTML, $matches);

		// Si hay coincidencias, recórrelas y guárdelas en el sistema de archivos, cambie la propiedad src
		// De la imagen a una URL real



		if(count($matches)) {
			// Buscar y reemplazar matrices se utilizarán en la función str_replace a continuación
			$search = array();
			$replace = array();
			
			foreach($matches[1] as $match) {
				// Elaborar un nombre de archivo único para él y guardarlo en el sistema de archivos, etc.

				$search[] = "src=\"cid:$match\"";
				$replace[] = "src=\"imgmail/$uniqueFilename\"";

			}
			// Ahora haz los reemplazos
			$emailMessage->bodyHTML = str_replace($search, $replace, $emailMessage->bodyHTML);
			//$emailMessage->bodyHTML = str_replace("'", '"', $emailMessage->bodyHTML);

			
		}

		/*
		$salida 	.= '<p-->UID: <b>'.$uid.'</b><br>';
		$salida 	.= '<p-->Fecha: <b>'.$fecha.'</b><br>';
		$salida 	.= '<p-->a: <b>'.$receptor.'</b><br>';
		$salida 	.= '<p-->de: <b>'.$emisor.'</b><br>';
		$salida 	.= '<p-->Asunto: <b>'.$asunto.'</b><br>';
		if ($cc != null || $cc != "") {
			$salida 	.= '<p-->CC: <b>'.$cc.'</b><br>';
		}
		*/
		$e1 = decodificar_nombre($emailMessage->bodyHTML);
		
		/*
		if (mb_detect_encoding($e1, 'UTF-8', true)) {
			$e1 = iconv('UTF-8','UTF-8',$e1);
			
			

		}else if(mb_detect_encoding($e1, 'ISO-8859-1', true)){
			$e1 = iconv('ISO-8859-1','UTF-8',$e1);
			
			
			

		}else {

			ini_set('mbstring.substitute_character', "none"); 
  			$e1= mb_convert_encoding($e1, 'UTF-8', 'UTF-8'); 
  			

		}
		*/
		
		$emailMessage->bodyHTML 	= $e1;
		
		
		$html = convertircadenas($emailMessage->bodyHTML);
		//$html = convertircadenas($emailMessage->bodyPlain);	
		if ($html == "") {
			$html = convertircadenas($emailMessage->bodyPlain);	
		}
		
		/*
		$html = $emailMessage->bodyHTML;
		//$html = convertircadenas($emailMessage->bodyPlain);	
		if ($html == "") {
			$html = $emailMessage->bodyPlain;	
		}
		*/

		
		$query6 = "UPDATE `fil01spam` SET `cuerpo`='$html' WHERE `uid`='$uid'";
		$resultUpdate6 = mysqli_query($con,$query6);
		            if (!$resultUpdate6) {
						error_log("Error al actualizar en fil01spam el cuerpo -- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
						exit;
					}

	
        
		//$salida.= '<p-->Tiene adj: <b>'.$adj.'</b><br>';
		
		/*
		$salida.= "Mensaje: <br>".$html;
		$salida.="<hr />";
		*/




   } // fin del Foreach de cada mail


imap_mail_move($connection, "0:".$CantMsjs."", 'TRASH');


//echo $salida;
echo "listo";



}
//fin
//imap_expunge($connection);
imap_close($connection);
mysqli_close($con);
exit;




class EmailMessage {

	protected $connection;
	protected $messageNumber;
	public $bodyHTML = '';
	public $bodyPlain = '';
	public $attachments;
	public $getAttachments = true;
	public function __construct($connection, $messageNumber) {
		$this->connection = $connection;
		$this->messageNumber = $messageNumber;
	}

	public function fetch() {
		$structure = @imap_fetchstructure($this->connection, $this->messageNumber);
		if(!$structure) {
			return false;

		}else {
			$myobj = get_object_vars($structure);
			//var_dump($structure);
			if(isset($myobj['parts'])){
				$this->recurse($structure->parts);

			} else{
				$this->norecurse($structure);
			}
			return true;
		}
	}
	public function norecurse($structure){
		$body=imap_body($this->connection, $this->messageNumber);

		//decodificar si citado-imprimible
		if ($structure->encoding==4) $body=quoted_printable_decode($body);

		//TRATAMIENTO
		if (strtoupper($structure->subtype)=='PLAIN') $this->bodyPlain .= nl2br($body);
		else if (strtoupper($structure->subtype)=='HTML') $this->bodyHTML .= $body;
	}

	public function recurse($messageParts, $prefix = '', $index = 1, $fullPrefix = true) {

		foreach($messageParts as $part) {
			
			$partNumber = $prefix . $index;
			$disposition = (isset($part->disposition) ? strtolower($part->disposition) : null);

			if($part->type == 0 && $disposition != 'attachment') {

				if($part->subtype == 'PLAIN') {
					$this->bodyPlain .= $this->getPart($partNumber, $part->encoding);
				}
				else {
					$this->bodyHTML .= $this->getPart($partNumber, $part->encoding);
				}
			}

			elseif($part->type == 2) {
				$msg = new EmailMessage($this->connection, $this->messageNumber);
				$msg->getAttachments = $this->getAttachments;
				if (array_key_exists("parts",$part)) {
					$msg->recurse($part->parts, $partNumber.'.', 0, false);
					//var_dump($part->parts);
				}
				
				

				
			}

			elseif(isset($part->parts)) {
				if($fullPrefix) {
					$this->recurse($part->parts, $prefix.$index.'.');
				}
				else {
					$this->recurse($part->parts, $prefix);
				}
			}

			elseif($part->type > 2 || $disposition == 'attachment') {

				if(isset($part->id)) {
					$id = str_replace(array('<', '>'), '', $part->id);

					
				}

				else {
					
				}
			}
			$index++;
		}
	}



	
	function getPart($partNumber, $encoding) {

		$data = imap_fetchbody($this->connection, $this->messageNumber, $partNumber);
		switch($encoding) {
			case 0: return $data; // 7BIT
			case 1: return $data; // 8BIT
			case 2: return $data; // BINARY
			case 3: return base64_decode($data); // BASE64
			case 4: return quoted_printable_decode($data); // QUOTED_PRINTABLE
			case 5: return $data; // OTHER
		}
	}


	



	
}

?>
