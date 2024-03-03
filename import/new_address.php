<?php

namespace ProcessWire;

/**
 * 
 * Import data from scraped webpages for the BRSA
 * Uses https://simplehtmldom.sourceforge.io/ to extract the relevent nodes.
 *  
 * 
 */

//die("wooooah tiger!");

include("../index.php");
$import = 0;
//$selector .= "status>=" . Comment::statusPending . "";
//$comments = FieldtypeComments::findComments('comments', "limit=10,status=Pending");
$nad=$pages->find("template=shop,addresses.count=0,limit=2");

foreach ($nad as $lc){
    echo '<a href="' . $lc->url .'">' . $lc->name . ' ' . $lc->addresses->count . '</a><br>';
    
    print_r($lc->addresses); 
    // $lc->of();
    // $new_address=$lc->addresses->getNew();
    // $new_address->county = $lc->county;
    // if($lc->town){
    //     $new_address->town = $lc->town;
    // }
    // $new_address->save();
    // $lc->addresses->add($new_address);
    $lc->save;

}