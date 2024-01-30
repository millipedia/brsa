<?php namespace ProcessWire;

if($page->files && $page->files->count){

        echo '<div class="aside-block article-downloads">';
       
       foreach ($page->files as $file) {

           $download_text='Download';

           if($file->description){
               $download_text=$file->description;
           }else{
               $download_text=$file->name;
           }

           echo '<div class="download-link-container">';
           echo '<a class="download_link download_link_' . $file->ext .'" href="' . $file->url .'"><div>';
           echo '<span class="download_link_text">' . $download_text . '</span>';
           echo '<span class="download_link_meta">('. $file->ext .' '. $file->filesizeStr .' )</span>';
           echo '</div></a>';
           echo '</div>';
       }

       echo '</div>';

   }