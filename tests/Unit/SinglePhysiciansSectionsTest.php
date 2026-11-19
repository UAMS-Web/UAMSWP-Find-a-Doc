<?php

/**
 * Whole sections of the provider profile template (templates/single-physicians.php), each pinned as it renders
 * today, so a change anywhere inside a section shows.
 */

declare(strict_types=1);

require_once __DIR__.'/../Support/single-physician.php';

/**
 * Render the profile and return what it printed with the whitespace around its tags removed and the rest collapsed.
 *
 * @param  array<string, mixed>  $fields
 * @param  array<string, mixed>  $options
 */
function single_physician_sections_markup(array $fields = [], array $options = []): string
{
    $vars = single_physician($fields + ['physician_first_name' => 'Leonard', 'physician_last_name' => 'McCoy'], $options);
    expect($vars['single_physician_warnings'])->toBe([]);

    return trim((string) preg_replace('/\s*(<[^>]+>)\s*/', '$1', (string) preg_replace('/\s+/', ' ', $vars['single_physician_output'])));
}

/**
 * The first section with the given id, from its opening tag to its closing one.
 */
function single_physician_section(string $output, string $id): string
{
    return preg_match('/<section [^>]*id="'.preg_quote($id, '/').'"[^>]*>.*?<\/section>/', $output, $match) === 1 ? $match[0] : '';
}

/**
 * A rating with three questions (one with no responses) and eight reviews.
 */
function single_physician_sections_rating(): object
{
    $rating = single_physician_rating('200', 40, 8, 4.5);
    $rating->data->entities[0]->overallRating->questionRatings = [
        (object) ['name' => 'Friendliness', 'responseCount' => 5, 'value' => 4.0],
        (object) ['name' => 'Likelihood of recommending this provider', 'responseCount' => 1, 'value' => 4.5],
        (object) ['name' => 'Unrated', 'responseCount' => 0, 'value' => 3.0],
    ];
    $rating->data->entities[0]->comments = array_map(static fn (int $n): object => (object) ['overallRating' => (object) ['value' => 4.0], 'comment' => 'Review '.$n, 'mentionTime' => '2026-03-0'.min(9, $n)], range(1, 8));

    return $rating;
}

test('the clinical section in two columns', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_clinical_bio' => '<p>Bio.</p>', 'physician_clinical_focus' => '<p>Focus.</p>']), 'clinical-info'))
        ->toBe('<section class="uams-module clinical-info bg-auto" id="clinical-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">About Leonard McCoy</span></h2><div class="row content-split-lg"><div class="col-xs-12 col-lg-7"><div class="content-width"><h3 class="sr-only">Clinical Biography</h3><p>Bio.</p></div></div><div class="col-xs-12 col-lg-5"><div class="content-width"><h3 class="h4">Clinical Focus</h3><p>Focus.</p></div></div></div></div></div></div></section>');
});

test('the clinical section in one column', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_clinical_bio' => '<p>Bio.</p>']), 'clinical-info'))
        ->toBe('<section class="uams-module clinical-info bg-auto" id="clinical-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">About Leonard McCoy</span></h2><div class="module-body"><h3 class="sr-only">Clinical Biography</h3><p>Bio.</p></div></div></div></div></section>');
});

test('the academic section in one column', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_academic_bio' => '<p>Academic.</p>']), 'academic-info'))
        ->toBe('<section class="uams-module academic-info bg-auto" id="academic-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Leonard McCoy\'s Academic Background</span></h2><div class="module-body"><h3 class="sr-only">Academic Biography</h3><p>Academic.</p></div></div></div></div></section>');
});

test('the academic section in two columns', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_academic_bio' => '<p>Academic.</p>', 'physician_boards' => [106]], ['terms' => [106 => 'Board A']]), 'academic-info'))
        ->toBe('<section class="uams-module academic-info bg-auto" id="academic-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Leonard McCoy\'s Academic Background</span></h2><div class="row content-split-lg"><div class="col-xs-12 col-lg-7"><div class="content-width"><h3 class="sr-only">Academic Biography</h3><p>Academic.</p></div></div><div class="col-xs-12 col-lg-5"><div class="content-width"><h3 class="h4">Professional Certifications</h3><ul><li>Board A</li></ul></div></div></div></div></div></div></section>');
});

