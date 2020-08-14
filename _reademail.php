<?php
//session_start();
header('Content-Type: text/html; charset=UTF-8');
include("connect.php");
include("funciones.php");
//salir();


date_default_timezone_set("America/Argentina/Buenos_Aires");


// El servidor debe ser una cadena de conexión completa, como se muestra en el siguiente ejemplo
$server = '{ftp.colegioing.com/notls}INBOX';
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
		$adj = "N";
		$cc  = "";
		$id_ticket=0;





   		/****************    CABECERA    ***************/
   		/****************    CABECERA    ***************/
   		/****************    CABECERA    ***************/
   		/****************    CABECERA    ***************/

		$overview = imap_fetch_overview($connection,$email_number,0);
		$mailHeader = imap_headerinfo($connection, $email_number);
        $from = $mailHeader->from;
        //var_dump($mailHeader->from);

        /****  EMISOR   ****/
        
        foreach ($from as $id => $object) {
        	if(!array_key_exists('personal', $object)){
        		$overview[$email_number]['from'] =  $object->mailbox;
        	}else {
        		$overview[$email_number]['from'] = $object->personal;
        	}
	        $emisor = $object->mailbox . "@" . $object->host;  
        }
        
        $to = $mailHeader->to;


        /****  RECEPTOR   ****/
        foreach ($to as $id => $object) {
	        if(!array_key_exists('personal', $object)){
        		$overview[$email_number]['to'] = $object->mailbox;
        	} else {
        		$overview[$email_number]['to'] = $object->personal;
        	}
	        $receptor = $object->mailbox . "@" . $object->host; 
        }


         /*****  FECHA   *****/
        $fecha 		= date("Y-m-d H:i:s", strtotime($overview[0]->date));  


		/****  ASUNTO   ****/
        if(!array_key_exists('subject', $overview[0])){
			$asuntodecode = "Sin Asunto";
		}else{
			$asuntodecode = mb_decode_mimeheader($overview[0]->subject);
		}
        $overview[0]->subject = $asuntodecode;
        
        $asunto = $overview[0]->subject; 
        
        $asunto = str_replace("_", " ", $asunto);
        $asunto = decodificar_nombre($asunto);

        $asuntocompara = mb_strtoupper($asunto);


        /*********** CC  ****************/
        if(array_key_exists("cc", $mailHeader)){
			
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

		} 


        /*	Si el asunto del ticket tiene como palabra Ticket(entra al if), buscamos quien es el ultimo que respondio para ese ticket, para luego asignarle este mail	*/

        if (strpos($asuntocompara, '[TICKET#') !== false || strpos($asuntocompara, '[INTERNO#') !== false) {
        	
        	if(strpos($asuntocompara, '[TICKET#') !== false){
        		$contar = strpos($asuntocompara, '[TICKET#');
            	$nom_ticket = substr($asuntocompara, $contar, 25);
        		$tipo_ticket = "E";

        	} else if(strpos($asuntocompara, '[INTERNO#') !== false){
        		$contar = strpos($asuntocompara, '[INTERNO#');
            	$nom_ticket = substr($asuntocompara, $contar, 26);
            	$tipo_ticket = "I";
        	}
            
            //$query0 = "SELECT `nro_ticket`,`categoria`,if(`cod_entidad` is null,0,`cod_entidad`)`cod_entidad` FROM `fil01mail` WHERE nombre_ticket='$nom_ticket' order by fecha ASC limit 1";

            $query0 = "SELECT f01.`nro_ticket`,f01.`categoria`,if(f01.`cod_entidad` is null,0,f01.`cod_entidad`)`cod_entidad`, 
			f03.`id_mail`,f03.`id`,f03.`quienderi`,f03.`usuarioasig`,f03.`rolasig`,f03.`fechaasig`,f03.`observacion`,f03.`quienresp`,f03.`respuesta`,f03.`estado`,f03.`adjunto`,f03.`tipo_ticket`,if(f03.`nro_mesaent` is null,0,f03.nro_mesaent)nro_mesaent,if(f03.`tipo_docum` is null,0,f03.`tipo_docum`)`tipo_docum`,if(f03.`ing_env` is null,'',f03.`ing_env`)`ing_env`
			FROM `fil01mail` f01 
			inner join fil03mail f03 on f01.id_mail = f03.id_mail
			WHERE f01.nombre_ticket='$nom_ticket' and f03.tipo_ticket= '$tipo_ticket' AND
			(f03.`estado`='R' OR f03.`estado` IS null)
			order by f01.fecha ASC,f03.fechaasig desc";


            if ($sql = mysqli_query($con,$query0)) {
            	if ($sql->num_rows>0) {
            		
		            $ver = mysqli_fetch_array($sql);
		            $nro_ticket 	= $ver['nro_ticket'];
		            $categoria 		= $ver['categoria'];
		            $cod_entidad 	= $ver['cod_entidad'];

		            if($ver["estado"] == null){
		            
		            			$quien_respondio = $ver['usuarioasig'];
		            
		            } else {
		            			$quien_respondio = $ver['quienresp'];
		            }
		            		
		            $ing_env = $ver['ing_env'];
		            $tipo_docum = $ver['tipo_docum'];
		            $nro_mesaent = $ver['nro_mesaent'];
		            $estado = "T";
		            $asunto = convertircadenas($asunto);
		    		
		            

		            $query1 = "INSERT INTO `fil01mail`
		            (`destinatario`, `emisor`, `asunto`,`cc`,`fecha`,`tipo_ingreso`,`estado`,`nro_ticket`,`fecha_ticket`,`nombre_ticket`,`categoria`,`tipo_ticket`) 
		            VALUES 
		            ('$receptor','$emisor','$asunto','$cc','$fecha','M','$estado','$nro_ticket','$fecha','$nom_ticket','$categoria','$tipo_ticket')";


		        	$resultInsert = mysqli_query($con,$query1);

		        	if (!$resultInsert) {
						error_log("Error al insertar en query1 -fil01mail- -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
				    	exit;
					}

		            $id_mail = mysqli_insert_id($con);

		            $date_fecha = date('Y-m-d H:i:s');
		            $query2 = "INSERT INTO `fil03mail`
			            (`id_mail`, `nro_ticket`, `quienderi`, `usuarioasig`, `fechaasig`, `estado`,`tipo_ticket`,`tipo_docum`,`nro_mesaent`,`ing_env`) 
			            VALUES 
			            ('$id_mail','$nro_ticket','0','$quien_respondio','$date_fecha','C','$tipo_ticket',$tipo_docum,$nro_mesaent,'$ing_env')";

			        $resultInsert2 = mysqli_query($con, $query2);
			        if (!$resultInsert2) {
						error_log("Error al insertar en query2 -fil03mail- -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
				    	exit;
					}
					$id_ticket = mysqli_insert_id($con);



            	}else{
            		//En el caso que contenga la palabra ticket pero que no encuentre el numero en la base
            		$asunto = convertircadenas($asunto);
				    $query3 = "INSERT INTO `fil01mail`
				       (`destinatario`, `emisor`, `asunto`,`cc`,`fecha`,`tipo_ingreso`) 
				       VALUES 
				       ('$receptor','$emisor','$asunto','$cc','$fecha','M')";
				    
				    $resultInsert3 = mysqli_query($con, $query3);
				        if (!$resultInsert3) {
							error_log("Error al insertar en query3 -READEMAIL- " .
						            		mysqli_errno($con) . " " . mysqli_error($con));
					    	exit;
						}
				    $id_mail = mysqli_insert_id($con);

            	}


            }else{
            	error_log("Error en la consulta query0 -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
				    		exit;


            }
        }else{

        		//Es el ELSE en el caso que el asunto no sea un ticket.

        		$asunto = convertircadenas($asunto);
			    $query4 = "INSERT INTO `fil01mail`
				       (`destinatario`, `emisor`, `asunto`,`cc`,`fecha`,`tipo_ingreso`) 
				       VALUES 
				       ('$receptor','$emisor','$asunto','$cc','$fecha','M')";
			    
			    $resultInsert3 = mysqli_query($con, $query4);
			        if (!$resultInsert3) {
						error_log("Error al insertar en query4 -fil01mail -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
				    	exit;
					}
			    $id_mail = mysqli_insert_id($con);





        } //fin del if si el asunto contiene la palabra ticket o interno






        
        /********** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ***********/
        /********** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ***********/
        /********** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ***********/
        /********** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ***********/
        /********** ESTRUCTURA DEL MAIL, ADJUNTOS Y MENSAJE  ***********/


		// El número en el constructor es el número del mensaje
		$emailMessage = new EmailMessage($connection, $email_number);


		// Se establece en verdadero para obtener las partes del mensaje (o no se establece en falso, el valor predeterminado es verdadero)
		$emailMessage->getAttachments = false;
		$emailMessage->fetch();
		
	

		// Emparejar imágenes en línea en contenido html
		preg_match_all('/src="cid:(.*)"/Uims', $emailMessage->bodyHTML, $matches);

		// Si hay coincidencias, recórrelas y guárdelas en el sistema de archivos, cambie la propiedad src
		// De la imagen a una URL real



		if(count($matches)) {
			//var_dump($emailMessage->bodyHTML);
			// Buscar y reemplazar matrices se utilizarán en la función str_replace a continuación
			$search = array();
			$replace = array();
			
			foreach($matches[1] as $match) {
				// Elaborar un nombre de archivo único para él y guardarlo en el sistema de archivos, etc.
				$nro_id 	= zero_fill($id_mail);
				//var_dump($emailMessage->attachments[$match]['filename']);
				$uniqueFilename = $nro_id."-".decodificar_nombre($emailMessage->attachments[$match]['filename']);
				$ruta = "imgmail/".$uniqueFilename;
				
				/*PREGUNTO SI YA ES TICKET, PARA INSERTAR EL NRO DE TICKET EN LA TABLA DE ADJUNTOS, SINO NO LE INSERTO NRO_TICKET*/
				if (strpos($asuntocompara, '[TICKET#') !== false || strpos($asuntocompara, '[INTERNO#') !== false) {
					$query5 = "INSERT INTO `fil01adj`(`id_mail`,`ruta`,`nombre`,`nro_ticket`,`tipo`) VALUES ('$id_mail','$ruta','$uniqueFilename','$nro_ticket','E')";

				}else{
					$query5 = "INSERT INTO `fil01adj`(`id_mail`,`ruta`,`nombre`,`tipo`) VALUES ('$id_mail','$ruta','$uniqueFilename','E')";

				}

				
		       $resultInsert5 = mysqli_query($con,$query5);

				if (!$resultInsert5) {
					error_log("Error al insertar en query5 -fil01adj- -READEMAIL- " .
				            		mysqli_errno($con) . " " . mysqli_error($con));
					exit;
				}

				



				file_put_contents("imgmail/$uniqueFilename", $emailMessage->attachments[$match]['data']);
				$search[] = "src=\"cid:$match\"";
				$replace[] = "src=\"imgmail/$uniqueFilename\"";
				if($emailMessage->attachments[$match]["filename"] !== ""){
					$adj = "S";
				} 
				$emailMessage->attachments[$match]["interno"] = true;

			}
			// Ahora haz los reemplazos
			$emailMessage->bodyHTML = str_replace($search, $replace, $emailMessage->bodyHTML);
			//$emailMessage->bodyHTML = str_replace("'", '"', $emailMessage->bodyHTML);

			
		}

		
	
		
		
		$e1 = decodificar_nombre($emailMessage->bodyHTML);
		

		//var_dump($emailMessage);
		$emailMessage->bodyHTML 	= $e1;
		
		$html = convertircadenas($emailMessage->bodyHTML);
		//$html = convertircadenas($emailMessage->bodyPlain);	
		if ($html == "") {
			//$html = convertircadenas($emailMessage->bodyPlain);	
			$e2 	= decodificar_nombre($emailMessage->bodyPlain);
			$emailMessage->bodyPlain = $e2;
			$html 	= convertircadenas($emailMessage->bodyPlain);
		}
		
		

        $query6 = "UPDATE `fil01mail` SET `cuerpo`='$html' WHERE `id_mail`='$id_mail'";

		$resultUpdate6 = mysqli_query($con,$query6);
		            if (!$resultUpdate6) {
						error_log("Error al actualizar en query6 -fil01mail- -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
						exit;
					}


		/** VALIDO SI TIENE ADJUNTOS Y LOS RECORRO  **/
		if($emailMessage->attachments !== null){
			foreach($emailMessage->attachments as $archivos)
		        {
		            if($archivos['interno'] == false)
		            {       
		            	
		            	if($archivos['subtype'] != "DELIVERY-STATUS"){
		            		
		            	$nombrearchivo = eliminar_tildes($archivos['filename']);
		            		 


		                
		                $carpeta = "adjuntos";
			                if(!is_dir($carpeta))
			                {
			                     mkdir($carpeta);
			                }
			            
			            if (strpos($asuntocompara, '[TICKET#') !== false){
			            	
			            	
			            	$query7 = "INSERT INTO `fil01adj`(`id_mail`,`nro_ticket`,`tipo_ticket`,`tipo`) VALUES ('$id_mail','$nro_ticket','E','E')";

			            }elseif (strpos($asuntocompara, '[INTERNO#') !== false) {
			            	
			            	
			            	$query7 = "INSERT INTO `fil01adj`(`id_mail`,`nro_ticket`,`tipo_ticket`,`tipo`) VALUES ('$id_mail','$nro_ticket','I','E')";
			            }else{

			            	$query7 = "INSERT INTO `fil01adj`(`id_mail`,`tipo`) VALUES ('$id_mail','E')";	
			            }
		             	
		                $resultInsert7 = mysqli_query($con,$query7);

		                if (!$resultInsert7) {
						error_log("Error al insertar en query7 -fil01adj- -READEMAIL- " .
					            		mysqli_errno($con) . " " . mysqli_error($con));
						exit;

						}


		                $id_file 	= mysqli_insert_id($con);
		                $nro_id 	= zero_fill($id_file);
		                $nombre 	= $nro_id . "-" . $nombrearchivo;
		                $ruta 		= $carpeta ."/". $nombre;
		                $fp 		= fopen("./". $carpeta ."/". $nombre, "w+");
		                fwrite($fp, $archivos['data']);
		                fclose($fp);
		                //error_log($nombrearchivo);
		                //error_log($nombre);
		                //$salida.= '<p-->Adjunto: <b>'.$archivos['filename'].'</b><br>';
		                $query8 = "UPDATE `fil01adj` SET `ruta`='$ruta',`nombre`='$nombre' WHERE `id_file`='$id_file' and `id_mail`='$id_mail'";
		                $resultUpdate8 = mysqli_query($con,$query8);
		                if (!$resultUpdate8) {
							error_log("Error al actualizar en query8 -fil01adj- -READEMAIL-  " .
						            		mysqli_errno($con) . " " . mysqli_error($con));
							exit;
						}



		            }//fin del if si tiene adjuntos dentro del mensaje
		         }   
		            $adj = "S";
		        }//fin foreach
		}//fin if principal

		$query9 = "UPDATE `fil01mail` SET `adjunto`='$adj' WHERE `id_mail`='$id_mail'";
        $resultUpdate9 = mysqli_query($con,$query9);
         if (!$resultUpdate9) {
							error_log("Error al actualizar en query9 -fil01mail- -READEMAIL-  " .
						            		mysqli_errno($con) . " " . mysqli_error($con));
							exit;
						}
		if ($id_ticket != 0) {
			
			$query10 = "UPDATE `fil03mail` SET `adjunto`='$adj' WHERE `id`='$id_ticket'";
	        $resultUpdate10 = mysqli_query($con,$query10);
	         if (!$resultUpdate10) {
								error_log("Error al actualizar en query10 -fil03mail- -READEMAIL-  " .
							            		mysqli_errno($con) . " " . mysqli_error($con));
								exit;
							}
		}
        
       /* $salida 	.= '<p-->Fecha: <b>'.$fecha.'</b><br>';
		$salida 	.= '<p-->a: <b>'.$receptor.'</b><br>';
		$salida 	.= '<p-->de: <b>'.$emisor.'</b><br>';
		$salida 	.= '<p-->Asunto: <b>'.$asunto.'</b><br>';
		$salida.= '<p-->Tiene adj: <b>'.$adj.'</b><br>';
		$salida.= "Mensaje: <br>".$emailMessage->bodyHTML;
		$salida.="<hr />";*/
		

imap_mail_move($connection, $email_number, 'INBOX.Trash');


   } // fin del Foreach de cada mail





//echo $salida;



}
//fin
imap_expunge($connection);
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
				$this->attachments[] = array(
					'interno' => false,
					'type' => $part->type,
					'subtype' => $part->subtype,
					'filename' => '',
					'data' => $msg,
					'inline' => false,
				);
				

				
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

					$this->attachments[$id] = array(
						'interno' => false,
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getPart($partNumber, $part->encoding) <> "" ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => true,
					);
				}

				else {
					$this->attachments[] = array(
						'interno' => false,
						'type' => $part->type,
						'subtype' => $part->subtype,
						'filename' => $this->getFilenameFromPart($part),
						'data' => $this->getPart($partNumber, $part->encoding) <> "" ? $this->getPart($partNumber, $part->encoding) : '',
						'inline' => false,
					);
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


	
	function getFilenameFromPart($part) {
		$filename = '';

		if($part->ifdparameters) {

			foreach($part->dparameters as $object) {

				if(strtolower($object->attribute) == 'filename') {
					$filename = $object->value;					
				}
			}
		}

		if(!$filename && $part->ifparameters) {

			foreach($part->parameters as $object) {

				if(strtolower($object->attribute) == 'name') {
					$filename = $object->value;
				}
			}
		}
		
		return $filename;
	}


	
}

?>
