//Inicializar sliders materialize al cargar todo el documento
$(document).ready(function(){
    inicializarSlider();
    inicializarSelects();
});

/*
  Creación de una función personalizada para jQuery que detecta cuando se detiene el scroll en la página
*/
$.fn.scrollEnd = function(callback, timeout) {
  $(this).scroll(function(){
    var $this = $(this);
    if ($this.data('scrollTimeout')) {
      clearTimeout($this.data('scrollTimeout'));
    }
    $this.data('scrollTimeout', setTimeout(callback,timeout));
  });
};
/*
  Función que inicializa el elemento Slider
*/

function inicializarSlider(){
  $("#rangoPrecio").ionRangeSlider({
    type: "double",
    grid: false,
    min: 0,
    max: 100000,
    from: 20000,
    to: 80000,
    prefix: "$"
  });
}
/*
    Función que reproduce el video de fondo al hacer scroll, y deteiene la reproducción al detener el scroll
*/
function playVideoOnScroll(){
  var ultimoScroll = 0,
      intervalRewind;
  var video = document.getElementById('vidFondo');
  $(window)
    .scroll((event)=>{
      var scrollActual = $(window).scrollTop();
      if (scrollActual > ultimoScroll){
       video.play();
     } else {
        //this.rewind(1.0, video, intervalRewind);
        video.play();
     }
     ultimoScroll = scrollActual;
    })
    .scrollEnd(()=>{
      video.pause();
    }, 10)
}

//funcion para inicializar los select
function inicializarSelects(){
    var tipos = [];
    var ciudades = [];
    $.get('data-1.json', function(data){
        for(let i = 0; i < data.length; i++){
            if(tipos.indexOf(data[i].Tipo) === -1) tipos.push(data[i].Tipo);
            if(ciudades.indexOf(data[i].Ciudad) === -1) ciudades.push(data[i].Ciudad);
        }
        for(let i = 0; i < ciudades.length; i++){
            $('#selectCiudad').append('<option value="'+ciudades[i]+'">'+ciudades[i]+'</option>');
        }
        for(let j = 0; j < tipos.length; j++){
            $('#selectTipo').append('<option value="'+tipos[j]+'">'+tipos[j]+'</option>');
        }
        $('select').material_select();
    });
}

//Funcion para agregar y renderizar los resultados en la pagina
function showResult(array){
    $('.resultados').empty();
    for(let i=0; i<array.length; i++){
        $('.resultados').append(`<div class="card horizontal">
            <div class="row">
                <div class="card-image place-wrapper col s12 m4">
                    <img class="img-responsive place-image" src="img/home.jpg">
                </div>
                <div class="card-stacked col s12 m8">
                    <div class="card-content">
                        <p>
                            <b>Dirección: </b>${array[i].Direccion}<br>
                            <b>Ciudad: </b>${array[i].Ciudad}<br>
                            <b>Teléfono: </b>${array[i].Telefono}<br>
                            <b>Código Postal: </b>${array[i].Codigo_Postal}<br>
                            <b>Tipo: </b>${array[i].Tipo}<br>
                            <span class="price"><b>Precio: </b>${array[i].Precio}</span>
                        </p>
                    </div>
                    <div class="card-action">
                        <a>Ver mas</a>
                    </div>
                </div>
            </div>
        </div>`);
    }
}

//funcion para mostrar todos los resultados
$('#mostrarTodos').click(function(){
    $.get('data-1.json', function(data){
        showResult(data);
    });
});