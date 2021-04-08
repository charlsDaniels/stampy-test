<?php

class MainController {

    protected static $instance;
    protected static $twig;

    public static function getInstance() {
        if (!isset(static::$instance)) {
            static::$instance = new static();
            // static::$twig = new TwigRenderer();
        }
        return static::$instance;
    }

    public function viewHome($params = array()){
      self::$twig->show('home.html');
    }

    public function redirectHome(){
      header('Location: /', true, 301 );
    }

}
