<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300);
error_reporting(E_ALL);
$CHOOSE_FILE = 4;


$FILENAMES = ['me_at_the_zoo', 'trending_today', 'videos_worth_spreading', 'kittens', 'test'];

$input_path = 'inputs/' . $FILENAMES[ $CHOOSE_FILE ] . '.in';
$output_path = 'outputs/' . $FILENAMES[ $CHOOSE_FILE ] . '.out';

function writeInFile($out_str){
    global $output_path;
    $file = fopen($output_path,"w");
    fwrite($file, $out_str);
    fclose($file);
}

function printJustArray($array){
    echo "<pre>";
    print_r($array);
    die;
}