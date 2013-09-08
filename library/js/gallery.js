jQuery(document).ready(function($) {
    var galleries = document.querySelectorAll(".gallery-masonry");

	for (var i = 0; i < galleries.length; ++i) {
		var gallery = galleries[i];  

	    console.log(gallery);
	    var msnry = new Masonry( gallery, {
	      // options
	      columnWidth: 150,
	      isFitWidth: true
	    });// code here
	}

});