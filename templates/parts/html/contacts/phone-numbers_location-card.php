<?php
/**
 * Template Name: Location Phone Numbers Variable Definitions
 * 
 * Description: A template part that displays a list of phone numbers using a 
 * definition list element.
 * 
 * Two output options are available: location profile and associated locations.
 * 
 * The location profile output is intended to be used in the contact information 
 * section of a location profile.
 * 
 * The associated locations output is intended to be used in secondary references 
 * to a location such as a location card or the primary location section of a 
 * provider profile.
 * 
 * Designed for UAMS Health Find-a-Doc
 * 
 * Required vars:
 * 	$location_single_name // System setting for Locations Plural Item Name
 * 	$phone_output_id (ID of location)
 */

// Display phone numbers for associated locations

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
						}

						?>
					</dd>
					<?php
	
				}

			}

			?>
		</dl>
		<?php

	} // endif $location_phone