test('the academic section with boards and no bio', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_boards' => [106]], ['terms' => [106 => 'Board A']]), 'academic-info'))
        ->toBe('<section class="uams-module academic-info bg-auto" id="academic-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Leonard McCoy\'s Academic Background</span></h2><div class="module-body"><h3 class="h4">Professional Certifications</h3><ul><li>Board A</li></ul></div></div></div></div></section>');
});

test('the research section with a publication that has no information', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_research_bio' => '<p>Research bio.</p>', 'physician_select_publications' => [['pubmed_information' => 'Paper one'], []]]), 'research-info'))
        ->toBe('<section class="uams-module research-info bg-auto" id="research-info"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2 class="module-title"><span class="title">Leonard McCoy\'s Research</span></h2><div class="module-body"><p>Research bio.</p><h3>Selected Publications</h3><ul><li>Paper one</li><li></li></ul></div></div></div></div></section>');
});

test('the expertise section lists the published areas and skips post 0', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_expertise' => [0, 1, 31]], ['status' => [0 => 'publish', 1 => 'publish', 31 => 'publish'], 'titles' => [1 => 'Cardiology', 31 => 'Oncology']]), 'expertise'))
        ->toBe('<section class="uams-module expertise-list bg-auto" id="expertise"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Leonard McCoy\'s Areas of Expertise</span></h2><div class="card-list-container"><div class="card-list card-list-expertise"><div class="card"><a href="https://example.test/p/1/" target="_self" aria-label="View Area of Expertise page for Cardiology" data-categorytitle="Photo" data-itemtitle="Cardiology"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" /></picture></a><div class="card-body"><h3 class="card-title h5"><span class="name"><a href="https://example.test/p/1/" target="_self" aria-label="View Area of Expertise page for Cardiology" data-categorytitle="Name" data-itemtitle="Cardiology">Cardiology</a></span></h3><p class="card-text">An excerpt.</p></div><!-- .card-body --><div class="btn-container"><div class="inner-container"><a href="https://example.test/p/1/" class="btn btn-primary" aria-label="View Area of Expertise page for Cardiology" data-categorytitle="View Area of Expertise" data-itemtitle="Cardiology">View Area of Expertise</a></div></div></div><!-- .card --><div class="card"><a href="https://example.test/p/31/" target="_self" aria-label="View Area of Expertise page for Oncology" data-categorytitle="Photo" data-itemtitle="Oncology"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" loading="lazy" /></picture></a><div class="card-body"><h3 class="card-title h5"><span class="name"><a href="https://example.test/p/31/" target="_self" aria-label="View Area of Expertise page for Oncology" data-categorytitle="Name" data-itemtitle="Oncology">Oncology</a></span></h3><p class="card-text">An excerpt.</p></div><!-- .card-body --><div class="btn-container"><div class="inner-container"><a href="https://example.test/p/31/" class="btn btn-primary" aria-label="View Area of Expertise page for Oncology" data-categorytitle="View Area of Expertise" data-itemtitle="Oncology">View Area of Expertise</a></div></div></div><!-- .card --></div></div></div></div></div></section>');
});

/**
 * Two published locations with an unpublished one between them.
 *
 * @return array<string, mixed>
 */
function single_physician_sections_locations(): array
{
    return ['status' => [21 => 'publish', 22 => 'draft', 23 => 'publish'], 'titles' => [21 => 'Heart Clinic', 23 => 'Third Clinic'], 'location_fields' => [21 => ['location_address_1' => '1 Main St', 'location_city' => 'Little Rock', 'location_state' => 'AR', 'location_zip' => '72205'], 23 => ['location_address_1' => '3 Elm St', 'location_city' => 'Conway', 'location_state' => 'AR', 'location_zip' => '72032']]];
}

