<?php namespace ProcessWire;

/**
 * 
 * A panel for the dashboard.
 * 
 */

 // missing towns.
 $fixins=$pages->find("template=shop,addresses.town=''");

?>
<p>oooh - done all the town eh... good for you.</p>
<p>Next challenge is adding any shops that the All Good Records site has. I've scraped their list into a spreadsheet here: <a href="https://next.millipedia.net/s/YX4a57EqjGpfgXe">https://next.millipedia.net/s/YX4a57EqjGpfgXe</a> (let me know if that doesn't work for you and I'll move to a Google Doc or something).</p>

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


		//$fixins=$pages->find("template=shop,addresses.town='',need_more_info!=1,limit=20");
		$fixins=$pages->find("template=shop,addresses.count<1,need_more_info!=1");


		echo '<p>Also. There are <strong>' . $fixins->count() . '</strong> shops that have something in the old address field but don\'t have new addresses. So they need to be addressed ...</p>';

        foreach($fixins as $fp){

           //upage=$pages->get($row['pages_id']);


            echo '<div><a href="' . $fp->editUrl() .'">' . $fp->title .'</a></div>';
        }
