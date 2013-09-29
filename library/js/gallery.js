jQuery(document).ready(function($) {
//jQuery(window).load(function($) {
    var galleries = document.querySelectorAll(".gallery-masonry");

	for (var i = 0; i < galleries.length; ++i) {
		var gallery = galleries[i];  

	    var msnry = new Masonry( gallery, {
	      // options
	      itemSelecor: ".gallery-item",
	      columnWidth: 22,
	      isFitWidth: true,
	      gutter: 10,
	      isOriginLeft: true,
	      animate: true,
	    });// code here
	} 
});