<?php

namespace ProcessWire;

/**
 * 
 * Sync up towns and counties.
 * 
 */

die("wooooah tiger!");

include("../index.php");
$import = 0;
//$selector .= "status>=" . Comment::statusPending . "";
//$comments = FieldtypeComments::findComments('comments', "limit=10,status=Pending");
$nad=$pages->find("template=shop,addresses.county=0,limit=20");

foreach ($nad as $lc){
    echo '<br><a href="' . $lc->editUrl .'">' . $lc->name . ' </a><br>';
    
    foreach ($lc->addresses as $address) {
        # code...
        $lc->of();

        if($address->town){
            echo '<br> ID ' . $address->id . ' : Has: ' . $address->town->title;

            if($address->county){
                bd($address->county);
                echo '<br> Has: ' .$address->county->id . ' '  . $address->county->title;
            }else{

                $new_county=$address->town->parent();

                echo '<br> should be in ' . $new_county->title;

                $address->of(false);
                $address->setAndSave('county',$new_county);



            }

        }else{
            echo '<br>No town';

            if($address->county){
                echo '<br> Has: ' .$address->county->id . ' '  . $address->county->title;
            }else{

               
                echo '<br> No county ';

                if($lc->town){
                    echo '<br> Page town :  ' . $lc->town;  
                    $address->of(false);
                    $address->setAndSave('town',$lc->town);
                }



            }
            
        }


        $lc->save('addresses');



    }

    $lc->save;

}