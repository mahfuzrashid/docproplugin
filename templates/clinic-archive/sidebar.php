<?php
/**
 * Doctors - Sidebar
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;


global $docpro_query;

$locations = array();

foreach ( $docpro_query->get_results() as $user_id ) {
	$doctor = docpro_get_profile( 'doctor', $user_id );
	if ( is_array( $doctor->locations ) ) {
		foreach ( $doctor->locations as $index => $location_id ) {
			$location    = docpro_get_location( $location_id );
			$locations[] = array( $location->post->post_title, $location->lat, $location->long, $index + 1 );
		}
	}
}

$center = docpro_calculate_map_center( $locations );

?>


<div class="map-inner ml-10">
	<?php wp_enqueue_script( 'googlemap' ); ?>
    <script>
        function initMap() {
            let marker, i,
                locations = <?php echo json_encode( $locations ); ?>,
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: <?php echo esc_attr( docpro()->get_option( 'docpro_map_zoom', 5 ) ); ?>,
                    center: new google.maps.LatLng(<?php echo esc_attr( $center[1] ); ?>, <?php echo esc_attr( $center[2] ); ?>),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }),
                infowindow = new google.maps.InfoWindow();

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });

                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
    </script>
    <div id="map" style="width: 100%; height: 100%; min-height: 450px;"></div>
</div>
