<?php

namespace ProcessWire;

/**
 * Shop page
 * 
 */

/**
 * Markup a single comment.
 */
 function comment_markup($comment){

    $cm='';

    $text = htmlentities($comment->text);// make sure output is entity encoded
    $cite = htmlentities($comment->cite);
    $date = date('d M Y \a\t h:i', $comment->created); // format the date

    $comment_class='';

    $meta=$comment->getMeta();

    $cm.= '<div id="comment_' . $comment->id .'" class="comment">';

    $comment_user=wire('users')->get("email={$comment->email},limit=1");

    $cm.= '<div class="comment_cite h3">' . $cite . '</div>';
    $cm.= '<div class="comment_date">'. $date . '</div>';
    $cm.= '<div class="comment_body text_measure">' . nl2br($text) . '</div>';
    
    $cm.= '</div>';

    return $cm;

}

?>
<main id="millco_layout" class="container">

    <div id="content_main">

        <div class="shop_grid">

            <div class="shop_main">

            <div class="shop_head_title">
            <?php
                echo cover($page, 0, 'shop', 'h1');
            ?></div>

                <div class="text-measure">

                    <?php

                    $pc = str_replace('<figcaption>Picture</figcaption>', '', $page->content);

                    echo '<div class="has_no_lede">' . $pc .'</div>';

                    echo '<hr>';
                    
                    echo '<h2 class="comments_header">Comments</h2>';

					if($page->old_comments){
						echo '<div class="old_comments_container">' . $page->old_comments . '</div>';
					}


                    echo '<div class="comments_container">';

                    // comments list 

                   $comments_marked_up='';

                    foreach($page->comments as $comment) {

                        if($comment->status < 1) continue; // skip unapproved or spam comments
                        
                        $comments_marked_up.=comment_markup($comment);
                    
                    
                    }
                    
                    echo '<div id="comments_list">' . $comments_marked_up . '</div>';
                   

                    echo '</div>';

                    //echo '<p class="alert">I\'ve turned commments off again for a mo. I think the form should only appear when you click a button - that looks neater and we dont have to run Turnstile validation on every page.</p>';

                    $comments_form = $page->comments->renderForm(array(
                        'requireHoneypotField' => 'email2'
                    ));

                    // add a div with class="cf-turnstil" to the form - this gets replaced with a token (after a successful challenge)
                    $cft_tag = '<div id="cft_field" class="cf-turnstile"></div>';

                    $cft_tag .='<div class="discoclaimer">We ask for your email address in case we need to contact you about your submission. Your email address is never published.</div>';

                    $comments_form = str_replace("</form>", $cft_tag . "</form>", $comments_form);

                    $comments_form_rot = str_rot13($comments_form);
                    $comments_form_slash_rot = addslashes($comments_form_rot);

                    // if we have feedback then show the form 
                    if ($input->get('comment_success')) {
                        echo '<div id="cf_clear">' . $comments_form . '</div>';
                    } else { // show our add comment button.
                        echo '<div id="cf_clear"></div>';
                        echo '<button id="show_comment_form" class="butt">Add a comment</button>';
                    }

                    ?>

                    <script nonce="<?= $nonce ?>">
                    
                        // our rotten form
                        const rot_form = "<?= $comments_form_slash_rot ?>";

                        // function to unrot our form
                        const rot13 = (message) => {
                            const originalAlpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
                            const cipher = "nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM"
                            return message.replace(/[a-z]/gi, letter => cipher[originalAlpha.indexOf(letter)])
                        }

                        // and to unslash it. Surely this should be native?
                        // stripslashes
                        function stripslashes(str) {
                            return str.replace(/\\'/g, '\'').replace(/\"/g, '"').replace(/\\\\/g, '\\').replace(/\\0/g, '\0');
                        }

                        const show_comment_form = document.getElementById("show_comment_form");

                        // Onload events.
                        document.addEventListener('DOMContentLoaded', function() {

                            // we don't have a butt if we're showing feedback.
                            // so check it first.
                            if (typeof(show_comment_form) != 'undefined') {

                                show_comment_form.addEventListener('click', event => {

                                    event.preventDefault();

                                    var form_html = rot13(stripslashes(rot_form));

                                    document.getElementById('cf_clear').innerHTML = form_html;

                                    turnstile.render('#cft_field', {
                                        sitekey: '0x4AAAAAAADbv7vokO1eANvL',
                                        callback: function(token) {
                                            console.log(`Challenge Success ${token}`);
                                        },
                                    });

									// hide the add comment button.
                                    show_comment_form.style.display='none';


                                });

                            }

                        }); // onload
                    </script>


                </div>
            </div>

            <div class="shop_aside shop_1">


            <div class="shop_meta">

            <h2>Details</h2>

            <?php

            $address_markup='';  
            $address_count=0;

            if($page->addresses){

                // Loop through all our addresses now.
                foreach($page->addresses as $address){

                    $address_count++;

                    $address_markup.= '<div class="shop_address">';

                    $address_markup.= nl2br($address->shop_address);

                    if ($address->shop_postcode) {
                        $address_markup.= ' <span class="shop_postcode">' . $address->shop_postcode . '</span>';
                    }

                    if ($address->town) {

                        $address_markup.= ' <a href="' . $address->town->url . '">' . $address->town->title . '</a>';
                        $address_markup.= ' / ';
                    }
        
                    if ($address->county) {
                        $address_markup.= ' <a href="' . $address->county->url . '">' . $address->county->title . '</a>';
                    }



                $address_markup.= '</div>'; 

                }
            }else if ($page->shop_address || $page->shop_postcode) {

                $address_count++;

                $address_markup.= '<div class="shop_address">';

                    $address_markup.= nl2br($page->shop_address);

                if ($page->shop_postcode) {
                    $address_markup.= ' <span class="shop_postcode">s' . $page->shop_postcode . '</span>';
                }

                $address_markup.= '</div>';
            }



            if($address_count > 1){
                echo '<h3>Locations</h3>';
            }elseif($address_count>0){
                echo '<h3>Location</h3>';
            }

            echo $address_markup;

            // only show the default county if we don't have one now.
            if($page->addresses->count < 1){

                echo '<div class="shop_meta_item shop_town_county">';

                if ($page->town) {
    
                    echo '<a href="' . $page->town->url . '">' . $page->town->title . '</a>';
                    echo ' / ';
                }
    
                if ($page->county) {
                    echo '<a href="' . $page->county->url . '">' . $page->county->title . '</a>';
                }
    
                echo '</div>';


            }
     
            // TODO  - this should show all the addresses field entries.

            $locations=array();

            if ($page->location) {
                $locations[]=$page->location;
            }

            if($page->addresses->count){

                foreach($page->addresses as $address){

                    if($address->location){
                        $locations[]=$address->location;
                    }

                }
            }

            if (count($locations)) {
            
                echo '<div id="map" class="shop_map">... loading ... </div>';

            ?>
                <script nonce="<?= $nonce ?>">
                    document.addEventListener('DOMContentLoaded', function() {

                        // set up the map
                        map = new L.Map('map');

                        // create the tile layer with correct attribution
                        var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                        var osmAttrib = '© OpenStreetMap';
                        var osm = new L.TileLayer(osmUrl, {
                            minZoom: 8,
                            maxZoom: 18,
                            attribution: osmAttrib
                        });

                        // var Stamen_Watercolor = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.{ext}', {
                        //     attribution: 'Map tiles <a href="http://stamen.com">Stamen Design</a> &mdash; Data <a href="https://www.openstreetmap.org/copyright">OSM</a> contributors',
                        //     subdomains: 'abcd',
                        //     minZoom: 4,
                        //     maxZoom: 16,
                        //     ext: 'png'
                        // });

                        map.attributionControl.setPrefix('');

                        map.addLayer(osm);

                        map.setView([<?=$locations[0]?>], 11);

						var marker_layer = new L.featureGroup();

                        <?php

                        $tick=1;
                        foreach($page->addresses as $address){

                            if($address->location && $address->location!==''){

                                echo 'var marker_' . $tick .' = L.marker([' . $address->location .']).setIcon(L.divIcon({className: \'brsa_map_pin\'})).addTo(marker_layer);' . PHP_EOL;



                            $popup_content='<div class="text-centre">';
                            $popup_content.='<div class="pu_title">' . $page->title .'</div>';
                            
                            if($address->town){
                                $popup_content.='<div class="pu_town">' . $address->town->title . '</div>';
                            }

                            $popup_content.='</div>';


                            echo ' marker_' .$tick .'.bindPopup(\''. $popup_content .'\');' . PHP_EOL;

						}

                            $tick ++;
    
                        }


                           
                        ?>
							marker_layer.addTo(map);
							map.fitBounds(marker_layer.getBounds());

                       

                    });
                </script>

            <?php

            }

            /* History */

            if ($page->opened || $page->closed) {

                echo '<hr>';
                echo '<h3>History</h3>';

                echo '<div class="shop_meta_item">';

                echo '<div class="history_grid">';
                        echo '<div class="history_label">Opened :</div>';
                        echo '<div class="history_date">' . $page->opened . '</div>';

                    if ($page->closed) {


                            echo '<div class="history_label">Closed :</div>';
                            echo '<div class="history_date">' . $page->closed . '</div>';

                    }
                echo '</div>';

                echo '</div>';
            }

            /* Website */

            if ($page->shop_website) {


                echo '<hr>';
                echo '<h3>Website</h3>';

                echo '<div class="shop_website">';

                echo '<a href="' . $page->shop_website . '" class="shop_website_link">' . $page->shop_website . '</a>';

                echo '</div>';
            }


           
            /* Chain */

            if ($page->chain && $page->chain->id) {


                echo '<hr>';
                echo '<h3>Chain</h3>';

                echo '<div class="shop_chain">';

                echo 'Part of the <a href="' . $page->chain->url . '" class="shop_website_link">' . $page->chain->title . '</a> chain';

                echo '</div>';
            }


            ?>

        </div>


        <?php




                // show images as a gallery
                if (($page->images && $page->images->count()) || $page->featured_image) {
                    include('includes/page_gallery.php');
                }

              
                echo '<div class="pt-2">';

                $county=$page->get_county();

                if ($next_in_county = $page->next("addresses.county=$county")) {
                    if ($next_in_county->id) {
                        echo '<div class="shop_meta_item shop_prev_next">';
						
						if($county){
							echo 'Next in ' . $county->title . ': ';
						}
						
                        echo '<a href="' . $next_in_county->url . '">' . $next_in_county->title . '</a>';
                        echo '</div>';
                    }
                }

                if ($prev_in_county = $page->prev("addresses.county=$county")) {
                    if ($prev_in_county->id) {
						echo '<div class="shop_meta_item shop_prev_next">';
						if($county){
							echo 'Prev in ' . $county->title . ': ';
						}
                        echo '<a href="' . $prev_in_county->url . '">' . $prev_in_county->title . '</a>';
                        echo '</div>';
                    }
                }

                echo '</div>';



                ?>
            </div>


        </div>


        <?php
        // include any extra content blocks
        include('includes/content_extras.php');
        ?>

        <div class="brsa_next_previous">

            <div class="d-flex w-100">

                <div>
                    <?php

                    if ($page->prev()) {
                        echo 'A-Z prev: <a href="' . $page->prev()->url . '">' . $page->prev()->title . '</a>';
                    }

                    ?>
                </div>

                <div>
                    <?php

                    if ($page->next()) {
                        echo 'A-Z next: <a href="' . $page->next()->url . '">' . $page->next()->title . '</a>';
                    }

                    ?>
                </div>

            </div>

        </div>

    </div>
</main>