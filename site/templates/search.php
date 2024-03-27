<?php namespace ProcessWire;

/**
 * Search results page.
 */


$post_list='';
$filters='';
$results_markup='';

function search_results_list($title,$matches){

	$results_list_markup='';

	$results_list_markup.= '<div class="search-results-list">';
	$results_list_markup.= '<div class="sr-title mt-2 mb-2">' . $title .'</div>';
			foreach($matches as $match){
				$results_list_markup.= '<a class="search_result" href="' . $match->url . '">';
				$results_list_markup.= '<div class="sr_title">' . $match->title . '</div>';
				if($match->county){
					$results_list_markup.= '<div class="sr_county">' . $match->county->title . '</div>';
				}
				$results_list_markup.= '</a>';

			}

	$results_list_markup.= '</div>';

	return $results_list_markup;
}


?>
<div id="content_main">
	<div class="text-measure">
                    
		<?php

		// taken from Ryans search.php template file
		// See README.txt for more information. 

		// look for a GET variable named 'q' and sanitize it
		$q = $sanitizer->text($input->get->q); 

		// did $q have anything in it?
		if($q) { 
			// Send our sanitized query 'q' variable to the whitelist where it will be
			// picked up and echoed in the search box by _main.php file. Now we could just use
			// another variable initialized in _init.php for this, but it's a best practice
			// to use this whitelist since it can be read by other modules. That becomes 
			// valuable when it comes to things like pagination. 
			$input->whitelist('q', $q); 

			// Sanitize for placement within a selector string. This is important for any 
			// values that you plan to bundle in a selector string like we are doing here.
			$q = $sanitizer->selectorValue($q); 

			// Search the title and body fields for our query text.
			// Limit the results to 50 pages. 



			// lets do a separate search for regions first
			$selector = "template=county,title~*=$q, limit=20";
			if($user->isLoggedin()) $selector .= ", has_parent!=2"; 
			$matches = $pages->find($selector); 

			// did we find any matches?
			if($matches->count) {

				$title = "Found $matches->count regions matching your query: <em>$q</em>";
				$results_markup.=search_results_list($title,$matches);

			}

			// hell and towns
			$selector = "template=town,title~*=$q, limit=20";
			if($user->isLoggedin()) $selector .= ", has_parent!=2"; 
			$matches = $pages->find($selector); 

			// did we find any matches?
			if($matches->count) {

				$title = "Found $matches->count towns matching your query: <em>$q</em>";
				$results_markup.=search_results_list($title,$matches);

			}


			
			// this is the default search query
			// let's do shops separately now.
			$selector = "template=shop,title|comments|content~*=$q, limit=80";
			
			// this is me tinkering 
			//	$selector = "title|content*+=$q, limit=50"; 

			// If user has access to admin pages, lets exclude them from the search results.
			// Note that 2 is the ID of the admin page, so this excludes all results that have
			// that page as one of the parents/ancestors. This isn't necessary if the user 
			// doesn't have access to view admin pages. So it's not technically necessary to
			// have this here, but we thought it might be a good way to introduce has_parent.
			if($user->isLoggedin()) $selector .= ", has_parent!=2"; 

			// Find pages that match the selector
			$matches = $pages->find($selector); 

			// did we find any matches?
			if($matches->count) {

				// the expanded search essentially brings back everything
				// but often with a low score. Let's prune
				// anything with a score below 500.

				// foreach($matches as $match){
				// 	if($match->_pfscore < 500){
				// 		$matches->remove($match);
				// 	}
				// }

				if($matches->count){

					// Cool
					$title = "Found $matches->count shops matching your query: <em>$q</em>";

					$results_markup.=search_results_list($title,$matches);


				}else {
					// we didn't find any
					$results_markup.= "Sorry, no matching shops were found.";
				}

			} else {
					// we didn't find any
					$results_markup.= "Sorry, no matching shops were found.";
			}

		} else {
			// no search terms provided
			$results_markup.= "Please enter a search term.";
		}

		?>


	<?php
	
	echo $results_markup;

	?>

</div>
</div>