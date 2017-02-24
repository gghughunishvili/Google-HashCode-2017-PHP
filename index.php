<?php

include_once "read.php";

$brain->sortByRequestCounts();
$brain->calculateAndSaveInAnswerString();

writeInFile($brain->answer_string);

printJustArray($brain);

echo "<br/> Celebration time! <br/>";