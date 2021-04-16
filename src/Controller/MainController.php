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

    public function prepareData($elements) {
      foreach($elements as $key){
        if(isset($_POST[$key])){
          $_POST[$key] = trim($_POST[$key]); //Elimina espacio en blanco (u otro tipo de caracteres) del inicio y el final de la cadena
          $_POST[$key] = strip_tags($_POST[$key]); //Retira las etiquetas HTML y PHP de un string
          $_POST[$key] = addslashes($_POST[$key]); //Escapa un string con barras invertidas
          $_POST[$key] = stripslashes($_POST[$key]); //Quita las barras de un string con comillas escapadas
          $_POST[$key] = htmlspecialchars($_POST[$key]); //Convierte caracteres especiales en entidades HTML
        }
      }
    }

    public function postElementsCheck($elements){
      $i = 0;
      $i_max = count($elements);
      $ok = ($i < $i_max);
      while($ok && $i < $i_max){
        $key = $elements[$i++];
        $ok = (isset($_POST[$key]) && (!empty($_POST[$key]) || is_numeric($_POST[$key])));
      }
      return $ok;
    }

}
