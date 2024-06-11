<?php

namespace ProcessWire;

/**
 * Categories
 * 
 */

?>

<div id="millco_main">

<ul>
  <?php

  $cats = $page->children();
	
		foreach($cats as $cat){

			echo '<li><a href="' . $cat->url .'">' . $cat->title .'</a></li>';

		}

  ?>
</ul>
</div>