<?php

include_once "helpers.php";
include_once "Cache.php";
include_once "Video.php";
include_once "Endpoint.php";

include_once "Brain.php";

$file = fopen($input_path, 'r');

/* Start Reading */

/////////////////////////////////////// Read First line \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$first_line = fgets($file);
list($number_of_videos, $number_of_endpoints, $number_of_request_descriptions, $number_of_caches, $capacity) = explode(' ', $first_line);

Video::$number_of_videos = (int)$number_of_videos;
Endpoint::$number_of_endpoints = (int)$number_of_endpoints;
Endpoint::$number_of_request_descriptions = (int)$number_of_request_descriptions;
Cache::$number_of_caches = (int)$number_of_caches;
Cache::$capacity = (int)$capacity;

/////////////////////////////////////// Create Brain object  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$brain = new Brain(Cache::$number_of_caches, Cache::$capacity);

/////////////////////////////////////// Read video sizes Line \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$second_line = fgets($file);
$videos = explode(' ', $second_line);
foreach ($videos as $id => $size) {
    $video = new Video($id, (int)$size);
    $brain->videos[] = $video;
}

/////////////////////////////////////// Read Cache Latencies \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
for ($id = 0; $id < Endpoint::$number_of_endpoints; $id++) {

    list($latency_to_datacenter, $caches_connected) = explode(' ', fgets($file));
    $endpoint = new Endpoint($id, (int)$latency_to_datacenter, (int)$caches_connected);

    for ($i = 0; $i < $caches_connected; $i++) {
        list($cache_id, $latency) = explode(' ', fgets($file));
        $endpoint->latency_to_cache[$cache_id] = (int)$latency;
    }
    // Sort by latencies increasing
    asort($endpoint->latency_to_cache);
    $brain->endpoints[$id] = $endpoint;
}

/////////////////////////////////////// Read Statistics \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
for ($i = 0; $i < Endpoint::$number_of_request_descriptions; $i++) {
    list($video_id, $endpoint_id, $request_amount) = explode(' ', fgets($file));
    $brain->endpoints[$endpoint_id]->statistics[$video_id] = (int)$request_amount;
    // Advanced stuff
    $brain->endpoints[$endpoint_id]->number_of_total_requests += (int)$request_amount;
    $brain->videos[$video_id]->number_of_requests += (int)$request_amount;
    $brain->videos[$video_id]->interested_endpoints_number ++;
}
//printJustArray($brain);
