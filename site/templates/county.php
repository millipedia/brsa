<?php

namespace ProcessWire;

/**
 * County
 * 
 */

?>

<div id="millco_main">
	<?php


	echo '<div class="shop_meta_item shop_town_county mb-1">';

	echo '<a href="/by-region/">All Regions</a>';
	echo ' / ';

	echo '<a href="' . $page->url . '">' . $page->title . '</a>';
	echo '</div>';

	echo '<div class="town_tags">';

	// do we have these buttons in cache
	$butts_cache_name = $page->name . '_town_butts';
	if (!$buttlist = $cache->get($butts_cache_name)) {

		$buttlist = '';
		foreach ($page->children() as $town) {
			$buttlist .= '<a href="' . $town->url . '" class="tag">' . $town->title . '</a>';
		}
		$cache->save($butts_cache_name,$buttlist,$page);

	}	
	echo $buttlist;

	echo '</div>';


	$shops_in_county = $pages->find("addresses.county={$page},sort=title");

	echo '<h3 class="pb-0 mb-0">Shops</h3>';

	echo shop_in_list($shops_in_county, 4, 'county');

	if ($page->content) {
		echo '<div class="county_content text-measure">' . $page->content . '</div>';
	}

	?>

</div>