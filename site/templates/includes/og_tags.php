<?php 

/**
 * Write out Open Graph tags
 * with a couple of defaults
 * 
 * You should probably have set twitter / facebook values the settings page
 * 
 */

namespace ProcessWire; 


// These values are set in our SettingsFactory page.
$twitter_user = '';
$site_name ='';

$site_url_canonical = "";

if($site_url_canonical==''){
    // meh this isn't always right but it'll do as a fallback for the mo.
    $site_url_canonical=$_SERVER['HTTP_HOST'];
}

?>

<?php 

    if ($page->title =="Home"){
        $millco_title=$site_name;
    }else{
        $millco_title=$page->title;
    };

    $desc=$site_name;

    // Do we have a page summary? 
    if($page->summary){
        $desc=strip_tags($page->summary);
    }elseif($page->content){
        $desc=$page->content;
    }
    
    // Truncate string to closest sentence within 165 characters
    $desc = $sanitizer->truncate($desc, 165, 'sentence');

	$desc = str_replace('"','',$desc);

    // NB. ProCache removes the quuotes from the following metatags
    // so they should NOT end with a slash for closing the tag. Who knew?
    ?>

    <meta name="description" content="<?=$desc;?>">

    <meta property="og:title" content="<?=$millco_title;?>">
    <meta property="og:description" content="<?=$desc;?>">
    <meta property="og:type" content="website">
    <?php

    $ogimage='/site/assets/images/socmed_landscape.png';// our default OG image 
    // on EB that's the same as twitter for some reason.

    // OG image

    $image = $page->featured_image;

    if($image){
        // Create thumbnail while specifying $options
        $thumb = $image->size(1200, 630, [
            'cropping' => 'center',
            'quality' => 60,
            'upscaling' => true,
            'sharpening' => 'medium'
        ]);

        $ogimage=$thumb->url;
    }
    ?>
    <meta name="og:image" content="https://<?=$site_url_canonical . $ogimage;?>">

    <meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="<?=$twitter_user;?>">
    <meta name="twitter:title" content="<?=$millco_title;?>">
    <meta name="twitter:description" content="<?=$desc;?>">
    <?php

    $twimage='/site/assets/images/socmed_square.png';// our default card image

    // do we have a featured image?
    $image = $page->featured_image;

    if($image){
        // Create thumbnail while specifying $options
        $thumb = $image->size(512, 512, [
            'cropping' => 'center',
            'quality' => 60,
            'upscaling' => true,
            'sharpening' => 'medium'
        ]);

        $twimage=$thumb->url;
    }
    ?>
    <meta name="twitter:image" content="https://<?=$site_url_canonical . $twimage;?>">