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
       //condiciones para cambiar el ÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â­cono de menÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Âº a x
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
	else {
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
	}

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
    $('.toggeable').bind('click', function () {
    	$(this).attr('checked',!$(this).is(':checked'));
    }).trigger('change');

    $('.toggeable').bind('change', function () {
    	if (!$(this).is(':checked')) {
    		$(this).parent().find( "span" ).html($(this).attr('data-toggle-off'))
    		if($(this).parent().hasClass("btn-success"))
    			$(this).parent().removeClass('btn-success').addClass('btn-secondary');
    	}
    	else {
    		$(this).parent().find( "span" ).html($(this).attr('data-toggle-on'))
    		if($(this).parent().hasClass("btn-secondary"))
    			$(this).parent().addClass('btn-success').removeClass('btn-secondary');
    	}
    });

    $('.toggeable').trigger('change');


    $('.switch .inputSlider').click(function(){
        if($(this).is(':checked')){
        	$(".switch-text").text('Activo').removeClass('text-danger').addClass('text-success');
        }
        else{
        	$(".switch-text").text('Inactivo').removeClass('text-success').addClass('text-danger');
        }
    });


    //Submenu en pantalla completa
    $('#menu-conten .url').bind('dblclick', function () {
    	var url = $(this).attr('data-url');
    	if(url) {
    		location = $(this).attr('data-url');
    	}
    });

    $('#form-model').on('change', '.select-cascade', function() {
      let data = $(this).data(), values = data.targetValue.split(',');
      $(data.targetEl).parent().prepend('<div class="w-100 h-100 text-center text-white align-middle loadingData">Cargando datos... <i class="material-icons align-middle loading">cached</i></div>');
      $.get(data.targetUrl.replace('#ID#', this.value), {with: data.targetWith} , function(request){
        let target = $(data.targetEl).empty(), options = [];
        if (request.success) {
          let i, response = request.data[values[0]];
          if (response.length > 0) {
            options.push('<option value="0" selected disabled>Seleccione una opcion ...</option>')
            for (i in response) {
              options.push('<option value="'+response[i][values[1]]+'">'+response[i][values[2]]+'</option>')
            }
          } else {
            options.push('<option value="0" selected disabled>Sin datos asociados ...</option>')
          }
        }
        target.append(options.join())
        $('.loadingData').remove();
      })
    });

    $('#form-model .select2').select2();
});

