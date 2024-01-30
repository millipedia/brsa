<?php

namespace ProcessWire;

/**
 * Chain
 * 
 */

?>

<div id="millco_main">
    <?php

   
    $shops_in_chain = $pages->find("chain={$page->name},sort=title");

    echo '<h3 class="pb-0 mb-0">Shops</h3>';

    echo shop_in_list($shops_in_chain, 4, 'all');
    
    if($page->content){
        echo '<div class="county_content text-measure">' . $page->content . '</div>';
    }

    ?>

</div>