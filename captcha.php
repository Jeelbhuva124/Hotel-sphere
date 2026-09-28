<?php
session_start();
$_SESSION['captcha'] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6);
$_SESSION['captcha_time'] = time();
echo $_SESSION['captcha'];
