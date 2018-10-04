<?php

header("Content-Type: text/plain");

$host = $_SERVER['SERVER_NAME'];
$subdomain = "";
$protocol = isset($_SERVER['HTTPS']) ? "https" : "http";
if (preg_match("/^([^\.]+)\.[^\.]+\..+$/", $host, $matches)) {
    $subdomain = $matches[1];
}

$robotsHandler = fopen("./robots.txt", "r");
while (($s = fgets($robotsHandler)) !== false) {
    if (preg_match("/https?:\/\/[^\.]+\.[^\s]+/", $s)) {
        $s = preg_replace('/https?:\/\/[^\.]+\.[^\/|\s]+(.*)/', "$protocol://$host$1", $s);
    }
    if (preg_match("/sitemap.*xml/", $s)) {
        $s = preg_replace("/sitemap.*xml/", "sitemap" . (!empty($subdomain) ? "-$subdomain" : "") . ".xml", $s);
    }

    echo $s;
}