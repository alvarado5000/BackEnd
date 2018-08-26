<?php

//Archivo php para llamada AJAX. 
$ciudad = htmlspecialchars($_POST['ciudad']);
$tipo = htmlspecialchars($_POST['tipo']);
$precio = htmlspecialchars($_POST['precio']);

$pos = strpos($precio, ';');

$min = substr($precio, 0, $pos);
$max = substr($precio, $pos+1);

$file = fopen("data-1.json", "r") or die("No se puede abrir el archivo");

$json = fread($file, filesize('data-1.json'));
$data = json_decode($json, true);

//primer filtro de precios
$arrayPrecios = array();
foreach($data as $registro){
    $price = $registro['Precio'];
    $price = substr($price, strpos($price,'$')+1);
    $c = strpos($price,',');
    $price = substr($price,0,$c).substr($price,$c+1);
    if($price>=$min && $price<=$max){
        array_push($arrayPrecios, $registro);
    }
}

$result = array();
if(!empty($ciudad) && !empty($tipo)){
    foreach($arrayPrecios as $registroPrecios){
        if($registroPrecios['Ciudad']==$ciudad && $registroPrecios['Tipo']==$tipo){
            array_push($result, $registroPrecios);
        }
    }
} elseif(!empty($ciudad)){
    foreach($arrayPrecios as $registroPrecios){
        if($registroPrecios['Ciudad']==$ciudad){
            array_push($result, $registroPrecios);
        }
    }
} elseif(!empty($tipo)){
    foreach($arrayPrecios as $registroPrecios){
        if($registroPrecios['Tipo']==$tipo){
            array_push($result, $registroPrecios);
        }
    }
} else {
    $result = $arrayPrecios;
}


//$resultjson = json_encode($result);
// echo '{"result":"success", "message":"Resultados obtenidos exitosamente", "data":'.$rjson.'}';

fclose($file);

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
  <link type="text/css" rel="stylesheet" href="css/customColors.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/ion.rangeSlider.skinFlat.css"  media="screen,projection"/>

  <link type="text/css" rel="stylesheet" href="css/index.css"  media="screen,projection"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Formulario</title>
</head>

<body>
  <video src="img/video.mp4" id="vidFondo"></video>

  <div class="contenedor">
    <div class="card rowTitulo">
      <h1>Buscador</h1>
    </div>
    <div class="colFiltros">
      <form action="buscador.php" method="post" id="formulario">
        <div class="filtrosContenido">

          <div class="tituloFiltros">
            <h5>Realiza una búsqueda personalizada</h5>
          </div>

          <div class="filtroCiudad input-field">
            <label for="selectCiudad">Ciudad:</label>
            <select name="ciudad" id="selectCiudad">
              <option value="" selected>Elige una ciudad</option>
            </select>
          </div>

          <div class="filtroTipo input-field">
            <label for="selecTipo">Tipo:</label><br>
            <select name="tipo" id="selectTipo">
              <option value="" selected>Elige un tipo</option>
            </select>
          </div>

          <div class="filtroPrecio">
            <label for="rangoPrecio">Precio:</label>
            <input type="text" id="rangoPrecio" name="precio" value="" />
          </div>

          <div class="botonField">
            <input type="submit" class="btn white" value="Buscar" id="submitButton">
          </div>

        </div>
      </form>
    </div>

    <div class="colContenido">

      <div class="tituloContenido card">
        <h5>Resultados de la búsqueda:</h5>
        <div class="divider"></div>
        <button type="button" name="todos" class="btn-flat waves-effect" id="mostrarTodos">Mostrar Todos</button>
      </div>

      <div class="resultados">
        <?php $longitud = count($result);
          for($i=0; $i<$longitud; $i++) { ?>
          <div class="card horizontal">
            <div class="row">
              <div class="card-image place-wrapper col s12 m4">
                <img class="img-responsive place-image" src="img/home.jpg">
              </div>
              <div class="card-stacked col s12 m8">
                  <div class="card-content">
                      <p>
                          <b>Dirección: </b><?php echo $result[$i]['Direccion']; ?><br>
                          <b>Ciudad: </b><?php echo $result[$i]['Ciudad']; ?><br>
                          <b>Teléfono: </b><?php echo $result[$i]['Telefono']; ?><br>
                          <b>Código Postal: </b><?php echo $result[$i]['Codigo_Postal']; ?><br>
                          <b>Tipo: </b><?php echo $result[$i]['Tipo']; ?><br>
                          <span class="price"><b>Precio: </b><?php echo $result[$i]['Precio']; ?></span>
                      </p>
                  </div>
                  <div class="card-action">
                      <a>Ver mas</a>
                  </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </div>
  </div>

  <script type="text/javascript" src="js/jquery-3.0.0.js"></script>
  <script type="text/javascript" src="js/ion.rangeSlider.min.js"></script>
  <script type="text/javascript" src="js/materialize.min.js"></script>
  <script type="text/javascript" src="js/index.js"></script>
</body>
</html>