<?php
require_once "../rssapi.php";

$rssapi = new RSSAPI();

// Good way
$unmarsh = $rssapi->unmarshal('http://korben.info/feed');
echo $unmarsh['title'] . ' - ' . $unmarsh['desc'];

// Bad way that throws InvalidArgumentExcetion
$rssapi->unmarshal(123);
