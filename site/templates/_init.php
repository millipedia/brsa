<?php

namespace ProcessWire;

/**
 * This _init.php file is called automatically by ProcessWire before every page render
 * We use it to generate or store site wide variables we might want.
 */

/** @var ProcessWire $wire */

// ini_set("display_errors", 0);

$nonce = base64_encode(random_bytes(20));

$app_classes = '';
$body_classes = '';
$has_video = '';

$scroll_to = '';

// load our site settings.
$twitter_user = '';

function listChildrenTree($children, $level = 1, $max_level = 3, $collapse = 1)
{

    foreach ($children as $child) {

        $classes = '';
        $current_page = 0;

        if ($collapse) {
            $show_kids = 0;
        } else {
            $show_kids = 1;
        }

        if (wire('page')->name == $child->name) {
            $classes .= ' current-page';
            $current_page = 1;
            $show_kids = 1;
        }

        if (wire('page')->parent->name == $child->name) {
            $classes .= ' current-parent';
            $current_page = 1;
            $show_kids = 1;
        }

        echo "<li class='nav-li-level-{$level}'><a href='{$child->url}' class='{$classes}'>{$child->title}</a>";

        $skip = array("home", "events", "podcasts");

        if ($show_kids && $level < $max_level && $child->numChildren  && !(in_array($child->name, $skip))) {
            echo "<ul>";
            listChildrenTree($child->children, $level + 1, $max_level);
            echo "</ul>";
        }

        echo "</li>";
    }
}


function ajax_response($content, $title = '')
{

    $resultsArray = array(
        "title" => $title,
        "content" => $content
    );

    echo json_encode($resultsArray);

    return;
}

/**
 * Accessible dropdown menu adapted from.
 * https://www.w3.org/WAI/tutorials/menus/flyout/
 */

function add_kids($children, $level = 1, $max_level = 3)
{

    foreach ($children as $page) {

        echo "<li class='nav-li-level-{$level} ";

        $skip = array();

        if ($level < $max_level && $page->numChildren && !(in_array($page->name, $skip))) {

            echo " has-submenu'>";
            echo '<a href="' . $page->url . '" aria-haspopup="true" aria-expanded="false" >' . $page->title . '</a>';
            echo "<ul class='nav-ul'>";
            add_kids($page->children, $level + 1, $max_level);
            echo "</ul>";
        } else {
            echo "'><a href='{$page->url}'>{$page->title}</a>";
        }

        echo "</li>";
    }
}


/**
 * Div based nav
 */

function nav_render($children, $level = 1, $max_level = 3)
{
    $nav_markup = '';

    $current_page = wire()->page;
    $current_page_id = $current_page->id;
    $current_page_parent_id = $current_page->parent->id;
    // Return all parents, excluding the homepage
    $parents = $current_page->parents("template!=home");
   
    foreach ($children as $page) {

        $classes = " ";
        $show_kids = 0;


        if ($page->id == $current_page_id) {
            $classes .= ' current_page';
            $show_kids = 1;
        } else if ($page->id == $current_page_parent_id) {
            $classes .= ' has-parent';
            $show_kids = 1;
        }
        foreach ($parents as $parent) {
            if ($parent->id == $page->id) {
                $show_kids = 1;
                $classes .= ' active_parent';
            }
        }
        // if($parents->getPage($page) && $parents->getPage($page)->count){
        //     bd($page->title . ' parent of current');
        //     $classes .= ' active_parent';
        //     $show_kids=1;

        // }

        //        bd("kid page_id " . $page->id . "kid parent page_id " . $page->parent->id . ' : current pd ' . $current_page_id . ' current ppd ' . $current_page_parent_id);

        $nav_markup .= "<div class='nav-item nav-level-{$level} {$classes}";

        $skip = array("shops", "blog", "by-region");


        // if ($level < $max_level && $page->numChildren && !(in_array($page->name, $skip))) {

        if (($show_kids) && $page->numChildren && !(in_array($page->name, $skip))) {
            $nav_markup .= " has-submenu'>";

            $nav_markup .= "<a class='millco_nav_link {$classes}' href='{$page->url}'>{$page->title}</a>";
            $nav_markup .= "<div class='subnav nav-level-" . ($level + 1) . "-container'>";
            $nav_markup .= nav_render($page->children, $level + 1, $max_level);
            $nav_markup .= "</div>";
        } else {
            $nav_markup .= "'><a class='{$classes}' href='{$page->url}'>{$page->title}</a>";
        }

        $nav_markup .= "</div>";
    }

    return $nav_markup;
}

