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

    $shops_in_county = $pages->find("town|addresses.town={$page->name}");

    // $shops_in_county->add($pages->find("addresses.town={$page->name}"));

        echo shop_in_list($shops_in_county, 4 , 'town');
    
    ?>
</div>