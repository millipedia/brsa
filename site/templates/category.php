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

    if($page->content){
        echo '<div class="county_content text-measure">' . $page->content . '</div>';
    }

	$shops_in_cat=$page->references("template=shop");

    echo shop_in_list($shops_in_cat, 4, 'category');

    ?>

</div>