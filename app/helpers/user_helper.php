<?php

function getUserId() {
    if(isset($_SESSION['user_id']))
        return $_SESSION['user_id'];
}

function getUsername() {
    if(isset($_SESSION['user_username']))
        return $_SESSION['user_username'];
}

function getUserAvatar() {
    if(isset($_SESSION['user_email'])) {
        $email = $_SESSION['user_email'];
        return "https://www.gravatar.com/avatar/"
            . md5(strtolower(trim($email)));
    }
}

function isUserLoggedIn() {
    return isset($_SESSION['user_id']) ? true : false;
}