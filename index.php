<?php

require_once('src/Controller/Dispatcher.php');

session_start();

$action=isset($_GET['action']) ? $_GET['action'] : 'view_login';

try {
  Dispatcher::$action();
} catch (\Throwable $t) {
  Dispatcher::view_login();
}