/**
 * Subnav
 */
function nav_simple($children, $current_page_id = -1, $level = 1)
{

    echo "<ul class='cr-subnav-ul cr-subnav-level-$level'>";
    foreach ($children as $page) {

        $link_class = ($page->id == $current_page_id ? ' ui-button-selected block-link-active' : '');

        echo "<li><a class='subnav-link ui-button d-flex $link_class' href='{$page->url}'>{$page->title}</a> ";
        if ($page->id == $current_page_id) { // show children of this 
            if ($page->numChildren) {
                $level++;
                nav_simple($page->children, $current_page_id, $level);
            }
        }

        echo "</li>";
    }
    echo "</ul>";
}

/**
 * Source set ... or at least a featured image in webp now
 */


function source_set($image, $caption = '', $alt = '', $width = 1440, $height = 'auto')
{

    $image_markup = '';
    $caption = '';
    $alt = '';

    if ($image) {

        // Create thumbnail while specifying $options
        $thumb = $image->size($width, $height, [
            'quality' => 60,
            'webpQuality' => 40,
            'upscaling' => false,
            'sharpening' => 'medium'
        ]);


        if ($image->title) {
            $alt = $image->title;
        }

        if ($image->image_caption) {
            $caption = $image->image_caption;
        }else if ($image->strap) {
            $caption = $image->strap;
        }

        $image_markup .= '<figure>';
        $image_markup .= '<picture>';
        $image_markup .= '<source srcset="' . $thumb->webp->url . '" type="image/webp">';
        $image_markup .= '<img class="img-fluid" src="' . $thumb->url . '" alt="' . $alt . '" width="' . $thumb->width . '" height="' . $thumb->height . '" loading=lazy>';
        $image_markup .= '</picture>';
        if ($caption !== '') {
            $image_markup .= '<figcaption>' . htmlspecialchars_decode($caption) . '</figcaption>';
        }
        $image_markup .= '</figure>';
    }

    return $image_markup;
}


/**
 * Function to write out cover
 */

function cover($shop, $cover_tick = 0, $cover_type = "all", $heading = '')
{

    $cover = '';
    $cover_class = '';

    if ($cover_tick % 2 == 0) {
        $cover_class .= ' card_even card_' . $cover_tick;
    } else {
        $cover_class .= ' card_odd card_' . $cover_tick;
    }

    $first_letter = $shop->name[0];

    $letter_pos = (ord(strtoupper($first_letter)) - ord('A') + 1);
    $hue = ($letter_pos * 360 / 27); // we have our primary as 0 and then 26 letters.



    $cover .= '<a class="cover ' . $cover_class . '" href="' . $shop->url . '">';
    $cover .= '<div class="cover_image_container">';


    if ($shop->featured_image) {

        // Create thumbnail while specifying $options
        $cover_image = $shop->featured_image->size(640, 640, [
            'quality' => 60,
            'webpQuality' => 40,
            'upscaling' => false,
            'sharpening' => 'medium'
        ]);

        $cover .= '<figure class="cover_image_figure">';
        $cover .= '<source srcset="' . $cover_image->webp->url . '" type="image/webp">';
        $cover .= '<img class="cover_image" src="' . $cover_image->url . '" alt="" width="' . $cover_image->width . '" height="' . $cover_image->height . '" loading=lazy>';
        $cover .= '</picture>';
        $cover .= '</figure>';
    } else {

        $cover .= '<figure class="cover_image_figure">';
        $cover .= '<img class="cover_image" src="/site/assets/images/cover_bg.svg?v=2" alt="" width="200px" height="200px">';
        $cover .= '</picture>';
        $cover .= '</figure>';
    }

    $cover .= '</div>';

    $cover .= '<div class="cover_meta">';

    // $cover.='debug: . ' . $cover_class;

    // if($heading=='h1'){
    //     $cover .= '<h1 class="cover_title" style="background-color: hsl(' . $hue . ', 57%, 54%) !important;">' . html_entity_decode($shop->title) . '</h1>';
    // }else{
    //     $cover .= '<div class="cover_title" style="background-color: hsl(' . $hue . ', 57%, 54%) !important;">' . html_entity_decode($shop->title) . '</div>';
    // }

    // 230130 Stephen
    // endarkening colours to hit accessibility. Should chat with Andy.

    if ($heading == 'h1') {
        $cover .= '<div class="cover_title" style="background-color: hsl(' . $hue . ', 57%, 31%) !important;"><h1 style="margin:0;padding:0;font-size:inherit;color:currentColor;font-weight:normal;">' . html_entity_decode($shop->title) . '</h1></div>';
    } else {
        $cover .= '<div class="cover_title" style="background-color: hsl(' . $hue . ', 57%, 31%) !important;">' . html_entity_decode($shop->title) . '</div>';
    }




    // don't show the county if we're on a county page.
    if ($cover_type == 'all' || $cover_type == 'town') {

        if($shop->template=='shop'){
            if ($shop->get_county()) {

                $county=$shop->get_county();
    
                $cover .= '<div class="card_meta card_meta_county">' . $county->title . '</div>';
            // }else{
    
            //     if($shop->addresses && $shop->addresses->count){
    
            //         if($shop->addresses->first()->county){
            //             $cover .= '<div class="card_meta card_meta_county">' . $shop->addresses->first()->county->title . '</div>';
            //         }
    
    
            //     }
    
            } 
        }
      
    }
    // don't show the town if we're on a town.
    if ($cover_type == 'all' || $cover_type == 'county') {

        if($shop->template=='shop'){
        if ($shop->get_town()) {
            $cover .= '<div class="card_meta card_meta_town">' . $shop->get_town()->title . '</div>';
        }
    }
    }



    $cover .= '</div>';
    $cover .= '</a>';

    return $cover;
}

