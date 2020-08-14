function HideEventListener(onevent) {
	this.onEvent=onevent;
    this.onClose = function(parametro){
		this.onEvent(parametro);
	};
}
 
// anti-pattern! keep reading...
function onHide() {
    
}

var eventListener=null;

$(document).ready(function() {
	formatearVentanas();
});

function formatearVentanas(){
	$('.window .close').click(function (e) {
			//Cancel the link behavior
			e.preventDefault();
		
			$('#mascara').hide();
			$('.window').hide();
		});		
	
		//if mask is clicked
		$('#mascara').click(function () {
			$(this).hide();
			$('.window').hide();
		});			

		$(window).resize(function () {
	 
			var box = $('#boxes .window');
 
        //Get the screen height and width
			var maskHeight = $(document).height();
			var maskWidth = $(window).width();
      
			//Set height and width to mask to fill up the whole screen
			$('#mascara').css({'width':maskWidth,'height':maskHeight});
               
			//Get the window height and width
			var winH = $(window).height();
			var winW = $(window).width();

			//Set the popup window to center
			if (screen.width>500){
				box.css('top',  winH/2 - box.height()/2);
				box.css('left', winW/2 - box.width()/2);
			}
	 
			});
}

function closeWindow(parametro){
  $('#mascara').hide();
  $('.window').hide();
  if (eventListener!=null){
	eventListener.onClose(parametro);
  }
}

function nuevaVentana(elid,listener,width,height,x,y){
		//Cancel the link behavior
		eventListener=listener;
		//Get the A tag
		var id="#"+elid;
		//Get the screen height and width
		var maskHeight = $(document).height()-96;
		var maskWidth = $(window).width();
	
		//Set heigth and width to mask to fill up the whole screen
		$('#mascara').css({'width':maskWidth,'height':maskHeight});
		
		//transition effect		
		$('#mascara').fadeIn(1000);
		$('#mascara').fadeTo("fast",0.8);
		//Get the window height and width
		var winH;
		var winW;
		winH = $(window).height();
		winW = $(window).width();
		if (width!=null && height!=null){
			//$(id).css('width',  width);
			//$(id).css('height',  height);
		}
		
              
		//Set the popup window to center
		if (x>0 && y>0){
			$(id).css('top',  y);
			$(id).css('left', x);
		}else{
			if (screen.width>500){
				$(id).css('top',  winH/2-$(id).height()/2);
				$(id).css('left', winW/2-$(id).width()/2);
			}
		}
		
	
		//transition effect
		//$(id).fadeIn(1000);
		var options = { direction: "up" };
		$(id).effect("slide", options, 500);
}
