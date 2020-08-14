<?php
error_reporting(E_ALL); // Error engine - always ON!

ini_set('ignore_repeated_errors', false); // always ON

ini_set('display_errors', false); // Error display - OFF in production env or real server

ini_set('log_errors', 1); // Error logging

ini_set('error_log', 'php-error.log'); // Logging file

ini_set('log_errors_max_len', 1024); // Logging file size
error_log("hola");
?>