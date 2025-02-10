<?php

namespace ProcessWire;

// Keep the locations in cache cos they're expensive to build.

// destroy the cache if were admin and have hit the big red button.
if($input->get('rebuild') && $input->get('rebuild')==1 && $user->isLoggedIn()){

	$cache->delete('shop_locations');
	$session->redirect($page->url);
}

$shop_locations = $cache->get('shop_locations', 2592000, function () {

	// this is called if cache expired or does not exist,
	// so generate a new cache value here and return it

	$shops = wire('pages')->find("template=shop,limit=10000");
	
	$shop_locations = array();

	foreach ($shops as $shop) {

		if ($shop->addresses->count) {

			foreach ($shop->addresses as $address) {

				if ($address->location) {

					$shop_location = array();
					$shop_location['title'] = $shop->title;

					// must do a function somewhere (init) for this.
					$location = explode(',', $address->location);
					$lat = $location[0];
					$lng = $location[1];

					$shop_location['lat'] = $lat;
					$shop_location['lng'] = $lng;

					$shop_location['url'] = $shop->url;
					$shop_location['town'] = $address->town->title;

					$shop_locations[] = $shop_location;
				}
			}
		}
	}
	return $shop_locations;
});


?>

<div id="millco_layout" class="millco_layout container">
	<div id="content_main" class="">

		<h1><?= $page->title ?></h1>

		<div class="text-measure">
			<p>We're working through adding location data for all of the shops. There's still a way to go, but here's <strong><?=count($shop_locations)?></strong> of them.</div>

			<?php
				if($user->isLoggedIn()){
					echo '<a class="butt" href="' . $page->url .'?rebuild=1">Rebuild marker cache</a>';
				}
			?>


		<div id="map" class="map"></div>
		<?php

		$location = explode(',', $page->location);
		$lat = $location[0];
		$lng = $location[1];

		
		include('includes/map_js.php');

		?>
	</div>
</div>