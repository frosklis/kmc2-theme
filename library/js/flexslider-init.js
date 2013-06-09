(function($) {
    $(window).load(function() {
        $('.flexslider').flexslider({
	        animation: 'slide',
		    controlsContainer: '.flex-container',
		    smoothHeight: true,
		    pausePlay: true,
	    });
    });
})(jQuery)

jQuery(document).ready(function($) {
    setInterval(function(){
        $('#full-bg img.active').animate({opacity:0},500, function(){
            $(this).removeClass('active');
        })
        if($('#full-bg img.active').next().length>0)
            $('#full-bg img.active').next().animate({opacity:1},500).addClass('active');
        else
            $('#full-bg img:first').animate({opacity:1},500).addClass('active');
 
    } ,4000);
    $('#full-bg img:first').animate({opacity:1},400).addClass('active');
});
