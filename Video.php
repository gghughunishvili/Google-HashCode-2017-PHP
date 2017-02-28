<?php

class Video
{

    public $id;

    // Size in Megabytes
    public $size;

    public $number_of_requests = 0;
    
    public $interested_endpoints_number = 0;

    public static $number_of_videos = 0;

    // Initialization
    public function __construct($id = 0, $size = 0)
    {
        $this->id = $id;
        $this->size = $size;
    }

}
