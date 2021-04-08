<?php

require_once './src/Repository/UserRepository.php';
// require_once './Extensions/UserUtilitiesTrait.php';

class UserController extends MainController{

  public function viewLogin($error=array()) {
    self::$twig->show('view_login.html', $error);
  }

  public function login() {
    if(!isset($_SESSION['id'])) { //no tiene ya una sesión iniciada
      if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(!empty($_POST['username']) && !empty($_POST['password'])){
          $repo = new UserRepository();
          $username = $repo->login($username, $password);
          if(!empty($username)) {
            $this->startUserSession($username);
            $this->redirectHome();
          }else{
            echo('Usuario o contraseña incorrectos');
            //devolver a Login con el error
            // $this->viewLogin(array("error"=>'Usuario o contraseña incorrectos'));
          }
        }else {
          echo('Faltó completar alguno de los datos');
          // $this->viewLogin(array("error"=>'Faltó completar alguno de los datos'));
        }
      }else {
        echo('Faltó completar alguno de los datos');
        // $this->viewLogin(array("error"=>'Faltó completar alguno de los datos'));
      }
    }
  }

  private function startUserSession($user){
    session_destroy();
    session_set_cookie_params(0);
    session_start();
    $_SESSION['id']= $user['id'];
    $_SESSION['user']= $user['usuario'];
  }

  public function logout(){
    session_destroy();
    $_SESSION= array();
    $this->redirectHome();
  }

  public function register() {

    if(isset($_SESSION['id'])){//es un usuario logueado

      $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
      $firstName =  isset($_POST['first_name']) ? $_POST['first_name'] : '';
      $email = isset($_POST['email']) ? $_POST['email'] : '';
      $password = isset($_POST['password']) ? $_POST['password'] : '';
      $username = isset($_POST['username']) ? $_POST['username'] : '';

      $err = $this->isValidForm(
        $lastName, 
        $firstName, 
        $email, 
        $password, 
        $username
      );

      if(empty($err)){
          $user_repo= new UserRepository();
          if($user_repo->checkUserName($username)){
            if($user_repo->checkEmail($email)) {
              $user = $user_repo->newUser(
                $lastName, 
                $firstName, 
                $email, 
                $password, 
                $username
              );
              echo('El usuario fue agregado exitosamente');
              // $this->viewUsersList('success', 'El usuario fue agregado exitosamente');
            }else {
              echo('Se produjo un error: el email ingresado ya existe');
              // $this->viewUsersList('error', 'Se produjo un error: el email ingresado ya existe');
            }
          }else {
            echo('Se produjo un error: el nombre de usuario ingresado ya existe');
            // $this->viewUsersList('error', 'Se produjo un error: el nombre de usuario ingresado ya existe');
          }
      }else {
        echo($err);
        // $this->viewUsersList('error', $err);
      }    
    }

  }

  public function user(){
    // if(isset($_SESSION['id'])){//es un usuario logueado
      $_GET['action']='';
      $user_repo = new UserRepository();
      $user_id = $_POST["id"];
      $data = array();
      $data['user'] = $user_repo->getUser($user_id);
      if(!empty($data)){
          echo(json_encode($data));
      }else {
        echo('error');
        // $this->viewUsersList('error', 'Se produjo un error');
      }
    // }else {
    //   echo('Debe iniciar sesión');
    // }
  }

  public function users($state=NULL, $msg="") {
    // if(isset($_SESSION['id'])){//es un usuario logueado

        $user_repo = new UserRepository();
        $data = array();

        if(!is_null($state)){
          $data[$state]= $msg;
        }

        $users= $user_repo->getAllUsuarios();
        $data['users']= $users;

        echo(json_encode($data));
        die;
        // $this::$twig->show('list_users.html', $data);
    // }else{
      $this->redirectHome();
    // }
  }

  public function userUpdate(){
    // if(isset($_SESSION['id'])){ //es un usuario logueado
      $userId = isset($_POST['user_id']) ? $_POST['user_id'] : '';
      $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
      $firstName =  isset($_POST['first_name']) ? $_POST['first_name'] : '';
      $email = isset($_POST['email']) ? $_POST['email'] : '';
      $password = isset($_POST['password']) ? $_POST['password'] : '';
      $username = isset($_POST['username']) ? $_POST['username'] : '';

      $err = $this->isValidForm(
        $lastName, 
        $firstName, 
        $email, 
        $password, 
        $username
      );
    
      if(empty($err)){
        $user_repo= new UserRepository();
        if ($user_repo->getUser($userId)) {

          if($user_repo->checkUserName($username)){
            if($user_repo->checkEmail($email)) {

              $user = $user_repo->updateUser(
                $userId,
                $lastName, 
                $firstName, 
                $email, 
                $password, 
                $username
              );

              echo('Usuario actualizado.');
              // $this->viewUsersList('success', 'El usuario se actualizó exitosamente');
                
          }else {
            echo('Se produjo un error: el email ingresado ya existe');
            // $this->viewUsersList('error', 'Se produjo un error: el email ingresado ya existe');
          }
        }else {
          echo('Se produjo un error: el nombre de usuario ingresado ya existe');
          // $this->viewUsersList('error', 'Se produjo un error: el nombre de usuario ingresado ya existe');
        }
      }else {
        echo('Se produjo un error: no existe el usuario');
      }   
    }else {//error con algun campo del form
      echo($err);
      // $this->viewUsersList('error', $err);
    }
      
    // }else {
    //   $this->redirectHome();
    // }
  }

  public function userDelete() {
    // if(isset($_SESSION['id'])){ //es un usuario logueado
      // if($_POST['id_user'] != $_SESSION['id']){ //el que quiere eliminar no es él mismo
        $user_repo= new UserRepository();
        $user_repo->removeUser($_POST['id_user']);
        echo('El usuario fue eliminado');
        // $this->viewUsersList('success', 'El usuario fue eliminado');
      // }else{
      //   echo('Se produjo un error: no puedes eliminar a ese usuario');
      //   // $this->viewUsersList('error', 'Se produjo un error: no puedes eliminar a ese usuario');
      // }
    // }else {
    //   $this->redirectHome();
    // }

  }

  public function postElementsCheck($elements){
    $i = 0;
    $i_max = count($elements);
    $ok = ($i < $i_max);
    while($ok && $i < $i_max){
      $key = $elements[$i++];
      $ok = (isset($_POST[$key]) && (!empty($_POST[$key]) || is_numeric($_POST[$key])));
    }

    //para debugear
    //   if(!$ok){
    //   echo('Falta parámetro: '.$elements[--$i]);
    //   die();
    // }

    return $ok;
  }

  public function isValidForm($last_name, $first_name, $email, $password, $username){
    $err='';
    if ($this->postElementsCheck(array('last_name','first_name','email','password','username'))) {
      $this->prepareData(array('last_name', 'first_name', 'email', 'password', 'username'));
      if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $last_name))) {
        $err.= 'error en apellido: se permiten solo letras, espacios y acentos.';
      }
      if (!(preg_match("/^[a-z ñáéíóú]{2,60}+$/i", $first_name))) {
        $err.= 'error en nombre: se permiten solo letras, espacios y acentos.';
      }
      if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
        $err.= 'error en email: no es un email válido. ';
      }
      if ( ( strlen($username) < 5 ) || ( strlen($username) > 20 ) || (!ctype_alnum($username) ) ){
        $err.= 'error en nombre de usuario: mínimo 6 caracteres alfanuméricos, máximo 20. ';
      }
      if ( ( strlen($password) < 8 ) || ( strlen($password) > 20 ) ) {
        $err.= 'error en password: mínimo 8 caracteres, máximo 20. ';
      }
    }else {
      $err.= 'Se produjo un error: faltó completar alguno/s de los datos. ';
    }
    return $err;
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

}
