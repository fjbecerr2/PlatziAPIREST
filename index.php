<html>
  <head>
    <title>PHP Test</title>
  </head>
  <body>
   <!-- LLAMAR LINK DE UNA PAGINA -->
   <!-- El link solo se ejecuta al pulsar
   sobre el y llama a la pagina URL-PHP -->
   <a href="cargarURL.php/?id=1">Ir a cargarURL.pnp</a><br>

   <!-- HACER EL INCLUDE DE UNA PAGINA -->
   <!-- El include ejecuta el codigo php en 
   la pagina actual antes de cargar el resto de
   la pagina -->
  <?php
    echo '[INFO] [EJECUTANDO cargaURL.php]<BR>';
    include 'cargarURL.php';
    echo '[INFO] [FIN cargaURL.php]<BR>';
  ?>   

  <!-- LLAMAR LINK DE UNA PAGINA -->
  <!-- El link solo se ejecuta al pulsar
  sobre el y llama a la pagina URL-PHP -->
  <a href="server.php/?recursoTipo=libros&ID=1">Ir a sever.pnp URL PHP</a><br>

  <!-- LLAMAR LINK DE UNA PAGINA -->
  <!-- El link solo se ejecuta al pulsar
  sobre el y llama a la pagina URL-API-REST -->
  <a href="server.php/libros/2">Ir a sever.pnp URL API-REST</a><br>

  <!-- USAR SERVER.PHP DIRECTAMENTE -->
  <?php 
    /***********
    * MAIN
    ************/
    //Datos para simular BBDD
    include 'libros.php';
    $listaLibros =libros_cargarLibros($listaLibros);
    libros_verListaLibros($listaLibros);
    //Servidor WEB
    /*ESTE CODIGO ES OPERATIVO SI SE 
    DESACTIVA LA OPCION LANZAR_DESDE_URL
    DEL SERVER.PHP 
    ASI EL CODIGO SE LANZA SIN PULSAR EN ENLACE*/
    include "server.php";
    #Definimos los tipos de recursos
    $recursosPermitidos =server_definirRecursos();
    server_listarTiposRecursos($recursosPermitidos);
    
    //-- TODO ESTE CODIGO SON PETICIONES GET --
    /*DESCRIPCIÓN: El método GET envía la información codificada del usuario en el header del HTTP request, directamente en la URL. La página web y la información codificada se separan por un interrogante ?:
    */
    echo '[INFO][** CODIGO-GET **]<BR>';

    #Hacemos get sobre el recurso de la URL SIN PARAMETRO - PROVOCA UN ERROR 400
    //http://localhost:8000
    //$resourceType = $_GET['resource_type'];

    #Hacemos get sobre el recurso de la URL CON PARAMETRO - NO PROVOCA ERROR
    //https://localhost:8000?resource_type=books
    //$resourceType = isset($_GET[$resource_type]) ? $_GET[$resource_type] : 'books';

    #Hacemos get sobre el recurso de la URL CON PARAMETRO - NO PROVOCA ERROR 404
    //https://localhost:8000?resource_type=books
    //$resourceType = isset($_GET[$resource_type]) ? $_GET[$resource_type] : 'authors';
    
    #Hacemos get sobre el recurso de la URL CON PARAMETRO (RECURSO Y ID) - NO PROVOCA ERROR
    //https://localhost:8000?resource_type=books&resourceId=1
    $resourceType = isset($_GET[$resource_type]) ? $_GET[$resource_type] : 'libros';
    $resourceId = isset($_GET[$resourceId]) ? $_GET[$resourceId] : 1;
    
    #Estructura basica de un SERVIDOR WEB
    #Capturar el metodo
    $method = $_SERVER['REQUEST_METHOD'];
    if( server_comprobarRecursoLibroGET($resourceType)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }

    if(server_comprobarTipoRecurso($resourceType, $recursosPermitidos)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }

    server_comprobarTipoPeticion($method,$resourceType,$resourceId,$listaLibros);
      
    //---- *** -----

    //-- TODO ESTE CODIGO SON PETICIONES POST --
    /*Descripcion: Con el método HTTP POST también se codifica la información, pero ésta se envía a través del body del HTTP Request, por lo que no aparece en la URL.
    */
    
  ?> 
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
  <p>Busque un libro por su id y el solo añade otro</p>
  Name: <input type="text" name="fID">
  <input type="submit">