test('the locations section lists the published locations', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_locations' => [21, 22, 23]], single_physician_sections_locations()), 'locations'))
        ->toBe('<section class="uams-module location-list bg-auto" id="locations"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Locations Where Leonard McCoy Practices</span></h2><div class="card-list-container location-card-list-container"><div class="card-list"><div class="card"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="Heart Clinic" loading="lazy" /></picture><div class="card-body"><h3 class="card-title h5"><span class="name"><a href="https://example.test/p/21/" target="_self" data-categorytitle="Name" data-itemtitle="Heart Clinic">Heart Clinic</a></span><span class="subtitle"><span class="sr-only">,</span>Primary Location</span></h3><p class="card-text">1 Main St<br/>Little Rock, AR 72205</p></div><!-- .card-body --><div class="btn-container"><div class="inner-container"><a href="https://example.test/p/21/" class="btn btn-primary" aria-label="View location page for Heart Clinic" data-categorytitle="View Location" data-itemtitle="Heart Clinic">View Location</a></div></div></div><!-- .card --><div class="card"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" class="card-img-top" data-categorytitle="Photo" data-itemtitle="Third Clinic" loading="lazy" /></picture><div class="card-body"><h3 class="card-title h5"><span class="name"><a href="https://example.test/p/23/" target="_self" data-categorytitle="Name" data-itemtitle="Third Clinic">Third Clinic</a></span></h3><p class="card-text">3 Elm St<br/>Conway, AR 72032</p></div><!-- .card-body --><div class="btn-container"><div class="inner-container"><a href="https://example.test/p/23/" class="btn btn-primary" aria-label="View location page for Third Clinic" data-categorytitle="View Location" data-itemtitle="Third Clinic">View Location</a></div></div></div><!-- .card --></div></div></div></div></div></section>');
});

test('the structured data has an address for each published location', function (): void {
    preg_match('/"address":.*?\]/s', single_physician_sections_markup(['physician_locations' => [21, 22, 23]], single_physician_sections_locations()), $match);

    expect($match[0] ?? '')->toBe('"address": [ { "@type": "PostalAddress", "streetAddress": "1 Main St ", "addressLocality": "Little Rock", "addressRegion": "AR", "postalCode": "72205", "telephone": "" } , { "@type": "PostalAddress", "streetAddress": "3 Elm St ", "addressLocality": "Conway", "addressRegion": "AR", "postalCode": "72032", "telephone": "" } ]');
});

