<?php

namespace ProcessWire;

// Keep the locations in cache cos they're expensive to build.

// destroy the cache if were admin and have hit the big red button.
if ($input->get('rebuild') && $input->get('rebuild') == 1 && $user->isLoggedIn()) {
	$cache->delete('shop_locations');
	$session->redirect($page->url);
}

$shop_locations = $cache->get('shop_locations', 2592000, function () { // month long cache

	// this is called if cache expired or does not exist,
	// so generate a new cache value here and return it

	// // TODO: This is slow. I think we need to pull this from the db directly ... but not sure how.
	// $shops = wire('pages')->find("template=shop,limit=10000");

	// $shop_locations = array();

	// foreach ($shops as $shop) {

	// 	if ($shop->addresses->count) {

	// 		foreach ($shop->addresses as $address) {

	// 			if ($address->location) {

	// 				$shop_location = array();
	// 				$shop_location['title'] = $shop->title;

	// 				// must do a function somewhere (init) for this.
	// 				$location = explode(',', $address->location);
	// 				$lat = $location[0];
	// 				$lng = $location[1];

	// 				$shop_location['lat'] = $lat;
	// 				$shop_location['lng'] = $lng;

	// 				$shop_location['url'] = $shop->url;
	// 				$shop_location['town'] = $address->town->title;

	// 				$shop_locations[] = $shop_location;
	// 			}
	// 		}
	// 	}
	// }


	// Ensure PagePaths module is installed so that URL is available to findRaw()
	$shop_data = wire('pages')->findRaw("template=shop", ['title', 'url', 'addresses'], ['nulls' => true, 'flat' => true]);

	// Load all our addresses that have locations
	$location_data = wire('pages')->findRaw("template=repeater_addresses,location!=null, check_access=0", ['location','town.title'], ['nulls' => true, 'flat' => true]);

	$shop_locations = [];

	// Loop over the shop data and see if we have the lat/lng for each location, matching by repeater page ID

	foreach($shop_data as $id => $item) {

		$add_to_shops=0;
		
		// do we have locations for this shop?
		$location_ids = explode(',', $item['addresses.data']);

		foreach($location_ids as $location_id) {

			if(!isset($location_data[$location_id])) continue; // just double check,

			$shop_location = array();
			$shop_location['title'] = $item['title'];

			// must do a function somewhere (init) for this.
			$location_string=$location_data[$location_id]['location'];
			$location = explode(',', $location_string);
			$lat = $location[0];
			$lng = $location[1];

			$shop_location['lat'] = $lat;
			$shop_location['lng'] = $lng;

			$shop_location['url'] = $item['url'];

			$town_array=$location_data[$location_id]['town.title'];

			if(is_array($town_array)) {
				$town=reset($town_array); // get the valie of the first item in this named array.

			}else{
 $town='';
			}
			

			$shop_location['town'] = $town;

			// push this to our shops locations.
			$shop_locations[] = $shop_location;

		}

	}




	return $shop_locations;
});


?>

<div id="millco_layout" class="millco_layout container">
	<div id="content_main" class="">

		<h1><?= $page->title ?></h1>

		<div class="text-measure">
			<p>We're working through adding location data for all of the shops. There's still a way to go, but here's <strong><?= count($shop_locations) ?></strong> of them.
			</div>

		<?php
		if ($user->isLoggedIn()) {
			echo '<a class="butt" href="' . $page->url . '?rebuild=1">Rebuild marker cache</a>';
		}
		?>

		<div class="map_typeahead">
			<label for="address">Start typing to select a town :</label>
			<input type="text" id="address" list="addressSuggestions" />
			<datalist id="addressSuggestions"></datalist>
		</div>
		<div id="map" class="map"></div>
		<?php

		$location = explode(',', $page->location);
		$lat = $location[0];
		$lng = $location[1];


		include('includes/map_js.php');

		?>
	</div>
</div>