var $jQ = jQuery.noConflict();

$jQ(document).ready(function() {
	 
    //Speed of the slideshow
    var speed = 5000;
     
    //You have to specify width and height in #slider CSS properties
    //After that, the following script will set the width and height accordingly
    $jQ('#mask-gallery, #gallery li').width($jQ('#slider').width());   
    $jQ('#gallery').width($jQ('#slider').width() * $jQ('#gallery li').length);
    $jQ('#mask-gallery, #gallery li, #mask-excerpt, #excerpt li').height($jQ('#slider').height());
     
    //Assign a timer, so it will run periodically
    var run = setInterval('newsslider(0)', speed); 
     
    $jQ('#gallery li:first, #excerpt li:first').addClass('selected');
 
    //Pause the slidershow with clearInterval
    $jQ('#btn-pause').click(function () {
        clearInterval(run);
        return false;
    });
 
    //Continue the slideshow with setInterval
    $jQ('#btn-play').click(function () {
        run = setInterval('newsslider(0)', speed); 
        return false;
    });
     
    //Next Slide by calling the function
    $jQ('#btn-next').click(function () {
        newsslider(0); 
        return false;
    });
 
    //Previous slide by passing prev=1
    $jQ('#btn-prev').click(function () {
        newsslider(1); 
        return false;
    });
     
    //Mouse over, pause it, on mouse out, resume the slider show
    $jQ('#slider').hover(
     
        function() {
            clearInterval(run);
        },
        function() {
            run = setInterval('newsslider(0)', speed); 
        }
    ); 
     
});
 
 
function newsslider(prev) {
 
    //Get the current selected item (with selected class), if none was found, get the first item
    var current_image = $jQ('#gallery li.selected').length ? $jQ('#gallery li.selected') : $jQ('#gallery li:first');
    var current_excerpt = $jQ('#excerpt li.selected').length ? $jQ('#excerpt li.selected') : $jQ('#excerpt li:first');
 
    //if prev is set to 1 (previous item)
    if (prev) {
         
        //Get previous sibling
        var next_image = (current_image.prev().length) ? current_image.prev() : $jQ('#gallery li:last');
        var next_excerpt = (current_excerpt.prev().length) ? current_excerpt.prev() : $jQ('#excerpt li:last');
     
    //if prev is set to 0 (next item)
    } else {
         
        //Get next sibling
        var next_image = (current_image.next().length) ? current_image.next() : $jQ('#gallery li:first');
        var next_excerpt = (current_excerpt.next().length) ? current_excerpt.next() : $jQ('#excerpt li:first');
    }
 
    //clear the selected class
    $jQ('#excerpt li, #gallery li').removeClass('selected');
     
    //reassign the selected class to current items
    next_image.addClass('selected');
    next_excerpt.addClass('selected');
 
    //Scroll the items
    $jQ('#mask-gallery').scrollTo(next_image, 800);      
    $jQ('#mask-excerpt').scrollTo(next_excerpt, 800);                
     
}