test('the ratings section with questions and more reviews than it shows', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_npi' => '123'], ['ratings' => single_physician_sections_rating()]), 'ratings'))
        ->toBe('<section class="uams-module ratings-and-reviews bg-auto" id="ratings"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title"><span class="title">Patient Ratings &amp; Reviews</span></h2><div class="card overall-ratings text-center"><div class="card-body"><h3 class="sr-only">Average Ratings</h3><dl><dt>Likelihood of recommending this provider</dt><dd><div class="rating" aria-label="Patient Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 90%;"></div></div><div class="ratings-score-lg">4.5<span class="sr-only">out of 5</span></div></div></dd><dt>Friendliness</dt><dd><div class="rating" aria-label="Patient Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg">4<span class="sr-only">out of 5</span></div></div></dd></dl></div><div class="card-footer bg-transparent text-muted small"><p class="h5">Overall: 4.5 out of 5</p><p>(40 Ratings, 8 Comments)</p></div></div><h3 class="sr-only">Individual Reviews</h3><div class="card-list-container"><div class="card-list"><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 1</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 01, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 2</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 02, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 3</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 03, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 4</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 04, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 5</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 05, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 6</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 06, 2026</div></div></div></div><div class="view-more text-center mt-8 mt-sm-10"><button class="btn btn-secondary" data-toggle="modal" data-target="#MoreReviews" aria-label="View more individual reviews">View More</button></div><!-- Modal --><div class="modal fade" id="MoreReviews" tabindex="-1" role="dialog" aria-labelledby="more-reviews-title" aria-modal="true"><div class="modal-dialog" role="document"><style>.modal-pagination { display: flex; justify-content: center; margin-top: 20px; } .modal-pagination button { margin: 5px; padding: 10px 20px; background-color: #f4f4f4; border: none; cursor: pointer; } .modal-pagination button.active, .modal-pagination button:hover { background-color: #ddd; } /* Loading spinner styles */ .loading-spinner { display: none; /* Initially hidden */ margin: 20px auto; width: 40px; height: 40px; border-radius: 50%; border: 3px solid rgba(0, 0, 0, 0.1); border-top-color: #09ad75; /* Color of the spinner */ animation: spin 1s ease-in-out infinite; } @keyframes spin { to { transform: rotate(360deg); } }</style><div class="modal-content"><div class="modal-header"><h5 class="modal-title" id="more-reviews-title">More Recent Reviews</h5><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body"><div id="comment-list" class="card-list" data-postid="5" data-npi="123" data-commentcount="8"><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 1</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 01, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 2</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 02, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 3</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 03, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 4</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 04, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 5</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 05, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 6</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 06, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 7</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 07, 2026</div></div><div class="card"><div class="card-header bg-transparent"><div class="rating rating-center" aria-label="Average Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 80%;"></div></div><div class="ratings-score-lg" itemprop="ratingValue">4<span class="sr-only">out of 5</span></div></div></div><div class="card-body"><h4 class="sr-only">Comment</h4><p class="card-text">Review 8</p></div><div class="card-footer bg-transparent text-muted small"><h4 class="sr-only">Date</h4>Mar 08, 2026</div></div></div><div class="loading-spinner"></div><!-- Loading spinner --><div class="modal-pagination" id="pagination-controls"><!-- Pagination buttons will be inserted here --></div><!-- </div>--></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div></div></div></div></div></div></div></section>');
});

test('the rating box', function (): void {
    preg_match('/<div class="rating" aria-label="Patient Rating">.*?<\/a><\/div>/', single_physician_sections_markup(['physician_npi' => '123'], ['ratings' => single_physician_sections_rating()]), $match);

    expect($match[0] ?? '')->toBe('<div class="rating" aria-label="Patient Rating"><div class="star-ratings-sprite"><div class="star-ratings-sprite-percentage" style="width: 90%;"></div></div><div class="ratings-score">4.5<span class="sr-only">out of 5</span></div><div class="w-100"></div><a href="#ratings" aria-label="Jump to Patient Ratings and Reviews" data-sectiontitle="Overview"><div class="ratings-count-lg" aria-hidden="true">40 Patient Satisfaction Ratings</div><div class="ratings-comments-lg" aria-hidden="true">8 comments</div></a></div>');
});

test('the structured data has the aggregate rating', function (): void {
    preg_match('/"aggregateRating".*?\}/s', single_physician_sections_markup(['physician_npi' => '123'], ['ratings' => single_physician_sections_rating()]), $match);

    expect($match[0] ?? '')->toBe('"aggregateRating": { "@type": "AggregateRating", "ratingValue": "4.5", "ratingCount": "40" , "reviewCount": "8" }');
});

test('the appointment section', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_locations' => [21], 'physician_eligible_appointments' => true], ['status' => [21 => 'publish'], 'titles' => [21 => 'Heart Clinic']]), 'appointment-info-1'))
        ->toBe('<section class="uams-module cta-bar cta-bar-1 bg-auto" id="appointment-info-1"><div class="container-fluid"><div class="row"><div class="col-xs-12"><h2>Make an Appointment With Leonard McCoy</h2><p>This provider is not currently accepting new patients.</p><p>Existing patients can make an appointment by<a href="https://example.test/p/21/" data-categorytitle="Make an Appointment | Block 1" data-typetitle="Contact the Clinic Directly | Direct Link | Heart Clinic">contacting the clinic&nbsp;directly</a>.</p></div></div></div></section>');
});

