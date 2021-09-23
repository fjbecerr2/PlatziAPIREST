<html>
  <head>
    <title>SERVER PHP</title>
  </head>
  <body>
    <H4>SERVER PHP</H4>
<?php 
  /*ESTA VARIABLE CONTROLA SI PODEMOS O NO
  ACCEDER CON UN LINK URL */
  $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  echo '[INFO-SERVER] [URL ACTUAL]<BR>'.$url."<BR>";
  $LANZAR_DESDE_URL = false;
  if($LANZAR_DESDE_URL==true){
    echo '*** EJECUTANDO DESDE LLAMADA URL ***<BR>';
  }
  else {
    echo '*** EJECUTANDO POR PARAMETROS ***<BR>';
  }

  /*Esta expresion regular comprueba el formato
  URL API-REST .../RECURSO/ID
  reasignando los valores de entrada*/
  if(preg_match('/\/([^\/]+)\/([^\/]+)\/([^\/]+)/',$_SERVER["REQUEST_URI"],$matches))
  {
     echo '[INFO-SERVER] [URL API-REST]<BR>';
    //echo 'MATCHES'.$matches[2];
    //echo 'MATCHES'.$matches[3];
    $_GET['recursoTipo']=$matches[2];    
    $_GET['ID']=$matches[3];
    error_log(print_r($matches,1));
  }
        
  if($LANZAR_DESDE_URL==true){
    //Es el mismo codigo que tenemos en index*/
    $recursosPermitidos =server_definirRecursos();
    server_listarTiposRecursos($recursosPermitidos);
    $method = $_SERVER['REQUEST_METHOD'];
    $resourceType = $_GET['recursoTipo'];
    $resourceId = $_GET['ID'];
    $method = $_SERVER['REQUEST_METHOD'];

    echo '[INFO][METODO]'. $method."<BR>";
    echo '[INFO][RECURSO]'. $resourceType."<BR>";
    echo '[INFO][ID]'.$resourceId."<BR>";

    //Si libros.php ya ha sido incluido no lo
    //incluira de nuevo
    require_once 'libros.php';
    $listaLibros =libros_cargarLibros($listaLibros);
    libros_verListaLibros($listaLibros);
      
    if(server_comprobarRecursoLibroGET($resourceType)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }

    if(server_comprobarTipoRecurso($resourceType, $recursosPermitidos)==false){
      echo "[INFO] [CIERRE]<BR>";
      die;
    }else{
      server_comprobarTipoPeticion($method,$resourceType,$resourceId,$listaLibros);
    }
  }

  //--------------
  function server_definirRecursos(){
    $recursosPermitidos = [
      'libros',
      'autores',
      'generos',
    ];
    return $recursosPermitidos ;
  }

   //-------------
  function server_listarTiposRecursos($recursosPermitidos){
    $numRecursos = count($recursosPermitidos);
    echo '[INFO-SERVER] [RECURSOS PERMITIDOS]'."<BR>";;
    for($i = 0; $i < $numRecursos; $i++) {
      echo '[INFO-SERVER] ['.$i.'] '.$recursosPermitidos[$u];
      echo "<BR>";
    }
  };

  //-- ES GET PERO EL RECURSO NO ES VALIDO
  function server_comprobarRecursoLibroGET($recursoTipo){
    if ( "libros" !== $recursoTipo ) {
      http_response_code( 404 );
      echo json_encode(
        ['[ERROR-SERVER] ' => $recursoTipo.' NO IMPLEMENTADO',]
      );
      return false;
      //die;
    }
    else{
      echo '[INFO-SERVER] [TIPO CORRECTO]<BR>';
      return true;
    }
  }

  //-----
  function server_comprobarTipoRecurso($recursoTipo, $recursosPermitidos){
    #Comprobamos que el tipo de recurso esta dentro de los definidos
    if (!in_array($recursoTipo, $recursosPermitidos)) {
      echo '[INFO-SERVER] [TIPO DE RECURSO NO VALIDO]'.'<BR>';
      #CODIGO DE ERROR AL SERVIDOR
      http_response_code( 400 );
      #MENSAJE JSON PARA LA PAGINA
      echo json_encode(
        ['ERROR-SERVER' => "$resourceType INVALIDO"]
      );
      #CERRAR
      return false;
      //die;
    }
    else{
      echo '[INFO-SERVER] [RECURSO VÁLIDO] '.$resourceType.'<BR>';
      return true;
    };
  }

  //----
  function server_comprobarTipoPeticion($metodo,$recursoTipo, $recursoId, $listaLibros){
    echo '[INFO-SERVER][COMPROBAR TIPO PETICION]<BR>';
    echo '[INFO-SERVER][METODO] => '.$metodo."<BR>";
    echo '[INFO-SERVER][RECURSO] => '.$recursoTipo."<BR>";
    echo '[INFO-SERVER][ID] => '.$recursoId."<BR>";

    // Se indica al cliente que lo que recibirá es un json
    //header('Content-Type: application/json');
    #Comprobar el tipo de peticion
     switch ( strtoupper( $metodo ) ) {
      case 'GET':
        echo '[INFO-SERVER] [Solicitud GET]<BR>';
         server_comprobarRecursoLibroGET($recursoTipo);
          /*Comprobamos si queremos un libro
          toda la lista o un solo libro filtrado*/
          if(empty($recursoId)){
            //Mostrar en formato JSON
            echo json_encode($listaLibros);
          }else{
            libros_comprobarLibroEnLista($recursoId,$listaLibros);
          }
      break;
      case 'POST':
        echo '[INFO-SERVER] [Solicitud POST]<BR>';
         server_comprobarRecursoLibroGET($recursoTipo);
         /*Comprobamos si queremos un libro
          toda la lista o un solo libro filtrado*/
          if(empty($recursoId)){
            //Mostrar en formato JSON
            echo json_encode($listaLibros);
          }else{
            libros_comprobarLibroEnLista($recursoId,$listaLibros);
          }
          /* Generamos un nuevo libro.
          Claro que esto debería venir de un formulario
          */
          $nuevoLibro=libros_generarLibroJSON('De ratones y hombres',4,1);
          //Decodificar el json
          $listaLibros[] =json_decode($nuevoLibro, true);
          libros_verListaLibros($listaLibros);
          echo '[INFO][SE HA INSERTADO UN NUEVO TITULO].<BR>';
      break;
      case 'PUT':
        echo '[INFO-SERVER] [Solicitud PUT]<BR>';
        //Comprobamos los datos
        if(!empty($recursoId)){
            if(libros_existeLibro($recursoId, $listaLibros)==true){
              echo '[PETICION] ID: '.$recursoId;
              $nuevoLibro=libros_generarLibroJSON('La Sombra',2,3);
               //Reemplazar el libro
              $listaLibros[$recursoId] =json_decode($nuevoLibro, true);
            }
            libros_verListaLibros($listaLibros);
         }
      break;
      case 'DELETE':
        echo '[INFO-SERVER] [Solicitud DELETE]';
        //Comprobamos los datos
         if(!empty($recursoId)){
            if(libros_existeLibro($recursoId, $listaLibros)==true){
              //Eliminar el libro del array
              unset($listaLibros[$recursoId]);
            }
         }
         libros_verListaLibros($listaLibros);
      break;
      default:
        echo '[ERROR-SERVER] [Solicitud INCORRECTA]';
      break;
    }  
  }
?> 
 </body>
</html>
 