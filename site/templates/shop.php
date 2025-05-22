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

                    $address_markup.= '<div class="shop_address ' . ($address->closed ? 'shop_address_closed' : '') . '">';

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

					if($address->closed){
						$address_markup.= ' <span class="shop_closed">(closed ' . $address->closed . ')</span>';
					}



                $address_markup.= '</div>'; 

                }
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
     
			$shop_locations=array();

            if($page->addresses->count){

                foreach($page->addresses as $address){

					if ($address->location) {

						$shop_location = array();
						$shop_location['title'] = $page->title;

						// must do a function somewhere (init) for this.
						$location = explode(',', $address->location);
						$lat = $location[0];
						$lng = $location[1];

						$shop_location['lat'] = $lat;
						$shop_location['lng'] = $lng;

						$shop_location['url'] = ''; // don't show the url link when we're already on the shop page.
						$shop_location['town'] = $address->town->title;

						if($address->closed){
							$shop_location['closed'] = $address->closed;
						}else{
							$shop_location['closed'] = 0;
						}

						$shop_locations[] = $shop_location;
					}

				}

            }

            if (count($shop_locations)) {
            
                echo '<div id="map" class="shop_map">... loading ... </div>';

				include('includes/map_js.php');

				$map_link='/map/?lnglat=' . $shop_locations[0]['lng'] .',' . $shop_locations[0]['lat'];

				echo '<div class="map_link_container"><a class="map_link" href="' . $map_link . '"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="m16 24l-6.09-8.6A8.14 8.14 0 0 1 16 2a8.08 8.08 0 0 1 8 8.13a8.2 8.2 0 0 1-1.8 5.13Zm0-20a6.07 6.07 0 0 0-6 6.13a6.2 6.2 0 0 0 1.49 4L16 20.52L20.63 14A6.24 6.24 0 0 0 22 10.13A6.07 6.07 0 0 0 16 4"/><circle cx="16" cy="9" r="2" fill="currentColor"/><path fill="currentColor" d="M28 12h-2v2h2v14H4V14h2v-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h24a2 2 0 0 0 2-2V14a2 2 0 0 0-2-2"/></svg> View on BRSA map</a></div>';

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
                if (($page->images && $page->images->count()) || ($page->bag_images && $page->bag_images->count()) || $page->featured_image) {
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