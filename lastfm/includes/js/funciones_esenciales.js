
function ajaxpage(url,contenedor){
   window.addEvent( 'domready', function() {
     $(contenedor).empty().addClass(contenedor);
     $(contenedor).innerHTML = "<img class='cargando' ></img> Cargando por favor espere ...";
	var x = new Request({
            url: url,
	    method: 'GET',
	    onComplete: function( response ) {
		$(contenedor).innerHTML = response;
	      }
          }).send();
      });

  }//fin de la funcion ajaxpage
  