</form>

  <?php
    echo '<HR>';
    echo '[INFO][** CODIGO-POST **]<BR>';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      //Asignamos el recurso, el id sale de form
      $resourceType = isset($_POST[$resource_type]) ? $_POST[$resource_type] : 'libros';
      $resourceId = $_POST['fID'];
               
    if( server_comprobarRecursoLibroGET($resourceType)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }

    if(server_comprobarTipoRecurso($resourceType, $recursosPermitidos)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }
      
      server_comprobarTipoPeticion($method,$resourceType,$resourceId,$listaLibros);
    }
    

    
  ?>

   <?php
    /* Descripcion: PUT cuando necesite reemplazar el estado de algunos datos ya existentes en ese sistema.*/
    echo '<HR>';
    echo '[INFO][** CODIGO-PUT **]<BR>';
    $_SERVER["REQUEST_METHOD"] ="PUT";
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($_SERVER["REQUEST_METHOD"] == "PUT") {
      //Asignamos el recurso, el id sale de form
      $resourceType = isset($_PUT[$resource_type]) ? $_PUT[$resource_type] : 'libros';
      $resourceId = isset($_PUT[$resourceId]) ? $_PUT[$resourceId] : 1;
  
      if( server_comprobarRecursoLibroGET($resourceType)==false){
        echo "[INFO] [CIERRE]<BR>";
        die;
      }

      if(server_comprobarTipoRecurso($resourceType, $recursosPermitidos)==false){
        echo "[INFO] [CIERRE]<BR>";
        die;
      }
      
      server_comprobarTipoPeticion($method,$resourceType,$resourceId,$listaLibros);
    }

  ?>

   <?php
    echo '<HR>';
    echo '[INFO][** CODIGO-DELETE **]<BR>';
    $_SERVER["REQUEST_METHOD"] ="DELETE";
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
      //Asignamos el recurso, el id sale de form
      $resourceType = isset($_PUT[$resource_type]) ? $_PUT[$resource_type] : 'libros';
      $resourceId = isset($_PUT[$resourceId]) ? $_PUT[$resourceId] : 3;
  
      if( server_comprobarRecursoLibroGET($resourceType)==false){
        echo "[INFO] [CIERRE]<BR>";
        die;
      }

      if(server_comprobarTipoRecurso($resourceType, $recursosPermitidos)==false){
        echo "[INFO] [CIERRE]<BR>";
        die;
      }
      
      server_comprobarTipoPeticion($method,$resourceType,$resourceId,$listaLibros);
    }

  ?>

  <form method="post" action="<?php echo $_SERVER  ['PHP_SELF'];?>">
    USUARIO: <input type="text" name="fUsuario"><BR>
    CONTRASEÑA: <input type="password" name="fPWD"><BR>
    <input type="submit">
  </form>

  <?php
    echo '<HR>';
    echo '[INFO][** CODIGO-AUTENTICACION HTTP **]<BR>';
    //GENERAR LA URL CON LOS DATOS DE AUTENTICACION
     //https://miUsuario:miContraseña@localhost:8000
    $_SERVER['PHP_AUTH_USER']=$_POST['fUsuario'];
    $_SERVER['PHP_AUTH_PW']=$_POST['fPWD'];

    //Cargar los datos sel SERVER
    $usuario =array_key_exists('PHP_AUTH_USER',$_SERVER)?$_SERVER['PHP_AUTH_USER']:'';
    $pwd =array_key_exists('PHP_AUTH_PW',$_SERVER)?$_SERVER['PHP_AUTH_PW']:'';

   //Comprobar user/pwd
    if($usuario!="FCO"){
      echo'[ERROR][USUARIO NO VALIDO] '.$usuario;
      die;
    }else{
      if($pwd!="PUERTA"){
        echo'[ERROR][PWD NO VALIDA] '.$pwd;
        die;
      }
      else{
        echo'[INFO][ACCESO OK] ';
      }
    }
    ?>

    <!-- ESTE CODIGO FUNCIONA SI PONEMOS
    BIEN EL USUARIO O CONTRASEÑA YA QUE SINO NO
    SACA DEL PROGRAMA :) -->
   <?php
    echo '<HR>';
    echo '[INFO][** CODIGO-AUTENTICACION HMAC **]<BR>';
    
    //ASIGNAMOS LOS DATOS
    $_SERVER['HTTP_X_HASH']='8161bc542236aeea7a26e6a7d13ebe08436ac7ed';
    $_SERVER['HTTP_X_TIMESTAMP']=30;
    $_SERVER['HTTP_X_UID']=256;

    //COMPROBAR DATOS NECESARIOS
    //header( 'Content-Type: application/json' );

    if ( 
      !array_key_exists('HTTP_X_HASH', $_SERVER) || 
      !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
      !array_key_exists('HTTP_X_UID', $_SERVER)  
      ) 
    {
        //header( 'Status-Code: 403' );
        echo json_encode(['error' => "No autorizado",]);
        die;
    }else{
      //ASIGNAMOS TODAS LA VARIABLES JUNTAS
      list( $hash, $uid, $timestamp ) = [ 
        $_SERVER['HTTP_X_HASH'], 
        $_SERVER['HTTP_X_UID'], 
        $_SERVER['HTTP_X_TIMESTAMP'] ];
      //Clave secreta
      $secret = 'Sh!! No se lo cuentes a nadie!';
      //Generamos nuestro hash por CONCATENACION
      $newHash = sha1($uid.$timestamp.$secret);

      echo 'HASH: '.$newHash;

      /*Una vez generado el hash se compara
      con el recibido*/
      if ( $newHash !== $hash ) {
        //header( 'Status-Code: 403' );
        echo json_encode([
          'error' => "No autorizado. Hash esperado: $newHash, hash recibido: $hash",
        ]);       
        die;
      }else{
        echo '[INFO][HASH ACEPTADO]';
      }

    }
  ?>

    <?php 
      //CODIGO PARA EL SERVIDOR DE AUTENTICACION
      include "generarToken.php";
      echo '<HR>';
      echo '[INFO][** CODIGO-TOKEN **]<BR>';
      //VALIDAR EL ACCESO Y CREAR EL TOKEN
      $_SERVER["REQUEST_METHOD"] ="POST";
      $_SERVER['HTTP_X_CLIENT_ID'] =1;
      $_SERVER['HTTP_X_SECRET'] ='ENTRADA';  

      $token =generarToken();
      echo $token."<BR>";
      //Asignamos el token al Servidor
      $_SERVER['HTTP_X_TOKEN'] =$token;

      //Validamos el token
      $_SERVER["REQUEST_METHOD"] ="GET";
      validarToken();
      
  
    ?>
 
  <?php
    //INFORMACION
    //DEVUELVE TODA LA INFORMACION DEL SERVIDOR
    //include "infoserver.php";
  ?>

  </body>
</html>