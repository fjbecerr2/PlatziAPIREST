 <?php
  //FORMATO JSON:
  /*
  {
    'titulo':'Lo que el viento se llevo',
    'id_autor':2,
    'id_genero':1
  }
  */
  //---
  function libros_cargarLibros($listaLibros){
    $listaLibros = [
      1 => [
        'titulo' => 'Lo que el viento se llevo',
        'id_autor' => 2,
        'id_genero' => 2,
      ],
      2 => [
        'titulo' => 'La Iliada',
        'id_autor' => 1,
        'id_genero' => 1,
      ],
      3 => [
        'titulo' => 'La Odisea',
        'id_autor' => 1,
        'id_genero' => 1,
      ],
    ];

    return $listaLibros;
  }

  //--
  function libros_verListaLibros($listaLibros){
    $arrlength = count($listaLibros);
    echo '[INFO] [LIBROS] ['.$arrlength."] <br>";;
    for($x = 1; $x < $arrlength+1; $x++) {
      echo '[INFO] ['.$x.'] '.$listaLibros[$x]['titulo'];
      echo "<br>";
    }
  }

    //---
  function libros_comprobarLibroEnLista($resourceId, $listaLibros){
    if ( array_key_exists($resourceId, $listaLibros ) ) {
      echo json_encode(	$listaLibros[ $resourceId ])."<BR>";
    } else {
      http_response_code( 404 );
      echo json_encode(
        ['ERROR' => 'Book '.$resourceId.' [NO ENCONTRADO]',]
      );
    }
  }

  function libros_existeLibro($resourceId, $listaLibros){
    if ( array_key_exists($resourceId, $listaLibros ) ) {
      return true;
    } else {
      return false;
    }
  }

  function libros_generarLibroJSON($titulo,$idAutor,$idGenero){
    //Montar formato JSON
    $textoJSON='{"titulo":"'.$titulo.'",';
    $textoJSON.='"id_autor":'.$idAutor.',';
    $textoJSON.='"id_genero":'.$idGenero.'}';
    //echo 'GENERANDO JSON '.$textoJSON;
    return $textoJSON;
  }
  
?>