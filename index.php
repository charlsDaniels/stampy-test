<?php

require_once('src/Controller/Dispatcher.php');

session_start();

$action=isset($_GET['action']) ? $_GET['action'] : 'view_login';

function dd($data){
  $format = print_r('<pre>');
  $format .= print_r($data);
  $format .= print_r('</pre>');
  echo $format;
}

try {
  Dispatcher::$action();
} catch (\Throwable $t) {
  Dispatcher::view_login();
}


