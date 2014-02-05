var HOST = '';
var SF_CULTURE = '';

function setHost(){
    HOST = "http://"+window.location.hostname+"/rafael_clemente/"+SF_CULTURE+"/";
}

function setCulture(){
    SF_CULTURE = $('#culture').html();
}

$(document).ready(function(){  
    function createLayer(obj, name){
		var layer = $('<div>', {
			id: name
		}).width(obj.width());
		layer.height(obj.height());
		var indicator = $('<div class=\"indicator\">').appendTo(layer);
		obj.append(layer);
	}
    
   
    
    function showLangs(){
        $('.langs-rel').hover(function(){
            $(this).find('.langs-rest').slideDown(400);
        },function(){
            $(this).find('.langs-rest').hide();
        });
    }
    
    setCulture();
    setHost();
    showLangs();
    artNavigation();
    exNavigation();
    pressNavigation();
});

function startBxSlider(){
    $('#main-page-slider').bxSlider({
      mode: 'fade',
      infiniteLoop: true,
      easing: 'ease-in',
      speed: 1000,
      pager: true,
      auto: true,
      pause: 10000,
      controls: false
    });
}

$(window).load(function(){
    startBxSlider();
});

$(window).resize(function(){ });