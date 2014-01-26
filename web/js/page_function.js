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
    
    function artNavigation(){
        $('.art-paging .art-next, .art-paging .art-prev').live('click', function(event){
            var selected = $(this).attr('class').split(' ')[1].split('-')[1];
            $(this).attr('class').split(' ')[2]
            var selectedPage = $(this).attr('class').split(' ')[2].split('-')[1];
            var target = $(".art-content");
            $.ajax({
                type: "POST",
                url: HOST+"artwork/getArtworks",
                data: {p_id: selected, page_id: selectedPage},
                beforeSend: function(){
                    createLayer($('.content-left'), 'layer');
                },
                success: function (data){
                    target.empty().html(data).fadeIn();
                    $('#layer').remove();
                }
            });
        });
    }
    
    function exNavigation(){
        $('.ex-next, .ex-prev').live('click', function(event){
            var selected = $(this).attr('class').split(' ')[1].split('-')[1];
            var selectedPage = $(this).attr('class').split(' ')[2].split('-')[1];
            var target = $(".ex-container");
            $.ajax({
                type: "POST",
                url: HOST+"exhibition/getExhibitions",
                data: {p_id: selected, page_id: selectedPage},
                beforeSend: function(){
                    createLayer($('.ex-container'), 'ex-layer');
                },
                success: function (data){
                    target.empty().html(data).fadeIn();
                    $('#layer').remove();
                }
            });
        });
    }
    
    function pressNavigation(){
        $('.art-paging .press-next, .art-paging .press-prev').live('click', function(event){
            var selected = $(this).attr('class').split(' ')[1].split('-')[1];
            var selectedPage = $(this).attr('class').split(' ')[2].split('-')[1];
            var target = $(".art-content");
            $.ajax({
                type: "POST",
                url: HOST+"press/getPresss",
                data: {p_id: selected, page_id: selectedPage},
                beforeSend: function(){
                    createLayer($('.content-left'), 'layer');
                },
                success: function (data){
                    target.empty().html(data).fadeIn();
                    $('#layer').remove();
                }
            });
        });
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