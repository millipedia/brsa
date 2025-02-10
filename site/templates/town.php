<?php

namespace ProcessWire;

/**
 * County
 * 
 */

?>
<div id="millco_main">
    <?php


echo '<div class="shop_meta_item shop_town_county">';

    echo '<a href="/by-region/">All Regions</a>';
    echo ' / ';

    echo '<a href="' . $page->parent->url . '">' . $page->parent->title . '</a>';
    echo ' / ';

    echo '<a href="' . $page->url . '">' . $page->title . '</a>';
echo '</div>';

    $shops_in_county = $pages->find("addresses.town={$page},sort=title");

    // $shops_in_county->add($pages->find("addresses.town={$page->name}"));
	// should we cache these or will that just do what PageCache does anyway...
    echo shop_in_list($shops_in_county, 4 , 'town');
    
    ?>
</div>