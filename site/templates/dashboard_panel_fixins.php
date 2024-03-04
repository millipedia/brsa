<?php namespace ProcessWire;

/**
 * 
 * A panel for the dashboard.
 * 
 */

 // missing towns.
 $fixins=$pages->find("template=shop,addresses.town=''");

?>
<p>Good effort team. But we have <strong><?=$fixins->count()?></strong> shops with no <em>Town</em> set. That might take a bit longer.</p>
<p>Here's a few of them. If we can't find a town then we now have a checkbox 'Needs more info' for when we have time to do a deep dive.</p>

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


		$fixins=$pages->find("template=shop,addresses.town='',need_more_info!=1,limit=20");


        foreach($fixins as $fp){

           //upage=$pages->get($row['pages_id']);


            echo '<div><a href="' . $fp->editUrl() .'">' . $fp->title .'</a></div>';
        }
?>