/*
kmc2 Scripts File
Author: Claudio Noguera
*/
/*global image_sizes_vars, blog_vars, jQuery: false*/

jQuery(function () {
    var menu        = jQuery('#topbar nav');
    // var menuHeight  = menu.height();

    if(!menu.is(':visible')) {
        var pull = jQuery('#topbar .logo');
        jQuery(pull).on('click', function (e) {
            e.preventDefault();
            menu.slideToggle();
        });
        pull = jQuery('.pullmenu');
        jQuery(pull).on('click', function (e) {
            e.preventDefault();
            menu.slideToggle();
        });
    }

});
jQuery(document).ready(function ($) {
    jQuery("#home-categories").masonry({
          // options
          itemSelecor: ".home-category",
          isFitWidth: true,
          gutter: 30,
          isOriginLeft: true,
          animate: false,
        });


    jQuery(".article-list").masonry({
          // options
          itemSelecor: "article, .article-thumb",
          isFitWidth: true,
          gutter: 30,
          isOriginLeft: true,
          animate: true,
        });

    jQuery('.flexslider').flexslider({
        controlNav: false,
        directionNav: false,
        before: function (slider) {
                if (slider.count < 10) {
                    jQuery.ajax({
                        type: 'POST',
                        url: blog_vars.siteurl + 'wp-admin/admin-ajax.php',
                        data: {
                            action: 'AddHomeSlide',
                        },
                        success: function (data /*, textStatus, XMLHttpRequest*/) {
                            jQuery(".flexslider ul").append(data);
                            startLoadingImages();
                            slider.addSlide(jQuery(".flexlider .slides li:last-child"),slider.count);
                        },
                        // error: function (MLHttpRequest, textStatus, errorThrown) {
                        //     alert(errorThrown);
                        // }
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

            if (0 === w) {
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
            var k;
            for (k in image_sizes_vars) {
                w = w * window.devicePixelRatio;
                h = w * padding;

                var w2 = image_sizes_vars[k][0];
                var h2 = w2 * padding;
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
                var imageLink = img.getAttribute("data-link");

                // console.log(imageTitle);

                if (imgSRC) {
                    jQuery(imageContainer).removeClass('not-loaded');
                    var imageElement = new Image();
                    imageElement.src = imgSRC;
                    imageElement.setAttribute("alt", imageTitle ? imageTitle : "");
                    imageContainer.appendChild(imageElement);
                    imageContainer.removeChild(imageContainer.children[0]);

                    // Add link
                    if (imageLink) {
                        jQuery(imageElement).wrap($('<a>',{
                            href: imageLink
                        }));
                    }

                    // Add the image title and caption
                    if (imageCaption || imageTitle) {
                        var text,
                            d = document.createElement("div");
                        d.className = "legend";

                        if (imageTitle) {
                            var em = document.createElement("em");
                            text = document.createTextNode(imageTitle);
                            em.appendChild(text);
                            d.appendChild(em);
                        }

                        if (imageCaption) {
                            if (imageTitle) {
                                text = document.createTextNode(" - " + imageCaption);
                            } else {
                                text = document.createTextNode(imageCaption);
                            }
                            d.appendChild(text);
                        }


                        // Add it as a sibling of the image container, in the image container wrapper
                        imageContainer.parentNode.appendChild(d);
                    }
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

jQuery( window ).resize(function () {
    var h = jQuery('.flexslider').height();
    jQuery('.flexslider .img-container-wrapper').css('max-height', h);
});