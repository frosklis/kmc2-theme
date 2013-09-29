/*
kmc2 Scripts File
Author: Claudio Noguera

*/

// IE8 ployfill for GetComputed Style (for Responsive Script below)
if (!window.getComputedStyle) {
    window.getComputedStyle = function(el, pseudo) {
        this.el = el;
        this.getPropertyValue = function(prop) {
            var re = /(\-([a-z]){1})/g;
            if (prop == 'float') prop = 'styleFloat';
            if (re.test(prop)) {
                prop = prop.replace(re, function () {
                    return arguments[2].toUpperCase();
                });
            }
            return el.currentStyle[prop] ? el.currentStyle[prop] : null;
        }
        return this;
    }
}

// as the page loads, call these scripts
jQuery(document).ready(function($) {

    /*
    Responsive jQuery is a tricky thing.
    There's a bunch of different ways to handle
    it, so be sure to research and find the one
    that works for you best.
    */
    
    /* getting viewport width */
    var w = jQuery(window).width();
    

    /* if is above or equal to 768px */
    if (w >= 768) {
    
        /* load gravatars */
        $('.comment img[data-gravatar]').each(function(){
            $(this).attr('src',$(this).attr('data-gravatar'));
        });
        
    }
    
    size_dependent_actions();

}); /* end of as page load scripts */


/*! A fix for the iOS orientationchange zoom bug.
 Script by @scottjehl, rebound by @wilto.
 MIT License.
*/
(function(w){
	// This fix addresses an iOS bug, so return early if the UA claims it's something else.
	if( !( /iPhone|iPad|iPod/.test( navigator.platform ) && navigator.userAgent.indexOf( "AppleWebKit" ) > -1 ) ){ return; }
    var doc = w.document;
    if( !doc.querySelector ){ return; }
    var meta = doc.querySelector( "meta[name=viewport]" ),
        initialContent = meta && meta.getAttribute( "content" ),
        disabledZoom = initialContent + ",maximum-scale=1",
        enabledZoom = initialContent + ",maximum-scale=10",
        enabled = true,
		x, y, z, aig;
    if( !meta ){ return; }
    function restoreZoom(){
        meta.setAttribute( "content", enabledZoom );
        enabled = true; }
    function disableZoom(){
        meta.setAttribute( "content", disabledZoom );
        enabled = false; }
    function checkTilt( e ){
		aig = e.accelerationIncludingGravity;
		x = Math.abs( aig.x );
		y = Math.abs( aig.y );
		z = Math.abs( aig.z );
		// If portrait orientation and in one of the danger zones
        if( !w.orientation && ( x > 7 || ( ( z > 6 && y < 8 || z < 8 && y > 6 ) && x > 5 ) ) ){
			if( enabled ){ disableZoom(); } }
		else if( !enabled ){ restoreZoom(); } }
	w.addEventListener( "orientationchange", restoreZoom, false );
	w.addEventListener( "devicemotion", checkTilt, false );
})( this );

jQuery(function() {  
    var pull        = jQuery('#pull');  
        menu        = jQuery('nav ul');  
        menuHeight  = menu.height();  
  
    jQuery(pull).on('click', function(e) {
        console.log("he hecho click");
        e.preventDefault();  
        menu.slideToggle();  
    });  
}); 

jQuery(window).resize(function(){ 
    var w = jQuery(window).width();  
    if(w > 320 && menu.is(':hidden')) {  
        menu.removeAttr('style');  
    }

    size_dependent_actions();
});

function size_dependent_actions() {

    var main_w = jQuery("#main").width();
    var pad = 54;
    if (main_w < 1024) {
        pad = Math.floor((main_w - 480) * 54 / (1024 - 480));
    }

    jQuery("body .post, body .page").css({
        paddingLeft: pad + 'px',
        paddingRight: pad + 'px'
    });
}



jQuery(document).ready(function($) {
// jQuery(window).load(function($) {
    var container_homepage = document.querySelector("#home-categories");

    if (container_homepage != null) {

        var msnry_homepage = new Masonry( container_homepage, {
          // options
          itemSelecor: ".home-category",
          isFitWidth: true,
          gutter: 30,
          isOriginLeft: true,
          animate: true,
        });// code here

    }

    var container_category_page = document.querySelector(".article-list");

    if (container_category_page != null ) {
        
        var msnry_category_page = new Masonry( container_category_page, {
          // options
          itemSelecor: "article",
          isFitWidth: true,
          gutter: 30,
          isOriginLeft: true,
          animate: true,
        });// code here

    }
});


// ----------------------------------------------------
// lazyload
// ----------------------------------------------------
jQuery(document).ready(function($) {
    var getImageVersion = function (imageContainer) {
        var w = jQuery(imageContainer).width() * window.devicePixelRatio;

        for (k in image_sizes_vars) {
            if (w <= image_sizes_vars[k]) return k;
        }
        
        return 'original';
    };

    var lazyloadImage = function (imageContainer) {

        var imageVersion = getImageVersion(imageContainer);
        // var imageVersion = 'small';

        if (!imageContainer || !imageContainer.children) {
            return;
        }
        var img = imageContainer.children[0];
        
        if (img) {
            var imgSRC = img.getAttribute("data-src-" + imageVersion);

            var imageCaption = img.getAttribute("data-caption");
            var imageTitle = img.getAttribute("data-title");

            if (imgSRC) {
                var imageElement = new Image();
                imageElement.src = imgSRC;
                imageElement.setAttribute("alt", imageTitle ? imageTitle : "");
                imageContainer.appendChild(imageElement);
                imageContainer.removeChild(imageContainer.children[0]);


                // Add the image caption
                if (imageCaption){
                    var d = document.createElement("div");
                    d.className = "legend";
                    var text = document.createTextNode(imageCaption);
                    d.appendChild(text);
                    imageContainer.appendChild(d);
                }
                // Add the image title
                if (imageTitle){
                    var d = document.createElement("div");
                    d.className = "title";
                    var text = document.createTextNode(imageTitle);
                    d.appendChild(text);
                    imageContainer.appendChild(d);
                }
            }
        }
    },
    lazyLoadedImages = document.getElementsByClassName("img-container");

    for (var i = 0; i < lazyLoadedImages.length; i++) {
        lazyloadImage(lazyLoadedImages[i]);
    }
});