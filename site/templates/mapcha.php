<?php

namespace ProcessWire;

/**
 * CAn we do something fancy with matching maps to addresses?
 * 
 */

if (!$user->isLoggedin()) {

	die("Need to be logged in.");
}

$feedback='';

// Did we get an address

bd($input);

if($input->get("ana") && $input->get("ana")> 1 ){

	$ana=(int)$input->get("ana");
	$address_to_update=$pages->get($ana);

	if($address_to_update && $address_to_update->id){

		$address_to_update->of(false);
		$latlng=$input->get("latin") . ',' . $input->get("lngin");

		if($latlng!==','){
			$address_to_update->setAndSave('location', $latlng);

			$feedback.='Updated address id ' . $address_to_update->id;
	
			$erp = str_replace('for-page-', '', $address_to_update->parent->name);
			$shop_page = $pages->get($erp);
	
			$feedback .=' which belongs to <a href="' . $shop_page->url . '">' . $shop_page->title . '</a> ';

		}else{
			$feedback.='didn\'t get a sensible lat/lng';
		}

		
	}
}




?>
<div id="millco_main">


<style nonce="<?=$mu->nonce?>">

	.feedback{
		font-size: 14px;
		background-color: var(--neutral-b);
		border-bottom: 1px solid #ccc;
	}

	.booo{
		color: red;
	}

	.hooray{
		color: green;
	}

</style>


<div class="feedback"><?=$feedback?></div>

	<p>Pulls a random address from the list if it has something in the address field but no location. You'll need to check the pin really matches the location cos sometimes it just falls back to the town.</p>

	<?php

	$an_address = $pages->findOne("template=repeater_addresses,shop_address!='',location='',include=all,sort=random");

	// bet there's a more sensible way ... but hey, it works.
	$erp = str_replace('for-page-', '', $an_address->parent->name);
	$shop_page = $pages->get($erp);
	if ($shop_page && $shop_page->id) {
		
		echo '<a href="' . $shop_page->url . '">' . $shop_page->title . '</a> ';

	} else {
		echo 'oooh ... dunno what shop this belongs to, thats not good.';
	}

	$search_text = $an_address->shop_address . ', ' . $an_address->town->title . ', ' . $an_address->county->title;
	$search_text = str_replace(PHP_EOL, "", $search_text); 

	echo "Looking up: {$search_text}";
	?>
	<div id="latlng" .... beep .. boop...></div>
	<br>
		
	<div id="map" class="shop_map"></div>

	<div class="butts">
	
<form>
	<input id="ana" name="ana" type="hidden" value="0">
	<input id="latin" name="latin" type="float" value=0>
	<input id="lngin" name="lngin" type="float" value=0>
	<button id="mubmit" class="butt" type="submit" disabled>Hmm...</button>
</form>


<a class="butt" href="<?=$shop_page->url?>">Edit shop</a>


<a class="butt" href="<?=$page->url?>">Next!</a>


	</div>


	<script nonce="<?= $mu->nonce ?>">


		ana.value=<?=$an_address->id?>;


		var map = new maplibregl.Map({
			container: 'map',
			style: 'https://tiles.stadiamaps.com/styles/osm_bright.json', 
			center: [0, 51], // Initial focus coordinate
			zoom: 4
		});

		// Add zoom and rotation controls to the map.
		map.addControl(new maplibregl.NavigationControl());

		// Initialize the Stadia Maps API
		const smaconfig = new stadiaMapsApi.Configuration();
		const smaapi = new stadiaMapsApi.GeocodingApi(smaconfig);

		// Function to perform the search and handle the results
		async function snurp(inputValue) {
			try {
				const res = await smaapi.search({ // **1. Add `await`**
					text: inputValue,
					boundary: {
						country: 'gbr'
					}
				});

				// console.log("stadia response", res);

				if (res.features && res.features.length > 0) { // **2. Check if results exist**
					const feature = res.features[0]; // **3. Access the first result**

					console.log(feature.properties.countryCode);

					if (feature.properties.countryCode === 'GB') { // Double check: only include GB results
						const latitude = feature.geometry.coordinates[1];
						const longitude = feature.geometry.coordinates[0];


						latlng.innerHTML = `<span>Got: ${feature.properties.label} (${latitude.toFixed(6)}, ${longitude.toFixed(6)})</span>`; // display lat/lng
						latlng.classList.add('hooray');


						mupdate(latitude.toFixed(6), longitude.toFixed(6));

					} else {
						latlng.innerHTML = 'Natch no results';
						latlng.classList.add('booo');
					}


				} else {
					latlng.classList.add('booo');
					latlng.innerHTML = '<p>No results found.</p>';
				}

			} catch (error) {
				latlng.classList.add('booo');
				console.error('Error during search:', error);
				latlng.innerHTML = '<p>Error during search.</p>';
			}
		}

		// run our search based on the address we got from processwire.
		snurp("<?= $search_text ?>");

		function mupdate(lat, lng) {

			latin.value=lat;
			lngin.value=lng;

			mubmit.innerHTML='looks good to me';
			mubmit.disabled=false;


			// Since these are HTML markers, we create a DOM element first, which we will later
			// pass to the Marker constructor.
			var elem = document.createElement('div');
			elem.className = 'marker';

			var marker = new maplibregl.Marker({
				element: elem,
				draggable: true
			});

			marker.setLngLat([lng, lat]);
			marker.on('dragend', onDragEnd);

			//

			// // You can also create a popup that gets shown when you click on a marker. You can style this using
			// // CSS as well if you so desire. A minimal example is shown. The offset will depend on the height of your image.
			// var popup = new maplibregl.Popup({ offset: 24, closeButton: false });
			// popup.setHTML('<div>' + point.properties.title + '</div>');

			// // Set the marker's popup.
			// marker.setPopup(popup);

			// Finally, we add the marker to the map.
			marker.addTo(map);
			map.flyTo({
				center: [lng, lat],
				zoom: 14
			});


			

		}




	function onDragEnd() {

		let mid=this._element.dataset.mid;
		const lngLat = this.getLngLat();
		let lat=lngLat.lat;
		let lng=lngLat.lng;

		latin.value=lat;
		lngin.value=lng;
	}



	</script>




</div>