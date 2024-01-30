<?php namespace ProcessWire;

?>
<div id="topnav" class="topnav">
    <?php
        $home = $pages->get('/');
        $kids = $home->children();
        if ($kids->count > 0) {
            echo nav_render($kids, 1, 1);
        }
    ?>
</div>