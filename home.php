<?php get_header(); ?>
			<div id="content">
				<div id="inner-content" class="wrap clearfix">

					<div id="map"></div>

						<script type="text/javascript">

						var map = Kartograph.map('#map');

						map.loadMap('wp-content/themes/kmc2-theme/images/mapa.svg', function() {
						    // do something with your map, add layers etc.
						    map.addLayer('countries', { key: 'iso_a3' });


							map.getLayer('countries').style('fill', function(data) {
								// if (data["iso-a3"] == "ESP") {
								// 	return "#f00";
								// } else if (data["iso-a3"] == "RUS") {
								// 	return "#0f0";
								// } 
								return "#fff";
								
								//return '#f00';
							});

							map.getLayer('countries')
							    .on('click', function(data, path, event) {
									if (data["iso-a3"] == "ESP") {
										window.location.href = "http://www.google.es";
								    }
							       // do something nice
							        path.attr('fill', 'red');
							    });

						});

						</script>


				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
