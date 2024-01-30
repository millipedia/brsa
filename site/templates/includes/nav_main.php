<?php namespace ProcessWire;

?>
<nav id="main_nav" class="brsa_nav">
    <?php
        $home = $pages->get('/');
        $kids = $home->children();
        if ($kids->count > 0) {

            echo nav_render($kids, 1, 1);
        }
    ?>
</nav>