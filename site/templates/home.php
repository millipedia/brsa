<?php

namespace ProcessWire;

/**
 * Homepage
 */


 ?>
<header id="brsa_heaader"></header>

<main id="millco_layout" class="container home_main">

<div id="content_main">

    <div class="grid grid_2 home_h1_grid">
        <div class="col_1">
            <h1>British Record<br>Shop Archive</h1>
        </div>
    </div>

    <div class="homepage_search">
        <form action="/search/" id="search-form" class="search-form">
            <label for="search-box" class="search_label sr-only">Search</label>
            <div class="search_box_layout">
            <input id="search-box" class="search-box" 
type="text"
autocomplete="off" 
name="q" list="shoptions" 
hx-get="/typeahead/" 
hx-target="#shoptions" 
hx-config="{'inlineScriptNonce' : '<?= $nonce ?>'}" hx-trigger="input[checkselection()] changed delay:25ms" 
required/>
                            <datalist id="shoptions"></datalist>

                            <script nonce="<?= $nonce ?>'">

                            const search_box=document.getElementById('search-box');

                                function checkselection() {


                                    let sbv=search_box.value;
                                    // does the value of the searchbox match
                                    // an option value ... in which case just go there.

                                    var option_hit = document.querySelector(`#shoptions option[value='${sbv}']`);
                                    
                                    if(option_hit){

                                        let shop_url=option_hit.dataset.shopurl;
                                        window.location.href=shop_url;
                                        return 0;


                                    };

                                        // let's wait till we have 3 chars before
                                        // doing a typeahead.

                                        if(search_box.value.length > 2){

                                            return true;
                                        };

                                   

  
                                    return false;
                                }
                            </script>

                <div class="search-submit-container">
                    <button id="search-submit" class="search-submit" type="submit"><svg clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="m31.28 34-9.648-9.624c-.701.533-1.523.951-2.466 1.253s-1.971.453-3.083.453c-2.805 0-5.187-.98-7.145-2.941-1.959-1.961-2.938-4.322-2.938-7.082 0-2.784.979-5.156 2.938-7.117 1.958-1.961 4.328-2.942 7.108-2.942 2.781 0 5.145.981 7.091 2.942 1.947 1.961 2.92 4.333 2.92 7.117 0 1.09-.151 2.095-.453 3.015s-.732 1.767-1.288 2.542l9.684 9.66zm-15.197-11.731c1.741 0 3.204-.605 4.389-1.815 1.184-1.211 1.777-2.676 1.777-4.395 0-1.743-.599-3.22-1.796-4.43-1.197-1.211-2.654-1.816-4.37-1.816-1.766 0-3.253.605-4.461 1.816-1.209 1.21-1.814 2.687-1.814 4.43 0 1.719.605 3.184 1.814 4.395 1.208 1.21 2.695 1.815 4.461 1.815z" fill="#cc4747" fill-rule="nonzero"/></svg></button>
                </div>
            </div>
        </form>
    </div>
    <div class="grid grid_2">
        <div class="col ">

                <?php

				echo '<div class="has_lede">';
	                echo $page->content;
				echo '</div>';
    
				if($page->sub_content){

					$pp_content=$page->sub_content;

					include('includes/promo_panel.php');
				}



                $shops=$pages->get('/shops/');
                if ($shops->children()->count) {
                    echo '<p>We currently have <strong style="font-size:1.4rem;">' . $shops->children()->count .'</strong> shops.</p>';
                };

                $randomshop = $pages->find("template=shop, content!=''")->getRandom();

                ?>
                <h3>Random shop</h3>
           
                <?php
                    echo cover($randomshop);
                ?>

        </div>

        <div class="col">

        <div class="home_spacer"><!-- clear the logo background by magic --></div>

            <nav class="brsa_nav">
            <a href="/map/">Map</a>
            <a href="/sleeve-notes/">Sleeve Notes</a>
            </nav>

            <h2 class="mt-4 mb-0">Recently updated</h2>
            <?php

            $rupdated=$pages->find("template=shop,limit=4,sort=-modified");

            echo shop_in_list($rupdated, 2);

            echo '<h2 class="mt-2">Latest comments</h2>';
            $field = $fields->get('comments'); // assumes your comments field is named 'comments'
            $latest_comments = $field->type->find($field, "limit=5, sort=-created");
            
            echo '<div class="latest_comments" style="display:flex;gap:0.5rem;flex-direction:column">';
            foreach ($latest_comments as $lc){

                $cp=$pages->get($lc->pages_id);
                echo '<div class="lc">';
                echo '<div class="lc_date">'. date("d M Y", $lc->created) . '</div>';
                echo '<a href="' . $cp->url .'" class="lclink">' . $cp->title . '</a>';
                echo '</div>';
            }
            echo '</div>';
            ?>

        </div>

    </div>
</div>
</main>