<?php

namespace ProcessWire;

/**
 * Shops landing page
 * Will be A-Z now.
 * 
 */

 $page_content='';

 function azlist(){



    $alphabet = range('a', 'z');

    $azlist='<div class="az_container">';
    $azlist.='<ul class="az">';

    foreach ($alphabet as $letter) {
      
        $azlist.='<li><a class="az_link" href="/shops/a-z/' . $letter .'/">' . $letter .'</a></li>';

    }

    $azlist.='</ul>';
    $azlist.='</div>';

    return $azlist;

 }




 if($input->urlSegment1 == 'a-z') {
    
   // $page_content='load a-z list';

    if(isset($input->urlSegment2)){

        //Check is a single letter and load shops';
        
        $letter=$input->urlSegment2[0];
        $letter=strtolower($letter);

        // This validation is actually done in the page template settings
        // now, but leaving this here in case we can have some fun at some point.
        if(preg_match("/^[a-z]$/", $letter)){

          $page_title='Shops ' . strtoupper($letter); 
          $matches = $pages->find("template=shop, title^='$letter'");
  
          $page_content.=shop_in_list($matches, 3);

        }else{
          
          $page_title='Widdicome!';
          $page_content='of the week.';
        }

    }
 
  } else {
    $page_content=azlist();
    $page_title=$page->title;
  }

?>
<div id="millco_layout" class="millco_layout container">
	<div id="content_main" class="text-centre">
		
		<h1><?=$page_title?></h1>
		
		<div class="text-measure">
			<?= $page->content ?>
		</div>

		<?php
			echo $page_content;
		?>

    </div>
</div>