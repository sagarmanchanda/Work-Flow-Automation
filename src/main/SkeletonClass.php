<?php

namespace WFA\main;
function collosus(){
	echo "function is being called";
}
class SkeletonClass
{
    /**
     * Create a new Skeleton Instance
     */
    public function __construct() {
    	$this->echoPhrase("roger that!");
    }

    public function echoPhrase($phrase) {
    	echo $phrase;
    }
}
