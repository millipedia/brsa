<?php

namespace ProcessWire;

/**
 * CAn we do something fancy with matching maps to addresses?
 * 
 */

if (!$user->isLoggedin()) {

	die("Need to be logged in.");
}



?>
<div id="millco_main">

<p>This is just a page for me to experiment with some experiments.</p>

<p>Current (all pages) takes 25 seconds.</p>
<?php

// this is the current way:
$shops = wire('pages')->find("template=shop,limit=10000");

	$shop_locations = array();

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

 // Access the ProcessWire database object
 $db = wire('database');

 // 1. Querying the database directly using the $db object.
 try {
	 // Prepare the SQL query
	 $query = $db->prepare("SELECT * FROM pages WHERE templates_id = :templates_id");

	 // Bind the parameter
	 $query->bindParam(':templates_id', $templates_id, \PDO::PARAM_INT);

	 // Set the value for templates_id (53 in this case)
	 $templates_id = 53;

	 // Execute the query
	 $query->execute();

	 // Fetch the results as an associative array
	 $shops = $query->fetchAll(\PDO::FETCH_ASSOC);

 } catch (\PDOException $e) {
	 echo "Error: " . $e->getMessage();
 }
	
 	$names=array();

	foreach($shops as $shop){
		$name="for-page-{$shop['id']}";
		$names[]=$name;
	}

	print_r($names);

	print_r($shop_locations);

?>
</div>