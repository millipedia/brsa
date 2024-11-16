<?php

namespace ProcessWire;

/**
 * Category
 * 
 */

?>

<div id="millco_main">
    <?php




	echo '<div class="shop_meta_item shop_town_county mb-1">';

		echo '<a href="/categories/">Categories</a>';
		echo ' / ';

		echo '<a href="' . $page->url . '">' . $page->title . '</a>';
	echo '</div>';

	echo '<div class="shop_grid">';

    if($page->content){
        echo '<div class="county_content text-measure">' . $page->content . '</div>';
    }

	echo '<div class="shop_aside shop_1">';
	  // show images as a gallery
	  if (($page->images && $page->images->count()) || $page->featured_image) {
		include('includes/page_gallery.php');
	}
	echo '</div>';
	
	echo '</div>';

	$shops_in_cat=$page->references("template=shop");

    echo shop_in_list($shops_in_cat, 4, 'category');

    ?>

</div>