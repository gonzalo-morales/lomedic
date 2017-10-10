$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip()
    
	 /* Nice Scroll */
   $("#rigth-sidebar").niceScroll({
       cursorcolor: '#26a69a',
       cursorwidth: 4,
       cursorborder: 'none'
   });
   
   
   /* Sidebar's */
   $('#sidebarCollapse').on('click', function () {
       $('#sidebar').toggleClass('active');
       //condiciones para cambiar el Ã­cono de menÃº a x
       if($('#sidebar').hasClass('active') && $(window).width() >= 768){
           $(this).find("i").text("menu");
       }
       else if($('#sidebar').not('active') && $(window).width() >= 768){
          $(this).find("i").text("close");
       }
       else{
           $(this).find("i").text("menu"); 
       }

   });

   $('#rigth-sidebarCollapse').on('click', function () {
       $('#rigth-sidebar').addClass('active');
       $('.overlay').fadeIn();
       $('.collapse.in').toggleClass('in');
       $('a[aria-expanded=true]').attr('aria-expanded', 'false');
   });
   
   $('.dismiss, .overlay').on('click', function () {
       $('#rigth-sidebar').removeClass('active');
       $('.overlay').fadeOut();
   });

   
	/* Kendo */
	$(".window").click(function (e) {
       e.preventDefault();

		$("#onload").append("<div id='mywindow'></div>");

       var myWindow = $("#mywindow"),
       href = $(this).parent().attr('href');

	    if(href != '#') {
	        myWindow.kendoWindow({
	            width:  "60%",
	            height: "65%",
	            position:{
                   top:"18%",
                   left:"26%"
               },
	            actions: ["Refresh","Pin","Minimize", "Maximize", "Close"],
	            title:   $(this).parent().data('original-title'),
	            content: href+'?kendoWindow=1',
	            visible: true,
	        });
	    }
   });

	if(self !== top){
		$("#sidebar").remove();
		$("#top-nav").remove();
		$("#rigth-sidebar").remove();
		$("#ticketHelp").remove();
		$("#content").removeClass('pt-3');
		$(".wrapper").removeAttr("style");
	}

	// Busqueda para menu
	document.querySelector("#filter-menu").addEventListener("keyup", function(e) {
       var filter = this.value.toLowerCase();
       var count = 0;
       document.querySelectorAll("#menu-conten li").forEach(function(li) {
           if (filter == "") {
           	li.style["display"] = "list-item";
           	li.querySelectorAll('.collapse').forEach(function(el) {
               	if($('.collapse:not(.in)')) {
                       $(el).collapse('hide');
                   }
               });
           }
           else {
               if (!li.textContent.toLowerCase().match(filter)) {
               	li.style["display"] = "none";
               }
               else {
               	li.style["display"] = "list-item";
               	li.querySelectorAll('.collapse').forEach(function(el) {
                   	if($('.collapse:not(.in)')) {
                           $(el).collapse('show');
                       }
                   });
               }
           }
       });
	});
	
	//Animaciones de inicio
    $('#infoSections').animate({
        top:'0',
        opacity:1
    },1000);
    $('#metro').animate({
        top:'0',
        opacity:1
    },1000);
    //Script para las variaciones de color en los accesos directos
    var rgbColor = [23,122,255]
    mBlue = $('#metro').find('a.blue');
    //Degradado azul
    mBlue.each(function(i,elem){
        $(elem).css({
            'background-color': 'rgb('+rgbColor[0]+','+rgbColor[1]+','+rgbColor[2]+')'
        })
        rgbColor[1] -= 8;
        rgbColor[2] -= 17;
        return;
    })

    rgbColor = [23,162,184]
    mGreen = $('#metro').find('a.green');
    //Degradado verde
    mGreen.each(function(i,elem){
        $(elem).css({
            'background-color': 'rgb('+rgbColor[0]+','+rgbColor[1]+','+rgbColor[2]+')'
        })
        rgbColor[1] -= 14;
        rgbColor[2] -= 21;
        return;
    })

    rgbColor = [255,193,7]
    mYellow = $('#metro').find('a.yellow');
    //Degradado amarillo
    mYellow.each(function(i,elem){
        $(elem).css({
            'background-color': 'rgb('+rgbColor[0]+','+rgbColor[1]+','+rgbColor[2]+')'
        })
        rgbColor[0] -= 17;
        rgbColor[1] -= 18;
        rgbColor[2] -= 1;
        return;
    })
	
	
	//Cambio de text para checkbox toggeable
    $('.toggeable').bind('change', function () {
    	if (!$(this).is(':checked')) {
    		$(this).parent().find( "span" ).html($(this).attr('data-toggle-off'))
    	}
    	else {
    		$(this).parent().find( "span" ).html($(this).attr('data-toggle-on'))
    	}
    });
    $('.toggeable').trigger('change');
    
    
    
    
    
});




