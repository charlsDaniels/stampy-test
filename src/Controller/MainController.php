<?php

require_once('src/Controller/Dispatcher.php');

class MainController {

    protected static $instance;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function redirectTo($action) {
      header("Location: ?action=".$action, true, 301);
    }

    public function render($template, $params = array()) {
      require_once('templates/'.$template.'.php');
    }

    public function isLoggedUser() {
      return isset($_SESSION['id']);
    }

}
