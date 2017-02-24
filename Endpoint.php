<?php

class Endpoint
{

    public $id;

    public $caches_connected;

    public $latency_to_datacenter;

    public $number_of_total_requests = 0;

    // $latency_to_cache [ CACHE_ID => LATENCY ]
    public $latency_to_cache = [];

    // $statistics [ VIDEO_ID => REQUEST_AMOUNT ]
    public $statistics = [];

    public static $number_of_endpoints = 0;

    public static $number_of_request_descriptions = 0;

    // Initialization
    public function __construct($id = 0, $latency_to_datacenter = 0, $caches_connected = 0)
    {
        $this->id = $id;
        $this->latency_to_datacenter = $latency_to_datacenter;
        $this->caches_connected = $caches_connected;
    }
}
