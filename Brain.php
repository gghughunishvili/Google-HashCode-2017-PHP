<?php

class Brain
{
    //
    public $videos = [];

    //
    public $endpoints = [];

    // $left_spaces_to_each_cache [ CACHE_ID => SPACE_ID];
    public $left_spaces_to_each_cache = [];

    // $videos_at_cache = [ CACHE_ID => [VIDEO_ID => VIDEO_ID]]; for quick search we make assoc array
    public $videos_at_cache = [];

    public $answer_string = "";

    // Initialization
    public function __construct($cache_numbers = 0, $cache_capacity)
    {
        if ($cache_numbers && $cache_capacity) {
            $this->left_spaces_to_each_cache = array_fill(0, $cache_numbers, $cache_capacity);
        }
    }

    public function solution1()
    {
        $this->sortByRequestCounts();
        $this->_calculateAndSaveInAnswerString();
    }

    public function solution2()
    {
        $this->sortByTotalRequests();
        $this->_calculateAndSaveInAnswerString();
    }

    public function solution3()
    {
        $this->sortByLatencyToDataCenter();
        $this->_calculateAndSaveInAnswerString();
    }

    public function solution4()
    {
        $this->sortByInterestingVideos();
        $this->sortByDifferenceBetweenCacheAndDataCenter();
        $this->_calculateAndSaveInAnswerString();
    }

    public function solution5()
    {
        /**
         * Under Development
         * 
         * take interesting videos and put in every possible cache where users are interested
         */
    }

    public function removeUnimportantVideos()
    {
        foreach ($this->videos as $video_id => $video) {
            if ($video->size > Cache::$capacity || $video->number_of_requests == 0) {
                unset($this->videos[$video_id]);
            }
        }
    }

    public function sortByInterestingVideos()
    {
        uasort($this->videos, function ($v1, $v2) {
            $interesting_1 = $v1->number_of_requests / $v1->size * $v1->interested_endpoints_number;
            $interesting_2 = $v2->number_of_requests / $v2->size * $v2->interested_endpoints_number;
            return $interesting_1 < $interesting_2;
        });
    }

    public function sortByRequestCounts()
    {
        foreach ($this->endpoints as $endpoint_id => $endpoint) {
            arsort($endpoint->statistics);
            $this->endpoints[$endpoint_id] = $endpoint;
        }
    }

    public function sortByTotalRequests()
    {
        uasort($this->endpoints, function ($e1, $e2) {
            return $e1->number_of_total_requests < $e2->number_of_total_requests;
        });
    }

    public function sortByLatencyToDataCenter()
    {
        uasort($this->endpoints, function ($e1, $e2) {
            return $e1->latency_to_datacenter < $e2->latency_to_datacenter;
        });
    }

    public function sortByDifferenceBetweenCacheAndDataCenter()
    {
        uasort($this->endpoints, function ($e1, $e2) {
            $dif_e1 = $e1->latency_to_datacenter - end($e1->latency_to_cache);
            $dif_e2 = $e2->latency_to_datacenter - end($e2->latency_to_cache);
            return $dif_e1 < $dif_e2;
        });
    }

    public function sortByVideoRequestCounts()
    {
        uasort($this->videos, function ($v1, $v2) {
            return $v1->number_of_requests < $v2->number_of_requests;
        });
    }

    private function _calculateAndSaveInAnswerString()
    {
        $this->sortByRequestCounts();
        foreach ($this->endpoints as $endpoint) {
            foreach ($endpoint->statistics as $video_id => $request_amount) {
                $video_size = $this->videos[$video_id]->size;
                // If our video is bigger then cache capacity we don't care for such video
                if ($video_size > Cache::$capacity) {
                    //unset($this->videos[$video_id]);
                    continue;
                }

                // Now if the video is good then store it in the available cache server. PS this cache data are sorted
                foreach ($endpoint->latency_to_cache as $cache_id => $latency) {
                    // if video id is already in cache then we don't care for this cache server
                    if (isset($this->videos_at_cache[$cache_id]) && isset($this->videos_at_cache[$cache_id][$video_id])) {
                        continue;
                    }
                    if ($this->left_spaces_to_each_cache[$cache_id] >= $video_size) {
                        $this->left_spaces_to_each_cache[$cache_id] -= $video_size;
                        $this->videos_at_cache[$cache_id][$video_id] = $video_id;
                    }
                }
            }
        }
        $this->_saveInAnswerString($this->videos_at_cache);
    }

    private function _saveInAnswerString($videos_at_cache)
    {
        $str = (string) count($videos_at_cache) . "\n";
        foreach ($videos_at_cache as $cache_id => $video_ids) {
            $str .= $cache_id . " ";
            $tmp_str = implode($video_ids, ' ');
            $str .= $tmp_str . "\n";
        }
        $this->answer_string = $str;
    }
}
