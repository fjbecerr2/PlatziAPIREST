<?php
  //Encriptar el token
  $token =sha1('mi token');
  
  function generarToken(){
    $method =strtoupper($_SERVER["REQUEST_METHOD"]);

    //Comprobar el método
    if($method=='POST'){
      //Comprobar elementos
      if(!array_key_exists('HTTP_X_CLIENT_ID',$_SERVER)
      || !array_key_exists( 'HTTP_X_SECRET', $_SERVER)
      ){
        /*
        400 Bad Request indica que el servidor no puede o no procesará la petición debido a algo que es percibido como un error del cliente
        */
        http_response_code(400);
        die('Faltan parámetros');
      }

      //Tomamos los valores
      $cliente =$_SERVER['HTTP_X_CLIENT_ID'];
      $secret =$_SERVER['HTTP_X_SECRET'];
      
      //Comprobamos los datos
      validarUsuario($cliente,$secret);
      //Si todo es correcto devolvemos el token
      return $token;
    }
  }

  function validarUsuario($usuario,$pw){
    echo 'uSUARIO'.$usuario.'- '.$pw;
      //Comprobamos los datos
    if($usuario!=2 || $pw!="ENTRADA"){
      /*403 El cliente no posee los permisos necesarios para cierto contenido, por lo que el servidor está rechazando otorgar una respuesta apropiada.*/
      http_response_code(403);
      die('No autorizado');
    }else{
        echo '[INFO][VALIDACION CORRECTA]<BR>';
        echo '[INFO][GENERANDO TOKEN]<BR>';
      }
  }

  function validarToken(){
    $method =strtoupper($_SERVER["REQUEST_METHOD"]);
    //Comprobar el método
    if(!$method=='GET'){
      return false;
    }

    //¿Existe el token?
    if(!array_key_exists('HTTP_X_TOKEN',$_SERVER)){
      http_response_code( 400 );
      die ( 'Faltan parametros' );
    }

    //Comprobar el valor del token
    if (!$_SERVER['HTTP_X_TOKEN'] == $token ) {
      echo '[INFO][TOKEN INVALIDO]<BR>';
      echo 'IN: '.$_SERVER['HTTP_X_TOKEN']."<BR>";
      echo 'OUT: '.$token."<BR>";
    } else {
      echo '[INFO][TOKEN VALIDO]<BR>';
    }
         
    
  }

?>

