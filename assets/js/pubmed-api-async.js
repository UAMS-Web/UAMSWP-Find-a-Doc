$(document).ready(function(){

	$('.pubmed-list').each(function(){

		var wrapper = $(this);

		var terms = $(this).attr('data-terms');

		var count = $(this).attr('data-count');

		searchPubMed(terms, wrapper, count);

	});

});

function searchPubMed(terms, wrapper, count) {

	var request = $.ajax({
		url: 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=' + terms + '&retmode=json&retmax=' + count,
		async: true
	});

	request.done(function(data){
		var ids = data.esearchresult.idlist;
		var publications = [];
		iterateJSON(ids, publications, wrapper);
	});

}

function iterateJSON(idlist, publications, wrapper) {

	var list = wrapper;
	var id = idlist.shift();

	var request = $.ajax({
		url: 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id='+id+'&retmode=json',
		async: true
	});

	request.done(function(summary){

		/* start the publication */
		var publication = '<li id="'+id+'" class="pubmed-item">';

		/* get the authors and list them */
		for ( author in summary.result[id].authors ) {

			var author_name = summary.result[id].authors[author].name;
			var author_last = summary.result[id].lastauthor;

			if ( typeof author_name != 'undefined' ) {

				publication += '<span class="pubmed-authors">' + author_name +'</span>';

				if ( author_name != author_last ) {
					publication += ', ';
				} else {
					publication += '. ';
				}

			} else {
				publication += '[[[[[[ooops]]]]]]';
			}
		}

		/* add title */
		if ( summary.result[id].title ) {
			publication += '<span class="pubmed-title">'+summary.result[id].title+'</span> ';
		}

		/* add journalname */
		if ( summary.result[id].fulljournalname ) {
			publication += '<em><span class="pubmed-fulljournalname">'+summary.result[id].fulljournalname+'</span></em>. ';
		}

		/* add pubdate */
		if ( summary.result[id].pubdate ) {
			publication += '<span class="pubmed-pubdate">' + summary.result[id].pubdate + '</span>; ';
		}

		/* add volume */
		if ( summary.result[id].volume ) {
			publication += '<span class="pubmed-volume">' + summary.result[id].volume + '</span>';
		}

		/* add issue */
		if ( summary.result[id].issue ) {
			publication += '<span class="pubmed-issue">(' + summary.result[id].issue + ')</span>:';
		}

		/* add pages */
		if ( summary.result[id].issue ) {
			publication += '<span class="pubmed-pages">' + summary.result[id].pages + '</span>.';
		}

		//The date needs to be followed by the journal volume, issue and page numbers, punctuated as follows "2014 Nov 24;31(4):448–60."

		/* add elocation ID */
		if ( summary.result[id].elocationid ) {
			publication += ' <span class="pubmed-elocationid">' + summary.result[id].elocationid + '</span>. ';
		}

		/* add pubmend ID */
		if ( summary.result[id].uid ) {
			publication += '<span class="pubmed-uid">PMID: ' + summary.result[id].uid + '</span>. ';
		}

		/* add link to pubmed */
		if ( summary.result[id].uid ) {
			publication += '<span class="pubmed-pubmed-link">[<a href="http://www.ncbi.nlm.nih.gov/pubmed/' + summary.result[id].uid + '">Pubmed</a>]</span> ';
		}


		/* close the publication */
		publication += '</ul>';

		list.append(publication);

		if(idlist.length!=0){
			iterateJSON(idlist, publications, wrapper);
		}

	});
}