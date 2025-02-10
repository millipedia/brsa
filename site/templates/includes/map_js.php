<?php

$features = array();

		foreach ($shop_locations as $shop_location) {

			$feature = array();
			$feature['type'] = 'Feature';

			$properties = array();
			$properties['title'] = $shop_location['title'];
			$properties['url'] = $shop_location['url'];
			$properties['town'] = $shop_location['town'];

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
		[36.48762981596457, -37.628866380125615], // Southwest coordinates
		[63.760676210065974, 10.623081895396105] // Northeast coordinates
	];

	var map = new maplibregl.Map({
		container: 'map',
		style: 'https://tiles.stadiamaps.com/styles/osm_bright.json', // Style URL; see our documentation for more options/ Style URL; see our documentation for more options
		attributionControl: false,
		center: [<?= $lng ?>, <?= $lat ?>], // Initial focus coordinate
		zoom: 5
	});

	map.addControl(new maplibregl.AttributionControl({
		compact: true,
		customAttribution: '<a href="https://millipedia.com/">millipedia</a>'
	}));

	// Simulate click on the attribution icon to keep it closed on launch.
	
	var ac = document.querySelector(".maplibregl-ctrl-attrib-button");
	const aclickEvent = new MouseEvent('click', { bubbles: true, cancelable: true, view: window });
	ac.dispatchEvent(aclickEvent);
	console.log(ac);

	// Add zoom and rotation controls to the map.
	map.addControl(new maplibregl.NavigationControl());

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

			// Ensure that if the map is zoomed out such that
			// multiple copies of the feature are visible, the
			// popup appears over the copy being pointed to.
			while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
				coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
			}

			let pu_content = '<div class="pu">';
			pu_content += '<div class=pu_title>' + title + '</div>';
			pu_content += '<div class=pu_town>' + town + '</div>';
			if(shop_url!==''){
				pu_content += '<div class=pu_url><a href="' + shop_url + '">View shop details</a></div>';
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

		// get our bounds.
		var bounds = new maplibregl.LngLatBounds();
		shop_json.features.forEach(function(feature) {
			bounds.extend(feature.geometry.coordinates);
		});

		map.fitBounds(bounds, {
			padding: 20,
	        maxZoom: 10 // Set the maximum zoom level
    	});

	});
</script>