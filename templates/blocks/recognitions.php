<?php

declare(strict_types=1);
/*
 *
 * UAMS Find-a-Doc Recognition List Block
 *
 */

// Editor placeholder for a newly inserted, still-empty block (ACF Blocks V3).
if (function_exists('uams_fad_block_placeholder') && uams_fad_block_placeholder($block ?? null, $is_preview ?? false)) {
    return;
}

$block_data = uamswp_fad_assoc($block ?? null);
$className = '';
if (! empty($block_data['className'])) {
    $className .= ' '.uamswp_fad_str($block_data['className']);
}

// Load Values
if (empty($recognition_id)) {
    $recognition_id = get_field('field_select_recognition_list');
}

// if ($recognition_id) :

// Build Main Query from Post Ids (Filtered IDs + Specific IDs)
$args = ([
    'post_type' => 'provider', // We only want providers
    'post_status' => 'publish', // We only want published providers
    'posts_per_page' => -1, // We do not want to limit the post count
    'order' => 'ASC',
    'orderby' => 'title',
    'tax_query' => [
        [
            'taxonomy' => 'recognition',
            'field' => 'term_id',
            'terms' => $recognition_id,
        ],
    ],
    // We can define any additional arguments that we need - see Codex for the full list
]);
$recognition_query = new WP_Query($args);
$recognition_list = '';
if ($recognition_query->have_posts()) {
    $recognition_list .= '<div class="table-responsive">
			<table class="table table-striped">
			<thead>
			<tr>
				<th scope="col" class="col-6">Name</th>
				<th scope="col" class="col-6">Title</th>
			</tr>
			</thead>
			<tbody>';

    while ($recognition_query->have_posts()) {
        $recognition_query->the_post();
        $id = get_the_ID(); // Grab the Post ID
        $degrees = get_field('physician_degree', $id);
        $degree_list = '';
        $i = 1;
        if ($degrees) {
            foreach ($degrees as $degree) {
                $degree_list .= uamswp_fad_term_name($degree, 'degree');
                if (count($degrees) > $i) {
                    $degree_list .= ', ';
                }

                $i++;
            }
        }

        $full_name = get_field('physician_first_name', $id).' '.(get_field('physician_middle_name', $id) ? get_field('physician_middle_name', $id).' ' : '').get_field('physician_last_name', $id).(get_field('physician_pedigree', $id) ? '&nbsp;'.get_field('physician_pedigree', $id) : '').($degree_list ? ', '.$degree_list : '');
        $recognition_list .= '<tr>';
        $recognition_list .= '<td><a href="'.get_permalink().'" title="'.$full_name.'">'.$full_name.'</a></td>';

        // Get resident values

        $resident = get_field('physician_resident', $id);
        $resident_title_name = 'Resident Physician';

        // Get clinical specialty and occupation title values

        // Eliminate PHP errors

        $provider_specialty = '';
        $provider_specialty_term = '';
        $provider_specialty_name = '';
        $provider_occupation_title = '';

        if ($resident) {

            // Clinical Occupation Title

            $provider_occupation_title = $resident_title_name;

        } else {

            // Clinical Specialty

            $provider_specialty = get_field('physician_title', $id);

            // Clinical Occupation Title

            if ($provider_specialty) {

                $provider_specialty_term = get_term(uamswp_fad_int($provider_specialty), 'clinical_title');

                if (is_object($provider_specialty_term) && ! is_wp_error($provider_specialty_term)) {

                    // Get term name

                    $provider_specialty_name = $provider_specialty_term->name;

                    // Get occupational title field from term

                    $provider_occupation_title = get_field('clinical_specialization_title', $provider_specialty_term);

                    // Set occupational title from term name as a fallback

                    if (! $provider_occupation_title) {

                        $provider_occupation_title = $provider_specialty_name;

                    }

                }

            }

        }

        if ($provider_occupation_title) {

            $recognition_list .= '<td>'.$provider_occupation_title.'</td>';

        } else {

            $recognition_list .= '<td>&nbsp;</td>';

        }

        $recognition_list .= '</tr>';

    }

    $recognition_list .= '</tbody>';
    $recognition_list .= '</table>';
    $recognition_list .= '</div>'; // responsive table

    echo $recognition_list;
}

// endif;
