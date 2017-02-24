<?php

class Cache {
    
    public $id = 0;
    
    // Capacity in Megabytes
    public static $capacity = 0;
    public static $number_of_caches = 0;

    // Initialization
    public function __construct($id = 0) {
        $this->id = $id;
    }
}