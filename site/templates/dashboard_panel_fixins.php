<?php namespace ProcessWire;

/**
 * 
 * A panel for the dashboard.
 * 
 */

 // missing towns.
 $fixins=$pages->find("template=shop,addresses.town=''");

?>
<p>Next challenge is adding any shops that the All Good Records site has.llipedia.net/s/YX4a57EqjGpfgXe</a></p>

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


		// //$fixins=$pages->find("template=shop,addresses.town='',need_more_info!=1,limit=20");
	$fixins=$pages->find("template=53, !shop_address='', need_more_info=0, sort=title, include=unpublished");


		 echo '<p>Getting there with the old addresses. These are shops that have something in the old address field but no addres... and are not published. Shall we take a look and see what we can do with them. Either publish them or stick them in the needs more info bucket... or just get rid of them for now.</p>';


		// $fixins=$pages->find("template=shop,!shop_address='',need_more_info=0,limit=25");
        foreach($fixins as $fp){

           //upage=$pages->get($row['pages_id']);


            echo '<div><a href="' . $fp->editUrl() .'">' . $fp->title .'</a></div>';
        }
