<?php
/**
 * Business Profile sync: the decisions that need no WordPress and no network.
 *
 * Mapping a table row onto Business Profile fields, deciding which fields differ from a
 * live listing, resolving a location's silo, and capping a silo's mode by environment all
 * live here as static functions so they can be tested on their own.
 *
 * @package UAMSWP_Find_a_Doc
 */

class UAMSWP_FAD_GBP_Logic {

	/** The Business Profile fields the sync owns. Photos, posts, hours and Q&A are never written. */
	const OWNED_FIELDS = array( 'title', 'storefrontAddress', 'latlng', 'phoneNumbers', 'websiteUri', 'categories', 'profile' );

	/** Modes a silo can be in, from least to most permissive. */
	const MODES = array( 'off', 'dry-run', 'live' );

	/**
	 * Resolve the silo a location belongs to from its brand fields.
	 *
	 * @param mixed                 $brand_query The `schema_brandorg_query` value. '1' replaces the default brand.
	 * @param mixed                 $brand_org   The `schema_brandorg` value, a `brand_organization` term slug.
	 * @param array<string, array>  $silos       Silo definitions keyed by silo name, each with a `brand` slug or null.
	 * @return string|null The silo name, or null when the brand has no silo (default-deny).
	 */
	public static function resolve_silo( $brand_query, $brand_org, array $silos ) {

		if ( '1' !== (string) $brand_query ) {
			return isset( $silos['uams'] ) ? 'uams' : null;
		}

		foreach ( $silos as $name => $silo ) {
			if ( ! empty( $silo['brand'] ) && (string) $silo['brand'] === (string) $brand_org ) {
				return $name;
			}
		}

		return null;

	}

	/**
	 * The mode a silo actually runs in: the configured mode, capped at dry-run off production.
	 *
	 * @param string $configured    The silo's configured mode. Anything unrecognized is treated as off.
	 * @param bool   $is_production Whether this site is production.
	 * @return string One of self::MODES.
	 */
	public static function effective_mode( $configured, $is_production ) {

		if ( ! in_array( $configured, self::MODES, true ) ) {
			return 'off';
		}

		if ( 'live' === $configured && ! $is_production ) {
			return 'dry-run';
		}

		return $configured;

	}

	/**
	 * Split a store code into its provider and location slugs.
	 *
	 * Provider store codes are `{provider_slug}_{location_slug}`. Slugs use hyphens, so the one
	 * underscore is the separator.
	 *
	 * @param string $store_code A provider store code.
	 * @return array{0: string, 1: string}|null The provider and location slugs, or null if malformed.
	 */
	public static function split_provider_store_code( $store_code ) {

		$parts = explode( '_', (string) $store_code );

		if ( 2 !== count( $parts ) || '' === $parts[0] || '' === $parts[1] ) {
			return null;
		}

		return array( $parts[0], $parts[1] );

	}

	/**
	 * Turn one table row into the Business Profile fields the sync owns.
	 *
	 * Column numbers match the shared table builders (0 store code through 17 description).
	 *
	 * @param array             $row          A row from uamswp_fad_gmb_provider_table() or the location builder.
	 * @param array<string,string> $category_ids Category display name => `categories/gcid:...` resource name.
	 * @return array The desired fields. A field with no value in the row is left out.
	 */
	public static function fields_from_row( array $row, array $category_ids ) {

		$cell = function ( $i ) use ( $row ) {
			return isset( $row[ $i ] ) ? trim( html_entity_decode( (string) $row[ $i ], ENT_QUOTES, 'UTF-8' ) ) : '';
		};

		$fields = array();

		if ( '' !== $cell( 1 ) ) {
			$fields['title'] = $cell( 1 );
		}

		$lines = array();
		foreach ( range( 2, 6 ) as $i ) {
			if ( '' !== $cell( $i ) ) {
				$lines[] = $cell( $i );
			}
		}
		if ( $lines && '' !== $cell( 7 ) && '' !== $cell( 8 ) && '' !== $cell( 10 ) ) {
			$fields['storefrontAddress'] = array(
				'regionCode'         => '' !== $cell( 9 ) ? $cell( 9 ) : 'US',
				'languageCode'       => 'en',
				'postalCode'         => $cell( 10 ),
				'administrativeArea' => $cell( 8 ),
				'locality'           => $cell( 7 ),
				'addressLines'       => $lines,
			);
		}

		if ( is_numeric( $cell( 11 ) ) && is_numeric( $cell( 12 ) ) ) {
			$fields['latlng'] = array(
				'latitude'  => (float) $cell( 11 ),
				'longitude' => (float) $cell( 12 ),
			);
		}

		if ( '' !== $cell( 13 ) ) {
			$fields['phoneNumbers'] = array( 'primaryPhone' => $cell( 13 ) );
		}

		if ( '' !== $cell( 14 ) ) {
			$fields['websiteUri'] = $cell( 14 );
		}

		$primary = isset( $category_ids[ $cell( 15 ) ] ) ? $category_ids[ $cell( 15 ) ] : '';
		if ( '' !== $primary ) {
			$categories = array( 'primaryCategory' => array( 'name' => $primary ) );
			$additional = array();
			foreach ( explode( ',', $cell( 16 ) ) as $name ) {
				$name = trim( $name );
				if ( '' !== $name && isset( $category_ids[ $name ] ) && $category_ids[ $name ] !== $primary ) {
					$additional[] = array( 'name' => $category_ids[ $name ] );
				}
			}
			if ( $additional ) {
				$categories['additionalCategories'] = $additional;
			}
			$fields['categories'] = $categories;
		}

		if ( '' !== $cell( 17 ) ) {
			$fields['profile'] = array( 'description' => $cell( 17 ) );
		}

		return $fields;

	}

