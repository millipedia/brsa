<?php 

/**
 * 
 * Good starting point for metatags.
 * 
 */
    
?>

<meta name="viewport" content="width=device-width, initial-scale=1">


<?php

if($config->debug){

    // allow unsafe inline if we have debug on.
    ?>

    <meta http-equiv="Content-Security-Policy" content=" default-src  'self' https://tiles.stadiamaps.com/ https://api.stadiamaps.com  https://www.paypalobjects.com/ https://www.youtube.com https://www.youtube-nocookie.com https://challenges.cloudflare.com https://cabin.millipedia.net/ https://cabin.millipedia.net/duration; font-src 'self' https://fonts.gstatic.com; style-src 'self' 'unsafe-inline'; script-src 'self' https://challenges.cloudflare.com https://analytics.millipedia.net https://cabin.millipedia.net/  https://cdn.usefathom.com https://www.youtube.com/ https://cabin.millipedia.net/duration 'unsafe-inline' 'unsafe-eval'; img-src * data: ; worker-src 'self' blob:">
    
    <?php
}else{
    
    ?>
    <meta http-equiv="Content-Security-Policy" content=" default-src  'self' https://tiles.stadiamaps.com/ https://api.stadiamaps.com https://www.paypalobjects.com/ https://www.youtube.com https://www.youtube-nocookie.com https://challenges.cloudflare.com https://cabin.millipedia.net/ https://cabin.millipedia.net/duration; font-src 'self' https://fonts.gstatic.com; style-src 'self' 'unsafe-inline'; script-src 'self' https://challenges.cloudflare.com https://analytics.millipedia.net https://cabin.millipedia.net/ https://cdn.usefathom.com https://www.youtube.com/ https://cabin.millipedia.net/duration 'nonce-<?=$nonce;?>'; img-src * data: ; worker-src 'self' blob:">

    <?php
}

?>

<!-- millipedia made this -->
<link rel="author" href="/humans.txt">

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#2b5797">
<meta name="theme-color" content="#ffffff">