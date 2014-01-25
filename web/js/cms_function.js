var $jQ = jQuery.noConflict();
var HOST = "http://"+window.location.hostname+"/admin.php/pl/";

$jQ(document).ready(function(){
	
	
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl> 
	 * Zaznacza wszystkie option w selec adresSend
	 */
	$jQ('#bt_sendemails').click(function(){
		$jQ('select#adresSend option').each(function(){
			$jQ(this).attr("selected", "selected");
		});
	});

	
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl> 
	 * funckja przenoszaca wybrany element opcji z adresBook do adresSend
	 */
	$jQ('#putOneright').click(function(){
		$jQ('select#adresBook option:selected').each(function(){
			
			$jQ("#adresSend").append('<option id="'+$jQ(this).val()+'" value="'+$jQ(this).val()+'">'+$jQ(this).text()+'</option>');
			$jQ(this).remove();
			
		});
		$jQ('select#adresBook option').eq(0).attr("selected", "selected");
	});
	
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl> 
	 * funkcja przenoszaca wszystki elementy z id="adresBook" do id="adresSend"
	 */
	$jQ('#putallright').click(function(){
		$jQ('select#adresBook option').each(function(){
			
			$jQ("#adresSend").append('<option id="'+$jQ(this).val()+'" value="'+$jQ(this).val()+'">'+$jQ(this).text()+'</option>');
			$jQ(this).remove();
			
		});
	});
	
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl> 
	 * funkcje przenoszaca czyszczaca adresSend select - przenosi option's z adresSend do adresBook
	 */
	$jQ('#putallleft').click(function(){
		$jQ('select#adresSend option').each(function(){
			
			$jQ("#adresBook").append('<option id="'+$jQ(this).val()+'" value="'+$jQ(this).val()+'">'+$jQ(this).text()+'</option>');
			$jQ(this).remove();
			
		});
	});
	
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl> 
	 * funckja przenoszaca wybrany element opcji z adresBook do adresSend
	 */
	$jQ('#putOneleft').click(function(){
		$jQ('select#adresSend option:selected').each(function(){
			
			$jQ("#adresBook").append('<option id="'+$jQ(this).val()+'" value="'+$jQ(this).val()+'">'+$jQ(this).text()+'</option>');
			$jQ(this).remove();
			
		});
		$jQ('select#adresSend option').eq(0).attr("selected", "selected");
	});
		
	/**
	 *  @author     Tomasz Płatek <tomasz.platek@onet.pl>
	 *  kopiowanie zawartosci inputa tytul_strony do link_strony z zmianam niedozwolonych znakow na "_"
	 */
	function motMakeLink(sourceID, destinationID)
	{

		$jQ(sourceID).keyup(function(){
			var regexp = /([^a-z0-9\-\_])/gi;
			var tmpURL	= $jQ(this).val().toLowerCase();
			
			tmpURL = tmpURL.replace(new RegExp("(ą)", 'gi'), "a");
			tmpURL = tmpURL.replace(new RegExp("(ć)", 'gi'), "c");
			tmpURL = tmpURL.replace(new RegExp("(ę)", 'gi'), "e");
			tmpURL = tmpURL.replace(new RegExp("(ł)", 'gi'), "l");
			tmpURL = tmpURL.replace(new RegExp("(ń)", 'gi'), "n");
			tmpURL = tmpURL.replace(new RegExp("(ó)", 'gi'), "o");
			tmpURL = tmpURL.replace(new RegExp("(ś)", 'gi'), "s");
			tmpURL = tmpURL.replace(new RegExp("(ź)", 'gi'), "z");
			tmpURL = tmpURL.replace(new RegExp("(ż)", 'gi'), "z"); 
			
			newURL = tmpURL.replace(regexp, "_");

			$jQ(destinationID).val(newURL);
		});

	}
	
	/*
	 * CHANGE PUBLISHED STATUS
	 */
	
	$jQ('.change_published img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		var place = span.attr('class').split(' ')[1].replace(/-/g,'_');
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+place+"/changePublished",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE ACTIVE STATUS
	 */
	
	$jQ('.change_active img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		var place = span.attr('class').split(' ')[1].replace(/-/g,'_');
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+place+"/changeActive",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE SUSPENDED STATUS
	 */
	
	$jQ('.change_suspended img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		var place = span.attr('class').split(' ')[1].replace(/-/g,'_');
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+place+"/changeSuspended",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE MAIN STATUS
	 */
	
	$jQ('.change_main img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		var place = span.attr('class').split(' ')[1].replace(/-/g,'_');
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+place+"/changeMain",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE MAIN STATUS
	 */
	
	$jQ('.change_more_button img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		var place = span.attr('class').split(' ')[1].replace(/-/g,'_');
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+place+"/changeMoreButton",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE CONTACT FORM ARCHIVE STATUS
	 */
	
	$jQ('.change_status img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+"contact_form_archive/changeStatus",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE NEW WINDOW STATUS
	 */
	
	$jQ('.change_newWindow img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+"link/changeNewWindow",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
	/*
	 * CHANGE MAIN BACKGROUND
	 */
	
	$jQ('.change_mainBackground img').live('click', function(){
		var span = $jQ(this).parent('span');
		var indicator = span.parent('td');
		var id = span.attr('id').split('_')[1];
		
		if (typeof id != '' && typeof id != undefined){	
			$jQ.ajax({
				type: "POST",
				url: HOST+"background/changeMainStatus",
				cache: true,
				beforeSend: function(){
				indicator.empty().addClass('loading');
			},
			data: { id: id },
			success: function(data){
				indicator.empty().html(data);
				indicator.removeClass('loading');
			}
			});
		}
	});
	
    function slideToggle(){
        $jQ('.show-toggle').click(function(){
            $jQ(this).siblings('div.slide').slideToggle();
        })
    }
	
    function imgUploadButton(){
        $jQ('input.img_upload').click(function(event){
            $jQ(this).val('').addClass('loading');
        });
    }
		
    function hideEveryPhotos(to_hide, _id){
        to_hide.each(function(){
           if ($jQ(this).attr('class').split('_')[1]!=_id){
               $jQ(this).css('display', 'none');
           }
        });
    }
        
    function showPhotoOnHover(to_hide, selector, cont){
        cont.change(function(){
            showPhotoBeforeHover(to_hide, selector, $jQ(this));
        })
    }
    
    function showPhotoBeforeHover(to_hide, selector, obj){
        var selected = obj.val();
        var handler = $jQ(selector+selected);
        hideEveryPhotos(to_hide, selected);            
        handler.css('display', 'block');
    }
        
        
        
    function insertInBlog(content){
        var len_cont = $jQ('.news_description_length');
        if (content.length>140){
            var insert = "<span class=\"error\">"+content.length+"</span>";
        }else{
            var insert = content.length;
        }
        len_cont.html(insert);
    }
        
    function checkDescriptionSign(obj){
        if (obj.length>0){
            obj.keyup(function(){
               insertInBlog($jQ(this).val())
            });
        }
    }
        
    function checkDescriptionSignOnStart(obj){
        if (obj.length>0){
            insertInBlog(obj.val());
        }
    }
    
    showPhotoBeforeHover($jQ('.media_container div'), '.media_container div.media_', $jQ('.photo_toggle'));
    showPhotoOnHover($jQ('.media_container div'), '.media_container div.media_', $jQ('.photo_toggle'));
    showPhotoBeforeHover($jQ('.theme_container div'), '.theme_container div.theme_', $jQ('.theme_toggle'));
    showPhotoOnHover($jQ('.theme_container div'), '.theme_container div.theme_', $jQ('.theme_toggle'));
    showPhotoBeforeHover($jQ('.background_container div'), '.background_container div.background_', $jQ('.photo_toggle'));
    showPhotoOnHover($jQ('.background_container div'), '.background_container div.background_', $jQ('.photo_toggle'));
	slideToggle();
	imgUploadButton();
	checkDescriptionSign($jQ('#news_description'));
	checkDescriptionSign($jQ('#news_translate_description'));
	checkDescriptionSignOnStart($jQ('#news_description'));
	checkDescriptionSignOnStart($jQ('#news_translate_description'));
});

$jQ(window).load(function(){ });