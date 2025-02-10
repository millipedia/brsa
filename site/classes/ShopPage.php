<?php

namespace ProcessWire;

/**
 * Some handy utilities for Shop pages
 * 
 */

class ShopPage extends Page
{


    public function get_county(){

        $county=0;

 	if($this->addresses && $this->addresses->count){
            $county=$this->addresses->first()->county;
        }else{
			if($this->county && $this->county->id){
				$county=$this->county;
	
			}
		}

        return $county;
    }

    public function get_town(){

        $town=0;
        
        if($this->town){
            $town=$this->town;

        }else if($this->addresses && $this->addresses->count){

            $town=$this->addresses->first()->town;
        }

        return $town;
    }


   public function county(){


        if($this->county && $this->county->id){

            $county=$this->county;

        }else if($this->addresses && $this->addresses->count){
            $county=$this->addresses->first()->county;
        }

        return $county;
    }

}
