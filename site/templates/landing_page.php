<?php

namespace ProcessWire;

/**
 * Landing page template.
 * Has 2 colums and a grid of kids.
 */

?>

<div id="millco_layout" class="millco_layout container">

<div class="landing_page">
<div id="content_main" class="text-centre">
    <h1><?=$page->title?></h1>
    <div class="text-measure m-auto pb-2">
        <?= $page->content ?>
    </div>
</div>

<?php

if ($page->children()->count) {


    echo '<div class="card_grid">';
    foreach ($page->children() as $card) {

        
        echo cover($card);
    }

    echo '</div>';
}

?>
</div>


</div>
