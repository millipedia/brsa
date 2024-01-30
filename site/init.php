<?php

namespace ProcessWire;


$this->addHookBefore('ProcessPageView::execute', function(HookEvent $event) {

	if(wire('input')->post('CommentForm_submit')){

                // get the Cloudflare token.
                $cf_token=wire('input')->post('cf-turnstile-response');


                // and send it to siteverify 
                $postdata = http_build_query(
                array(
                        'secret' => '0x4AAAAAAADbv_sSRBcyooyM4QcZ-TXjGTU',
                        'response' => $cf_token
                )
                );

                $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-Type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );

                $context  = stream_context_create($opts);

                $api_json_response = file_get_contents('https://challenges.cloudflare.com/turnstile/v0/siteverify', false, $context);
				
               if($api_json_response ){
                        // check result and redirect if failed.
                        $result=json_decode($api_json_response,TRUE); //service returns json in this sample

                        if(!($result['success'])){
                                // die or redirect or whaterver you fancy.
								// print_r($result);
								// die("Failed verification.");
								wire('session')->redirect('/some-help-page-or-something/'); 

                        }
               }else{
                        // die or redirect or whaterver you fancy.
						die("No response.");
               }

	}
  

  });

 


