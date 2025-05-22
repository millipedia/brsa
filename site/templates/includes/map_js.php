<?php

$features = array();

foreach ($shop_locations as $shop_location) {

	$feature = array();
	$feature['type'] = 'Feature';

	$properties = array();
	$properties['title'] = $shop_location['title'];
	$properties['url'] = $shop_location['url'];
	$properties['town'] = $shop_location['town'];
	if (isset($shop_location['closed'])) {
		$properties['closed'] = $shop_location['closed'];
	}else{
		$properties['closed'] = 0;
	}
	$feature['properties'] = $properties;
	$feature['geometry'] = ['type' => 'Point', 'coordinates' => [$shop_location['lng'], $shop_location['lat']]];

	$features[] = $feature;
}


$collection = array(
	'type' => 'FeatureCollection',
	'features' => $features,
);

$shop_json = json_encode($collection);


?>

<script nonce="<?= $mu->nonce ?>" type="text/javascript">
	var scUser = {}; //container object for variables we use in a few places

	const maxbounds = [
		[-20, 42], // Southwest coordinates
		[10, 64] // Northeast coordinates
	];

	var map = new maplibregl.Map({
		container: 'map',
		style: 'https://tiles.stadiamaps.com/styles/osm_bright.json', // Style URL; see our documentation for more options/ Style URL; see our documentation for more options
		attributionControl: false,
		center: [<?= $lng ?>, <?= $lat ?>], // Initial focus coordinate
		zoom: 6,
		maxBounds: maxbounds
	});

	map.addControl(new maplibregl.AttributionControl({
		compact: true,
		customAttribution: '<a href="https://millipedia.com/">millipedia</a>'
	}));

	// Simulate click on the attribution icon to keep it closed on launch.

	var ac = document.querySelector(".maplibregl-ctrl-attrib-button");
	const aclickEvent = new MouseEvent('click', {
		bubbles: true,
		cancelable: true,
		view: window
	});
	ac.dispatchEvent(aclickEvent);
	// console.log(ac);


	// var map_search = new maplibreSearchBox.MapLibreSearchControl({
	//     useMapFocusPoint: true,
	// 	layers: ['locality'],
	//     onResultSelected: feature => {
	//       // You can add code here to take some action when a result is selected.
	//       console.log(feature.geometry.coordinates);
	//     },
	//     // You can also use our EU endpoint to keep traffic within the EU using the basePath option:
	//     // baseUrl: "https://api-eu.stadiamaps.com",
	//   });
	//   map.addControl(map_search, "top-left");

	// // Add zoom and rotation controls to the map.
	// map.addControl(new maplibregl.NavigationControl());

	const shop_json = <?= $shop_json ?>;

	map.on('load', () => {
		map.addSource('shops', {
			type: 'geojson',
			data: shop_json,
			cluster: true,
			clusterMaxZoom: 14, // Max zoom to cluster points on
			clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
		});

		map.addLayer({
			id: 'clusters',
			type: 'circle',
			source: 'shops',
			filter: ['has', 'point_count'],
			paint: {
				// Use step expressions (https://maplibre.org/maplibre-style-spec/#expressions-step)
				// with three steps to implement three types of circles:
				//   * Blue, 20px circles when point count is less than 100
				//   * Yellow, 30px circles when point count is between 100 and 750
				//   * Pink, 40px circles when point count is greater than or equal to 750
				'circle-color': [
					'step',
					['get', 'point_count'],
					'hsla(0, 57%, 54%, 0.5)',
					30,
					'hsla(0, 57%, 54%, 0.7)',
					50,
					'hsla(0, 57%, 54%, 0.9)'
				],
				'circle-radius': [
					'step',
					['get', 'point_count'],
					20,
					100,
					30,
					750,
					40
				]
			}
		});

		map.addLayer({
			id: 'cluster-count',
			type: 'symbol',
			source: 'shops',
			filter: ['has', 'point_count'],
			layout: {
				'text-field': '{point_count_abbreviated}',
				'text-size': 16,
				'text-font': ['Stadia Bold'],
			},
			paint: {
				'text-color': 'rgb(45,45,45)'
			}
		});

		map.addLayer({
			id: 'unclustered-point',
			type: 'circle',
			source: 'shops',
			filter: ['!', ['has', 'point_count']],
			paint: {
				'circle-color': 'red',
				'circle-radius': 4,
				'circle-stroke-width': 7,
				'circle-stroke-color': 'rgb(45,45,45)'
			}
		});

		// inspect a cluster on click
		map.on('click', 'clusters', async (e) => {
			const features = map.queryRenderedFeatures(e.point, {
				layers: ['clusters']
			});
			const clusterId = features[0].properties.cluster_id;
			const zoom = await map.getSource('shops').getClusterExpansionZoom(clusterId);
			map.easeTo({
				center: features[0].geometry.coordinates,
				zoom
			});
		});

		// When a click event occurs on a feature in
		// the unclustered-point layer, open a popup at
		// the location of the feature, with
		// description HTML from its properties.
		map.on('click', 'unclustered-point', (e) => {
			const coordinates = e.features[0].geometry.coordinates.slice();
			const title = e.features[0].properties.title;
			const shop_url = e.features[0].properties.url;
			const town = e.features[0].properties.town;
			const shop_closed = e.features[0].properties.closed;
			// Ensure that if the map is zoomed out such that
			// multiple copies of the feature are visible, the
			// popup appears over the copy being pointed to.
			while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
				coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
			}

			let pu_content = '<div class="pu">';
			pu_content += '<div class=pu_title>' + title + '</div>';
			pu_content += '<div class=pu_town>' + town + '</div>';
			if (shop_url !== '') {
				pu_content += '<div class=pu_url><a href="' + shop_url + '">View shop details</a></div>';
			}
			if (shop_closed > 0) {
				pu_content += '<div class=pu_closed>Closed ' + shop_closed + '</div>';
			}
			pu_content += '</div>';

			new maplibregl.Popup()
				.setLngLat(coordinates)
				.setHTML(pu_content)
				.addTo(map);
		});

		map.on('mouseenter', 'clusters', () => {
			map.getCanvas().style.cursor = 'pointer';
		});
		map.on('mouseleave', 'clusters', () => {
			map.getCanvas().style.cursor = '';
		});


		//if we have a latlng get parameter then zoom mostly to that
		const queryString = window.location.search;

		var map_focus;
		if (queryString) {

			const urlParams = new URLSearchParams(queryString);

			if (urlParams.size) {

				let map_focus_point_string = urlParams.get('lnglat');

				if (map_focus_point_string) {

					let map_focus_point = map_focus_point_string.split(',');

					try {
						map_focus = new maplibregl.LngLat(parseFloat(map_focus_point[0]), parseFloat(map_focus_point[1]));
					} catch (err) {
						console.log('invalid laltntn');
					}

				}


			}
		}



		if (map_focus !== undefined) {
			console.log("got a point");
			map.flyTo({
				center: map_focus,
				zoom: 13
			});
		} else { // fit map to our bounds.
			

			// get our bounds.
			var bounds = new maplibregl.LngLatBounds();
			shop_json.features.forEach(function(feature) {
				bounds.extend(feature.geometry.coordinates);
			});

			map.fitBounds(bounds, {
				padding: 20,
				maxZoom: 10 // Set the maximum zoom level
			});

		}


		

	});
