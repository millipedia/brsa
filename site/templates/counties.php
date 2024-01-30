<?php

namespace ProcessWire;

/**
 * Regions / Counties landing page. 
 * 
 */

?>

<div id="millco_main">

  <?php

  $shops = $page->children();

  echo shop_in_list($shops);
  ?>

</div>