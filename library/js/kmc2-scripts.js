/*
kmc2 Scripts File
Author: Claudio Noguera
*/
/*global image_sizes_vars, blog_vars, jQuery: false*/


jQuery(document).ready(function (jQuery) {
    "use strict";
    var menu, pull, startLoadingImages, lazyloadImage, lazyLoadedImages, i,
        currentPage = 2;

    menu = jQuery('#topbar nav');

    if (!menu.is(':visible')) {
        pull = jQuery('#topbar .logo');
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

    jQuery("#home-categories").masonry({
        // options
        itemSelecor: ".home-category",
        isFitWidth: true,
        gutter: 30,
        isOriginLeft: true,
        animate: false
    });


    jQuery(".article-list").masonry({
        // options
        itemSelecor: "article, .article-thumb",
        isFitWidth: true,
        gutter: 30,
        isOriginLeft: true,
        animate: true
    });


    // ----------------------------------------------------
    // lazyload
    // ----------------------------------------------------
    startLoadingImages = function () {
        var getImageVersion = function (imageContainer) {

            var imageWrapper = imageContainer.parentNode,
                padding = parseFloat(imageContainer.style.paddingBottom) / 100, //padding in fraction,
                w = jQuery(imageWrapper).width(),
                h = jQuery(imageWrapper).height(),
                minRatio = 1.1,
                ancestor = jQuery(imageContainer).closest('.flexslider'),
                k,
                w2,
                h2;

            if (0 === w) {
                w = window.screen.width;
                h = w * padding;
            }

            // If image is vertical, limit its height
            if (w / h < minRatio) {
                w = w * 9 / 16;
                jQuery(imageWrapper).width(w);
                h = jQuery(imageWrapper).height();
            }

            // If there is overflow, we have to make the parent narrower
            // The way this works overflow can only occur when the wrapper is too short,
            // and if it is short, it is for a reason.
            if (ancestor.length > 0) {
                ancestor = ancestor[0];
            }

            if (imageWrapper.offsetHeight > ancestor.offsetHeight ||
                    imageWrapper.offsetWidth > ancestor.offsetWidth) {

                w = jQuery(ancestor).height() / padding;
                jQuery(imageWrapper).width(w);

            }

            for (k = 0; k < image_sizes_vars.length; k++) {
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

        lazyloadImage = function (imageContainer) {

            var d,
                text,
                em,
                img,
                imgSRC,
                imageCaption,
                imageTitle,
                imageLink,
                imageElement,
                imageVersion = getImageVersion(imageContainer);

            if (!imageContainer || !imageContainer.children) {
                return;
            }

            img = imageContainer.children[0];

            if (img) {
                imgSRC = img.getAttribute("data-src-" + imageVersion);

                imageCaption = img.getAttribute("data-caption");
                imageTitle = img.getAttribute("data-title");
                imageLink = img.getAttribute("data-link");

                if (imgSRC) {
                    jQuery(imageContainer).removeClass('not-loaded');
                    imageElement = new Image();
                    imageElement.src = imgSRC;
                    imageElement.setAttribute("alt", imageTitle || "");
                    imageContainer.appendChild(imageElement);
                    imageContainer.removeChild(imageContainer.children[0]);

                    // Add link
                    if (imageLink) {
                        jQuery(imageElement).wrap(jQuery('<a>', {
                            href: imageLink
                        }));
                    }

                    // Add the image title and caption
                    if (imageCaption || imageTitle) {
                        d = document.createElement("div");
                        d.className = "legend";

                        if (imageTitle) {
                            em = document.createElement("em");
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
        };

        lazyLoadedImages = document.getElementsByClassName("img-container");

        for (i = 0; i < lazyLoadedImages.length; i++) {
            if (jQuery(lazyLoadedImages[i]).hasClass('not-loaded')) {
                lazyloadImage(lazyLoadedImages[i]);
            }
        }
    };
    startLoadingImages();



    jQuery('.flexslider').flexslider({
        controlNav: false,
        directionNav: false,
        before: function (slider) {
            if (slider.count < 10) {
                jQuery.ajax({
                    type: 'POST',
                    url: blog_vars.siteurl + 'wp-admin/admin-ajax.php',
                    data: {
                        action: 'AddHomeSlide'
                    },
                    success: function (data) {
                        jQuery(".flexslider ul").append(data);
                        startLoadingImages();
                        slider.addSlide(jQuery(".flexlider .slides li:last-child"), slider.count);
                    }
                    // error: function (MLHttpRequest, textStatus, errorThrown) {
                    //     alert(errorThrown);
                    // }
                });
            }
        }
    });


    function loadArticle(pageNumber) {
        jQuery.ajax({
            url: blog_vars.siteurl + 'wp-admin/admin-ajax.php',
            type: 'GET',
            data: "action=kmc2_infinite_scroll&page_no=" + pageNumber + '&loop_file=loop',
            success: function (html) {

                if (html === "0") { // Response code for no more posts
                    jQuery(window).unbind('scroll');
                    return null;
                }
                // jQuery(".article-list").append(html);   // This will be the div where our content will be loaded
                // jQuery(".article-list").masonry();

                html = jQuery.parseHTML(html);

                jQuery('.article-list').masonry()
                    .append(html)
                    .masonry('appended', html);
            }
        });
        return false;
    }

    jQuery(window).scroll(function () {
        if (jQuery(window).scrollTop() === jQuery(document).height() - jQuery(window).height()) {
            loadArticle(currentPage);
            currentPage++;
        }
    });

});

jQuery(window).resize(function () {
    "use strict";
    var h = jQuery('.flexslider').height();
    jQuery('.flexslider .img-container-wrapper').css('max-height', h);
});

