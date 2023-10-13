<?php
/**
 * Template Name: Location Appointment Phone Numbers List
 *
 * Description: A template part that displays a list of appointment phone
 * numbers using a definition list element.
 *
 * The output is intended to be used in secondary references to a location such as
 * a location card or the primary location section of a provider profile.
 *
 * Designed for UAMS Health Find-a-Doc
 *
 * Required vars:
 * 	$location_phone_numbers // array
 * 	$location_phone_data_categorytitle // string // data-categorytitle attribute value
 */

if ( $location_phone_numbers ) {

	?>
	<dl <?php echo $location_phone_data_categorytitle ? ' data-categorytitle="' . $location_phone_data_categorytitle . '"' : '' ?>>
		<?php

		foreach ( $location_phone_numbers as $key => $value ) {

			?>
			<dt><?php echo $key; ?></dt>
			<?php

			foreach( $value as $item) {

				?>
				<dd>
					<?php

					echo $item['link'];

					if ( $item['subtitle'] ) {

						?><br/>
						<span class="subtitle"><?php echo $item['subtitle']; ?></span>
						<?php
					} // endif

					?>
				</dd>
				<?php

			}

		} // endforeach

		?>
	</dl>
	<?php

} // endif $location_phone_numbers
