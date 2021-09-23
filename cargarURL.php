<html>
  <head>
    <title>PHP PASAR URL</title>
  </head>
  <body>
    <H4>-- CARGAR URL --</H4>
    <?php 
    $url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

    echo 'URL ACTUAL: '.$url."<BR>";
    $allowedResourceTypes =cargarURL_definirRecursos();
    cargarURL_listarTiposRecursos($allowedResourceTypes);

    //-------
    function cargarURL_definirRecursos(){
      $allowedResourceTypes = [
        'books',
        'authors',
        'genres',
      ];
      return $allowedResourceTypes ;
    }

    //--------
    function cargarURL_listarTiposRecursos($allowedResourceTypes){
      $arrlength = count($allowedResourceTypes);
      echo '[INFO] [RECURSOS PERMITIDOS CARGAR URL]'."<br>";;
      for($x = 0; $x < $arrlength; $x++) {
        echo '[INFO] ['.$x.'] '.$allowedResourceTypes[$x];
        echo "<br>";
      }
    };

    //--------
     function cargarURL_comprobarTipoPeticion($method,$resourceType, $resourceId, $listaLibros){
      // Se indica al cliente que lo que recibirá es un json
   // header('Content-Type: application/json');
      #Comprobar el tipo de peticion
      switch ( strtoupper( $method ) ) {
        case 'GET':
            echo '[INFO] [Solicitud GET]<BR>';
            comprobarRecursoLibroGET($resourceType);
            
            /*Comprobamos si queremos un libro
           toda la lista o un solo libro filtrado*/
            if(empty($resourceId)){
              //Mostrar en formato JSON
              echo json_encode($listaLibros);
            }else{
              comprobarLibroEnLista($resourceId,$listaLibros);
            }
          break;
        case 'POST':
            echo '[INFO] [Solicitud POST]';
          break;
        case 'PUT':
          echo '[INFO] [Solicitud PUT]';
          break;
        case 'DELETE':
          echo '[INFO] [Solicitud DELETE]';
          break;
      }   
    }

     ?> 
    <H4>-- FIN CARGAR URL --</H4>
  </body>
</html>