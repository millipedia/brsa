<?php namespace ProcessWire;

/**
 * 
 * A panel for the dashboard.
 * 
 */

 // missing towns.
 $fixins=$pages->find("template=shop,addresses.town=''");

?>
<p>Next challenge is adding any shops that the All Good Records site has. I've scraped their list into a Google Docs spreadsheet here: <a href="https://docs.google.com/spreadsheets/d/1wMkp0GwgRnpzWuHiM_tsVC5FMf5cM9fxMoFUuqx1CYY/edit?usp=sharing">https://docs.google.com/spreadsheets/d/1wMkp0GwgRnpzWuHiM_tsVC5FMf5cM9fxMoFUuqx1CYY/edit?usp=sharing</a>.</p>

<p>I might be able to do some limited importing of shops at some point, but you might as well get stuck in.</p>

<?php

		// //log_id	user_key	activity_key	start_time	duration
		// $select="SELECT COUNT(pages_id) AS upcount FROM `field_content` WHERE (`data` LIKE '%wsite-multicol-table%')";

        // $query = $this->database->prepare($select);
		// $query->execute();

		// if($row = $query->fetch()){

        //     echo '<p>We have <strong>' . $row['upcount'] .'</strong> to do - heres some of them:</p>';
        // }

		// //log_id	user_key	activity_key	start_time	duration
		// $select="SELECT * FROM `field_content` WHERE (`data` LIKE '%wsite-multicol-table%') LIMIT 20";

		

		// $query = $this->database->prepare($select);
		// $query->execute();
		// $result = $query->fetchAll();


		$shops=$pages->find("template=shop,addresses.location='', need_more_info='',include=all");

		echo '<p>There are <strong>' . $shops->count .'</strong> shops with missing locations... here\'s a few of them that have no location at all.:</p>'; 

		$tick=0;

		foreach($shops as $shop){

			$no_loci=1;
			// loop through all the addresses
			foreach($shop->addresses as $sadd){

				if($sadd->location){
					$no_loci=0;
					break;
				}
			}

			if($no_loci){
				echo '<a class="PageEdit" href="' . $shop->editUrl .'">' . $shop->title .'</a><br>';
				$tick++;
			}else{
				// echo $shop->title . ' has at least one address';
			}

			if($tick>25){
				break;
			}
		


	}