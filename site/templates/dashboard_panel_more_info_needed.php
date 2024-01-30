<?php namespace ProcessWire;

/**
 * 
 * A panel for the dashboard.
 * 
 */

?>
<p>These are shops that have been flagged as <em>More info neede</em> ... probably because we need more infomation about them :-)</p>

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


		$fixins=$pages->find("need_more_info=1");


        foreach($fixins as $fp){

           //upage=$pages->get($row['pages_id']);


            echo '<div><a href="' . $fp->editUrl() .'">' . $fp->title .'</a></div>';
        }
?>