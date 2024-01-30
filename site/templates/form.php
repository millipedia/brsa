<?php

namespace ProcessWire;

/**
 * Form
 */

$feedback = '';

if ($input->post('ys_submitted'))


    // when processing form (POST request), check to see if token is present
    if ($session->CSRF->hasValidToken()) {

        // form submission is valid
        // okay to process

        //bd($input->post);

        $mp_title = $input->post('ys_title');
        $mp_content = $input->post('ys_content');
        $mp_location = $input->post('ys_location');
        $mp_email_address = $input->post('ys_email');

        $new_page = $pages->add('map_point', '/shops/', [
            'title' => $mp_title,
            'content' => $mp_content,
            'location' => $mp_location,
            'email_address' => $mp_email_address,
            'user_submitted' => 1
        ]);

        $new_page->addStatus(Page::statusUnpublished);

        $new_page->save();


        // dunno how to do this with input->post
        if(file_exists($_FILES["ys_image"]["tmp_name"][0])){

            $check = getimagesize($_FILES["ys_image"]["tmp_name"][0]);

            if($check !== false) {

                $new_page->of();

                // instantiate the class and give it the name of the HTML field
                $u = new WireUpload('ys_image');

                // tell it to only accept 1 file
                $u->setMaxFiles(1);

                // tell it to rename rather than overwrite existing files
                $u->setOverwrite(false);

                // have it put the files in their final destination. this should be okay since
                // the WireUpload class will only create PW compatible filenames
                $u->setDestinationPath($new_page->featured_image->path());

                // tell it what extensions to expect
                $u->setValidExtensions(array('jpg', 'jpeg', 'gif', 'png'));

                // execute() returns an array, so we'll foreach() it even though only 1 file
                foreach($u->execute() as $filename) { $new_page->featured_image->add($filename); }
                $new_page->save();

            }else{
                 // bd("no image");
            }

        }

        $feedback .= $page->sub_content;

    } else {
        // form submission is NOT valid
        throw new WireException('CSRF check failed!');
    }

?>

<div id="millco_main">

<div class="ys_form_container">
<h2 class="block_heading"><div class="block_heading_icon"><svg clip-rule="evenodd" fill-rule="evenodd" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="44" width="44"><clipPath id="ys_heading_a"><path clip-rule="evenodd" d="m0 0h44v44h-44z"/></clipPath><g clip-path="url(#ys_heading_a)"><circle cx="22" cy="21.12" fill="none" r="14.74" stroke="#698b97" stroke-width="3.52"/><path d="m19.969 29.92h4.062v-6.769h6.769v-4.062h-6.769v-6.769h-4.062v6.769h-6.769v4.062h6.769z" fill="#fbea75" fill-rule="nonzero"/></g></svg></div><div class="block_heading_text">Add your story</div></h2>

    <?php

    if ($feedback == '') {

        echo '<div class="ys_intro">' . $page->content .'</div>';
    ?>


    <form id="ys_form" class="ys_form" method="POST" enctype="multipart/form-data">
        <input name="ys_submitted" type="hidden" value="ys_submitted">

            <?php
                echo $session->CSRF->renderInput();
            ?>



        <label class="ys_label" for="ys_title">1) Name the location of your story</label>
        <span class="label_help">For example 'Smelly Bridge'</span>
        <input id="ys_title" type="text" class="ys_text" name="ys_title" required />

        <label class="ys_label" for="ys_location">2) Zoom in on the map, then click or tap on your story's location to add a pin to the map.</label>
        <div style="display:none;">
            <input id="ys_location" name="ys_location" type="text">
        </div>


        <label class="ys_label" for="ys_location">3) What is your story about?</label>
       
        
        <label class="ys_label" for="ys_email">5) Enter your story</label>
        <span class="label_help">If required, you can paste your story from Word etc.</span>
        <textarea id="ys_content" class="ys_content" name="ys_content" rows="10" required></textarea>

        <div class="ys_upload_container">
            
            <label id="ys_upload_label" for="ys_image" class="ys_upload">
                <div class="yt_upload_image"><svg fill="none" height="200" viewBox="0 0 200 200" width="200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><mask id="a" height="144" maskUnits="userSpaceOnUse" width="144" x="28" y="28"><path d="m28.5715 28.5715h142.857v142.857h-142.857z" fill="#d9d9d9"/></mask><g mask="url(#a)"><path d="m142.261 75.7429v-13.6893h-13.839v-12.0535h13.839v-13.6893h11.907v13.6893h13.986v12.0535h-13.986v13.6893zm-95.9823 87.9461c-4.2643 0-7.9095-1.513-10.9357-4.539-3.0262-3.024-4.5393-6.719-4.5393-11.086v-71.7246c0-4.2667 1.5131-7.9369 4.5393-11.0108 3.0262-3.0761 6.6714-4.6142 10.9357-4.6142h18.4536l12.65-14.4358h35.8607v15.625h-28.8679l-12.65 14.4358h-25.4464v71.7246h96.1323v-55.0568h15.625v55.0568c0 4.367-1.538 8.062-4.614 11.086-3.074 3.026-6.744 4.539-11.011 4.539zm48.0679-25.592c7.3404 0 13.4904-2.481 18.4504-7.443 4.961-4.96 7.442-11.11 7.442-18.45 0-7.243-2.481-13.3444-7.442-18.3039-4.96-4.9619-11.11-7.4429-18.4504-7.4429-7.2429 0-13.3441 2.481-18.3036 7.4429-4.9619 4.9595-7.4429 11.0609-7.4429 18.3039 0 7.34 2.481 13.49 7.4429 18.45 4.9595 4.962 11.0607 7.443 18.3036 7.443z" fill="#1f2642"/></g></svg></div>
                
                <input id="ys_image" name="ys_image[]" type="file" accept="image/*;capture=camera">
                <span id="yt_upload_text" class="yt_upload_text">Add a photograph</span>
            </label>
        </div>

        <label class="ys_label" for="ys_content">4) Enter your email address</label>
        <input id="ys_email" type="email" class="ys_email" name="ys_email" />

        <button type="submit" class="butt">Submit</button>

        <div class="pt-1 pb-1">You can <a href="/about/privacy-and-cookies" class="link_reverse">Read our privacy policy here</a></div>

    </form>

   <script nonce="<?= $nonce ?>">

        document.addEventListener('DOMContentLoaded', function() {

           
        })
    </script>

        <?php

        } else {

            echo '<div class="alert">' . $feedback . '</div>';
        }
    ?>
</div>
</div>
