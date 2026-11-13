<?php

/**
 * The markup of the provider profile template (templates/single-physicians.php): the heading, the primary
 * location, the overview list and the rating box.
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/single-physician.php';

/**
 * Render the profile and return what it printed with the whitespace around its tags removed and the rest collapsed.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 */
function single_physician_markup(array $fields = [], array $options = []): string
{
    $output = single_physician($fields + ['physician_first_name' => 'Leonard', 'physician_last_name' => 'McCoy'], $options)['single_physician_output'];

    return trim((string) preg_replace('/\s*(<[^>]+>)\s*/', '$1', (string) preg_replace('/\s+/', ' ', $output)));
}

/**
 * The options of a provider with one published location (21) titled `Heart Clinic`.
 *
 * @param  array<string, mixed>  $location  The ACF fields of the location.
 * @return array<string, mixed>
 */
function single_physician_clinic(array $location = []): array
{
    return [
        'status' => [21 => 'publish'],
        'titles' => [21 => 'Heart Clinic'],
        'location_fields' => [21 => $location + ['location_address_1' => '1 Main St', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205']],
    ];
}

test('the heading names the provider and the clinical title', function (): void {
    $output = single_physician_markup();

    expect($output)->toContain('<h1 class="page-title"><span class="name">Leonard McCoy</span><span class="subtitle">Cardiologist</span></h1>');
});

test('the heading leaves out the subtitle for a provider with no clinical title', function (): void {
    $output = single_physician_markup(['physician_title' => null]);

    expect($output)->toContain('<h1 class="page-title"><span class="name">Leonard McCoy</span></h1>');
});

test('the primary location prints the address of the first published location', function (): void {
    $options = single_physician_clinic();
    $output = single_physician_markup(['physician_locations' => [21]], $options);

    expect($output)->toContain('<div data-sectiontitle="Primary Location"><h2 class="h3">Primary Location</h2>')
        ->and($output)->toContain('<p><strong>Heart Clinic</strong><br />1 Main St<br/>Little Rock, AR 72205')
        ->and($output)->toContain('<a class="btn btn-primary" href="https://example.test/p/21/" data-itemtitle="Heart Clinic" data-categorytitle="View Location">View Location</a>')
        ->and($output)->not->toContain('View All Locations');
});

test('the primary location is named for appointments when the provider takes them', function (): void {
    $output = single_physician_markup(['physician_locations' => [21], 'physician_eligible_appointments' => true], single_physician_clinic());

    expect($output)->toContain('<h2 class="h3">Primary Appointment Location</h2>');
});

test('the primary location offers the list of locations when there are several', function (): void {
    $options = single_physician_clinic();
    $options['status'][22] = 'publish';
    $output = single_physician_markup(['physician_locations' => [21, 22]], $options);

    expect($output)->toContain('<a class="btn btn-outline-primary" href="#locations" aria-label="Jump to list of locations for this provider" data-categorytitle="View All Locations">View All Locations</a>')
        ->and(substr_count($output, '<div data-sectiontitle="Primary Location">'))->toBe(1);
});

test('the primary location prints the building, floor and suite lines', function (): void {
    $options = single_physician_clinic(['location_building' => 70, 'location_suite' => '200']);
    $options['terms'] = [70 => 'Tower'];
    $options['objects'] = ['location_building_floor' => ['value' => '2', 'choices' => ['2' => 'Second floor']]];
    $output = single_physician_markup(['physician_locations' => [21]], $options);

    expect($output)->toContain('1 Main St<br/>Tower<br />Second floor, 200<br/>Little Rock, AR 72205');
});

test('a location with the placeholder building prints no building line', function (): void {
    $options = single_physician_clinic(['location_building' => 70, 'location_suite' => '200']);
    $options['terms'] = [70 => 'None'];
    $options['slugs'] = [70 => '_none'];
    $output = single_physician_markup(['physician_locations' => [21]], $options);

    expect($output)->toContain('1 Main St<br/>200<br/>Little Rock, AR 72205')
        ->and($output)->not->toContain('None');
});

test('a location that is part of another prints the parent in the heading lines', function (): void {
    $options = single_physician_clinic(['location_parent' => true, 'location_parent_id' => 90]);
    $options['posts'] = [90 => (object) ['ID' => 90, 'post_title' => 'Medical Center']];
    $options['location_fields'][90] = ['location_prepend_the' => true];
    $output = single_physician_markup(['physician_locations' => [21]], $options);

    expect($output)->toContain('<strong>Heart Clinic</strong><br />(Part of the<a href="https://example.test/p/90/" data-categorytitle="Parent Name">Medical Center</a>)<br />');
});

test('the overview lists the areas of expertise with a plural heading', function (): void {
    $options = ['status' => [30 => 'publish', 31 => 'draft', 32 => 'publish'], 'titles' => [30 => 'Heart Care', 32 => 'Lung Care']];
    $output = single_physician_markup(['physician_expertise' => [30, 31, 32]], $options);

    expect($output)->toContain('<dt>Areas of Expertise</dt>')
        ->and($output)->toContain('<dd><a href="https://example.test/p/30/" target="_self" data-sectiontitle="Overview" data-categorytitle="View Area of Expertise">Heart Care</a></dd>')
        ->and($output)->toContain('Lung Care</a></dd>')
        ->and($output)->not->toContain('Title 31');
});

test('the overview uses the singular heading for one area of expertise', function (): void {
    $output = single_physician_markup(['physician_expertise' => [30]], ['status' => [30 => 'publish']]);

    expect($output)->toContain('<dt>Area of Expertise</dt>');
});

test('the overview hides the areas of expertise when the ontology is hidden', function (): void {
    $output = single_physician_markup(
        ['physician_expertise' => [30], 'physician_service_line' => 7],
        ['status' => [30 => 'publish'], 'rules' => [['remove_regions' => null, 'remove_service_lines' => [7]]]],
    );

    expect($output)->not->toContain('of Expertise</dt>');
});

test('the overview shows whether a provider accepts new patients', function (array $fields, string $answer): void {
    $output = single_physician_markup($fields + ['physician_eligible_appointments' => true]);

    expect($output)->toContain('<dt>Accepting New Patients</dt><dd>'.$answer.'</dd>');
})->with([
    'not accepting' => [[], 'No'],
    'accepting' => [['physician_accepting_patients' => true], 'Yes'],
    'accepting with a referral' => [['physician_accepting_patients' => true, 'physician_referral_required' => true], 'Yes (Referral Required)'],
]);

test('the overview leaves out new patients for a provider who takes no appointments', function (): void {
    expect(single_physician_markup(['physician_accepting_patients' => true]))->not->toContain('Accepting New Patients');
});

test('the overview shows second opinions', function (): void {
    expect(single_physician_markup(['physician_second_opinion' => true]))->toContain('<dt>Provides Second Opinion</dt><dd>Yes</dd>')
        ->and(single_physician_markup())->not->toContain('Second Opinion');
});

test('the overview lists the patient types', function (): void {
    $terms = ['terms' => [50 => 'Adult', 51 => 'Child']];

    expect(single_physician_markup(['physician_patient_types' => [50, 51]], $terms))->toContain('<dt>Patient Types</dt><dd>Adult</dd><dd>Child</dd>')
        ->and(single_physician_markup(['physician_patient_types' => [50]], $terms))->toContain('<dt>Patient Type</dt><dd>Adult</dd>')
        ->and(single_physician_markup())->not->toContain('Patient Type');
});

test('the overview hides a language list of English alone from sight', function (): void {
    $output = single_physician_markup(['physician_languages' => [60]], ['terms' => [60 => 'English']]);

    expect($output)->toContain('<dt class="sr-only">Language</dt><dd class="sr-only">English</dd>');
});

test('the overview shows other languages in the list', function (): void {
    $terms = ['terms' => [60 => 'English', 61 => 'Spanish']];

    expect(single_physician_markup(['physician_languages' => [60, 61]], $terms))->toContain('<dt>Languages</dt><dd>English, Spanish</dd>')
        ->and(single_physician_markup(['physician_languages' => [61]], $terms))->toContain('<dt>Language</dt><dd>Spanish</dd>');
});

test('the rating box says ratings are not available without a valid rating', function (): void {
    $output = single_physician_markup();

    expect($output)->toContain('<div class="rating" aria-label="Patient Rating"><p class="small"><em>Patient ratings are not available for this provider.')
        ->and($output)->toContain('<div id="why_not_modal" class="modal fade"');
});

test('the rating box shows the stars, score and counts of a valid rating', function (): void {
    $output = single_physician_markup([], ['ratings' => single_physician_rating('200', 40, 7, 4.5)]);

    expect($output)->toContain('<div class="rating" aria-label="Patient Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 90%;"></div></div><div class="ratings-score">4.5<span class="sr-only">out of 5</span></div><div class="w-100"></div><a href="#ratings" aria-label="Jump to Patient Ratings and Reviews" data-sectiontitle="Overview"><div class="ratings-count-lg" aria-hidden="true">40 Patient Satisfaction Ratings</div><div class="ratings-comments-lg" aria-hidden="true">7 comments</div></a></div>')
        ->and($output)->not->toContain('why_not_modal');
});

test('the clinical bio section prints the bio in one column', function (): void {
    $output = single_physician_markup(['physician_clinical_bio' => '<p>Bio.</p>']);

    expect($output)->toContain('<section class="uams-module clinical-info bg-auto" id="clinical-info">')
        ->and($output)->toContain('<div class="module-body"><h3 class="sr-only">Clinical Biography</h3><p>Bio.</p></div>')
        ->and($output)->not->toContain('content-split-lg')
        ->and($output)->not->toContain('Clinical Focus');
});

test('the clinical bio section splits into two columns when there is a clinical focus', function (): void {
    $output = single_physician_markup(['physician_clinical_bio' => '<p>Bio.</p>', 'physician_clinical_focus' => '<p>Focus.</p>']);

    expect($output)->toContain('<div class="row content-split-lg"><div class="col-xs-12 col-lg-7"><div class="content-width"><h3 class="sr-only">Clinical Biography</h3><p>Bio.</p></div></div><div class="col-xs-12 col-lg-5"><div class="content-width"><h3 class="h4">Clinical Focus</h3><p>Focus.</p></div></div></div>');
});

test('a clinical focus alone does not show the clinical bio section', function (): void {
    expect(single_physician_markup(['physician_clinical_focus' => '<p>Focus.</p>']))->not->toContain('id="clinical-info"');
});

test('a video from YouTube is played by the lite player', function (string $video): void {
    $output = single_physician_markup(['physician_youtube_link' => $video]);

    expect($output)->toContain('<div class="alignwide">lyte:'.str_replace(['https:', 'http:'], 'httpv:', $video).'</div>')
        ->and($output)->not->toContain('<iframe');
})->with([
    'a watch link' => ['https://www.youtube.com/watch?v=abc'],
    'a short link' => ['https://youtu.be/abc'],
    'an insecure link' => ['http://youtube.com/watch?v=abc'],
]);

test('a video from another source is embedded', function (): void {
    $output = single_physician_markup(['physician_youtube_link' => 'https://vimeo.com/123']);

    expect($output)->toContain('<div class="alignwide wp-block-embed is-type-video embed-responsive embed-responsive-16by9"><iframe src="https://vimeo.com/123"></iframe></div>')
        ->and($output)->not->toContain('lyte:');
});

test('the podcast section loads the episodes for the provider', function (): void {
    $output = single_physician_markup(['physician_podcast_name' => 'Leonard McCoy']);

    expect($output)->toContain('<section class="uams-module podcast-list bg-auto" id="podcast">')
        ->and($output)->toContain('radiomd_embedded_filtered_doctor("uams","radiomd-embedded-filtered-doctor",303,1837,"Leonard McCoy");')
        ->and($output)->toContain('<h2 class="module-title"><span class="title">UAMS Health Talk Podcast</span></h2>')
        ->and(single_physician_markup())->not->toContain('id="podcast"');
});

test('the academic section prints the bio in one column', function (): void {
    $output = single_physician_markup(['physician_academic_bio' => '<p>Academic.</p>']);

    expect($output)->toContain('<section class="uams-module academic-info bg-auto" id="academic-info">')
        ->and($output)->toContain('<div class="module-body"><h3 class="sr-only">Academic Biography</h3><p>Academic.</p></div>')
        ->and($output)->toContain('Academic Background</span></h2>')
        ->and($output)->not->toContain('content-split-lg');
});

test('the academic section lists roles, appointments, education, boards and associations', function (): void {
    $options = [
        'terms' => [100 => 'Surgery', 101 => 'Chair', 102 => 'Medicine', 103 => 'Professor', 104 => 'Harvard', 105 => 'M.D.', 106 => 'Board A', 107 => 'Society B'],
        'rows' => [
            'physician_academic_admin_title' => [['department' => 100, 'academic_admin_title_tax' => 101]],
            'physician_academic_appointment' => [
                ['department' => 102, 'academic_title_tax' => 103],
                ['department' => 102, 'academic_title_tax' => 999, 'academic_title' => 'Lecturer'],
            ],
            'physician_education' => [
                ['school' => 104, 'education_type' => 105, 'description' => 'Honors'],
                ['school' => 104, 'education_type' => 105],
            ],
        ],
    ];
    $output = single_physician_markup([
        'physician_academic_bio' => '<p>Academic.</p>',
        'physician_academic_admin_title' => [1],
        'physician_academic_appointment' => [1],
        'physician_education' => [1],
        'physician_boards' => [106],
        'physician_associations' => [107],
    ], $options);

    expect($output)->toContain('<div class="row content-split-lg"><div class="col-xs-12 col-lg-7"><div class="content-width"><h3 class="sr-only">Academic Biography</h3><p>Academic.</p></div></div><div class="col-xs-12 col-lg-5"><div class="content-width">')
        ->and($output)->toContain('<h3 class="h4">Administrative Roles</h3><dl><dt>Surgery</dt><dd>Chair</dd></dl>')
        ->and($output)->toContain('<h3 class="h4">Faculty Appointments</h3><dl><dt>Medicine</dt><dd>Professor</dd><dt>Medicine</dt><dd>Lecturer</dd></dl>')
        ->and($output)->toContain('<h3 class="h4">Education and Training</h3><dl><dt>M.D.</dt><dd>Harvard<br /><span class="subtitle">Honors</span></dd><dt>M.D.</dt><dd>Harvard</dd></dl>')
        ->and($output)->toContain('<h3 class="h4">Professional Certifications</h3><ul><li>Board A</li></ul>')
        ->and($output)->toContain('<h3 class="h4">Associations</h3><ul><li>Society B</li></ul>');
});

test('a resident has the residency program in the academic section', function (): void {
    $output = single_physician_markup(
        ['physician_resident' => true, 'physician_resident_profile_group' => ['physician_resident_academic_department' => 40, 'physician_resident_academic_chief' => false, 'physician_resident_academic_year' => 41]],
        ['terms' => [40 => 'Surgery', 41 => 'PGY-3']],
    );

    expect($output)->toContain('<h3 class="h4">Residency Program</h3><dl><dt>Surgery</dt><dd>PGY-3</dd></dl>');
});

/**
 * The jump links of the rendered profile as [href => text].
 *
 * @return array<string, string>
 */
function single_physician_jump_links(string $output): array
{
    preg_match_all('/<a class="nav-link" href="(#[^"]*)"[^>]*>([^<]*)<\/a>/', $output, $matches);

    return array_combine($matches[1], $matches[2]);
}

test('the jump links list every section that has content, in page order', function (): void {
    $output = single_physician_markup(
        [
            'physician_eligible_appointments' => true,
            'physician_clinical_bio' => 'Bio.',
            'physician_boards' => [1],
            'physician_podcast_name' => 'Pod',
            'physician_clinical_resources' => [4],
            'physician_research_bio' => 'x',
            'physician_conditions_cpt' => [1],
            'physician_treatments_cpt' => [1],
            'physician_expertise' => [30],
            'physician_locations' => [21],
        ],
        ['status' => [21 => 'publish', 30 => 'publish'], 'queries' => [[1], [1], [4]], 'ratings' => single_physician_rating()],
    );

    expect(single_physician_jump_links($output))->toBe([
        '#appointment-info-1' => 'Make an Appointment',
        '#clinical-info' => 'About',
        '#podcast' => 'Podcast',
        '#related-resources' => 'Resources',
        '#academic-info' => 'Academic Background',
        '#research-info' => 'Research',
        '#conditions' => 'Conditions',
        '#treatments' => 'Treatments &amp; Procedures',
        '#expertise' => 'Areas of Expertise',
        '#locations' => 'Locations',
        '#ratings' => 'Ratings &amp; Reviews',
    ]);
});

test('the jump links stay out of the page with fewer than two sections', function (): void {
    expect(single_physician_markup(['physician_podcast_name' => 'Pod']))->not->toContain('id="jump-links"')
        ->and(single_physician_markup(['physician_podcast_name' => 'Pod', 'physician_research_bio' => 'x']))->toContain('<nav class="uams-module less-padding navbar navbar-dark navbar-expand-xs jump-links" id="jump-links">');
});

test('the research section lists each kind of research content', function (): void {
    $output = single_physician_markup([
        'physician_research_bio' => '<p>Research bio.</p>',
        'physician_research_interests' => '<p>Interests.</p>',
        'physician_select_publications' => [['pubmed_information' => 'Paper one'], ['pubmed_information' => 'Paper two']],
        'physician_pubmed_author_id' => ' McCoy L ',
        'physician_research_profiles_link' => 'https://example.test/r',
    ]);

    expect($output)->toContain('<section class="uams-module research-info bg-auto" id="research-info">')
        ->and($output)->toContain('<div class="module-body"><p>Research bio.</p><h3>Research Interests</h3><p>Interests.</p><h3>Selected Publications</h3><ul><li>Paper one</li><li>Paper two</li></ul><h3>Latest Publications</h3>')
        ->and($output)->toContain('shortcode:[pubmed terms="McCoy+L%5BAuthor%5D" count="3"]')
        ->and($output)->toContain('<h3>UAMS Research Profile</h3>')
        ->and($output)->toContain('href="https://example.test/r" data-categorytitle="View Research Profile">View ');
});

test('the latest publications use the author number as the count', function (): void {
    $output = single_physician_markup(['physician_pubmed_author_id' => 'McCoy L', 'physician_author_number' => '8']);

    expect($output)->toContain('count="8"')
        ->and($output)->not->toContain('<h3>Research Interests</h3>')
        ->and($output)->not->toContain('<h3>Selected Publications</h3>')
        ->and($output)->not->toContain('UAMS Research Profile');
});

/**
 * A patient review as the ratings service returns it.
 */
function single_physician_review(string $comment, float $value = 5.0, string $date = '2026-03-04'): object
{
    return (object) ['overallRating' => (object) ['value' => $value], 'comment' => $comment, 'mentionTime' => $date];
}

test('the ratings section prints the average, each question and the first reviews', function (): void {
    $rating = single_physician_rating('200', 40, 2, 4.5);
    $rating->data->entities[0]->overallRating->questionRatings = [
        (object) ['name' => 'Friendliness', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Likelihood of recommending this provider', 'responseCount' => 5, 'value' => 4.5],
        (object) ['name' => 'Unrated', 'responseCount' => 0, 'value' => 3.0],
    ];
    $rating->data->entities[0]->comments = [single_physician_review('Great doctor.', 5.0, '2026-03-04'), single_physician_review('Kind.', 4.0, '2026-02-01')];
    $output = single_physician_markup([], ['ratings' => $rating]);

    expect($output)->toContain('<section class="uams-module ratings-and-reviews bg-auto" id="ratings">')
        ->and($output)->toContain('<h3 class="sr-only">Average Ratings</h3><dl><dt>Likelihood of recommending this provider</dt>')
        ->and($output)->toContain('<dt>Friendliness</dt>')
        ->and($output)->not->toContain('<dt>Unrated</dt>')
        ->and($output)->toContain('<p class="h5">Overall: 4.5 out of 5</p>')
        ->and($output)->toContain('Great doctor.')
        ->and($output)->toContain('Mar 04, 2026')
        ->and($output)->toContain('Feb 01, 2026')
        ->and($output)->not->toContain('id="MoreReviews"');
});

test('more than six reviews offer the rest in a window', function (): void {
    $rating = single_physician_rating('200', 40, 8, 4.5);
    $rating->data->entities[0]->comments = array_map(static fn (int $n): object => single_physician_review('Review '.$n), range(1, 8));
    $output = single_physician_markup(['physician_npi' => '123'], ['ratings' => $rating]);

    expect($output)->toContain('aria-label="View more individual reviews">View More</button>')
        ->and($output)->toContain('<div class="modal fade" id="MoreReviews"')
        ->and($output)->toContain('id="comment-list" class="card-list" data-postid="5" data-npi="123"')
        ->and(substr_count($output, 'Review 7'))->toBe(1)
        ->and(substr_count($output, 'Review 1<'))->toBe(2);
});

test('a question with a recommendation drops the questions it repeats when there are many', function (): void {
    $rating = single_physician_rating('200', 40, 0, 4.5);
    $rating->data->entities[0]->overallRating->questionRatings = [
        (object) ['name' => 'Likelihood of recommending this provider', 'responseCount' => 5, 'value' => 4.5],
        (object) ['name' => 'Would Recommend', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Listened Carefully', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Knew Medical History', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Gave Enough Information', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Trust Provider', 'responseCount' => 5, 'value' => 4.0],
    ];
    $output = single_physician_markup([], ['ratings' => $rating]);

    expect($output)->toContain('<dt>Likelihood of recommending this provider</dt>')
        ->and($output)->not->toContain('<dt>Would Recommend</dt>')
        ->and($output)->not->toContain('<dt>Trust Provider</dt>');
});

/**
 * The structured data the profile prints, decoded.
 *
 * @return array<string, mixed>
 */
function single_physician_schema(string $output): array
{
    preg_match("/<script type='application\/ld\+json'>(.*?)<\/script>/s", $output, $match);

    return json_decode($match[1] ?? '', true) ?? [];
}

/**
 * Posts as the queries of the template return them.
 *
 * @param  array<int, string>  $titles  The post titles by post id.
 * @return list<object>
 */
function single_physician_posts(array $titles): array
{
    return array_map(static function (int $id, string $title): WP_Post {
        $post = new WP_Post;
        $post->ID = $id;
        $post->post_title = $title;

        return $post;
    }, array_keys($titles), $titles);
}

test('the structured data names the provider and describes the profile', function (): void {
    $output = single_physician_markup(['physician_short_clinical_bio' => 'Short bio.'], ['thumbnail' => true]);
    $schema = single_physician_schema($output);

    expect($schema)->toBe([
        '@context' => 'https://schema.org',
        '@type' => 'Physician',
        'name' => 'Leonard McCoy',
        'url' => 'https://example.test/p/0/',
        'logo' => 'https://example.test/theme/assets/svg/uams-logo_health_horizontal_dark_386x50.png',
        'image' => 'https://example.test/photo-778x1038.jpg',
        'description' => 'Short bio.',
    ]);
});

test('the structured data lists the conditions and treatments as specialties', function (): void {
    $output = single_physician_markup(
        ['physician_conditions_cpt' => [11, 12], 'physician_treatments_cpt' => [21]],
        ['queries' => [single_physician_posts([11 => 'Asthma', 12 => 'Angina']), single_physician_posts([21 => 'Bypass'])]],
    );
    $schema = single_physician_schema($output);

    expect($schema['medicalSpecialty'])->toBe([
        ['@type' => 'MedicalSpecialty', 'name' => 'Asthma', 'url' => 'https://example.test/p/11/'],
        ['@type' => 'MedicalSpecialty', 'name' => 'Angina', 'url' => 'https://example.test/p/12/'],
        ['@type' => 'MedicalSpecialty', 'name' => 'Bypass', 'url' => 'https://example.test/p/21/'],
    ]);
});

test('the structured data lists treatments alone as specialties', function (): void {
    $output = single_physician_markup(
        ['physician_treatments_cpt' => [21, 22]],
        ['queries' => [[], single_physician_posts([21 => 'Bypass', 22 => 'Stent'])]],
    );

    expect(single_physician_schema($output)['medicalSpecialty'])->toBe([
        ['@type' => 'MedicalSpecialty', 'name' => 'Bypass', 'url' => 'https://example.test/p/21/'],
        ['@type' => 'MedicalSpecialty', 'name' => 'Stent', 'url' => 'https://example.test/p/22/'],
    ]);
});

test('the structured data lists each published location as an address', function (): void {
    $options = single_physician_clinic(['location_phone' => '501-1']);
    $options['status'][22] = 'publish';
    $options['status'][23] = 'draft';
    $options['location_fields'][22] = ['location_address_1' => '2 Oak St', 'location_city' => 'Conway', 'location_state' => 'AR', 'location_zip' => '72032'];
    $schema = single_physician_schema(single_physician_markup(['physician_locations' => [21, 22, 23]], $options));

    expect($schema['address'])->toBe([
        ['@type' => 'PostalAddress', 'streetAddress' => '1 Main St ', 'addressLocality' => 'Little Rock', 'addressRegion' => 'AR', 'postalCode' => '72205', 'telephone' => 'dash:501-1'],
        ['@type' => 'PostalAddress', 'streetAddress' => '2 Oak St ', 'addressLocality' => 'Conway', 'addressRegion' => 'AR', 'postalCode' => '72032', 'telephone' => ''],
    ]);
});

test('the structured data adds the aggregate rating of a valid rating', function (): void {
    $schema = single_physician_schema(single_physician_markup([], ['ratings' => single_physician_rating('200', 40, 7, 4.5)]));

    expect($schema['aggregateRating'])->toBe(['@type' => 'AggregateRating', 'ratingValue' => '4.5', 'ratingCount' => '40', 'reviewCount' => '7']);
});

test('the aggregate rating leaves out the review count when there are no comments', function (): void {
    $schema = single_physician_schema(single_physician_markup([], ['ratings' => single_physician_rating('200', 40, 0, 4.0)]));

    expect($schema['aggregateRating'])->toBe(['@type' => 'AggregateRating', 'ratingValue' => '4', 'ratingCount' => '40']);
});

test('the conditions and treatments sections print their loops for a provider', function (): void {
    $output = single_physician_markup(
        ['physician_conditions_cpt' => [11], 'physician_treatments_cpt' => [21]],
        ['queries' => [single_physician_posts([11 => 'Asthma']), single_physician_posts([21 => 'Bypass'])]],
    );

    expect($output)->toContain('id="conditions"')
        ->and($output)->toContain('id="treatments"');
});

test('the areas of expertise section prints a card for each published area', function (): void {
    $output = single_physician_markup(
        ['physician_expertise' => [30, 31, 32]],
        ['status' => [30 => 'publish', 31 => 'draft', 32 => 'publish'], 'titles' => [30 => 'Heart Care', 32 => 'Lung Care']],
    );

    expect($output)->toContain('<section class="uams-module expertise-list bg-auto" id="expertise">')
        ->and($output)->toContain('Areas of Expertise</span></h2>')
        ->and(substr_count($output, 'class="card-list card-list-expertise"'))->toBe(1)
        ->and($output)->toContain('Heart Care')
        ->and($output)->toContain('Lung Care')
        ->and($output)->not->toContain('Title 31');
});

test('the locations section prints a card for each published location', function (): void {
    $options = single_physician_clinic();
    $options['status'][22] = 'publish';
    $options['status'][23] = 'draft';
    $options['titles'][22] = 'Lung Clinic';
    $output = single_physician_markup(['physician_locations' => [21, 22, 23]], $options);

    expect($output)->toContain('<section class="uams-module location-list bg-auto" id="locations">')
        ->and($output)->toContain('<span class="title">Locations Where Leonard McCoy Practices</span>')
        ->and($output)->toContain('Heart Clinic')
        ->and($output)->toContain('Lung Clinic')
        ->and($output)->not->toContain('Title 23');
});

test('the second appointment block follows the page only when the first has other sections to follow', function (): void {
    $with = single_physician_markup(['physician_eligible_appointments' => true, 'physician_podcast_name' => 'Pod']);
    $alone = single_physician_markup(['physician_eligible_appointments' => true]);
    $without = single_physician_markup(['physician_podcast_name' => 'Pod']);

    expect(substr_count($with, 'id="appointment-info-'))->toBe(2)
        ->and(substr_count($alone, 'id="appointment-info-'))->toBe(1)
        ->and(substr_count($without, 'id="appointment-info-'))->toBe(0);
});

test('the photo prints at each size with the name as its text', function (): void {
    $output = single_physician_markup([], ['thumbnail' => true]);

    expect($output)->toContain('<div class="col-12 col-xs px-0 px-xs-4 px-sm-8 order-1 image"><picture>')
        ->and($output)->toContain('<source srcset="https://example.test/photo-389x519.jpg" media="(min-width: 1200px)">')
        ->and($output)->toContain('<img src="https://example.test/photo-778x1038.jpg" alt="Leonard McCoy" />')
        ->and(single_physician_markup())->not->toContain('<picture>');
});
