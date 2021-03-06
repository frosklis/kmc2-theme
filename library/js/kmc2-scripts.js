/*
kmc2 Scripts File
Author: Claudio Noguera
*/
/*global image_sizes_vars, blog_vars, jQuery: false*/

Object.size = function (obj) {
    'use strict';
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) {
            size++;
        }
    }
    return size;
};

// ----------------------------------------------------
// lazyload
// ----------------------------------------------------
window.startLoadingImages = function () {
    'use strict';

    var lazyloadImage, lazyLoadedImages, i, getImageVersion;
    getImageVersion = function (imageContainer) {

        var imageWrapper = imageContainer.parentNode,
            padding = parseFloat(imageContainer.style.paddingBottom) / 100, //padding in fraction,
            w = jQuery(imageWrapper).width(),
            h = jQuery(imageWrapper).height(),
            minRatio = 1.1,
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

        // for (k = 0; k < Object.size(image_sizes_vars); k++) {
        for (k in image_sizes_vars) {
            if (image_sizes_vars.hasOwnProperty(k)) {
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


jQuery(document).ready(function (jQuery) {
    "use strict";
    var menu, pull;

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

    jQuery(".gallery-masonry").masonry({
        // options
        itemSelecor: ".gallery-item",
        isFitWidth: true,
        gutter: 30,
        isOriginLeft: true,
        animate: true
    });

    window.startLoadingImages();
});


function loadGallery() {
    'use strict';
    var gallery, chunks, chunk;
    gallery = jQuery(".gallery");
    chunks = parseInt(gallery[0].getAttribute("data-chunks"), 10);
    chunk = gallery[0].getAttribute("data-include-" + chunks.toString());


    gallery[0].setAttribute("data-chunks", chunks - 1);
    gallery[0].removeAttribute("data-include-" + chunks.toString());

    jQuery.ajax({
        url: blog_vars.siteurl + 'wp-admin/admin-ajax.php',
        type: 'GET',
        data: "action=kmc2_load_gallery&include=" + chunk,
        beforeSend: function () {
            jQuery('#main').addClass("loading");
        },
        complete: function () {
            jQuery('#main').removeClass("loading");
        },
        success: function (html) {
            html = jQuery.parseHTML(html);

            gallery.masonry()
                .append(html)
                .masonry('appended', html);

            window.startLoadingImages();
        }
    });
    return false;
}

jQuery(window).scroll(function () {
    'use strict';
    if (jQuery(window).scrollTop() === jQuery(document).height() - jQuery(window).height()) {
        loadGallery();
    }
});