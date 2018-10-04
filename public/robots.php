<?php

header("Content-Type: text/plain");

$host = $_SERVER['SERVER_NAME'];
$subdomain = "";
$protocol = isset($_SERVER['HTTPS']) ? "https" : "http";

$robotsHandler = fopen("./robots.txt", "r");
while (($s = fgets($robotsHandler)) !== false) {
    if (preg_match('/https?:\/\/[^\.]+\.[^\s]+/', $s)) {
        $s = preg_replace('/https?:\/\/[^\.]+\.[^\/|\s]+(.*)/', "$protocol://$host$1", $s);
    }
    echo $s;
}