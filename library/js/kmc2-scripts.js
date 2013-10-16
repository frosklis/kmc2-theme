/*
kmc2 Scripts File
Author: Claudio Noguera

*/

jQuery(document).ready(function($) {
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

    jQuery('.flexslider').flexslider({
        before: function(slider) {
                if (slider.count < 10) {
                    jQuery.ajax({
                        type: 'POST',
                        url: blog_vars.siteurl + 'wp-admin/admin-ajax.php',
                        data: {
                            action: 'AddHomeSlide',
                        },
                        success: function(data, textStatus, XMLHttpRequest){
                            jQuery(".flexslider ul").append(data);
                            startLoadingImages();     
                            slider.addSlide(jQuery(".flexlider .slides li:last-child"),slider.count);
                        },
                        error: function(MLHttpRequest, textStatus, errorThrown){
                            alert(errorThrown);
                        }
                    });
                }
            }
        });


    // ----------------------------------------------------
    // lazyload
    // ----------------------------------------------------


    var startLoadingImages = function () {
        var getImageVersion = function (imageContainer) {
    
            var imageWrapper = imageContainer.parentNode;
            var padding = parseFloat(imageContainer.style.paddingBottom) / 100; //padding in fraction
            var w = jQuery(imageWrapper).width();
            var h = jQuery(imageWrapper).height();

            if (0 == w) {
                w = window.screen.width;
                h = w * padding;
            }
    
            // If image is vertical, limit its height
            var minRatio = 1.1;
            if (w / h < minRatio) {
                w = w * 9 / 16;  
                jQuery(imageWrapper).width(w);
                h = jQuery(imageWrapper).height();
            }

            // If there is overflow, we have to make the parent narrower
            // The way this works overflow can only occur when the wrapper is too short,
            // and if it is short, it is for a reason.
            var ancestor = jQuery(imageContainer).closest('.flexslider');
            if (ancestor.length > 0) {
                ancestor = ancestor[0];
            }
    
            if( imageWrapper.offsetHeight > ancestor.offsetHeight ||
                imageWrapper.offsetWidth > ancestor.offsetWidth) {

                w = jQuery(ancestor).height() / padding;  
                jQuery(imageWrapper).width(w);

            }
    
            for (k in image_sizes_vars) {  
                w = w * window.devicePixelRatio;
                h = w * padding;

                w2 = image_sizes_vars[k][0];
                h2 = w2 * padding;
                if (h2 > image_sizes_vars[k][1]) {
                    h2 = image_sizes_vars[k][1];
                    w2 = h2 / padding;
                }

                if (w <= w2 && h <= h2) {
                    return k;
                }
            }
            
            return 'original';
        };
    
        var lazyloadImage = function (imageContainer) {
    
            var imageVersion = getImageVersion(imageContainer);
            // console.log("Escogida: " + imageVersion);
    
            if (!imageContainer || !imageContainer.children) {
                return;
            }
            var img = imageContainer.children[0];
            
            if (img) {
                var imgSRC = img.getAttribute("data-src-" + imageVersion);
    
                var imageCaption = img.getAttribute("data-caption");
                var imageTitle = img.getAttribute("data-title");
    
                // console.log(imageTitle);
    
                if (imgSRC) {
                    jQuery(imageContainer).removeClass('not-loaded');
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
                    // if (imageTitle){
                    //     var d = document.createElement("div");
                    //     d.className = "title";
                    //     var text = document.createTextNode(imageTitle);
                    //     d.appendChild(text);
                    //     imageContainer.appendChild(d);
                    // }
                }
            }
        },
        lazyLoadedImages = document.getElementsByClassName("img-container");
    
        for (var i = 0; i < lazyLoadedImages.length; i++) {
            if (jQuery(lazyLoadedImages[i]).hasClass('not-loaded')) {
                lazyloadImage(lazyLoadedImages[i]);
            }
        }
    };
    startLoadingImages();
});

jQuery( window ).resize(function() {
    h = jQuery('.flexslider').height();
    jQuery('.flexslider .img-container-wrapper').css('max-height', h);
});