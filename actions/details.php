<?php

$question_id = get_input('question_id');
$endpoint = 'http://api.stackoverflow.com/1.1/questions/' . $question_id . '?type=jsontext&body=true&answers=true';
echo http_inflate(file_get_contents($endpoint));
die();