</script>

<?php 

// if this is the main map page then add the script for our town lookup.
if($page->template=='map'){
	?>
<script nonce="<?= $mu->nonce ?>">
	/**
	 * Typeahead for looking up towns so we can zoom to them. 
	 */


	// Initialize the Stadia Maps API using the global stadiaMapsApi object
	const smaconfig = new stadiaMapsApi.Configuration();
	const api = new stadiaMapsApi.GeocodingApi(smaconfig);

	// Get the input and datalist elements
	const addressInput = document.getElementById('address');
	const addressSuggestions = document.getElementById('addressSuggestions');


	// we set maxbounds previously as the er maxbounds for the map.
	const bounds = {
		southwest: {
			lat: maxbounds[0][1],
			lng: maxbounds[0][0]
		},
		northeast: {
			lat: maxbounds[1][1],
			lng: maxbounds[1][0]
		}
	};


	// Function to update the datalist with suggestions
	async function updateSuggestions(inputValue) {
		if (inputValue.length < 3) {
			// Clear suggestions if input is less than 3 characters
			addressSuggestions.innerHTML = '';
			return;
		}

		try {

			const res = await api.autocomplete({
				text: inputValue,
				bounds: bounds,
				layers: ['coarse']
			});

			const options = res.features.map(feature => {

				if (feature.properties.country_code == "GB") {
					let locstring = feature.properties.name + ' - ' + feature.properties.county;
					return `<option value="${locstring}" data-coords="${feature.geometry.coordinates}">${locstring}</option>`;
				} else {
					return;
				}


			}).join('');

			addressSuggestions.innerHTML = options;

		} catch (error) {

			console.error('Error fetching suggestions:', error);
		}
	}

	// Add an input event listener to the address input
	addressInput.addEventListener('input', () => {

		// Get the current value of the address input
		const inputValue = addressInput.value;

		// See if it matches an option.
		const selectedOption = Array.from(addressSuggestions.children).find(option => option.value === inputValue);

		// if it does match (probably becasue we selected it)
		// then grab the coordinates, cape and fly!
		if (selectedOption) {
			const coords = selectedOption.getAttribute('data-coords');
			const lnglat = coords.split(",");
			console.log('Selected coordinates:', coords);

			map.flyTo({
				center: lnglat,
				zoom: 12
			});

		} else { // get more suggestions.

			updateSuggestions(addressInput.value);
		}



	});
</script>

<?php
}// close map page conditional
?>