test('the related resources section is headed with the provider and lists the resources', function (): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_clinical_resources' => [61, 62, 63, 64, 65]], ['queries' => [[], [], [61, 62, 63, 64, 65]], 'titles' => [61 => 'R1', 62 => 'R2', 63 => 'R3', 64 => 'R4', 65 => 'R5']]), 'related-resources'))
        ->toBe('<section class="uams-module stacked-image-text bg-auto" id="related-resources" aria-labelledby="related-resources-title"><div class="container-fluid"><div class="row"><div class="col-12"><h2 class="module-title" id="related-resources-title"><span class="title">Resources Related to Leonard McCoy</span></h2></div><div class="col-12"><div class="card-list card-list-left"><div class="item"><div class="card"><div class="card-img-top"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" /></picture></div><div class="card-body"><h3 class="card-title h5">Title 5<span class="subtitle"><span class="sr-only">(</span><span class="sr-only">)</span></span></h3><p class="card-text">An excerpt.</p><a href="https://example.test/p/5/" class="btn btn-primary stretched-link" aria-label="View the Clinical Resource, Title 5" data-itemtitle="Title 5">View the Clinical Resource</a></div></div></div><div class="item"><div class="card"><div class="card-img-top"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" /></picture></div><div class="card-body"><h3 class="card-title h5">Title 5<span class="subtitle"><span class="sr-only">(</span><span class="sr-only">)</span></span></h3><p class="card-text">An excerpt.</p><a href="https://example.test/p/5/" class="btn btn-primary stretched-link" aria-label="View the Clinical Resource, Title 5" data-itemtitle="Title 5">View the Clinical Resource</a></div></div></div><div class="item"><div class="card"><div class="card-img-top"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" /></picture></div><div class="card-body"><h3 class="card-title h5">Title 5<span class="subtitle"><span class="sr-only">(</span><span class="sr-only">)</span></span></h3><p class="card-text">An excerpt.</p><a href="https://example.test/p/5/" class="btn btn-primary stretched-link" aria-label="View the Clinical Resource, Title 5" data-itemtitle="Title 5">View the Clinical Resource</a></div></div></div><div class="item"><div class="card"><div class="card-img-top"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" /></picture></div><div class="card-body"><h3 class="card-title h5">Title 5<span class="subtitle"><span class="sr-only">(</span><span class="sr-only">)</span></span></h3><p class="card-text">An excerpt.</p><a href="https://example.test/p/5/" class="btn btn-primary stretched-link" aria-label="View the Clinical Resource, Title 5" data-itemtitle="Title 5">View the Clinical Resource</a></div></div></div><div class="item"><div class="card"><div class="card-img-top"><picture><source srcset="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.svg" media="(min-width: 1px)"><img src="/wp-content/plugins/UAMSWP-Find-a-Doc/assets/svg/no-image_16-9.jpg" alt="" role="presentation" /></picture></div><div class="card-body"><h3 class="card-title h5">Title 5<span class="subtitle"><span class="sr-only">(</span><span class="sr-only">)</span></span></h3><p class="card-text">An excerpt.</p><a href="https://example.test/p/5/" class="btn btn-primary stretched-link" aria-label="View the Clinical Resource, Title 5" data-itemtitle="Title 5">View the Clinical Resource</a></div></div></div></div></div></div><!-- End .row --></div><!-- End .container-fluid --></section>');
});

test('an academic bio with any other academic content splits the section', function (string $field): void {
    expect(single_physician(['physician_academic_bio' => '<p>Academic.</p>', $field => [1]])['physician_academic_split'])->toBeTrue();
})->with(['physician_academic_appointment', 'physician_academic_admin_title', 'physician_education', 'physician_boards']);

test('more published resources than a page holds offer the rest', function (): void {
    // The more button names its link target with a helper the blocks file declares.
    require_once __DIR__.'/../Support/acf-blocks.php';
    uamswp_test_acf_blocks_file();

    $output = single_physician_section(single_physician_sections_markup(['physician_clinical_resources' => [61, 62, 63, 64, 65]], ['queries' => [[], [], [61, 62, 63, 64, 65]], 'status' => [61 => 'publish', 62 => 'publish', 63 => 'publish', 64 => 'publish', 65 => 'publish']]), 'related-resources');

    expect($output)->toContain('Want to find more resources related to Leonard McCoy?')
        ->and($output)->toContain('/clinical-resource/?_resource_provider=');
});

