<nav class="breadcrumbs" aria-labelledby="breadcrumblist">
<h2 id="breadcrumblist" class="sr-only">Breadcrumbs</h2>
<ol class="cakes" itemscope itemtype="http://schema.org/BreadcrumbList">

<?php
// Display breadcrumbs in a Google-friendly aria-compliant microdata format 
    $count = 0;	// count link depth 
    foreach($page->parents() as $item) {
        $count++;
        $link_title=$item->title;
        if($item->id == 1){
            $link_title='Home';
        }

        // output parent pages, links and schema breadcrumb info 
        echo'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <a class="breadcrumb-link" itemprop="item" href="' . $item->url . '"><span itemprop="name">' . $link_title . '</span></a>
        <meta itemprop="position" content="' . $count . '"><span class="breadcrumb-separator"></span></li>'; 

    }
    // // output the current page as the last item
    // $count = $count+1;
    // echo'<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
    // <link itemprop="item" href="' . $page->url . '">
    // <span class="breadcrumb-last-item" itemprop="name" aria-current="page">' . $page->title . '</span>
    // <meta itemprop="position" content="' . $count . '"></li>';
    
    ?>
</ol>
</nav>