	/**
	 * The desired fields that differ from the live listing.
	 *
	 * Values are compared after normalization (phone digits only, coordinates to five places,
	 * categories by resource name), so formatting differences do not count as changes.
	 *
	 * @param array $desired Fields from self::fields_from_row().
	 * @param array $live    The live listing as the API returns it.
	 * @return array The subset of $desired that differs. Empty when nothing needs writing.
	 */
	public static function changed_fields( array $desired, array $live ) {

		$changed = array();

		foreach ( self::OWNED_FIELDS as $field ) {

			if ( ! isset( $desired[ $field ] ) ) {
				continue;
			}

			$live_value = isset( $live[ $field ] ) ? $live[ $field ] : null;

			if ( self::normalize( $field, $desired[ $field ] ) !== self::normalize( $field, $live_value ) ) {
				$changed[ $field ] = $desired[ $field ];
			}
		}

		return $changed;

	}

	/**
	 * The `updateMask` for a set of changed fields.
	 *
	 * @param array $changed Fields from self::changed_fields().
	 * @return string Comma-separated field names, restricted to the fields the sync owns.
	 */
	public static function update_mask( array $changed ) {

		return implode( ',', array_values( array_intersect( self::OWNED_FIELDS, array_keys( $changed ) ) ) );

	}

	/**
	 * Reduce a field value to a form that compares equal across harmless formatting differences.
	 *
	 * @param string $field A field name from self::OWNED_FIELDS.
	 * @param mixed  $value The field value, or null when the live listing has none.
	 * @return mixed
	 */
	private static function normalize( $field, $value ) {

		if ( null === $value ) {
			return null;
		}

		switch ( $field ) {

			case 'phoneNumbers':
				return array( 'primaryPhone' => preg_replace( '/\D+/', '', (string) ( $value['primaryPhone'] ?? '' ) ) );

			case 'latlng':
				return array(
					round( (float) ( $value['latitude'] ?? 0 ), 5 ),
					round( (float) ( $value['longitude'] ?? 0 ), 5 ),
				);

			case 'storefrontAddress':
				return array(
					'postalCode'         => trim( (string) ( $value['postalCode'] ?? '' ) ),
					'administrativeArea' => trim( (string) ( $value['administrativeArea'] ?? '' ) ),
					'locality'           => trim( (string) ( $value['locality'] ?? '' ) ),
					'addressLines'       => array_values( array_map( 'trim', (array) ( $value['addressLines'] ?? array() ) ) ),
				);

			case 'categories':
				$additional = array();
				foreach ( (array) ( $value['additionalCategories'] ?? array() ) as $category ) {
					$additional[] = (string) ( $category['name'] ?? '' );
				}
				sort( $additional );
				return array(
					'primary'    => (string) ( $value['primaryCategory']['name'] ?? '' ),
					'additional' => $additional,
				);

			case 'profile':
				return array( 'description' => trim( (string) ( $value['description'] ?? '' ) ) );

			default:
				return is_string( $value ) ? trim( $value ) : $value;
		}

	}

}
