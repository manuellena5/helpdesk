function paginaPrincipal(){
	datosSesion();

	$("#formPrincipal").empty();
}

function datosSesion(){
	    $.get("botonera_profesional.php",function(data){
		    $("#formPrincipal").append(data);
		});
}

function cargarPagina(pagina){
	var data = new FormData();
	data.append('pagina',pagina);
	$.ajax({  
	    url: "funciones.php",
        type: "POST",
        // Podrías separar las funciones de PHP en un fichero a parte
        data:data,
        dataType: 'json',
        contentType: false,  
        cache: false,  
        processData:false,
	})
	.done(function(respuesta) {
		
		if(respuesta.success){
	     	$.get(pagina,function(data){
				document.getElementById("formPrincipal").innerHTML="";
				$("#formPrincipal").append(data);
			});
		} else {
			alert("No posee permisos.");
		}
	})
	.fail(function(respuesta,jqXHR, textStatus, errorThrown) {
		console.log(respuesta);
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);
    	alert("Error");
	}); 
}

function menuLink(){
	$.get("menu/menu.php",function(data){
		$("#menuSide").empty();
		$("#menuSide").html(data);
		sideBarScript();
	});
}

$(document).ready(function() {
	$.getScript("jquery/funciones.js");
	
});

var $bodyEl,$sidedrawerEl;

function sideBarScript(){
	$bodyEl = $('body');
	$sidedrawerEl = $('#sidedrawer');
	$('.js-show-sidedrawer').on('click', showSidedrawer);
	$('.js-hide-sidedrawer').on('click', hideSidedrawer);

	var $titleEls = $('strong', $sidedrawerEl);

	/*$titleEls
	.next()
	.hide();*/

	$titleEls.on('click', function() {
		$(this).next().slideToggle(200);
	});
}

function showSidedrawer() {
	// show overlay
	var options = {
			onclose: function() {
				$sidedrawerEl
				.removeClass('active')
				.appendTo(document.body);
			}
	};

	var $overlayEl = $(mui.overlay('on', options));

	// show element
	$sidedrawerEl.appendTo($overlayEl);
	setTimeout(function() {
		$sidedrawerEl.addClass('active');
	}, 20);
}


function hideSidedrawer() {
	$bodyEl.toggleClass('hide-sidedrawer');
}
