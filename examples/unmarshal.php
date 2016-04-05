<?php
require_once '../rssapi.php';

$rssapi = new RSSAPI;

// Good way
$unmarsh = $rssapi->unmarshal('http://korben.info/feed');
echo $unmarsh['title'] . ' - ' . $unmarsh['description'] . ' : ' . $unmarsh['link'];

echo '<br />';

// Errors detection about RSS2.0 specifications
if (isset($rssErrors)) {
  foreach ($$rssErrors as $error) {
    echo $error . '<br />';
  }
} else {
  echo 'No error detected <br />';
}

// Bad way that throws an InvalidArgumentExcetion
$rssapi->unmarshal(123);