test('a provider whose locations are all unpublished has no locations section', function (): void {
    expect(single_physician_sections_markup(['physician_locations' => [22]], ['status' => [22 => 'draft']]))->not->toContain('id="locations"');
});

test('a successful rating response with too few ratings has no ratings section', function (): void {
    expect(single_physician_sections_markup([], ['ratings' => single_physician_rating('200', 29, 4)]))->not->toContain('id="ratings"');
});

test('the structured data carries the second address line of a location', function (): void {
    $options = single_physician_sections_locations();
    $options['location_fields'][21]['location_suite'] = '200';
    preg_match('/"address":.*?\]/s', single_physician_sections_markup(['physician_locations' => [21]], $options), $match);

    expect($match[0] ?? '')->toContain('"streetAddress": "1 Main St 200",')
        ->and($match[0] ?? '')->toStartWith('"address": [ { "@type": "PostalAddress"');
});

/**
 * A valid rating with the given questions, each with responses, and the given reviews.
 *
 * @param  list<string>  $questions
 * @param  list<object>  $reviews
 */
function single_physician_sections_questions(array $questions, array $reviews = [], int $comments = 4): object
{
    $rating = single_physician_rating('200', 40, $comments, 4.5);
    $rating->data->entities[0]->overallRating->questionRatings = array_map(static fn (string $name): object => (object) ['name' => $name, 'responseCount' => 5, 'value' => 4.0], $questions);
    $rating->data->entities[0]->comments = $reviews;

    return $rating;
}

test('five questions are all shown, the ones a recommendation repeats included', function (): void {
    $output = single_physician_section(single_physician_sections_markup([], ['ratings' => single_physician_sections_questions(['A', 'B', 'C', 'D', 'Would Recommend'])]), 'ratings');

    expect($output)->toContain('<dt>Would Recommend</dt>');
});

test('a rating with no comments says only the number of ratings', function (): void {
    expect(single_physician_section(single_physician_sections_markup([], ['ratings' => single_physician_sections_questions(['A'], [], 0)]), 'ratings'))->toContain('<p>(40 Ratings)</p>');
});

/**
 * Reviews numbered from 1, the given one with a date that does not parse.
 *
 * @return list<object>
 */
function single_physician_sections_reviews(int $count, int $undated = 0): array
{
    return array_map(static fn (int $n): object => (object) ['overallRating' => (object) ['value' => 4.0], 'comment' => 'Review '.$n, 'mentionTime' => $n === $undated ? 'not a date' : '2026-03-04'], range(1, $count));
}

test('a review whose date does not parse is dated the start of 1970', function (int $count, int $undated): void {
    expect(single_physician_section(single_physician_sections_markup(['physician_npi' => '123'], ['ratings' => single_physician_sections_questions(['A'], single_physician_sections_reviews($count, $undated))]), 'ratings'))->toContain('Jan 01, 1970');
})->with([
    'among the first six' => [1, 1],
    'in the window of the rest' => [8, 8],
]);

test('the window of more reviews opens only after six', function (int $count, bool $more): void {
    $output = single_physician_section(single_physician_sections_markup(['physician_npi' => '123'], ['ratings' => single_physician_sections_questions(['A'], single_physician_sections_reviews($count))]), 'ratings');

    expect(str_contains($output, 'id="MoreReviews"'))->toBe($more);
})->with([
    'six' => [6, false],
    'seven' => [7, true],
]);

test('the second appointment section follows a clinical section and is numbered 2', function (): void {
    $output = single_physician_sections_markup(['physician_locations' => [21], 'physician_eligible_appointments' => true, 'physician_clinical_bio' => '<p>Bio.</p>'], ['status' => [21 => 'publish'], 'titles' => [21 => 'Heart Clinic']]);

    expect($output)->toContain('id="appointment-info-2"')
        ->and(substr_count($output, 'id="appointment-info-'))->toBe(2);
});
