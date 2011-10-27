<?php

$title = get_input('title');
$endpoint = 'http://api.stackoverflow.com/1.1/similar?type=jsontext&title=' . urlencode($title);
echo http_inflate(file_get_contents($endpoint));
