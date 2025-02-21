<?php

namespace ProcessWire;

/**
 * Some handy utilities for Shop pages
 * 
 */

class ShopPage extends Page
{

	/**
	 * Return a single county
	 */

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

	/**
	 * Return a string of all the Counties this shop is in. (in which this shop is.)
	 * @return String
	 */

	 public function get_counties(){

        $counties=array();

		if($this->addresses && $this->addresses->count){

			$addresses=$this->addresses;

			foreach($addresses as $address){

				if($address->county && !in_array($address->county->title, $counties)) {
					$counties[]=$address->county->title;
				}
	
			}
		}

		$counties_string=implode(' / ' , $counties);
        return $counties_string;
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
