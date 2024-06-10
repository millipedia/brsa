<?php namespace ProcessWire;

/**
 * write out a gallery from page images.
 */


 echo '<hr>';
echo '<h2 class="gallery_title">Gallery</h2>';

echo '<div id="gallery">';
echo '<div class="gallery_image_grid">';


// lets' do our featured image first

if($page->featured_image){
  $pi=$page->featured_image;

  $pi_thumb = $pi->size(200, 200, [
    'quality' => 60,
    'webpQuality' => 40,
    'upscaling' => false,
    'sharpening' => 'medium'
  ]);

  echo '<div class="gallery_image">';

  echo '<a href="'. $pi->webp->url . '"';

  echo ' data-pswp-width="' . $pi->width . '"';
  echo ' data-pswp-height="' . $pi->height . '"';
  echo ' target="_blank">';
  echo '<img class="g_img" src="' . $pi_thumb->url . '" alt="' . $pi->description . '" width="' . $pi_thumb->width . '" height="' . $pi_thumb->height . '" loading=lazy>';

  echo '<div class="lf_slide_meta">';
    echo '<div class="slm_title">' . $pi_thumb->description  .'</div>';

$caption='';
    if ($pi_thumb->image_caption) {

      $caption = $pi_thumb->image_caption;
  }else if ($pi_thumb->strap) {
      $caption = $pi_thumb->strap;
  }
  $caption=$sanitizer->entities($caption);

    echo '<div class="slm_caption">' . $caption .'</div>';
  echo '</div>';

  echo '</a>';

  echo '</div>';
}

foreach($page->images as $pi){
       
        $pi_thumb = $pi->size(200, 200, [
            'quality' => 60,
            'webpQuality' => 40,
            'upscaling' => false,
            'sharpening' => 'medium'
        ]);

        echo '<div class="gallery_image">';

        echo '<a href="'. $pi->webp->url . '"';

          echo ' data-pswp-width="' . $pi->width . '"';
          echo ' data-pswp-height="' . $pi->height . '"';
          echo ' target="_blank">';
          echo '<img class="g_img" src="' . $pi_thumb->url . '" alt="' . $pi->description . '" width="' . $pi_thumb->width . '" height="' . $pi_thumb->height . '" loading=lazy>';

          echo '<div class="lf_slide_meta">';
            echo '<div class="slm_title">' . $pi_thumb->description  .'</div>';

            $caption=' - ';
    if ($pi_thumb->image_caption) {

      $caption = $pi_thumb->image_caption;

    }else if ($pi_thumb->strap) {
        $caption = $pi_thumb->strap;
    }
  
    $caption=$sanitizer->text($caption);

            echo '<div class="slm_caption">' . $caption .'</div>';
          echo '</div>';

      echo '</a>';

        echo '</div>';

    }

?>
</div>
</div>

<script nonce="<?=$nonce?>" type="module">

import PhotoSwipeLightbox from '/site/assets/vendor/photoswipe/photoswipe-lightbox.esm.js';

const lightbox = new PhotoSwipeLightbox({
  gallery: '#gallery',
  children: 'a',
  bgOpacity: 0.9,
  pswpModule: () => import('/site/assets/vendor/photoswipe/photoswipe.esm.js'),
  padding: { top: 10, bottom: 120, left: 10, right: 10 }
});

lightbox.on('uiRegister', function() {
lightbox.pswp.ui.registerElement({
name: 'slide_meta',
order: 9,
isButton: false,
appendTo: 'root',
html: 'Caption text',
onInit: (el, pswp) => {
  lightbox.pswp.on('change', () => {
    const currSlideElement = lightbox.pswp.currSlide.data.element;
    let captionHTML = '';

    if (currSlideElement) {
      const slide_meta= currSlideElement.querySelector('.lf_slide_meta');
      captionHTML+=slide_meta.innerHTML;
    }
    el.innerHTML = captionHTML || '';
  });
}
});
});

lightbox.init();

</script>