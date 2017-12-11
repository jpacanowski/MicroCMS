<?php

function redirect($location) {
    header('Location: ?url=' . $location);
}

function isUrl($url) {
    return rtrim($_GET['url'], '/') === $url ? true : false;
}