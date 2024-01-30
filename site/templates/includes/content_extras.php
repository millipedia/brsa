<?php namespace ProcessWire;

/**
 * Render any extra content blocks.
 *  
 */

$extra_content='';
$stats_content='';

// do we have any extra content blocks
if($page->content_extras){
    
    foreach ($page->content_extras as $extra) {

        if ($extra->type == 'content_team') { 
           echo $extra->render();// template in /templates/fields/content_blocks
        }else{
            echo 'unknown type ' . $extra->type;
        }

    }

}    


?>