/**
 * Function to write out cover with no link or meta.
 */

function cover_version($shop)
{

    $cover = '';
    $cover_class = '';
    $cover .= '<div class="cover ' . $cover_class . '">';
    $cover .= '<div class="cover_image_container">';

    if ($shop->featured_image) {

        // Create thumbnail while specifying $options
        $cover_image = $shop->featured_image->size(640, 640, [
            'quality' => 60,
            'webpQuality' => 40,
            'upscaling' => false,
            'sharpening' => 'medium'
        ]);

        $cover .= '<figure class="cover_image_figure">';
        $cover .= '<source srcset="' . $cover_image->webp->url . '" type="image/webp">';
        $cover .= '<img class="cover_image" src="' . $cover_image->url . '" alt="" width="' . $cover_image->width . '" height="' . $cover_image->height . '">';
        $cover .= '</picture>';
        $cover .= '</figure>';
    } else {

        $cover .= '<figure class="cover_image_figure">';
        $cover .= '<img class="cover_image" src="/site/assets/images/cover_bg.svg" alt="" width="200px" height="200px">';
        $cover .= '</picture>';
        $cover .= '</figure>';
    }

    $cover .= '</div>';

    $cover .= '<div class="cover_meta">';
    $cover .= '<div class="card_title">' . html_entity_decode($shop->title) . '</div>';

    $cover .= '</div>';
    $cover .= '</div>';

    return $cover;
}

/**
 * Write out a list of shops.
 */

function shop_in_list($shops, $cols = 4, $sil_type = 'all')
{

    $sil = '';

    if ($shops->count()) {


        $sil .= '<div class="shop_in_list col_count_' . $cols . ' pt-2">';
        foreach ($shops as $card) {

            // hack to replace uploads path.

            // $card->of(false);
            // $card->content=str_replace('src="upload','src="/upload', $card->content);
            // $card->save();

            // echo ' Updated: ';


            // hack to save files to sort images

            //$boops=$pages->

            // $card->of(false);
            // $card->content=str_replace('src="upload','src="/upload', $card->content);
            // $card->save();

            // echo ' Updated: ';

            // $shop_link='';
            // $shop_link .= '<a class="card-link " href="' . $card->url . '">';

            // $shop_link .= '<div class="card-flex">';
            // $shop_link .= '<div class="card-meta">';
            // $shop_link .= '<div class="card-title">' . html_entity_decode($card->title) . '</div>';
            // $shop_link .= '</div>';
            // $shop_link .= '</div>';
            // $shop_link .= '</a>';

            //echo $shop_link;
            $sil .= cover($card, 0, $sil_type);
        }

        $sil .= '</div>';

        return $sil;
    }
}
