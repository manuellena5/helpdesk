<?php 
    
    define ("WSDL", "https://wsaa.afip.gov.ar/ws/services/LoginCms?wsdl");     # The WSDL corresponding to WSAA
    define ("URL", "https://wsaa.afip.gov.ar/ws/services/LoginCms");
    define ("SERVICE", "ws_sr_padron_a13");       
    define ("CERT", "ciecuit_ea9ced0affc8562.crt");       
    define ("PRIVATEKEY", "cert.key");
    define ("PASSPHRASE", ""); # The passphrase (if any) to sign
    define ("CUITREPRESENTADA", 30684707910);
    define ("TA", "TA.xml");
   
    
    if (isset($_GET['cuitbuscar'])) {

       $campocuit = $_GET['cuitbuscar'];
       getPersona($campocuit);
    }
    




    #**********************************************
    #** Metodos ***********************************
    #**********************************************
    function CreateTRA($SERVICE)
    {
      $TRA = new SimpleXMLElement(
        '<?xml version="1.0" encoding="UTF-8"?>' .
        '<loginTicketRequest version="1.0">'.
        '</loginTicketRequest>');
      $TRA->addChild('header');
      $TRA->header->addChild('uniqueId',date('U'));
      $TRA->header->addChild('generationTime',date('c',date('U')-60));
      $TRA->header->addChild('expirationTime',date('c',date('U')+60));
      $TRA->addChild('service', $SERVICE);
      $TRA->asXML('TRA.xml');
    }
    









    #**************************************************************************************
    #** Esta función hace que la firma PKCS # 7 use TRA como archivo de entrada, CERT y ***
    #** PRIVATEKEY para firmar. Genera un archivo intermedio y finalmente ajusta el     ***
    #** Encabezado MIME dejando el CMS final requerido por WSAA                         ***
    #**************************************************************************************
    function SignTRA()
    {
        $currentPath = getcwd() . "/";
        if (!file_exists('TRA.xml')) {exit("Failed to open TRA.xml\n");}
        $STATUS=openssl_pkcs7_sign(
          $currentPath.'TRA.xml', 
          $currentPath.'TRA.tmp', 
          "file://".$currentPath.CERT,
            array("file://".$currentPath.PRIVATEKEY,PASSPHRASE),
            array(),
            !PKCS7_DETACHED
        );
      if (!$STATUS) {exit("ERROR generating PKCS#7 signature ". $currentPath . CERT."\n" );}
      $inf=fopen($currentPath.'TRA.tmp', "r");
      $i=0;
      $CMS="";
      while (!feof($inf)) 
        { 
          $buffer=fgets($inf);
          if ( $i++ >= 4 ) {$CMS.=$buffer;}
        }
      fclose($inf);
      unlink('TRA.tmp');
      return $CMS;

    }









        #************************************
    #*** Metodo que crea el objeto Soap y devuelve el ticket de acceso ***
    #************************************

    
    function CallWSAA($CMS)
    { 
        try {
          
        
        $client= new SoapClient(WSDL, array(
                'verifypeer'      => false, 
                'verifyhost'      => false, 
                'soap_version'    => SOAP_1_2,
                'location'        => URL,
                'trace'           => 1,
                'exceptions'      => 0,
                'cache_wsdl'      =>WSDL_CACHE_NONE,
                "stream_context"  => stream_context_create(
                  array(
                        'ssl' => array('crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                                'verify_peer' => false,
                                'verify_peer_name'  => false
                                      )
                        )
                                                          )
                  )); 
        $results=$client->loginCms(array('in0'=>$CMS));
        file_put_contents("request-loginCms.xml",$client->__getLastRequest());
        file_put_contents("response-loginCms.xml",$client->__getLastResponse());
        
        if (is_soap_fault($results)) 
        { 
          
          exit("Error ".$results->faultstring);  
        
           }
      //file_put_contents("Ta.xml",$results->loginCmsReturn)
      return $results->loginCmsReturn;
      
      } catch (Exception $e) {
         echo $e->getMessage(); 
        }
    }







    #************************************
    #*** Metodo que crea el Ticket de acceso en archivo xml ***
    #************************************

    function CreateTA($TA)
    {
      $token = $TA->credentials->token;
      $sign = $TA->credentials->sign;
      $expiracionTime = horaExpiracion();
      $TA = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<loginTicketResponse version="1.0">'.
            '</loginTicketResponse>');
      $TA->addChild('header');
      $TA->header->addChild('token',$token);
      $TA->header->addChild('sign',$sign);
      $TA->header->addChild('CUITREPRESENTADA',CUITREPRESENTADA);
      $TA->header->addChild('expiracionTime',$expiracionTime);
      $TA->asXML('TA.xml');
    
    }











    #************************************
    #*** funcion para mostrar los datos de un solo cuit ***
    #************************************

    function getPersona($campocuit){

      /*
        Parametros de entrada cuit

        Parametros de salida
        
        cuitvalido si/no 
        Error mensaje de error de afip
        cuit numero de cuit
        tipoClave cuit/cuil/cdi
        tipoPersona fisica/juridica
        razonSocial o apellido+nombre
        idProvincia 
        direccion 
        localidad
      */

  try {
        

    if (!TAvalido()) {
      
      $TA = conexionws();
    }else{

    $currentPath = getcwd() . "/";
    $TA = simplexml_load_file($currentPath."TA.xml");

    }

    $cuitbuscar = (double)$campocuit;
    $cuitRepresentada = (double)$TA->header->CUITREPRESENTADA;
    $token = $TA->header->token;
    $sign = $TA->header->sign;

    
    $wservice = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13";
    $wsdl_padron = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13?WSDL";
    
    $padron = new SoapClient($wsdl_padron, array(
     'verifypeer'     => false, 
     'verifyhost'     => false, 
     'soap_version'   => SOAP_1_1,
     'location'       => $wservice,
     'trace'          => 1,
     'exceptions'     => 0,
     'cache_wsdl'     =>WSDL_CACHE_NONE,
     "stream_context" => stream_context_create(
      array(
        'ssl' => array('crypto_method' =>STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
          'verify_peer' => false,
          'verify_peer_name'  => false
        )
      )
    )
   )
  );


    $resultado = $padron->getPersona(
      array(
        'token' => $token,
        'sign' => $sign,
        'cuitRepresentada' => (double)$cuitRepresentada,
        'idPersona' => (DOUBLE)$cuitbuscar
      )
    );
    //echo $cuitbuscar;
    //echo "REQUEST:\n" . $padron->__getLastRequest() . "\n";
    //echo "RESPONSE:\n" . $padron->__getLastResponse() . "\n";
   
  //var_dump($resultado);
    if (is_soap_fault($resultado)) 
    {//
      //exit("SOAP Fault: ".$resultado->faultstring);
          
          $mensajeerror = "No existe CUIT/CUIL en afip ";
          $cuitvalido = false; //no existe el cuit
          $idProvincia= "";
          $direccion ="";
          $localidad="";
          $tipoClave="";
          $tipoPersona="";
          $razonSocial = "";
          $nombre = "";
          $apellido = "";
    
    }else{
    
    $mensajeerror = "";
    $cuitvalido = true;
    $datos = json_encode($resultado);
    $persona =  json_decode($datos);

     //var_dump($persona);
     //var_dump($persona->personaReturn->persona->domicilio);
     
      if (array_key_exists('domicilio', $persona->personaReturn->persona)){
        $domicilio = $persona->personaReturn->persona->domicilio;
      }
     
     $tipoClave = $persona->personaReturn->persona->tipoClave;
     $tipoPersona = $persona->personaReturn->persona->tipoPersona;
     //var_dump($domicilio);
    
    if ($tipoPersona == 'JURIDICA') {
     
            $razonSocial = $persona->personaReturn->persona->razonSocial;
          if (!array_key_exists('domicilio', $persona->personaReturn->persona)) {
              $codPostal = "";
              $descripcionProvincia = "";
              $direccion = "";
              $tipoDomicilio = "";
              $idProvincia = "";
              $localidad = "";
          }else{
            if (is_array($domicilio) && count($domicilio)>1) {
                  foreach ($domicilio as $dom) {
                      foreach ($dom as $indice =>$valor) {
                        if($dom->tipoDomicilio == "FISCAL"){
                          
                          $codPostal = $dom->codigoPostal; 
                          $descripcionProvincia = $dom->descripcionProvincia;
                          $direccion = $dom->direccion;
                          $tipoDomicilio = $dom->tipoDomicilio;  
                          $idProvincia = $dom->idProvincia;
                          if ($idProvincia == 0) {
                            $localidad = "CABA"; 

                          }elseif (!array_key_exists('localidad', $dom)) {
                              $localidad = "";

                            }else{
                              $localidad = $dom->localidad;
                            }
                          
                          
                        
                        }//FIN DEL IF
                      }
                  }//FIN DEL FOREACH
            }else{
                      
                      foreach ($domicilio as $dom) {

                        $codPostal = $domicilio->codigoPostal; 
                        $descripcionProvincia = $domicilio->descripcionProvincia;
                        $direccion = $domicilio->direccion;
                        $tipoDomicilio = $domicilio->tipoDomicilio; 
                        $idProvincia = $domicilio->idProvincia;
                        if ($idProvincia == 0) {
                            $localidad = "CABA"; 

                          }elseif (!array_key_exists('localidad', $domicilio)) {
                              $localidad = "";
                             
                            }else{
                              $localidad = $domicilio->localidad;
                            }

                      }//FIN DEL FOREACH


            }//fin else del count<=1
          }//fin del ese si no tiene domicilio

    }elseif(($tipoPersona == 'FISICA') && ($tipoClave=='CUIT')){
              if (!array_key_exists('nombre', $persona->personaReturn->persona)) {
                $nombre = "";
              }else{
                $nombre = $persona->personaReturn->persona->nombre;  
              }

              if (!array_key_exists('apellido', $persona->personaReturn->persona)) {
                $apellido = "";
              }else{
                $apellido = $persona->personaReturn->persona->apellido;  
              }

             // $nombre = $persona->personaReturn->persona->nombre;
              //$apellido = $persona->personaReturn->persona->apellido;
              $razonSocial = $apellido." ".$nombre;
              
            if (!array_key_exists('domicilio', $persona->personaReturn->persona)) {
              $codPostal = "";
              $descripcionProvincia = "";
              $direccion = "";
              $tipoDomicilio = "";
              $idProvincia = "";
              $localidad = "";
            }else{
              if (is_array($domicilio) && count($domicilio)>1) {
                    foreach ($domicilio as $dom) {
                        foreach ($dom as $indice =>$valor) {
                          if($dom->tipoDomicilio == "FISCAL"){
                          
                            $codPostal = $dom->codigoPostal; 
                            $descripcionProvincia = $dom->descripcionProvincia;
                            $direccion = $dom->direccion;
                            $tipoDomicilio = $dom->tipoDomicilio; 
                            $idProvincia = $dom->idProvincia;
                            if ($idProvincia == 0) {
                            $localidad = "CABA"; 

                            }elseif (!array_key_exists('localidad', $dom)) {
                                $localidad = "";
                               
                              }else{
                                $localidad = $dom->localidad;
                              }
                            
                             
                          
                          }//FIN IF
                        }
                    }//FIN DEL FOREACH
              }else{

                       //var_dump($domicilio);
                      foreach ($domicilio as $dom) {

                        $codPostal = $domicilio->codigoPostal; 
                        $descripcionProvincia = $domicilio->descripcionProvincia;
                        $direccion = $domicilio->direccion;
                        $idProvincia = $domicilio->idProvincia;
                        if ($idProvincia == 0) {
                            $localidad = "CABA"; 

                            }elseif (!array_key_exists('localidad', $domicilio)) {
                                $localidad = "";
                               
                              }else{
                                $localidad = $domicilio->localidad;
                              }
                        $tipoDomicilio = $domicilio->tipoDomicilio; 

                      }

              }//fin else del count<=1
            } // fin del else si no tiene domicilio
      

    }elseif(($tipoPersona == 'FISICA') && ($tipoClave=='CUIL')){
      
                if (!array_key_exists('nombre', $persona->personaReturn->persona)) {
                  //PERSONA QUE NO TIENE NOMBRE";
                  $nombre = "";
                }else{

                  $nombre = $persona->personaReturn->persona->nombre;
                }

                if (!array_key_exists('apellido', $persona->personaReturn->persona)) {
                  //PERSONA QUE NO TIENE NOMBRE";
                  $apellido = "";
                }else{

                  $apellido = $persona->personaReturn->persona->apellido;
                }
            
                //$nombre = $persona->personaReturn->persona->nombre;
                //$apellido = $persona->personaReturn->persona->apellido;
                $razonSocial = $apellido." ".$nombre;
            if (!array_key_exists('domicilio', $persona->personaReturn->persona)) {
              $codPostal = "";
              $descripcionProvincia = "";
              $direccion = "";
              $tipoDomicilio = "";
              $idProvincia = "";
              $localidad = "";
            }else{
                if (is_array($domicilio) && count($domicilio)>1) {
                      
                      foreach ($domicilio as $dom) {
                        foreach ($dom as $indice =>$valor) {
                              if($dom->tipoDomicilio == "FISCAL"){
                              
                                $codPostal = $dom->codigoPostal; 
                                $descripcionProvincia = $dom->descripcionProvincia;
                                $direccion = $dom->direccion;
                                $tipoDomicilio = $dom->tipoDomicilio;
                                $idProvincia = $dom->idProvincia;
                                if ($idProvincia == 0) {
                                $localidad = "CABA"; 

                                }elseif (!array_key_exists('localidad', $dom)) {
                                    $localidad = "";
                                   
                                  }else{
                                    $localidad = $dom->localidad;
                                  }

                                }//FIN DEL IF
                         }
                      }//FIN FOREACH
                }else{
                  //var_dump($domicilio);
                        foreach ($domicilio as $dom) {

                              $codPostal = $domicilio->codigoPostal; 
                              $descripcionProvincia = $domicilio->descripcionProvincia;
                              $direccion = $domicilio->direccion;
                              $idProvincia = $domicilio->idProvincia;
                              if ($idProvincia == 0) {
                                $localidad = "CABA"; 

                                }elseif (!array_key_exists('localidad', $domicilio)) {
                                    $localidad = "";
                                   
                                  }else{
                                    $localidad = $domicilio->localidad;
                                  }
                              $tipoDomicilio = $domicilio->tipoDomicilio; 
                        }

                    }//fin else del count<=1
                }//fin del else si no tiene domicilio
    
          }elseif(($tipoPersona == 'FISICA') && ($tipoClave=='CDI')){
      
                if (!array_key_exists('nombre', $persona->personaReturn->persona)) {
                  //PERSONA QUE NO TIENE NOMBRE";
                  $nombre = "";
                }else{

                  $nombre = $persona->personaReturn->persona->nombre;
                }
            
                //$nombre = $persona->personaReturn->persona->nombre;
                $apellido = $persona->personaReturn->persona->apellido;
                $razonSocial = $apellido." ".$nombre;
            if (!array_key_exists('domicilio', $persona->personaReturn->persona)) {
              $codPostal = "";
              $descripcionProvincia = "";
              $direccion = "";
              $tipoDomicilio = "";
              $idProvincia = "";
              $localidad = "";
            }else{
                if (is_array($domicilio) && count($domicilio)>1) {
                      
                      foreach ($domicilio as $dom) {
                        foreach ($dom as $indice =>$valor) {
                              if($dom->tipoDomicilio == "FISCAL"){
                              
                                $codPostal = $dom->codigoPostal; 
                                $descripcionProvincia = $dom->descripcionProvincia;
                                $direccion = $dom->direccion;
                                $tipoDomicilio = $dom->tipoDomicilio;
                                $idProvincia = $dom->idProvincia;
                                if ($idProvincia == 0) {
                                $localidad = "CABA"; 

                                }elseif (!array_key_exists('localidad', $dom)) {
                                    $localidad = "";
                                   
                                  }else{
                                    $localidad = $dom->localidad;
                                  }

                                }//FIN DEL IF
                         }
                      }//FIN FOREACH
                }else{
                  //var_dump($domicilio);
                        foreach ($domicilio as $dom) {

                              $codPostal = $domicilio->codigoPostal; 
                              $descripcionProvincia = $domicilio->descripcionProvincia;
                              $direccion = $domicilio->direccion;
                              $idProvincia = $domicilio->idProvincia;
                              if ($idProvincia == 0) {
                                $localidad = "CABA"; 

                                }elseif (!array_key_exists('localidad', $domicilio)) {
                                    $localidad = "";
                                   
                                  }else{
                                    $localidad = $domicilio->localidad;
                                  }
                              $tipoDomicilio = $domicilio->tipoDomicilio; 
                        }

                    }//fin else del count<=1
                }//fin del else si no tiene domicilio
    
          }//fin else de los tipos de personas//fin else de los tipos de personas
      
    }



    $per = array();
    $per['cuitvalido'] = $cuitvalido;
   //ingresa true o false de acuerdo si el cuit es valido o no
    $per['cuit'] = $cuitbuscar;
    $per['tipoClave']=$tipoClave;
    $per['tipoPersona']= $tipoPersona;
    $per['razonSocial']= $razonSocial;
    $per['idProvincia']= $idProvincia;
    $per['direccion'] =$direccion;
    $per['localidad']=$localidad;
    $per['mensajeerror']=$mensajeerror; //NO EXISTE EL CUIT/CUIL o NO ESTA ACTIVO EN AFIP

     if ($per['tipoClave'] == "CUIT") {
            $per['id_tipodoc']=2;
          }elseif($per['tipoClave'] == "CUIL"){
             $per['id_tipodoc']=3;
          }
          elseif($per['tipoClave'] == "CDI"){
             $per['id_tipodoc']=3;
          }
     
     /*
    if ($per['cuitvalido']) { 
     echo "cuitvalido SI <br>";
    }else{
      echo "cuitvalido NO <br>";
     
    }
    echo "Error ".$per['mensajeerror']."<br>";
    //echo "noexiste ".$per['noexiste']."<br>";
    //echo "cuitvalido ".$per['cuitvalido']."<br>"; //ingresa true o false de acuerdo si el cuit es valido o no
    echo "cuit ".$per['cuit']."<br>";;
    echo "tipoClave ".$per['tipoClave']."<br>";;
    echo "tipoPersona ".$per['tipoPersona']."<br>";;
    echo "razonSocial ".$per['razonSocial']."<br>";;
    echo "idProvincia ".$per['idProvincia']."<br>";;
    echo "direccion ".$per['direccion']."<br>";;
    echo "localidad ".$per['localidad']."<br>";;
    */
    


     
   return $per;
    
    
   
    
    }catch (Exception $e) {
     //var_dump($e);
      $e->getMessage();
      error_log($e->getMessage());
    }


    }























     #************************************
    #*** Valida si esta funcionando bien el webservice ***
    #************************************


    function validarWS(){

         try {
          
        $wservice = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13";
        $wsdl_padron = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13?WSDL";

        $client= new SoapClient($wsdl_padron, array(
                'verifypeer'      => false, 
                'verifyhost'      => false, 
                'soap_version'    => SOAP_1_1,
                'location'        => $wservice,
                'trace'           => 1,
                'exceptions'      => 0,
                'cache_wsdl'      =>WSDL_CACHE_NONE,
                "stream_context"  => stream_context_create(
                  array(
                        'ssl' => array('crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                                'verify_peer' => false,
                                'verify_peer_name'  => false
                                      )
                        )
                                                          )
                  )); 
        
       $resultado = $client->dummy();
      
     //var_dump($resultado);
    //echo $cuitbuscar;
    //echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
    //echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";
          
          if ($resultado->return->appserver == 'OK' && 
              $resultado->return->authserver == 'OK' &&
              $resultado->return->dbserver == 'OK')
          {
            return true;
            }else{ 
            return false;}
       
        if (is_soap_fault($resultado)) 
        { 

          exit("Error SOAP Fault: ".$resultado->faultstring);  
        
           }
     
      
      } catch (Exception $e) {
         echo $e->getMessage(); 
        }






    }








    #************************************
    #*** Rutina de inicio ***
    #************************************
    function conexionws(){
    
    try { 
    


      //chequeo si el TA no es valido o si no hay TA creado
    if (!TAvalido() or !file_exists('TA.xml')) {

    if (file_exists('TA.xml')) {
      unlink('TA.xml');
    }

    ini_set("soap.wsdl_cache_enabled", "0");
    
       
    
     // chequeo de que exiten los archivos locales.
    if (!file_exists(CERT)) {exit("Error al abrir el archivo de certificado");}
    if (!file_exists(PRIVATEKEY)) {exit("Error al abrir el archivo de la clave ");}
   
    CreateTRA(SERVICE);
    $CMS=SignTRA(); 


    $TA = simplexml_load_string(CallWSAA($CMS));
    //var_dump($TA);
    CreateTA($TA);
    $currentPath = getcwd() . "/";
    $TA = simplexml_load_file($currentPath."TA.xml");
    }
    else{
        $currentPath = getcwd() . "/";
        $TA = simplexml_load_file($currentPath."TA.xml");

    }

    
    if (!validarWS()) {
      echo("Error en la conexion con el webservice");
      return false;
    }else{
      return $TA;
    }
    
      
     }catch (Exception $e) {
      echo "Error: ".$e->getMessage();
    }

    }





























    #************************************
    #*** funcion para validar si hay que generar o no el TA ***
    #************************************

    function TAvalido(){


     if (!file_exists("response-loginCms.xml")) {
        return false;
      }else{
        
        
        $fechayhora = horaExpiracion();

        $fechaTA = substr($fechayhora,0,-19);
        $horaTA = substr($fechayhora,11,-10);

       


        $date = new DateTime();
        $fechaservidor = $date->format('Y-m-d');
        $horaservidor = $date->format('H:i:s');


        /*ECHO "Fecha servidor: ".$fechaservidor."<br>";
        echo "Fecha TA: ".$fechaTA."<br>";
        ECHO "Hora servidor ".$horaservidor."<br>";
        echo "Hora ticket ".$horaTA."<br>";
        */


        if ($fechaservidor<$fechaTA) {
          
            return true; 

        }elseif($fechaservidor==$fechaTA){
                if ($horaservidor<$horaTA) {
               
                return true;
                
              }}else{
                  
                  return false;
                  
              }
          
        

      } //fin del if si existe el archivo o no
    
    }








    #************************************
    #*** Funcion para ver el estado del webservice ***
    #************************************

    function verEstadoWs(){

        
        
         try {
        $TA = conexionws();

          
        $wservice = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13";
        $wsdl_padron = "https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA13?WSDL";

        $client= new SoapClient($wsdl_padron, array(
                'verifypeer'      => false, 
                'verifyhost'      => false, 
                'soap_version'    => SOAP_1_1,
                'location'        => $wservice,
                'trace'           => 1,
                'exceptions'      => 0,
                'cache_wsdl'      =>WSDL_CACHE_NONE,
                "stream_context"  => stream_context_create(
                  array(
                        'ssl' => array('crypto_method' => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT,
                                'verify_peer' => false,
                                'verify_peer_name'  => false
                                      )
                        )
                                                          )
                  )); 
        
       $resultado = $client->dummy();
      
     //var_dump($resultado);
    //echo $cuitbuscar;
    //echo "REQUEST:\n" . $client->__getLastRequest() . "\n";
    //echo "RESPONSE:\n" . $client->__getLastResponse() . "\n";
          
          
          
       
        if (is_soap_fault($resultado)) 
        { 

          echo("Error SOAP Fault: ".$resultado->faultstring);  
        
           }
        echo "Conexion a webservice: $wservice <br>";
        echo "appServer: ".$resultado->return->appserver."<br>";
        echo "authserver: ".$resultado->return->authserver."<br>";
        echo "dbserver: ".$resultado->return->dbserver."<br>";
        echo "Ticket de acceso valido hasta: ".$TA->header->expiracionTime."<br>";
       
        
     
      
      } catch (Exception $e) {
         echo $e->getMessage(); 
        }

    








    }






    function horaExpiracion(){


      if (file_exists("response-loginCms.xml")) {
        
        
        $currentPath = getcwd() . "/";
        $archivoResponse = file_get_contents($currentPath."response-loginCms.xml");
        $xmlDOM = new DOMDocument();  
        $xmlDOM->loadXML($archivoResponse);
        $xx = $xmlDOM->saveXML();
    

        $expirationTime = 'expirationTime';
        $pos1 = strpos($xx, $expirationTime);
        $pos1 += 18;
        //echo $pos1;

        $expirationTime2 = '/expirationTime';
        $pos2 = strpos($xx, $expirationTime2);
        $pos2 -= 4; 
        //echo $pos2;

        $longitud = $pos2-$pos1;
        $fechayhora = substr($xx,$pos1,$longitud);

        return $fechayhora;

      }



    }




 ?>    
