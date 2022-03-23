<?php
/**
 * Clinic Details - Nav Item - Location
 *
 * @copyright docpro @2020
 */

defined( 'ABSPATH' ) || exit;

global $clinic;

$locations = array();
$chambers  = array();

foreach ( $clinic->get_locations() as $index => $location_id ) {
	$location     = docpro_get_location( $location_id );
	$locations[]  = array( $location->post->post_title, $location->lat, $location->long, $index + 1 );
	$chamber_data = array();

	if ( ! empty( $chamber_address = $location->get_formatted_address() ) ) {
		$chamber_data[] = sprintf( '<li><i class="fas fa-map-marker-alt"></i>%s</li>', $chamber_address );
	}

	if ( ! empty( $chamber_phone = $location->phone ) ) {
		$chamber_data[] = sprintf( '<li><i class="fas fa-phone"></i><a href="tel:%1$s">%1$s</a></li>', $chamber_phone );
	}

	ob_start();
	printf( '<h5>%s</h5>', $location->post->post_title );
	printf( '<ul class="location-info clearfix">%s</ul>', implode( '', $chamber_data ) );

	$chambers[] = ob_get_clean();
}
$center = $locations[ array_rand( $locations ) ];

?>

<div class="tab <?php echo esc_attr( $active_class ); ?>" id="locations">
    <div class="location-box">
        <h3><?php esc_html_e( 'Locations', 'docpro' ); ?></h3>
        <div class="map-inner">
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
            <div id="map" style="width: 100%; height: 100%;"></div>
        </div>

		<?php printf( '<h3>%s</h3>%s', esc_html__( 'Clinic Locations', 'docpro' ), implode( '', $chambers ) ); ?>
    </div>
</div>